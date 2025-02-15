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
                <table class="table mt-3" id="salesTable" style="width: 100%;">
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
                                <td>Rp. {{ number_format($sale->total) }}</td>
                                <td>Rp. {{ $sale->tax_status == 'ppn' ? number_format($sale->tax) : '0' }}</td>
                                <td>{{ $sale->due_date ?? 'COD' }}</td>
                                <td>
                                    {{ ucfirst($sale->status) }}
                                    @if ($sale->payment) <!-- Periksa apakah ada relasi payment -->
                                        <span class="badge bg-success" style="cursor: pointer;" data-toggle="modal" data-target="#paymentDetailModal{{ $sale->id }}">
                                            Terbayar
                                        </span>
                                    @endif
                                </td>
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
                                                <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display:inline;" id="deleteForm{{ $sale->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="dropdown-item text-danger" onclick="confirmDelete({{ $sale->id }})">Delete</button>
                                                </form>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('print.pdf', $sale->id) }}">Print</a>
                                            </li>
                                            @if (!$sale->shipment) <!-- Pastikan pengiriman belum dibuat -->
                                                {{-- <li>
                                                    <a class="dropdown-item" href="{{ route('shipments.create', $sale->id) }}" onclick="return confirm('Apakah Anda yakin ingin membuat pengiriman?')">Kirim</a>
                                                </li> --}}
                                                <li>
                                                    <button type="button" class="btn btn-text-primary kirim-barang-btn" data-invoice-id="{{ $sale->id }}">KIRIM BARANG</button>
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

                            <!-- Modal untuk Menampilkan Data Payment -->
                            <div class="modal fade" id="paymentDetailModal{{ $sale->id }}" tabindex="-1" role="dialog" aria-labelledby="paymentDetailModalLabel{{ $sale->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="paymentDetailModalLabel{{ $sale->id }}">Detail Pembayaran</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Tampilkan Data Payment -->
                                            @if ($sale->payment)
                                                <div class="form-group">
                                                    <label>Bukti Pembayaran (Photo)</label>
                                                    <img src="{{ asset($sale->payment->photo) }}" alt="Bukti Pembayaran" style="max-width: 100%; height: auto;">
                                                </div>
                                                <div class="form-group">
                                                    <label>File PPN</label>
                                                    <a href="{{ asset($sale->payment->pph) }}" target="_blank" class="btn btn-link">Lihat File PPN</a>
                                                </div>
                                                <div class="form-group">
                                                    <label>File PPH</label>
                                                    <a href="{{ asset($sale->payment->ppn) }}" target="_blank" class="btn btn-link">Lihat File PPH</a>
                                                </div>
                                            @else
                                                <p>Tidak ada data pembayaran.</p>
                                            @endif

                                            <!-- Form untuk Mengubah Status -->
                                            <form id="updateStatusForm{{ $sale->id }}" action="{{ route('sales.updateStatus', $sale->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group">
                                                    <label for="status{{ $sale->id }}">Status</label>
                                                    <select class="form-control" id="status{{ $sale->id }}" name="status">
                                                        <option value="pending" {{ $sale->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="completed" {{ $sale->status == 'completed' ? 'selected' : '' }}>Complete</option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@push('custom-scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#salesTable').DataTable();
            $('.kirim-barang-btn').on('click', function() {
                var invoiceId = $(this).data('invoice-id');
                console.log(invoiceId);

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Ingin memproses pengiriman barang ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, kirim!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika pengguna mengkonfirmasi, lakukan AJAX request
                        $.ajax({
                            url: '/kirim/' + invoiceId,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}', // Sertakan CSRF token
                                delivery_date: new Date().toISOString().slice(0, 10) // Contoh data tambahan
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    Swal.fire(
                                        'Berhasil!',
                                        response.message,
                                        'success'
                                    ).then(() => {
                                        location.reload(); // Reload halaman untuk memperbarui status
                                    });
                                } else {
                                    Swal.fire(
                                        'Gagal!',
                                        response.message,
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Terjadi kesalahan!',
                                    'Permintaan gagal diproses. Silakan coba lagi.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

        });
        
        function confirmDelete(saleId) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit form jika pengguna mengonfirmasi
                        document.getElementById('deleteForm' + saleId).submit();
                    }
                });
            }
    </script>
    <script>
        // Tangani submit form update status
        document.querySelectorAll('form[id^="updateStatusForm"]').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Mencegah form submit default

                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 3000
                        }).then(() => {
                            window.location.reload(); // Reload halaman setelah sukses
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat mengirim data.',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
            });
        });
    </script>
@endpush
@endsection