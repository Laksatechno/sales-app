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
            // Cari sale berdasarkan ID
            $sale = Sale::with('details')->findOrFail($request->id);

            // Cek apakah produk sudah ada di sales_details
            $detail = $sale->details()->where('product_id', $request->product_id)->first();
    
            if ($detail) {
                // Jika produk sudah ada, update quantity dan total
                $detail->quantity += $request->quantity;
                $detail->total = $detail->price * $detail->quantity;
            } else {
                // Jika produk belum ada, buat entri baru di sales_details
                $detail = new SaleDetail();
                $detail->sale_id = $sale->id;
                $detail->product_id = $request->product_id;
                $detail->quantity = $request->quantity;
                $detail->price = $request->price; // Ambil harga dari tabel products
                $detail->total = $request->price * $request->quantity; // Hitung total
            }
    
            // Simpan perubahan di sales_details
            $detail->save();
    
            // Hitung ulang total penjualan
            $totalSales = $sale->details()->sum('total');
    
            // Update total di tabel sales
            $sale->total = $totalSales;
            $sale->save();

                // Kurangi stok produk
                $product = Product::find($request->product_id);
                if ($product) {
                    if ($product->stock < $request->quantity) {
                        return response()->json(['message' => 'error', "Stok produk {$product->name} tidak mencukupi."], 200);
                    }
        
                    $product->stock -= $request->quantity;
                    $product->save();
                }
            return response()->json([
                'status' => 'success',
                'message' => 'Detail berhasil disimpan atau diperbarui.',
            ],200);
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function deleteDetail(Request $request)
    {
        $detailId = $request->input('id');
    
        // Cari detail penjualan berdasarkan ID
        $detail = SaleDetail::find($detailId);
        if (!$detail) {
            return response()->json(['message' => 'Detail not found'], 404);
        }
    
        // Cari sale berdasarkan sale_id dari detail
        $sale = Sale::with('details')->findOrFail($detail->sale_id);
    
        // Hapus detail penjualan
        $detail->delete();
    
        // Kurangi stok produk
        $product = Product::find($detail->product_id);
        if ($product) {
            $product->stock += $detail->quantity;
            $product->save();
        }
    
        // Hitung ulang total penjualan
        $totalSales = $sale->details()->sum('total');
    
        // Update total di tabel sales
        $sale->total = $totalSales;
        $sale->save();
    
        // return response()->json(['message' => 'Detail deleted successfully']);
        return response()->json([
            'status' => 'success',
            'message' => 'Detail deleted successfully.',
        ]);
    }
}

