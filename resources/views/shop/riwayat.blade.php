@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')
{{-- <div class="section mt-2">
    <div class="card">
        <div class="card-body">
            <h2>Riwayat Pembelian</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No Invoice</th>
                        <th>Nama Produk</th>
                        <th>Quantity</th>
                        <th>Tanggal</th>
                        <th>Marketing</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <tr>
                        <td>{{ $sale->invoice_number }}</td>
                        <td>{{ $sale->details[0]->product->name }}</td>
                        <td>{{ $sale->details[0]->quantity }}</td>
                        <td>{{ $sale->created_at->format('d-m-Y') }}</td>
                        <td>{{ $sale->marketing->name }}</td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
    </div>
</div> --}}
<div class="section mt-4">
    <div class="section-heading">
        <h2 class="title">Transaksi</h2>
        {{-- <a href="app-transactions.html" class="link">View All</a> --}}
    </div>
    <div class="transactions">
        <!-- item -->
        @foreach ($sales as $sale)

        <div class="item">
            <div class="detail">
                {{-- <img src="assets/img/sample/brand/1.jpg" alt="img" class="image-block imaged w48"> --}}
                <div>
                    <strong>{{ $sale->invoice_number }}</strong>
                    <p>{{ $sale->details[0]->product->name ?? 'No product name available' }} x{{ $sale->details[0]->quantity ?? 'No quantity available' }} </p>
                    <p>{{ $sale->created_at->format('d-m-Y') }}</p>
                    <p>{{ $sale->marketing->name }}</p>
                    <span class="badge bg-secondary">Rp. {{ number_format($sale->total, 2) }}</span>
                    {{-- <a href="{{ url('shop/edit', $sale->id) }}" class="btn btn-primary">edit</a> --}}
                </div>
            </div>
            <div class="right">
                <a href="{{ url('shop/detailsinvoice', $sale->id) }}" class="btn btn-success btn-sm">
                    Detail
                </a>
                <a href="{{ url('shop/edit', $sale->id) }}" class="btn btn-warning btn-sm">
                    Edit
                </a>
            </div>
        </div>

        @endforeach
        <!-- * item -->
    </div>
</div>
@endsection