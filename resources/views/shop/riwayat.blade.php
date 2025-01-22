@extends('layouts.app')

    @section('header')
        @include('layouts.appheaderback')
    @endsection

    @section('content')
    <div class="section mt-4">
        <div class="section-heading">
            <h2 class="title">Transaksi</h2>
        </div>
        <div class="transactions">
            <!-- item -->
            @foreach ($sales as $sale)
            <div class="item">
                <div class="detail">
                    <div>
                        <strong>INVOICE {{ $sale->invoice_number }}</strong>
                        <p>{{ $sale->details[0]->product->name ?? 'No product name available' }} x{{ $sale->details[0]->quantity ?? 'No quantity available' }} </p>
                        <p>{{ $sale->created_at->format('d-m-Y') }}</p>
                        <p>{{ $sale->marketing->name }}</p>
                        <span class="badge bg-secondary">Rp. {{ number_format($sale->total + $sale->tax) }}</span>
                        @if (Auth::user()->tipe_pelanggan == 'Reguler')
                            @if ($sale->payment) <!-- Periksa apakah ada relasi payment -->
                                <span class="badge bg-success">Terbayar</span>
                            @else
                                <span class="badge bg-danger">Belum Bayar</span>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="right">
                    @if (Auth::user()->tipe_pelanggan == 'Reguler' && !$sale->payment) <!-- Tampilkan tombol bayar jika belum terbayar -->
                    <!-- Tombol Bayar -->
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#bayarModal{{ $sale->id }}">
                        Bayar
                    </button>
            
                    <!-- Modal -->
                    <div class="modal fade" id="bayarModal{{ $sale->id }}" tabindex="-1" role="dialog" aria-labelledby="bayarModalLabel{{ $sale->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="bayarModalLabel{{ $sale->id }}">Form Pembayaran</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="paymentForm{{ $sale->id }}" onsubmit="submitPaymentForm(event, {{ $sale->id }})">
                                    @csrf
                                    <div class="modal-body">
                                        <!-- Form Input Gambar dengan Preview -->
                                        <div class="form-group">
                                            <label for="gambarInput{{ $sale->id }}">Upload Bukti Pembayaran</label>
                                            <input type="file" class="form-control-file" id="gambarInput{{ $sale->id }}" name="photo" accept="image/*" required>
                                            <img id="gambarPreview{{ $sale->id }}" src="#" alt="Preview Gambar" style="display: none; max-width: 100%; margin-top: 10px;">
                                        </div>
            
                                        <!-- Input File PPN -->
                                        <div class="form-group">
                                            <label for="ppnInput{{ $sale->id }}">Upload File PPN</label>
                                            <input type="file" class="form-control-file" id="ppnInput{{ $sale->id }}" name="pph">
                                        </div>
            
                                        <!-- Input File PPH -->
                                        <div class="form-group">
                                            <label for="pphInput{{ $sale->id }}">Upload File PPH</label>
                                            <input type="file" class="form-control-file" id="pphInput{{ $sale->id }}" name="ppn">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
            
                    <a href="{{ url('shop/detailsinvoice', $sale->id) }}" class="btn btn-success btn-sm">
                        Detail
                    </a>
                    <!-- Tombol Edit (Hanya Tampil Jika Kurang dari 24 Jam) -->
                    @if ($sale->created_at->diffInHours(now()) < 24)
                        <a href="{{ url('shop/edit', $sale->id) }}" class="btn btn-warning btn-sm">
                            Edit
                        </a>
                    @endif
                </div>
            </div>
            @endforeach
            <!-- * item -->
        </div>
    </div>
    @endsection

    @push('custom-scripts')

    <script>
        // Fungsi untuk menampilkan preview gambar
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function(event) {
                const file = event.target.files[0];
                const previewId = event.target.id.replace('Input', 'Preview');
                const preview = document.getElementById(previewId);

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.src = '#';
                    preview.style.display = 'none';
                }
            });
        });

        // Fungsi untuk menangani submit form dengan AJAX dan SweetAlert2
        function submitPaymentForm(event, saleId) {
            event.preventDefault(); // Mencegah form submit default

            const form = document.getElementById(`paymentForm${saleId}`);
            const formData = new FormData(form);

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan menyimpan data pembayaran ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/payment/${saleId}`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                }
            });
        }
    </script>
    @endpush