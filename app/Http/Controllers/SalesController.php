<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {
        $sales = Sale::with('customer', 'user')->paginate(10);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::all();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $sale = Sale::create([
            'customer_id' => $validated['customer_id'],
            'user_id' => auth()->id(),
            'total' => 0, // Akan dihitung nanti
            'due_date' => now()->addMonth(),
        ]);

        $total = 0;
        foreach ($validated['products'] as $productData) {
            $product = Product::find($productData['id']);
            $sale->details()->create([
                'product_id' => $product->id,
                'quantity' => $productData['quantity'],
                'price' => $product->price,
            ]);
            $total += $product->price * $productData['quantity'];
        }

        $sale->update(['total' => $total]);

        return redirect()->route('sales.index')->with('success', 'Sale created successfully');
    }
}

