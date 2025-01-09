<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PrintController extends Controller
{
    //
    public function generatePdf($id)
    {
        // Ambil data transaksi berdasarkan ID
        $sale = \App\Models\Sale::with(['customer', 'details.product'])->findOrFail($id);

        // Data untuk template
        $data = [
            'sale' => $sale,
            'customer' => $sale->customer,
            'details' => $sale->details,
            'date' => now()->format('d-m-Y'),
        ];

        // Load view dan gabungkan menjadi PDF
        $pdf = PDF::loadView('sales.printnonppn', $data)
                  ->setPaper('a4');

        // Stream PDF ke browser
        return $pdf->stream('invoice_kwitansi_suratjalan.pdf');
    }
}
