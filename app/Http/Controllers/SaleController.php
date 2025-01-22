<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Product;
use App\Models\Customer;
use App\Models\CustomerProductPrice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'marketing') {
            $sales = Sale::with('customer', 'user', 'details','users', 'shipment' , 'payment')->where('user_id', $user->id)->get();
        }
        else {
          // Ambil data penjualan dan tampilkan di view
        $sales = Sale::with('customer', 'user', 'details','users', 'shipment' , 'payment')->get();
        }

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
        // Validasi input
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'tax_status' => 'required|in:non-ppn,ppn',
            'items' => 'required|json',
        ]);
    
        // Decode items dari JSON ke array
        $items = json_decode($request->items, true);
    
        if (empty($items)) {
            return back()->withErrors(['items' => 'Tidak ada barang yang ditambahkan.'])->withInput();
        }
    
        // Mulai transaksi database
        DB::beginTransaction();
    
        try {
            $totalSale = 0;
            foreach ($items as $item) {
                // Cari produk berdasarkan ID dari item
                $product = Product::find($item['product_id']);
    
                if (!$product) {
                    throw new \Exception('Produk tidak ditemukan dengan ID: ' . $item['product_id']);
                }
    
                // Periksa apakah stok mencukupi
                if ($product->stock < $item['quantity']) {
                    throw new \Exception('Stok tidak mencukupi untuk produk: ' . $product->name);
                }
    
                // Kurangi stok berdasarkan jumlah yang dibeli
                $product->decrement('stock', $item['quantity']);
    
                // Hitung total penjualan
                $item['total'] = $item['quantity'] * $item['price'];
                $totalSale += $item['total'];
            }
    
            // Hitung diskon
            $diskonPercentage = $request->diskon;
            $diskonAmount = ($diskonPercentage / 100) * $totalSale;
    
            // Hitung pajak jika tax_status adalah 'ppn'
            $tax = 0;
            if ($request->tax_status === 'ppn') {
                $dpp = ceil($totalSale*11)/12;
                $tax = $dpp * 0.12; // 12% pajak
            }
    
            // Generate invoice number
            // $currentYear = Carbon::now()->year;

            // // Ambil invoice terakhir untuk tahun ini
            // $latestInvoice = Sale::whereYear('created_at', $currentYear)
            //     ->orderBy('invoice_number', 'desc')
            //     ->first();
            
            // if (empty($latestInvoice) || date('Y', strtotime($latestInvoice->created_at)) !== $currentYear) {
            //     // Jika tidak ada invoice untuk tahun ini, mulai dari 1
            //     $id = 1;
            // } else {
            //     // Ambil nilai integer dari invoice_number terakhir dan tambahkan 1
            //     $id = (int)$latestInvoice->invoice_number + 1;
            // }

            $currentYear = date('Y');
            $lastInvoice = Sale::whereYear('created_at', $currentYear)->orderBy('invoice_number', 'DESC')->first();
    
            if ($lastInvoice) {
                // If the last invoice is from the current year, increment the number
                $id = intval(substr($lastInvoice->invoice_number, -4)) + 1;
            } else {
                // If there are no invoices for the current year, start from 1
                $id = 1;
            }
            
            // Format invoice_number dengan padding 4 digit
            $invoiceNumber = str_pad($id, 4, '0', STR_PAD_LEFT);
    
            // Pastikan invoice_number unik
            while (Sale::where('invoice_number', $invoiceNumber)->exists()) {
                $id++;
                $invoiceNumber = str_pad($id, 4, '0', STR_PAD_LEFT);
            }
    
            // Tentukan due_date
            $dueDate = null;
            if ($request->due_date === '1') {
                $dueDate = now()->addMonth();
            } elseif ($request->due_date === '2') {
                $dueDate = now()->addMonth(2);
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
                'diskon' => $diskonAmount,
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
    
            // Commit transaksi jika semua operasi berhasil
            DB::commit();
    
            return redirect()->route('sales.index')->with('success', 'Penjualan berhasil dibuat.');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();
    
            // Log error untuk debugging
            Log::error('Error saat membuat penjualan: ' . $e->getMessage());
    
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
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
    $subTotal = $totalPrice - $diskon;
    if ($request->tax_status == 'ppn'){
        $dpp = ceil($subTotal*11)/12;
        $tax = $dpp*0.12;
        $finalTotal = $subTotal;
    }
    else {
        $tax = 0;
        $finalTotal = $subTotal;
    }

    // $tax = $finalTotal * ($request->tax_status == 'ppn' ? 0.12 : 0) ;
    // Update data penjualan
    $sale->update([
        'customer_id' => $request->customer_id,
        'tax' => $tax,
        'tax_status' => $request->tax_status,
        'diskon' => $request->diskon ?? 0,
        'total' => $finalTotal,
    ]);

    // dd ($sale);
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

    public function destroy(Sale $sale)
    {
        // Ambil semua detail penjualan yang terkait dengan sale
        $details = $sale->details;

        // Kembalikan stok produk berdasarkan quantity di sale_details
        foreach ($details as $detail) {
            $product = Product::find($detail->product_id);
            if ($product) {
                $product->stock += $detail->quantity; // Tambahkan kembali quantity ke stok
                $product->save();
            }
        }

        // Hapus penjualan
        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Penjualan berhasil dihapus.');
    }

    public function payment(Request $request, $id) {
        // Validasi input
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pph' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'ppn' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Cari data penjualan berdasarkan ID
        $sale = Sale::findOrFail($id);

        // Unggah foto bukti pembayaran
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photo->move(public_path('payment'), $photoName);
            $photoPath = 'payment/' . $photoName;
        } else {
            return response()->json([
                'success' => false,
                'message' => 'File foto bukti pembayaran wajib diunggah.'
            ], 400);
        }

        // Unggah file PPH (jika ada)
        $pphPath = null;
        if ($request->hasFile('pph')) {
            $pph = $request->file('pph');
            $pphName = time() . '_' . $pph->getClientOriginalName();
            $pph->move(public_path('payment'), $pphName);
            $pphPath = 'payment/' . $pphName;
        }

        // Unggah file PPN (jika ada)
        $ppnPath = null;
        if ($request->hasFile('ppn')) {
            $ppn = $request->file('ppn');
            $ppnName = time() . '_' . $ppn->getClientOriginalName();
            $ppn->move(public_path('payment'), $ppnName);
            $ppnPath = 'payment/' . $ppnName;
        }

        // Simpan data pembayaran ke database
        Payment::create([
            'sales_id' => $sale->id,
            'photo' => $photoPath,
            'pph' => $pphPath,
            'ppn' => $ppnPath,
        ]);

        // // Update status penjualan menjadi "complete"
        // $sale->update([
        //     'status' => 'complete',
        // ]);

        // Kembalikan respons JSON
        return response()->json([
            'success' => true,
            'message' => 'Penjualan berhasil dibayar.'
        ], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|in:pending,completed',
        ]);

        // Cari data sale berdasarkan ID
        $sale = Sale::findOrFail($id);

        // Update status
        $sale->update([
            'status' => $request->status,
        ]);

        // Kembalikan respons JSON
        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui.',
        ]);
    }
}
