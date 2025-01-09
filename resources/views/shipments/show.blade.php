@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')
<style type="text/css">
.timeline {
    list-style: none;
    padding: 0;
    margin: 0;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
    padding-left: 20px;
    border-left: 2px solid #007bff;
}

.timeline-item:last-child {
    border-left: none;
}

.timeline-time {
    font-size: 0.9em;
    color: #6c757d;
    margin-bottom: 5px;
    display: block;
}

.timeline-status {
    font-size: 1em;
    font-weight: bold;
    color: #343a40;
}

/* Additional styling for different statuses */
.timeline-item.secondary .timeline-status {
    color: #6c757d; /* Secondary color */
}

.timeline-item.success .timeline-status {
    color: #28a745; /* Success color */
}
</style>

<div class="section mt-3">
    <div class="card">
        <div class="card-body">
            <h3>Detail Pengiriman</h3>
            <p><strong>No. Invoice:</strong> {{ $shipment->sale->invoice_number }}</p>
            <p><strong>Customer:</strong> {{ $shipment->sale->customer->name ?? '-' }}</p>
            <p><strong>Status Pengiriman:</strong> {{ $shipment->statuses->last()->status ?? 'Belum Ada Status' }}</p>
            <hr>

            <h5>Timeline Pengiriman</h5>
            <ul class="timeline">
                @foreach ($shipment->statuses as $index => $status)
                    <li class="timeline-item 
                        @if ($loop->last) success 
                        @elseif ($loop->index == $loop->count - 2) secondary 
                        @endif">
                        <span class="timeline-time">{{ $status->timestamp }}</span>
                        <span class="timeline-status">{{ $status->status }}</span>
                    </li>
                @endforeach
            </ul>

            @if ($shipment->photo_proof)
                <h5 class="mt-4">Bukti Foto</h5>
                <img src="{{ asset('storage/' . $shipment->photo_proof) }}" alt="Proof Photo" class="img-fluid">
            @endif
        </div>
    </div>
</div>
@endsection
