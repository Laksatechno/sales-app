@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')
    <div class="section mt-2">
        <div class="card">
            <div class="card-body pt-0 table-responsive" >
                <!-- Nav Tabs -->
                <ul class="nav nav-tabs lined" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="overview-tab" data-bs-toggle="tab" href="#laporan" role="tab" aria-controls="laporan" aria-selected="true">
                            Report
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="cards-tab" data-bs-toggle="tab" href="#perproduk" role="tab" aria-controls="perproduk" aria-selected="false">
                            Per Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="cards-tab" data-bs-toggle="tab" href="#percustomer" role="tab" aria-controls="percustomer" aria-selected="false">
                            Per Customer
                        </a>
                    </li>
                </ul>
                <!-- Tab Content -->
                <div class="tab-content mt-2">
                    <!-- Tab Panel Report -->
                    <div class="tab-pane fade show active" id="laporan" role="tabpanel" aria-labelledby="overview-tab">
                                <h2>Laporan Penjualan</h2>
                                <table class="table table-bordered mt-3">
                                    <thead>
                                        <tr>
                                            <th>No Faktur</th>
                                            <th>Tanggal Penjualan</th>
                                            <th>Customer</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sales as $sale)
                                            <tr>
                                                <td>{{ $sale->invoice_number }}</td>
                                                <td>{{ $sale->created_at->format('d-m-Y') }}</td>
                                                <td>{{ $sale->customer->name ?? $sale->users->name }}</td>
                                                <td>{{ number_format($sale->total, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                    </div>
                    <!-- Tab Panel Cards -->
                    <div class="tab-pane fade" id="perproduk" role="tabpanel" aria-labelledby="cards-tab">
                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Lihat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                    <td> {{ $product->name }}</td>
                                    <td>
                                        <a href="{{ route('reports.show', $product->id) }}" class="btn btn-primary">Lihat</a>
                                    </td>
                                    </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="percustomer" role="tabpanel" aria-labelledby="cards-tab">
                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Lihat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $customer)
                                    <tr>
                                    <td> {{ $customer->name }}</td>
                                    <td>
                                        <a href="{{ route('reports.reportbycustomer', $customer->id) }}" class="btn btn-primary">Lihat</a>
                                    </td>
                                    </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
