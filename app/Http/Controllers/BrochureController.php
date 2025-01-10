<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brochure;
use Illuminate\Support\Facades\Storage;

class BrochureController extends Controller
{
    // Menampilkan semua data
    public function index()
    {
        $brochures = Brochure::all();
        return view('brochures.index', compact('brochures'));
    }

    // Menampilkan form untuk membuat data baru
    public function create()
    {
        return view('brochures.create');
    }

    // Menyimpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048', // Hanya menerima file PDF, DOC, DOCX
        ]);

        try {
            // Simpan file
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('brochures', $fileName, 'public');

            // Simpan data ke database
            Brochure::create([
                'title' => $request->title,
                'description' => $request->description,
                'file' => $fileName,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Brochure created successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create brochure: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Menampilkan detail data
    public function show(Brochure $brochure)
    {
        return view('brochures.show', compact('brochure'));
    }

    // Menampilkan form untuk mengedit data
    public function edit(Brochure $brochure)
    {
        return view('brochures.edit', compact('brochure'));
    }

    // Mengupdate data
    public function update(Request $request, Brochure $brochure)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // File opsional
        ]);

        try {
            // Jika ada file baru diunggah
            if ($request->hasFile('file')) {
                // Hapus file lama
                Storage::disk('public')->delete('brochures/' . $brochure->file);

                // Simpan file baru
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('brochures', $fileName, 'public');
                $brochure->file = $fileName;
            }

            // Update data
            $brochure->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Brochure updated successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update brochure: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Menghapus data
    public function destroy(Brochure $brochure)
    {
        try {
            // Hapus file dari storage
            Storage::disk('public')->delete('brochures/' . $brochure->file);

            // Hapus data dari database
            $brochure->delete();

            return response()->json([
                'success' => true,
                'message' => 'Brochure deleted successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete brochure: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Mengunduh file
    public function download(Brochure $brochure)
    {
        // Path file di storage
        $filePath = storage_path('app/public/brochures/' . $brochure->file);
    
        // Periksa apakah file ada
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }
    
        // Download file
        return response()->download($filePath);
    }
}