@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')
<div class="section mt-2">
    <div class="card">
    <div class  = "card-header">
        <div class="section-title">Daftar Produk</div>
    </div>
        <div class="card-body">
            {{-- <h1>Daftar yang tersedia</h1> --}}
            @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
            @elseif (session('error'))
            <div class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
            @endif
            <form id="add-to-cart-form" method="POST">
                @csrf <!-- Tambahkan CSRF token untuk keamanan -->
                <div class="form-group">
                    <label for="product-select">Pilih Produk:</label>
                    <select class="form-control" id="product-select" name="product_id">
                        @foreach ($products as $product)
                        <option value="{{ $product->product_id }}" data-price="{{ $product->price }}">
                            {{ $product->product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }} (Stok: {{ $product->product->stock }})
                        </option>
                        @endforeach
                    </select>
                </div>
            
                <div class="form-group">
                    <label for="quantity">Jumlah:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" class="form-control">
                </div>
            
                <button type="button" id="add-to-cart-btn" class="btn btn-primary">Tambah Keranjang</button>
            </form>
            <div class="section-title mt-3">Pilihan</div>
            <hr>

            <!-- Cart Section -->
            <h3>Keranjang Anda</h3>
            <table class="table-responsive" id="cart-table" style="display: none;">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="cart-items">
                    <!-- Cart items will be populated here -->
                </tbody>
            </table>

            <a href="{{ route('shop.checkout') }}" class="btn btn-success btn-block mt-3 mb-3 ">Tekan Disini Untuk Pembelian</a>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Handle "Tambah Keranjang" button click
        $('#add-to-cart-btn').click(function () {
            const productId = $('#product-select').val(); // Ambil product_id dari select
            const quantity = $('#quantity').val(); // Ambil jumlah dari input
            const price = $('#product-select').find(':selected').data('price'); // Ambil harga dari data-price

            // Kirim data ke server menggunakan AJAX
            $.ajax({
                url: '{{ route("shop.add_to_cart") }}', // Route untuk menambahkan ke keranjang
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token
                    product_id: productId,
                    quantity: quantity,
                    price: price,
                },
                success: function (response) {
                    // alert(response.message); // Tampilkan pesan sukses
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 1500,
                    });
                    // Perbarui tampilan keranjang
                    if (response.cart) {
                        updateCartTable(response.cart);
                    }
                },
                error: function (response) {
                    // alert(response.responseJSON.message); // Tampilkan pesan error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: response.responseJSON.message,
                        timer: 1500,
                    });
                }
            });
        });

        // Fungsi untuk memperbarui tabel keranjang
        function updateCartTable(cart) {
            let cartHtml = '';
            $.each(cart, function (key, item) {
                cartHtml += `<tr>
                                <td>${item.name}</td>
                                <td>${new Intl.NumberFormat().format(item.price)}</td>
                                <td>${item.quantity}</td>
                                <td>${new Intl.NumberFormat().format(item.total)}</td>
                                <td>
                                    <button class="btn btn-danger remove-from-cart" data-id="${key}">Hapus</button>
                                </td>
                             </tr>`;
            });
            $('#cart-items').html(cartHtml);
            $('#cart-table').toggle(cartHtml !== ''); // Tampilkan tabel jika ada item

            // Bind event untuk tombol "Hapus"
            bindRemoveFromCart();
        }

        // Fungsi untuk menghapus item dari keranjang
        function bindRemoveFromCart() {
            $('.remove-from-cart').click(function () {
                const productId = $(this).data('id');

                $.ajax({
                    url: '{{ route("shop.remove_from_cart") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: productId,
                    },
                    success: function (response) {
                        // alert(response.message);
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 1500,
                        });

                        // Perbarui tampilan keranjang
                        if (response.cart) {
                            updateCartTable(response.cart);
                        }
                    },
                    error: function (response) {
                        // alert(response.responseJSON.message);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: response.responseJSON.message,
                            timer: 1500,
                        });
                    }
                });
            });
        }

        // Binding awal untuk tombol "Hapus"
        bindRemoveFromCart();
    });
</script>
@endsection
