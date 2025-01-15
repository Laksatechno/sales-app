@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')

    <div class="section mt-2">
        <div class="section-heading">
            <h2 class="title">Penjualan</h2>
            <a href="{{ route('sales.create') }}" class="btn btn-primary">Buat Penjualan</a>
        </div>
        @if (session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
        @endif
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table mt-3" id="salesTable" style=" width: 100%;">
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
                                <td>{{ ucfirst($sale->status) }} 
                                    @if ($sale->payment) <!-- Periksa apakah ada relasi payment -->
                                    <span class="badge bg-success">Terbayar</span>
                                    {{-- @else
                                        <span class="badge bg-danger">Belum Bayar</span> --}}
                                    @endif
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

@push ('custom-scripts')
    <script>
        $(document).ready(function() {
            $('#salesTable').DataTable();
        });
    </script>
@endpush
@endsection
