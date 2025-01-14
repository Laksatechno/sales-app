@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')
<div class="section mt-2">
        <div class="card-body table-responsive">
            <h2>Pengiriman</h2>

            @if (session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger mt-3">
                    {{ session('error') }}
                </div>
            @endif
            <div class="transactions">
                <!-- item -->
                @foreach ($shipments as $shipment)
                <div class="item">
                    <div class="product">
                        <div class="product-title">
                            <div class="product-name">
                                <a href="{{ route('shipments.show', $shipment->id) }}">{{ $shipment->sale->invoice_number }}</a>
                                <p> {{ $shipment->sale->customer->name ?? $shipment->sale->users->name }} </p>
                                <p> Dikirim : {{tgl_indo($shipment->delivery_date)}}</p>
                                <p> Status : {{ $shipment->statuses->last()->status ?? 'Belum Ada Status' }} </p>
                            </div>
                        </div>
                    </div>
                    <div class="right">
                        <a href="{{ route('shipments.show', $shipment->id) }}" class="btn btn-info btn-sm">Detail</a>
                        <!-- Tombol untuk membuka modal -->
                        @if (Auth:: user()->role == 'admin' || Auth:: user()->role == 'superadmin' || Auth:: user()->role == 'logistik')
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#updateStatusModal" data-id="{{ $shipment->id }}">
                            Update Status
                        </button>
                        @endif
                        {{-- <form action="{{ route('shipments.updateStatus', $shipment->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                    
                            <select name="status" class="form-control mb-2">
                                <option value="Dalam Perjalanan">Dalam Perjalanan</option>
                                <option value="Tertunda">Tertunda</option>
                                <option value="Sampai">Sampai</option>
                            </select>
                    
                            <input type="file" name="photo_proof" class="form-control mb-2" accept="image/*">
                            <button type="submit" class="btn btn-primary">Update Status</button>
                        </form> --}}
                    </div>
                </div>
                @endforeach
            </div>
            {{-- <table class="table mt-3">
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
            </table> --}}
        </div>
</div>

<!-- Modal untuk Update Status -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">Update Status Pengiriman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateStatusForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="Dalam Perjalanan">Dalam Perjalanan</option>
                            <option value="Tertunda">Tertunda</option>
                            <option value="Sampai">Sampai</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="photo_proof" class="form-label">Bukti Foto</label>
                        <input type="file" name="photo_proof" id="photo_proof" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Ketika modal dibuka
        $('#updateStatusModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Tombol yang memicu modal
            var shipmentId = button.data('id'); // Ambil ID dari atribut data-id

            // Set action form dengan route yang sesuai
            var form = $('#updateStatusForm');
            form.attr('action', '/shipments/' + shipmentId + '/update-status');
        });
    });
</script>
@endsection
