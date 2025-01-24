<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\ShipmentStatus;
use App\Models\Sale;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index()
    {
                // Ambil user yang sedang login
                $user = auth()->user();
    
                // Pastikan user memiliki role 'customer'
                if ($user->role === 'customer') {
                    // Filter shipments berdasarkan user_customer_id yang sesuai dengan customer_id dari user yang sedang login
                    $shipments = Shipment::with('statuses', 'sale')
                        ->whereHas('sale', function($query) use ($user) {
                            $query->where('user_customer_id', $user->id);
                        })
                        ->orderBy('created_at', 'desc')
                        ->get();
                } else {
                    // Jika user bukan customer, kembalikan semua shipments (atau sesuai kebijakan aplikasi Anda)
                    $shipments = Shipment::with('statuses', 'sale')
                        ->orderBy('created_at', 'desc')
                        ->get();
                }
        return view('shipments.index', compact('shipments'));
    }


    public function create($id)
    {
        $sale = Sale::findOrFail($id);
        return view('shipments.create', compact('sale'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'delivery_date' => 'nullable|date',
        ]);

        $shipment = Shipment::create([
            'sale_id' => $request->sale_id,
            'delivery_date' => $request->delivery_date ?? now(),
        ]);

        ShipmentStatus::create([
            'shipment_id' => $shipment->id,
            'status' => 'Dalam Perjalanan',
            'timestamp' => now(),
        ]);

        return redirect()->route('shipments.index')->with('success', 'Shipment created successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|string',
            'photo_proof' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi untuk file gambar
        ]);
    
        // Temukan shipment berdasarkan ID
        $shipment = Shipment::findOrFail($id);
    
        // Unggah foto bukti jika ada
        $photoPath = $shipment->photo_proof;
        if ($request->hasFile('photo_proof')) {
            // Hapus foto lama jika ada
            if ($photoPath && file_exists(public_path('shipment_photos/' . $photoPath))) {
                unlink(public_path('shipment_photos/' . $photoPath));
            }
    
            // Simpan foto baru di direktori public/shipment_photos
            $photo = $request->file('photo_proof');
            $photoName = time() . '_' . $photo->getClientOriginalName(); // Nama file unik
            $photo->move(public_path('shipment_photos'), $photoName); // Pindahkan file ke public/shipment_photos
            $photoPath = $photoName; // Simpan nama file ke variabel $photoPath
        }
    
        // Buat entri status shipment baru
        ShipmentStatus::create([
            'shipment_id' => $shipment->id,
            'status' => $request->status,
            'timestamp' => now(),
        ]);
    
        // Jika statusnya 'Sampai', tambahkan arrival_date dan bukti foto
        if ($request->status === 'Sampai') {
            $shipment->update([
                'arrival_date' => now(),
                'photo_proof' => $photoPath,
            ]);
        }
    
        return redirect()->route('shipments.index')->with('success', 'Shipment status updated successfully!');
    }

    public function kirim(Request $request, $sale_id)
    {
        // \Log::info('Request data:', $request->all()); // Log data request
        // \Log::info('Sale ID:', ['sale_id' => $sale_id]); // Log sale_id
    
        // Validasi request
        $request->validate([
            'delivery_date' => 'nullable|date',
        ]);
    
        // Membuat shipment
        $shipment = Shipment::create([
            'sale_id' => $sale_id,
            'delivery_date' => $request->delivery_date ?? now(),
        ]);
    
        // \Log::info('Shipment created:', $shipment->toArray()); // Log shipment
    
        // Membuat status shipment
        $shipmentStatus = ShipmentStatus::create([
            'shipment_id' => $shipment->id,
            'status' => 'Pesanan Anda Sudah Diserahkan ke Pihak Logistik',
            'timestamp' => now(),
        ]);
    
        // \Log::info('Shipment status created:', $shipmentStatus->toArray()); // Log shipment status
    
        // Kembalikan response JSON
        return response()->json([
            'status' => 'success',
            'message' => 'Barang berhasil diserahkan ke Logistik.'
        ], 200);
    }

    public function jalan($shipment_id)
    {
        // Temukan shipment berdasarkan ID
        $shipment = Shipment::findOrFail($shipment_id);
        // Buat entri status shipment baru
        ShipmentStatus::create([
            'shipment_id' => $shipment->id,
            'status' => 'Barang Sudah Diperjalanan',
            'timestamp' => now(),
        ]);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Barang Dalam Perjalanan!'
        ], 200);
    }

    public function jalanekspedisi($shipment_id)
    {
        // Temukan shipment berdasarkan ID
        $shipment = Shipment::findOrFail($shipment_id);
        // Buat entri status shipment baru
        ShipmentStatus::create([
            'shipment_id' => $shipment->id,
            'status' => 'Barang Dikirim Melalui Ekspedisi',
            'timestamp' => now(),
        ]);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Barang Dikirim Melalui Ekspedisi!'
        ], 200);
    }


    public function sampai(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'photo_proof' => 'nullable|string', // Gambar dikirim sebagai base64
        ]);
    
        // Temukan shipment berdasarkan ID
        $shipment = Shipment::findOrFail($id);
    
        // Unggah foto bukti jika ada
        $photoPath = $shipment->photo_proof;
        if ($request->has('photo_proof')) {
            $image = $request->photo_proof;
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = time() . '_' . uniqid() . '.jpeg';
            \File::put(public_path('shipment_photos/' . $imageName), base64_decode($image));
            $photoPath = $imageName;
        }
    
        // Buat entri status shipment baru
        ShipmentStatus::create([
            'shipment_id' => $shipment->id,
            'status' => 'Barang Sudah Sampai',
            'timestamp' => now(),
        ]);
    
        // Update shipment dengan arrival_date dan bukti foto
        $shipment->update([
            'arrival_date' => now(),
            'photo_proof' => $photoPath,
        ]);
    
        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $shipment = Shipment::with(['sale', 'statuses'])->orderBy('created_at', 'desc')->findOrFail($id);

        return view('shipments.show', compact('shipment'));
    }

}
