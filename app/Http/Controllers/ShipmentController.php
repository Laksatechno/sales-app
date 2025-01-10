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
        $shipments = Shipment::with('statuses', 'sale')->orderBy('created_at', 'desc')->get();
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

    public function show($id)
    {
        $shipment = Shipment::with(['sale', 'statuses'])->orderBy('created_at', 'desc')->findOrFail($id);

        return view('shipments.show', compact('shipment'));
    }

}
