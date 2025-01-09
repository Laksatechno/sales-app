@extends('layouts.app')
@section('content')
    <div class="section mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <b class="card-title">Semua Penawaran</b>
                            </div>
                            
                            <div class="col-md-8">
                                <form action="{{ route('cari.penawaran') }}" method="GET" class="form-inline">
                                  <input class="form-control mr-sm-2" name="cari" type="search" placeholder="Search" aria-label="Search" value="{{ old('cari') }}">
                                  <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                                </form>
                            </div>                       
                    
                    </div>
                    </div>
                    <div class="table-responsive">
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {!! session('success') !!}
                            </div>
                        @endif
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Perihal</th>
                                    <th>No HP MKT</th>
                                    <!--<th>Nama MKT</th>-->
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- DIRECTIVE FORELSE SAMA DENGAN FOREACH -->
                                <!-- HANYA SAJA SUDAH FORELSE SUDAH DILENGKAPI FUNGSI UNTUK MENGECEK DATA ADA ATAU TIDAK SEHINGGA KITA TIDAK PERLU LAGI MENGGUNAKAN IF CONDITION -->
                                <!-- JIKA DATA KOSONG MAKA FUNGSI YANG BERJALAN ADALAH CODE BERADA PADA BLOCK CODE @3MPTY -->
                                @forelse($penawarans as $penawaran)
                                <tr>
                                    <!-- MENAMPILKAN VALUE DARI TITLE -->
                                    <td>{{ $penawaran->customer }}</td>
                                    <td>{{ $penawaran->perihal }}</td>
                                    <td>{{ $penawaran->no_hp }}</td>
                                    <!--<td>{{ $penawaran->user }}</td>-->
                                    <!-- TOMBOL DELETE MENGGUNAKAN METHOD DELETE DALAM ROUTING SEHINGGA KITA MEMASUKKAN TOMBOL TERSEBUT KEDALAM TAG <FORM></FORM> -->
                                    <td>
                                        <a href="{{ url('/penawaran/detail/' . $penawaran->id) }}" class="btn btn-warning btn-sm"><i class="mdi mdi-pencil"></i></a>
                                        <a href="{{ route('print.penawaran', $penawaran->id) }}" class="btn btn-secondary btn-sm"><i class="mdi mdi-printer"></i></a>
                                        
                                        <form class="btn-group" action="{{ url('/penawaran/delete/' . $penawaran->id) }}" method="POST">
                                            <!-- @csrf ADALAH DIRECTIVE UNTUK MEN-GENERATE TOKEN CSRF -->
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE" class="form-control">
<button class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus penawaran ini?')"><i class="mdi mdi-trash-can"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="text-center" colspan="6">Empty Data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{-- <div class="float-right">
                            {{ $products->links() }}
                        </div> --}}
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

 