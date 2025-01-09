@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')
<div class="section mt-2">
    <div class="card">
        <div class="card-body table-responsive">
            <h2>Shipments List</h2>

            @if (session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Sale</th>
                        <th>Delivery Date</th>
                        <th>Arrival Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($shipments as $shipment)
                        <tr>
                            <td>{{ $shipment->sale->invoice_number }}</td>
                            <td>{{ $shipment->delivery_date }}</td>
                            <td>{{ $shipment->arrival_date ?? '-' }}</td>
                            <td>
                                @foreach ($shipment->statuses as $status)
                                    <div>{{ $status->timestamp }}: {{ $status->status }}</div>
                                @endforeach
                            </td>
                            <td>
                                <td>
                                    <a href="{{ route('shipments.show', $shipment->id) }}" class="btn btn-info">Lihat Detail</a>
                                    <form action="{{ route('shipments.updateStatus', $shipment->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PATCH')
                                
                                        <select name="status" class="form-control mb-2">
                                            <option value="Dalam Perjalanan">Dalam Perjalanan</option>
                                            <option value="Tertunda">Tertunda</option>
                                            <option value="Sampai">Sampai</option>
                                        </select>
                                
                                        <input type="file" name="photo_proof" class="form-control mb-2" accept="image/*">
                                        <button type="submit" class="btn btn-primary">Update Status</button>
                                    </form>
                                </td>
                                
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
