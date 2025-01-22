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
</style>

<div class="section mt-2 mt-3">
    <div class="card">
        <div class="card-body mb-3">
            <h3>Detail Pengiriman</h3>
            <p><strong>No. Invoice:</strong> {{ $shipment->sale->invoice_number }}</p>
            <p><strong>Customer:</strong> {{ $shipment->sale->customer->name ?? $shipment->sale->users->name }}</p>
            <p><strong>Status Pengiriman:</strong> {{ $shipment->statuses->last()->status ?? 'Belum Ada Status' }}</p>
            <hr>

            {{-- <h5>Timeline Pengiriman</h5> --}}
            <div class="timeline timed ms-1 me-2">
                @foreach ($shipment->statuses as $index => $status)
                    <div class="item ">
                        <span class="time">{{ jam_id($status->timestamp) }}<br>{{ tgl_indo($status->timestamp) }}</span>
                        <div class="dot @if ($loop->last) bg-info 
                            @elseif ($loop->index == $loop->count - 2) bg-secondary 
                            @endif"></div>
                        <div class="content">
                            <h4 class="text">{{ $status->status }}</h4>
                            @if ($shipment->photo_proof == !null)
                            @if ($loop->last)
                            <!-- Badge untuk membuka modal -->
                            <span class="badge bg-info" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#DialogImage{{ $shipment->id }}">
                                Lihat Bukti Pengiriman
                            </span>
                        
                            <!-- Modal -->
                            {{-- <div class="modal fade dialogbox" id="DialogImage{{ $shipment->id }}" data-bs-backdrop="static" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="proofModalLabel{{ $shipment->id }}">Bukti Pengiriman</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            @if ($shipment->photo_proof)
                                                <img src="{{ asset('shipment_photos/' . $shipment->photo_proof) }}" alt="Proof Photo" class="img-fluid">
                                            @else
                                                <p>Tidak ada bukti pengiriman yang tersedia.</p>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="modal fade dialogbox" id="DialogImage{{ $shipment->id }}" data-bs-backdrop="static" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        @if ($shipment->photo_proof)
                                        <img src="{{ asset('shipment_photos/' . $shipment->photo_proof) }}" alt="Proof Photo" class="img-fluid">
                                    @else
                                        <p>Tidak ada bukti pengiriman yang tersedia.</p>
                                    @endif
                                        <div class="modal-footer ">
                                            <div class="btn-inline">
                                                <a href="#" class="btn btn-text-secondary btn-sm" data-bs-dismiss="modal">TUTUP</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @endif
                        </div>
                        {{-- <span class="timeline-status">{{ $status->status }}</span> --}}
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</div>
@endsection
