<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CustomerProductPrice;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\SaleDetail;
use App\Models\User;
use App\Models\UserCustomerProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerPurchaseController extends Controller
{
    public function index()
    {
        $user = Auth::user()->id;


        // Ambil produk beserta harga khusus customer
        $products = UserCustomerProductPrice::where('user_id', $user)
            ->with('product')  // Relasi ke produk
            ->get();
    
        return view('shop.index', compact('products'));
    }
    

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
    
        // Ambil harga khusus berdasarkan customer_id dan product_id
        $customer = Auth::user()->id;
        $product = UserCustomerProductPrice::where('product_id', $request->product_id)
            ->where('user_id', $customer)
            ->with('product') // Relasi untuk mendapatkan nama produk
            ->first();
    
        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan atau tidak tersedia untuk Anda.'], 404);
        }
    
        if ($product->product->stock < $request->quantity) {
            return response()->json(['message' => 'Stok produk tidak mencukupi.'], 400);
        }
    
        $cart = session()->get('cart', []);
        $cart[$product->product_id] = [
            'name' => $product->product->name,
            'price' => $product->price,
            'quantity' => $request->quantity,
            'total' => $product->price * $request->quantity,
        ];
    
        session(['cart' => $cart]);
    
        return response()->json(['message' => 'Produk berhasil ditambahkan ke keranjang.', 'cart' => $cart]);
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->product_id])) {
            unset($cart[$request->product_id]);
            session(['cart' => $cart]);
            return response()->json([
                'message' => 'Produk berhasil dihapus dari keranjang.',
                'cart' => $cart // Return the updated cart data
            ]);
        }

        return response()->json(['message' => 'Produk tidak ditemukan di keranjang.'], 404);
    }

    

    public function checkout(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Keranjang belanja kosong.');
        }
    
        $total = array_sum(array_column($cart, 'total'));
        $customer = Auth::user()->id;
    
        $sale = Sale::create([
            'invoice_number' => 'INV-' . time(),
            'user_customer_id' => $customer,
            'user_id' => $customer = Auth::user()->marketing_id,
            'total' => $total,
            'tax' => 0,
            'diskon' => 0,
            'tax_status' => 'non-ppn',
            'due_date' => now()->addDays(7),
            'status' => 'pending',
        ]);
    
        foreach ($cart as $productId => $item) {
            SaleDetail::create([
                'sale_id' => $sale->id,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['total'],
            ]);

        }
                // Kurangi stok produk
                $product = Product::find($productId);
                if ($product) {
                    if ($product->stock < $item['quantity']) {
                        return redirect()->route('shop.index')->with('error', "Stok produk {$product->name} tidak mencukupi.");
                    }
        
                    $product->stock -= $item['quantity'];
                    $product->save();
                }

    
        session()->forget('cart');
        return redirect()->route('shop.index')->with('success', 'Pembelian berhasil.');
    }
    
    public function riwayat()
    {
        $customer = Auth::user()->id;
        $sales = Sale::with('details.product', 'customer', 'user' ,'shipment')->where('user_customer_id', $customer)->get();
        // dd($sales);
        return view('shop.riwayat', compact('sales'));
    }

    public function detailsinvoice($id)
    {
        $sale = Sale::with(['details.product', 'marketing'])->findOrFail($id);
        return view('sales.show', compact('sale'));
    }

    // public function edit($id)
    // {
    //     $user = Auth::user()->id;

    //     // Ambil produk beserta harga khusus customer
    //     $productsprice = UserCustomerProductPrice::where('user_id', $user)
    //         ->with('product')  // Relasi ke produk
    //         ->get();
    //         // dd($products);
    //     $saledetails = Sale::with('details', 'details.product')->find($id);
    //     // dd($saledetails);
    //     return view('shop.edit', compact('saledetails', 'productsprice'));
    // }

    public function edit($id)
    {
        return view('shop.edit', ['id' => $id]);
    }


    public function deletedetails(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:sale_details,id',
        ]);
    
        // Cari SaleDetail berdasarkan ID
        $saleDetail = SaleDetail::find($request->id);
    
        if ($saleDetail) {
            $saleDetail->delete(); // Hapus SaleDetail
            return response()->json(['message' => 'Produk berhasil dihapus dari keranjang.'], 200);
        }
    
        return response()->json(['message' => 'Produk tidak ditemukan di keranjang.'], 404);
    }

    public function updateDetail(Request $request)
    {
        $request->validate([
            'sales_id' => 'required|exists:sale_details,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            // Temukan detail berdasarkan ID
            $detail = SaleDetail::findOrFail($request->detail_id);

            // Update detail
            $detail->product_id = $request->product_id;
            $detail->quantity = $request->quantity;
            $detail->total = $detail->price * $request->quantity; // Update total
            $detail->save();

            return response()->json(['message' => 'Detail berhasil diperbarui.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui detail.'], 500);
        }
    }

    
}
