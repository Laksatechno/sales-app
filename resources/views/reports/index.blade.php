@extends('layouts.app')

@section('header')
    @include('layouts.appheaderback')
@endsection

@section('content')
<div class="section mt-2">
    <div class="card">
        <div class="card-body pt-0 table-responsive">
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
                    <h5 class="text-center">Laporan Penjualan</h5>
                    

                    <!-- Form Filter -->
                    <form id="filter-form" method="GET" action="{{ route('reports.index') }}">
                        <div class="row">
                            <!-- Input Search -->
                            <div class="col-md-3 form-group">
                                <input type="text" name="search" class="form-control search" placeholder="Cari invoice atau customer..." value="{{ request('search') }}">
                            </div>
                            
                            <!-- Rentang Waktu -->
                            <div class="form-group col-md-3">
                                <select name="date_range" id="date-range" class="form-control">
                                    <option value="">Pilih Rentang Waktu</option>
                                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                                    <option value="last_7_days" {{ request('date_range') == 'last_7_days' ? 'selected' : '' }}>7 Hari Terakhir</option>
                                    <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                                    <option value="custom_range" {{ request('date_range') == 'custom_range' ? 'selected' : '' }}>Pilih Rentang Tanggal</option>
                                </select>
                            </div>

                            <!-- Input DateRangePicker -->
                            <div class="form-group col-md-3" id="custom-date-range-container" style="{{ request('date_range') == 'custom_range' ? '' : 'display: none;' }}">
                                <input type="text" name="date_range_picker" id="date-range-picker" class="form-control" placeholder="Tanggal Mulai - Tanggal Akhir">
                                <input type="hidden" name="start_date" id="start-date" value="{{ request('start_date') }}">
                                <input type="hidden" name="end_date" id="end-date" value="{{ request('end_date') }}">
                            </div>

                            <!-- Jenis Transaksi -->
                            <div class="form-group col-md-3">
                                <select name="transaction_type" id="transaction-type" class="form-control">
                                    <option value="">Semua Transaksi</option>
                                    <option value="customer" {{ request('transaction_type') == 'customer' ? 'selected' : '' }}>Customer</option>
                                    <option value="user" {{ request('transaction_type') == 'user' ? 'selected' : '' }}>User</option>
                                </select>
                            </div>

                            <!-- Marketing -->
                            <div class="form-group col-md-3">
                                <select name="marketing_id" id="marketing-id" class="form-control">
                                    <option value="">Pilih Marketing</option>
                                    @foreach ($marketings as $marketing)
                                    <option value="{{ $marketing->id }}" {{ request('marketing_id') == $marketing->id ? 'selected' : '' }}>{{ $marketing->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>

                    <button id="print-pdf-btn" class="btn btn-danger btn-sm btn-block mb-2">
                        <i class="fas fa-file-pdf"></i>PDF
                    </button>
                    {{-- <div class="transactions mt-1">
                        <div class="item">
                            <div class="detail row">
                                <div class="col">
                                    <strong>Invoice</strong>
                                </div>
                                <div class="col">
                                    <strong>Customer</strong>
                                </div>
                                <div class="col">
                                    <strong>Tanggal</strong>
                                </div>
                                <div class="col">
                                    <strong>Total</strong>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <!-- Daftar Penjualan -->
                    <div id="sales-list" class="transactions">
                        @include('reports.sales_list', ['sales' => $sales])
                    </div>
                </div>

                <!-- Tab Panel Per Produk -->
                <div class="tab-pane fade" id="perproduk" role="tabpanel" aria-labelledby="cards-tab">
                    <table class="table table-bordered mt-3" id="productTable" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Lihat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>
                                        <a href="{{ route('reports.show', $product->id) }}" class="btn btn-primary">Lihat</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Tab Panel Per Customer -->
                <div class="tab-pane fade" id="percustomer" role="tabpanel" aria-labelledby="cards-tab">
                    <table class="table table-bordered mt-3" id="customerTable" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Lihat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $customer)
                                <tr>
                                    <td>{{ $customer->name }}</td>
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

@push('custom-scripts')
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- DateRangePicker CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Moment.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<!-- DateRangePicker JS -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
    $(document).ready(function () {
        // Inisialisasi DateRangePicker
        $('#date-range-picker').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'YYYY-MM-DD'
            }
        });
        
        // $('#productTable').DataTable();
        // $('#customerTable').DataTable();
        

        // Event ketika rentang tanggal dipilih
        $('#date-range-picker').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            $('#start-date').val(picker.startDate.format('YYYY-MM-DD'));
            $('#end-date').val(picker.endDate.format('YYYY-MM-DD'));
            loadSales(); // Muat ulang data penjualan
        });

        // Event ketika rentang tanggal dihapus
        $('#date-range-picker').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            $('#start-date').val('');
            $('#end-date').val('');
            loadSales(); // Muat ulang data penjualan
        });

        // Fungsi untuk memuat data penjualan berdasarkan filter
        function loadSales() {
            const formData = $('#filter-form').serialize(); // Ambil data form
            $.ajax({
                url: '{{ route("reports.index") }}',
                method: 'GET',
                data: formData,
                success: function (response) {
                    $('#sales-list').html(response); // Perbarui daftar penjualan
                    console.log(response);
                },
                error: function (xhr) {
                    alert('Terjadi kesalahan saat memuat data.');
                }
            });
        }

        // Event listener untuk perubahan pada input filter
        $('.search, #date-range, #transaction-type, #marketing-id').on('input change', function () {
            loadSales(); // Muat ulang data penjualan
        });

        // Toggle input rentang tanggal
        $('#date-range').on('change', function () {
            if ($(this).val() === 'custom_range') {
                $('#custom-date-range-container').show();
            } else {
                $('#custom-date-range-container').hide();
            }
        });

        // Handle tombol cetak PDF
    $('#print-pdf-btn').click(function () {
        const formData = $('#filter-form').serialize(); // Ambil data form

        // Kirim data filter ke server menggunakan AJAX
        $.ajax({
            url: '{{ route("reports.print") }}',
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token
            },
            xhrFields: {
                responseType: 'blob' // Mengindikasikan respons adalah file binary
            },
            success: function (response) {
                // Buat URL dari blob
                const blob = new Blob([response], { type: 'application/pdf' });
                const url = window.URL.createObjectURL(blob);

                // Buka PDF di tab baru
                window.open(url, '_blank');

                // Bersihkan URL setelah digunakan
                window.URL.revokeObjectURL(url);
            },
            error: function (xhr) {
                alert('Terjadi kesalahan saat mencetak PDF.');
            }
        });
    });
    });
</script>
@endpush