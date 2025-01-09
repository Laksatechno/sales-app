<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Product;
use App\Models\Customer;
use App\Models\CustomerProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index()
    {
        // Ambil data penjualan dan tampilkan di view
        $sales = Sale::with('customer', 'user', 'details','users', 'shipment')->get();
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        // Ambil data pelanggan dan produk untuk dropdown
        $customers = Customer::all();
        $products = Product::all();
        $customerProductPrices = CustomerProductPrice::with ('product')->get();
        return view('sales.create', compact('customers', 'products', 'customerProductPrices'));
    }

    public function getProductsByCustomer($customerId)
    {
        $products = CustomerProductPrice::where('customer_id', $customerId)
            ->with('product')
            ->get();

        return response()->json($products);
    }


    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'tax_status' => 'required|in:non-ppn,ppn',
            'items' => 'required|json',
        ]);
    
        $items = json_decode($request->items, true);
    
        if (empty($items)) {
            return back()->withErrors(['items' => 'Tidak ada barang yang ditambahkan.'])->withInput();
        }

        $totalSale = 0;
        foreach ($items as $item) {
            // Cari produk berdasarkan ID dari item
            $product = Product::find($item['product_id']);
            
            if ($product) {
                // Periksa apakah stok mencukupi
                if ($product->stock >= $item['quantity']) {
                    // Kurangi stok berdasarkan jumlah yang dibeli
                    $product->decrement('stock', $item['quantity']);
                } else {
                    return response()->json(['error' => 'Stok tidak mencukupi untuk produk: ' . $product->name], 400);
                }
            } else {
                return response()->json(['error' => 'Produk tidak ditemukan dengan ID: ' . $item['product_id']], 404);
            }
        
            // Hitung total penjualan
            $item['total'] = $item['quantity'] * $item['price'];
            $totalSale += $item['total'];
        }
                // Hitung diskon
        $diskonPercentage = $request->diskon;
        $diskonAmount = ($diskonPercentage / 100) * $totalSale;
        // dd($stock);
    
        // Hitung pajak jika tax_status adalah 'ppn'
        $tax = 0;
        if ($request->tax_status === 'ppn') {
            $tax = $totalSale * 0.12; // 12% pajak
        }
    
        // Ambil tahun saat ini
        $currentYear = Carbon::now()->year;
    
        // Cari invoice terakhir pada tahun yang sama
        $lastInvoice = Sale::whereYear('created_at', $currentYear)
            ->orderBy('invoice_number', 'desc')
            ->first();
    
        // Tentukan nomor urut berdasarkan invoice terakhir
        $newInvoiceNumber = 1;
        if ($lastInvoice) {
            $lastInvoiceNumber = explode('-', $lastInvoice->invoice_number);
            $newInvoiceNumber = intval(end($lastInvoiceNumber)) + 1;
        }
    
        // Format invoice number sesuai dengan format INV-YYYY-XXX
        $invoiceNumber = 'INV-' . $currentYear . '-' . str_pad($newInvoiceNumber, 3, '0', STR_PAD_LEFT);
    
        if ($request->due_date === '1') {
            $dueDate = now()->addMonth();
        } elseif ($request->due_date === '2') {
            $dueDate = now()->addMonth(2);
        } else {
            $dueDate = null;
        }

        // Simpan data penjualan
        $sale = Sale::create([
            'customer_id' => $request->customer_id,
            'user_id' => auth()->id(),
            'total' => $totalSale,
            'tax_status' => $request->tax_status,
            'due_date' => $dueDate,
            'status' => 'pending',
            'invoice_number' => $invoiceNumber,
            'tax' => $tax,
            'diskon' => $diskonAmount
        ]);
    
        // Simpan detail penjualan
        foreach ($items as $item) {
            $sale->details()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['total'],
            ]);
        }
    
        return redirect()->route('sales.index')->with('success', 'Penjualan berhasil dibuat.');
    }
    
    
    
    

    public function show(Sale $sale)
    {
        // dd($sale);
        return view('sales.show', compact('sale'));
    }

    public function getPrice($customer_id, $product_id)
    {
        // Log incoming request
        Log::info('Received request for price', ['customer_id' => $customer_id, 'product_id' => $product_id]);
    
        // Fetch the price for the given customer and product
        try {
            $customerProductPrice = CustomerProductPrice::where('customer_id', $customer_id)
                                                       ->where('product_id', $product_id)
                                                       ->first();
    
            // Log the result
            Log::info('Fetched price', ['price' => $customerProductPrice ? $customerProductPrice->price : 'not found']);
    
            if ($customerProductPrice) {
                return response()->json(['price' => $customerProductPrice->price]);
            } else {
                return response()->json(['error' => 'Price not found for the selected product.'], 404);
            }
        } catch (\Exception $e) {
            // Log any errors
            Log::error('Error fetching price', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }


    public function edit($id)
    {
        $sale = Sale::with('details.product')->findOrFail($id);
        $customers = Customer::all();
        return view('sales.edit', compact('sale', 'customers'));
    }
    public function update(Request $request, $id)
{
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'tax_status' => 'required|in:non-ppn,ppn',
        'diskon' => 'nullable|numeric|min:0|max:100',
        'items' => 'required|json',
    ]);

    $sale = Sale::findOrFail($id);

    $items = json_decode($request->items, true);
    if (empty($items)) {
        return back()->withErrors(['items' => 'Barang belum dipilih.'])->withInput();
    }

    $totalPrice = array_reduce($items, fn($sum, $item) => $sum + $item['total'], 0);
    $diskon = $request->diskon ? ($totalPrice * $request->diskon / 100) : 0;
    $finalTotal = $totalPrice - $diskon;

    // Update data penjualan
    $sale->update([
        'customer_id' => $request->customer_id,
        'tax_status' => $request->tax_status,
        'diskon' => $request->diskon ?? 0,
        'total_price' => $finalTotal,
    ]);

    // Hapus rincian lama dan tambahkan rincian baru
    $sale->details()->delete();

    foreach ($items as $item) {
        $sale->details()->create([
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'total' => $item['total'],
        ]);
    }

    return redirect()->route('sales.index')->with('success', 'Penjualan berhasil diperbarui.');
}

    public function destroy(Sale $sale){
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Penjualan berhasil dihapus.');
    }
}
