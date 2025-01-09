@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')
<div class="section mt-2">
    <div class="card">
        <div class="card-body">
            <h2>Harga Produk untuk Customer</h2>
            <a href="{{ route('customer-product-price.create') }}" class="btn btn-primary mb-3">Tambah Harga Produk</a>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customerProductPrices as $price)
                        <tr>
                            <td>{{ $price->customer->name }}</td>
                            <td>{{ $price->product->name }}</td>
                            <td>{{ number_format($price->price, 2) }}</td>
                            <td>
                                <a href="{{ route('customer-product-price.edit', $price->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('customer-product-price.destroy', $price->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @foreach ($userProductPrices as $price)
                    <tr>
                        <td>{{ $price->user->name }}</td>
                        <td>{{ $price->product->name }}</td>
                        <td>{{ number_format($price->price, 2) }}</td>
                        <td>
                            <a href="" class="btn btn-warning btn-sm">Edit</a>
                            <form action="" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
