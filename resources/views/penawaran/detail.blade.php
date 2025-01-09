@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')
@section ('header')

    <!-- App Header -->
    <div class="appHeader bg-purple text-light">
        <div class="left">
            <a href="/home" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">DETAIL PENAWARAN</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->

@endsection

<div class="section mt-2">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <b class="card-title">Tambahkan Kondisi Penawaran & Harga</b>
                </div>
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success mt-3 mb-3 text-center ">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Form Tambah Kondisi -->
                    <form action="{{ url('/penawaran/save-kondisi/') }}" method="post">
                        @csrf
                        <input type="hidden" name="penawaran_id" value="{{ $penawarans->id }}">
                        <div class="form-group">
                            <label for="">Kondisi Penawaran</label>
                            <input type="text" name="kondisi" class="form-control {{ $errors->has('kondisi') ? 'is-invalid':'' }}" placeholder="Contoh : Pembayaran tempo 30 hari kerja">
                            <p class="text-danger">{{ $errors->first('kondisi') }}</p>
                        </div>
                        <button class="btn btn-primary btn-block">Tambah</button>
                    </form>

                    <!-- Tabel Kondisi -->
                    <br>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Kondisi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kondisis as $kondisi)
                                <tr>
                                    <td>{{ $kondisi->kondisi }}</td>
                                    <td>
                                        <form class="btn" action="{{ url('/penawaran/delete/kondisi/' . $kondisi->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus kondisi ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="6">Data Masih Kosong</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Form Tambah Harga -->
                    <form action="{{ url('/penawaran/save-harga/') }}" method="post">
                        @csrf
                        <input type="hidden" name="penawaran_id" value="{{ $penawarans->id }}">
                        <div class="form-group">
                            <label for="">Produk</label>
                            <select class="form-control js-example-basic-single {{ $errors->has('product_id') ? 'is-invalid' : '' }}" id="product_id" name="product_id">
                                <option value="">-- Pilih Produk --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-danger">{{ $errors->first('product_id') }}</p>
                        </div>
                        <div class="form-group">
                            <label for="">Harga</label>
                            <input type="number" name="price" class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" placeholder="Masukkan Harga">
                            <p class="text-danger">{{ $errors->first('price') }}</p>
                        </div>
                        <div class="form-group">
                            <label for="">Quantity</label>
                            <input type="text" name="qty" class="form-control {{ $errors->has('qty') ? 'is-invalid' : '' }}" placeholder="Contoh : 1 Box / 1 Pcs">
                            <p class="text-danger">{{ $errors->first('qty') }}</p>
                        </div>
                        <button class="btn btn-primary btn-sm btn-block">Tambah</button>
                    </form>

                    <!-- Tabel Harga -->
                    <br>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hargapenawarans as $hargapenawaran)
                                <tr>
                                    <td>{{ $hargapenawaran->product->name }}</td>
                                    <td>{{ $hargapenawaran->price }}</td>
                                    <td>{{ $hargapenawaran->qty }}</td>
                                    <td>
                                        <form class="btn" action="{{ url('/penawaran/delete/harga/' . $hargapenawaran->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus produk ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="6">Data Masih Kosong</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Tombol Cetak Penawaran -->
                    <br>
                    <button class="btn btn-danger btn-sm btn-block" id="buatpenawaran">Cetak Penawaran</button>
                </div>
            </div>
        </div>
    </div>
</div>
 
<script >
 
$(document).ready(function() {
    $('.js-example-basic-single').select2();
            $('#buatpenawaran').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Cetak Penawaran?',
                text: "Apakah Anda yakin ingin mencetak penawaran?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Cetak!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('print.penawaran', $penawarans->id) }}";
                }
            });
        });
});
</script>

@endsection