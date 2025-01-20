@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')
    <div class="section mt-2">
        <div class="card">
            <div class="card-body">
            <h2>Detail Invoice: {{ $sale->invoice_number }}</h2>

            <table class="table mt-3">
                <tr>
                    <th>No. Invoice</th>
                    <td>{{ $sale->invoice_number }}</td>
                </tr>
                <tr>
                    <th>Customer</th>
                    <td>{{ $sale->customer->name ?? $sale->users->name }}</td>
                </tr>


                <tr>
                    <th>Jatuh Tempo</th>
                    <td>{{ $sale->due_date}}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ ucfirst($sale->status) }}</td>
                </tr>
                <tr>
                    <th>Rincian</th>
                    <td>
                        @foreach ($sale->details as $detail)
                            <p>{{ $detail->product->name }} {{ $detail->quantity }}x {{ number_format($detail->price, 2) }}</p>
                            <p></p>
                            <p></p>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th>Tax</th>
                    <td>{{ $sale->tax_status == 'ppn' ? number_format($sale->tax) : '0' }}</td>
                </tr>
                <tr>
                    <th>Total</th>
                    <td>{{ number_format($sale->total, 2) }}</td>
                </tr>
            </table>

            {{-- <a href="{{ route('sales.index') }}" class="btn btn-primary">Back</a> --}}
            </div>
        </div>
    </div>
@endsection
