<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data sales dengan filter
        $sales = Sale::with('details.product', 'customer', 'marketing')->orderBy('created_at', 'desc');
    
        // Filter berdasarkan pencarian (invoice number atau nama customer)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $sales->where(function ($query) use ($search) {
                $query->where('invoice_number', 'like', '%' . $search . '%')
                      ->orWhereHas('customer', function ($q) use ($search) {
                          $q->where('name', 'like', '%' . $search . '%');
                      })
                      ->orWhereHas('marketing', function ($q) use ($search) {
                          $q->where('name', 'like', '%' . $search . '%');
                      });
            });
        }
    
        // Filter berdasarkan rentang waktu
        if ($request->has('date_range')) {
            $dateRange = $request->date_range;
            if ($dateRange == 'today') {
                $sales->whereDate('created_at', today());
            } elseif ($dateRange == 'last_7_days') {
                $sales->where('created_at', '>=', now()->subDays(7));
            } elseif ($dateRange == 'this_month') {
                $sales->whereMonth('created_at', now()->month);
            } elseif ($dateRange == 'custom_range' && $request->has('start_date') && $request->has('end_date')) {
                $sales->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }
        }
    
        // Filter berdasarkan jenis transaksi
        if ($request->has('transaction_type')) {
            $transactionType = $request->transaction_type;
            if ($transactionType == 'customer') {
                $sales->whereNotNull('customer_id');
            } elseif ($transactionType == 'user') {
                $sales->whereNotNull('user_id');
            }
        }
    
        
        // Filter berdasarkan marketing jika user auth role superadmin dan admin
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
                if ($request->has('user_id')) {
                $sales->where('user_id', $request->id);
            }
        }elseif (Auth::user()->role === 'marketing') {
            $sales->where('user_id', Auth::user()->id);
        }
    

    
        // Ambil data sales yang sudah difilter
        $sales = $sales->get();
    
        // Jika permintaan AJAX, kembalikan partial view
        if ($request->ajax()) {
            return view('reports.sales_list', compact('sales'))->render();
        }
    
        // Ambil data tambahan untuk dropdown filter
        $products = Product::all();
        $customers = Customer::all();
        $marketings = User::where('role', 'marketing')->get();
    
        return view('reports.index', compact('sales', 'products', 'customers', 'marketings'));
    }

    public function print(Request $request)
{
    
    // Ambil data sales dengan filter yang sama seperti di index
    $sales = Sale::with('details.product', 'customer', 'marketing');

    // Filter berdasarkan pencarian
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $sales->where(function ($query) use ($search) {
            $query->where('invoice_number', 'like', '%' . $search . '%')
                  ->orWhereHas('customer', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('marketing', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  });
        });
    }

    // Filter berdasarkan rentang waktu
    if ($request->has('date_range')) {
        $dateRange = $request->date_range;
        if ($dateRange == 'today') {
            $sales->whereDate('created_at', today());
        } elseif ($dateRange == 'last_7_days') {
            $sales->where('created_at', '>=', now()->subDays(7));
        } elseif ($dateRange == 'this_month') {
            $sales->whereMonth('created_at', now()->month);
        } elseif ($dateRange == 'custom_range' && $request->has('start_date') && $request->has('end_date')) {
            $sales->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
    }

    // Filter berdasarkan jenis transaksi
    if ($request->has('transaction_type')) {
        $transactionType = $request->transaction_type;
        if ($transactionType == 'customer') {
            $sales->whereNotNull('customer_id');
        } elseif ($transactionType == 'user') {
            $sales->whereNotNull('user_id');
        }
    }

    // Filter berdasarkan marketing
    if ($request->has('user_id')) {
        $sales->where('user_id', $request->id);
    }

    // Ambil data sales yang sudah difilter
    $sales = $sales->get();

    // Load view untuk PDF
    $pdf = Pdf::loadView('reports.print', compact('sales'));

    // Download atau tampilkan PDF
    // return $pdf->download('laporan_penjualan.pdf');
    return $pdf->stream('laporan_penjualan.pdf');
}

    public function show($product_id)
    {
        // Filter penjualan berdasarkan product_id
        $sales = Sale::whereHas('details', function ($query) use ($product_id) {
            $query->where('product_id', $product_id);
        })->with(['details.product', 'marketing'])->get();
    
        $product = Product::findOrFail($product_id); // Mengambil nama produk untuk ditampilkan di view
        return view('reports.show', compact('sales', 'product'));
    }

    public function reportbycustomer($customer_id)
    {
        // Filter penjualan berdasarkan customer_id
        $sales = Sale::where('customer_id', $customer_id)->with('details.product')->get();
        return view('reports.customer', compact('sales'));
    }
    

    public function generate(Request $request)
    {
        // Validasi input
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Ambil data berdasarkan rentang tanggal
        $sales = Sale::whereBetween('created_at', [$request->start_date, $request->end_date])
            ->with('details.product') // Pastikan relasi sudah diatur di model Sale
            ->get();

        // Tampilkan data ke view
        return view('reports.result', [
            'sales' => $sales,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
    }
}
