
@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')

<div class="section mt-2">
    <div class="card">
        <div class="card-body table-responsive">
            <h2>Penjualan untuk Produk: {{ $product->name }}</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Invoice Number</th>
                        <th>Produk</th>
                        <th>Quantity</th>
                        <th>Tanggal Penjualan</th>
                        <th>Marketing</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sales as $sale)
                        @foreach ($sale->details as $detail)
                            @if ($detail->product_id == $product->id)
                                <tr>
                                    <td>{{ $sale->invoice_number }}</td>
                                    <td>{{ $detail->product->name }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>{{ $sale->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $sale->marketing->name }}</td>
                                </tr>
                            @endif
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data penjualan untuk produk ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
        </div>
    </div>
</div>
@endsection