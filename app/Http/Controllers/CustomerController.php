<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //
        // Menampilkan daftar customer
        public function index()
        {
            // Mengambil semua data customer
            $customers = Customer::all();
            return view('customers.index', compact('customers'));
        }
    
        // Menampilkan form untuk menambah customer
        public function create()
        {
            return view('customers.create');
        }
    
        // Menyimpan customer baru
        public function store(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:customers',
                'tipe_pelanggan' => 'required|string|max:50',
            ]);
    
            // Menyimpan customer baru ke dalam database
            Customer::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'email' => $request->email,
                'tipe_pelanggan' => $request->tipe_pelanggan,
            ]);
    
            return redirect()->route('customers.index')->with('success', 'Customer added successfully.');
        }
    
        // Menampilkan form untuk mengedit customer
        public function edit($id)
        {
            $customer = Customer::findOrFail($id);
            return view('customers.edit', compact('customer'));
        }
    
        // Memperbarui data customer
        public function update(Request $request, $id)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:customers,email,' . $id,
                'tipe_pelanggan' => 'required|string|max:50',
            ]);
    
            $customer = Customer::findOrFail($id);
            $customer->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'email' => $request->email,
                'tipe_pelanggan' => $request->tipe_pelanggan,
            ]);
    
            return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
        }
    
        // Menghapus customer
        public function destroy($id)
        {
            $customer = Customer::findOrFail($id);
            $customer->delete();
    
            return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
        }
}
