<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;

class ReportController extends Controller
{
    public function index()
    {
        $sales = Sale::with('details.product')->get();
        $products = Product::all();
        $sale = Sale::where('id', 1)->with('details.product')->first();
        $customers = Customer::all();
        // dd($products);
        return view('reports.index' , compact('sales', 'products', 'sale', 'customers'));
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
