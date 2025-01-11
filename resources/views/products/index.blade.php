@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')
<div class="section mt-2">
    <div class="section-heading">
        <h2 class="title">Product</h2>
        <a href="{{ route('products.create') }}" class="btn btn-primary">Tambah Produk</a>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif        
    </div>
    <div class="card">
        <div class="card-body table-responsive">
            {{-- <h2>Data Product</h2>
            <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Tambah Produk</a> --}}

            <table class="table "  id="productTable" style=" width: 100%;" >
                <thead>
                    <tr>
                        <th>Nama</th>
                        {{-- <th>Harga</th> --}}
                        <th>Stock</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            {{-- <td>{{ number_format($product->price, 2) }}</td> --}}
                            <td>{{ $product->stock }}</td>
                            <td>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24"><path fill="currentColor" d="M20.71 7.04c.39-.39.39-1.04 0-1.41l-2.34-2.34c-.37-.39-1.02-.39-1.41 0l-1.84 1.83l3.75 3.75M3 17.25V21h3.75L17.81 9.93l-3.75-3.75z"/></svg>
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 4h-3.5l-1-1h-5l-1 1H5v2h14M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6z"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@push ('custom-scripts')
    <script>
        $(document).ready(function() {
            $('#productTable').DataTable();
        });
    </script>
@endpush
@endsection
