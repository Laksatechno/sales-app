<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\CustomerProductPrice;
use App\Models\UserCustomerProductPrice;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerProductPriceController extends Controller
{
    public function index()
    {
        // Ambil semua data CustomerProductPrice dan relasi terkait
        $customerProductPrices = CustomerProductPrice::with(['customer', 'product'])->get();
        $userProductPrices = UserCustomerProductPrice::with(['user', 'product'])->get();
        return view('customer_product_price.index', compact('customerProductPrices', 'userProductPrices'));
    }

    public function create()
    {
        // Ambil data customer dan produk untuk form input
        $customers = Customer::all();
        // ambil data user dengan role customer
        $users = User::where('role', 'customer')->get();
        $products = Product::all();
        return view('customer_product_price.create', compact('customers', 'products', 'users'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'price' => 'required|numeric',
        ]);

        // Simpan data CustomerProductPrice
        CustomerProductPrice::create([
            'customer_id' => $request->customer_id,
            'product_id' => $request->product_id,
            'price' => $request->price,
        ]);

        return redirect()->route('customer-product-price.index')->with('success', 'Harga produk berhasil ditambahkan');
    }

    public function storeusercustomer(Request $request)
    {
        // Validasi input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'price' => 'required|numeric',
        ]);

        // Simpan data UserCustomerProductPrice
        UserCustomerProductPrice::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'price' => $request->price,
        ]);

        return redirect()->route('customer-product-price.index')->with('success', 'Harga produk berhasil ditambahkan');
    }

    public function show($id)
    {
        // Ambil data berdasarkan ID
        $customerProductPrice = CustomerProductPrice::with(['customer', 'product'])->findOrFail($id);
        return view('customer_product_price.show', compact('customerProductPrice'));
    }

    public function edit($id)
    {
        // Ambil data customer, produk, dan harga produk untuk customer tertentu
        $customerProductPrice = CustomerProductPrice::findOrFail($id);
        $customers = Customer::all();
        $products = Product::all();
        return view('customer_product_price.edit', compact('customerProductPrice', 'customers', 'products'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'price' => 'required|numeric',
        ]);

        // Update data CustomerProductPrice
        $customerProductPrice = CustomerProductPrice::findOrFail($id);
        $customerProductPrice->update([
            'customer_id' => $request->customer_id,
            'product_id' => $request->product_id,
            'price' => $request->price,
        ]);

        return redirect()->route('customer-product-price.index')->with('success', 'Harga produk berhasil diperbarui');
    }

    public function destroy($id)
    {
        // Hapus data CustomerProductPrice
        $customerProductPrice = CustomerProductPrice::findOrFail($id);
        $customerProductPrice->delete();

        return redirect()->route('customer-product-price.index')->with('success', 'Harga produk berhasil dihapus');
    }
    
}
