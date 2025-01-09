<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Product;
use App\Models\UserCustomerProductPrice;
use Illuminate\Support\Facades\Auth;

class ShopApiController extends Controller
{
    public function index()
    {
        $user = Auth::user()->id;

        // Ambil produk beserta harga khusus customer
        $products = UserCustomerProductPrice::where('user_id', $user)
            ->with('product')  // Relasi ke produk
            ->get();

        return response()->json(['products' => $products]);
    }

    public function editjson($id)
    {
        // Ambil data penjualan
        $sale = Sale::with('details', 'details.product')->find($id);
    
        // Periksa apakah data ditemukan
        if (!$sale) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
            ], 404);
        }
    
        // Ambil data harga produk terkait user
        $user = Auth::user()->id; // Pastikan user telah diautentikasi
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 401);
        }
    
        $productsprice = UserCustomerProductPrice::where('user_id', $user)
            ->with('product')
            ->get();
    
        // Format response
        return response()->json([
            'success' => true,
            'invoice_number' => $sale->invoice_number,
            'id' => $sale->id,
            'details' => $sale->details->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'product_name' => $detail->product->name,
                    'quantity' => $detail->quantity,
                    'price' => $detail->price,
                    'total' => $detail->total,
                ];
            }),
            'productsprice' => $productsprice,
        ]);
    }
    
    

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
    
        try {
            $sale = Sale::with('details')->findOrFail($request->id);
    
            $detail = $sale->details()->where('product_id', $request->product_id)->first();
    
            if ($detail) {
                $detail->quantity += $request->quantity;
                $detail->total = $detail->price * $detail->quantity;
            } else {
                $detail = new SaleDetail();
                $detail->sale_id = $sale->id;
                $detail->product_id = $request->product_id;
                $detail->quantity = $request->quantity;
                $detail->price = $request->price;
                $detail->total = $request->price * $request->quantity;
            }
    
            $detail->save();
    
            return response()->json(['message' => 'Detail berhasil disimpan atau diperbarui.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function deleteDetail(Request $request)
    {
        $detailId = $request->input('id');

        $detail = SaleDetail::find($detailId);
        if (!$detail) {
            return response()->json(['message' => 'Detail not found'], 404);
        }

        $detail->delete();

        return response()->json(['message' => 'Detail deleted successfully']);
    }
}

