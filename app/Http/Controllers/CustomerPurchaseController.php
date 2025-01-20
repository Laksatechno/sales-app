<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CustomerProductPrice;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\SaleDetail;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserCustomerProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


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
        // Ambil data keranjang dari session
        $cart = session('cart', []);
    
        // Cek jika keranjang kosong
        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Keranjang belanja kosong.');
        }
    
        // Hitung total belanja
        $total = array_sum(array_column($cart, 'total'));
    
        // Ambil data customer dan marketing
        $customer = Auth::user()->id;
        $marketingId = Auth::user()->marketing_id;
    
        // Tentukan tax_status berdasarkan jenis_institusi
        $taxstatus = (Auth::user()->jenis_institusi == 'pmi') ? 'non-ppn' : 'ppn';
        
        // dd($taxstatus);
        // Tentukan due_date berdasarkan tipe_pelanggan
        $dueDate = (Auth::user()->tipe_pelanggan == 'subdis') ? null : now()->addMonth(1);
    
        // Generate invoice number
        $currentYear = Carbon::now()->year;
        $latestInvoice = Sale::whereYear('created_at', $currentYear)
            ->orderBy('invoice_number', 'desc')
            ->first();
    
        if (empty($latestInvoice) || date('Y', strtotime($latestInvoice->created_at)) !== $currentYear) {
            $id = 1;
        } else {
            $id = (int)$latestInvoice->invoice_number + 1;
        }
    
        $invoiceNumber = str_pad($id, 4, '0', STR_PAD_LEFT); // Format 4 digit
    
        // Pastikan invoice_number unik
        while (Sale::where('invoice_number', $invoiceNumber)->exists()) {
            $id++;
            $invoiceNumber = str_pad($id, 4, '0', STR_PAD_LEFT);
        }
    
        // Mulai transaksi database
        DB::beginTransaction();
    
        try {

            $tax = 0;
            if (Auth::user()->jenis_institusi == 'non-pmi') {
                $dpp = ceil($total*11)/12;
                $tax = $dpp * 0.12; // 12% pajak
            }

            // dd($tax);

            // Simpan data penjualan
            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'user_customer_id' => $customer,
                'user_id' => $marketingId,
                'total' => $total,
                'tax' => $tax,
                'diskon' => 0,
                'tax_status' => $taxstatus,
                'due_date' => $dueDate,
                'status' => 'pending',
            ]);
    
            // Simpan detail penjualan dan kurangi stok produk
            foreach ($cart as $productId => $item) {
                // Cari produk berdasarkan ID
                $product = Product::find($productId);
    
                if (!$product) {
                    throw new \Exception("Produk dengan ID {$productId} tidak ditemukan.");
                }
    
                // Periksa stok produk
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok produk {$product->name} tidak mencukupi.");
                }
    
                // Kurangi stok produk
                $product->decrement('stock', $item['quantity']);
    
                // Simpan detail penjualan
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total'],
                ]);
            }
    
            // Commit transaksi jika semua operasi berhasil
            DB::commit();
    
            // Hapus keranjang belanja dari session
            session()->forget('cart');
    
            return redirect()->route('shop.index')->with('success', 'Pembelian berhasil.');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();
    
            // Kembalikan pesan error
            return redirect()->route('shop.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function riwayat()
    {
        $customer = Auth::user()->id;
        $sales = Sale::with('details.product', 'customer', 'user' ,'shipment', 'payment')->where('user_customer_id', $customer)->orderBy('created_at', 'desc')->get();
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
