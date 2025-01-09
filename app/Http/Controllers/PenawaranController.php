<?php

namespace App\Http\Controllers;

use App\Models\HargaPenawaran;
use App\Models\KondisiPenawaran;
use Illuminate\Http\Request;
use App\Models\Penawaran;
use App\Models\Product;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PenawaranController extends Controller
{
    //
    // public function index()
    // {
    //     $penawarans = Penawaran::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get(); // 2
    //     // CODE DIATAS SAMA DENGAN > select * from `products` order by `created_at` desc 
    //     return view('penawaran.index', compact('penawarans')); // 3
    // }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $userId = $request->input('user_id');
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin' || Auth::user()->role === 'keuangan') {
            $query = Penawaran::orderBy('created_at', 'DESC');
        }else {
        $query = Penawaran::where('user_id', $userId);
        }
    
        if (!empty($search)) {
            $query->where('customer', 'like', '%' . $search . '%');
        }
    
        $penawarans = $query->get();
    
        if ($request->ajax()) {
            return view('penawaran.partials.penawaran_list', compact('penawarans'))->render();
        }
    
        return view('penawaran.index', compact('penawarans'));
    }
    
    
    public function allpenawaran()
    {
        $penawarans = Penawaran::orderBy('created_at', 'DESC')->get(); // 2
        // CODE DIATAS SAMA DENGAN > select * from `products` order by `created_at` desc 
        return view('penawaran.allpenawaran', compact('penawarans')); // 3
    }

    public function create()
    {
        $penawarans = Penawaran::orderBy('created_at', 'DESC')->get(); // 2
        return view('penawaran.create', compact('penawarans')); // 3
    }

    public function save(Request $request)
    {
        //MELAKUKAN VALIDASI DATA YANG DIKIRIM DARI FORM INPUTAN
        $this->validate($request, [
            'customer' => 'required|string|max:100',
            'address' => 'required|string',
            'perihal' => 'required|string',
        ]);

        try {
            //MENYIMPAN DATA KEDALAM DATABASE
            $penawarans = Penawaran::create([
                'customer' => $request->customer,
                'address' => $request->address,
                'perihal' => $request->perihal,
                'no_hp' => Auth::user()->no_hp,
                'user_id' => Auth::user()->id,
            ]);
            $kondisi = KondisiPenawaran::create([
                'penawaran_id' => $penawarans->id,
            ]);

            $hargapenawarans = HargaPenawaran::create([
                'penawaran_id' => $penawarans->id,
            ]);
            //REDIRECT KEMBALI KE HALAMAN /PRODUCT DENGAN FLASH MESSAGE SUCCESS
            return redirect()->route('detail.penawaran', $penawarans->id)->with(['success' => '<strong>' . $penawarans->customer . '</strong> Penawaran Telah dibuat']);
        } catch (\Exception $e) {
            //APABILA TERDAPAT ERROR MAKA REDIRECT KE FORM INPUT
            //DAN MENAMPILKAN FLASH MESSAGE ERROR
            return redirect('/penawaran/new')->with(['error' => $e->getMessage()]);
        }
    }

    public function detail($id)
    {
        $products = Product::orderBy('created_at', 'DESC')->get();
        // dd($products);
        $kondisis = KondisiPenawaran::where('penawaran_id', $id)->where('kondisi', '!=', null)->orderBy('created_at', 'ASC')->get();
        $hargapenawarans = HargaPenawaran::where('penawaran_id', $id)->where('price', '!=', null)->orderBy('created_at', 'ASC')->get();
        $penawarans = Penawaran::where('id', $id)->first(); // 2
        // CODE DIATAS SAMA DENGAN > select * from `products` order by `created_at` desc 
        return view('penawaran.detail', compact('penawarans', 'products', 'kondisis', 'hargapenawarans')); // 3
    }

    public function savekondisi(Request $request)
    {
        //MELAKUKAN VALIDASI DATA YANG DIKIRIM DARI FORM INPUTAN
        $this->validate($request, [
            'kondisi' => 'required|string',
        ]);

        try {
            //MENYIMPAN DATA KEDALAM DATABASE
            $kondisis = KondisiPenawaran::create([
                'penawaran_id' => $request->penawaran_id,
                'kondisi' => $request->kondisi,
            ]);
            //REDIRECT KEMBALI KE HALAMAN /PRODUCT DENGAN FLASH MESSAGE SUCCESS
            return redirect()->back()->with(['success' => 'Kondisi Penawaran' . $kondisis->kondisi . ' Telah ditambah']);
            
        } catch (\Exception $e) {
            //APABILA TERDAPAT ERROR MAKA REDIRECT KE FORM INPUT
            //DAN MENAMPILKAN FLASH MESSAGE ERROR
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function saveharga(Request $request)
    {
        //MELAKUKAN VALIDASI DATA YANG DIKIRIM DARI FORM INPUTAN
        $this->validate($request, [
            'price' => 'required|integer',
        ]);

        try {
            //MENYIMPAN DATA KEDALAM DATABASE
            $hargapenawarans = HargaPenawaran::create([
                'penawaran_id' => $request->penawaran_id,
                'product_id' => $request->product_id,
                'price' => $request->price,
                'qty' => $request->qty,
            ]);
            //REDIRECT KEMBALI KE HALAMAN /PRODUCT DENGAN FLASH MESSAGE SUCCESS
            return redirect()->back()->with(['success' => 'Harga Produk' . $hargapenawarans->product->name . ' Telah ditambah']);
        } catch (\Exception $e) {
            //APABILA TERDAPAT ERROR MAKA REDIRECT KE FORM INPUT
            //DAN MENAMPILKAN FLASH MESSAGE ERROR
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function destroyKondisi($id)
    {
        $kondisis = KondisiPenawaran::find($id);
        $kondisis->delete(); // MENGHAPUS DATA YANG ADA DIDATABASE
        return redirect()->route('detail.penawaran', [$kondisis->penawaran_id])->with(['success' => '' . $kondisis->kondisi . 'Dihapus']);
    }


    public function destroyHarga($id)
    {
        $hargapenawarans = HargaPenawaran::find($id);
        $hargapenawarans->delete(); // MENGHAPUS DATA YANG ADA DIDATABASE
        return redirect()->route('detail.penawaran', [$hargapenawarans->penawaran_id])->with(['success' => '' . $hargapenawarans->product->name . ' Dihapus']);
    }

    public function destroy($id)
    {
        $penawarans = Penawaran::find($id);
        $kondisis = KondisiPenawaran::where('penawaran_id', $penawarans->id)->delete(); //QUERY KEDATABASE UNTUK MENGAMBIL DATA BERDASARKAN ID
        $hargapenawarans = HargaPenawaran::where('penawaran_id', $penawarans->id)->delete();
        $penawarans->delete(); // MENGHAPUS DATA YANG ADA DIDATABASE
        return redirect()->back()->with(['success' => '<strong>' . $penawarans->customer . '' . ' Penawaran telah dihapus']); // DIARAHKAN KEMBALI KEHALAMAN /product
    }
         
    // public function printpenawaran($id)
    // {
    //     $kondisis = KondisiPenawaran::where('penawaran_id', $id)->where('kondisi', '!=', null)->orderBy('created_at', 'ASC')->get();
    //     $hargapenawarans = HargaPenawaran::where('penawaran_id', $id)->where('price', '!=', null)->orderBy('created_at', 'ASC')->get();
    //     $penawarans = Penawaran::where('id', $id)->first();
    //     // CODE DIATAS SAMA DENGAN > select * from `products` order by `created_at` desc 
    //     $pdf = PDF::loadView('penawaran.print', compact('kondisis', 'hargapenawarans', 'penawarans'))->setPaper('a4', 'potrait');
    //     return $pdf->stream();
    // }
    
public function printpenawaran($id)
{
    $penawarans = Penawaran::where('id', $id)->first();

    // Pastikan penawaran dengan ID tersebut ditemukan
    if (!$penawarans) {
        abort(404, 'Penawaran not found');
    }

    // Ambil informasi pelanggan dari tabel customer
    $customerID = $penawarans->customer; // Sesuaikan dengan nama kolom yang berisi ID pelanggan

    $kondisis = KondisiPenawaran::where('penawaran_id', $id)->where('kondisi', '!=', null)->orderBy('created_at', 'ASC')->get();
    $hargapenawarans = HargaPenawaran::where('penawaran_id', $id)->where('price', '!=', null)->orderBy('created_at', 'ASC')->get();

    // Generate file name with customer ID
    $fileName = 'penawaran_customer_' . $customerID . '_id_' . $id . '.pdf';

    // Load PDF view
    $pdf = PDF::loadView('penawaran.print', compact('kondisis', 'hargapenawarans', 'penawarans'))->setPaper('a4', 'portrait');

    // Save or stream the PDF
    return $pdf->download($fileName);
    
}

    
//Search Bar 

public function search(Request $request)
{
    $search = $request->input('search');
    // Query to filter Penawaran records based on search input
    $penawarans = Penawaran::where('perihal', 'like', '%' . $search . '%')
                            ->orWhere('customer', 'like', '%' . $search . '%')
                            ->orWhere('created_at', 'like', '%' . $search . '%')
                            ->get();
    
    return view('penawaran.index', compact('penawarans'));
}


}