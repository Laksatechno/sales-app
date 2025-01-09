@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')

    <div class="section mt-2">
        <div class="card">
            <div class="card-body table-responsive">
                <h2>Data Penjualan</h2>
                <a href="{{ route('sales.create') }}" class="btn btn-primary">Buat Penjualan</a>

                @if (session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>No. Invoice</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Tax</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales as $sale)
                            <tr>
                                <td>{{ $sale->invoice_number }}</td>
                                <td>{{ $sale->customer->name ?? $sale->users->name }}</td>
                                <td>Rp. {{ number_format($sale->total, 2) }}</td>
                                <td>Rp. {{ $sale->tax_status == 'ppn' ? number_format($sale->tax, 2) : '0' }}</td>
                                <td>{{ $sale->due_date ?? 'COD'}} </td>
                                <td>{{ ucfirst($sale->status) }}</td>
                                <td>
                                    <!-- Tombol Dropdown -->
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            Detail
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <!-- Item Dropdown -->
                                            <li>
                                                <a class="dropdown-item" href="{{ route('sales.show', $sale->id) }}">Lihat</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('sales.edit', $sale->id) }}">Edit</a>
                                            </li>
                                            <li>
                                                <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                                </form>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('print.pdf', $sale->id) }}">Print</a>
                                            </li>
                                            @if (!$sale->shipment) <!-- Pastikan pengiriman belum dibuat -->
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('shipments.create', $sale->id) }}">Kirim</a>
                                                </li>
                                            @else
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('shipments.show', $sale->shipment->id) }}">Detail Pengiriman</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
