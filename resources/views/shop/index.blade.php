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
            <table class="table-responsive table ">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>stock</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->product->name }}</td>
                        <td>{{ $product->product->stock }}</td>
                        <td>{{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>
                            <input type="number" id="quantity-{{ $product->product_id }}" value="1" min="1" class="form-control">
                        </td>
                        <td>
                            <button class="btn btn-primary add-to-cart" data-id="{{ $product->product_id }}">Tambah Keranjang</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
    $('.add-to-cart').click(function () {
        const productId = $(this).data('id');
        const quantity = $(`#quantity-${productId}`).val();

        $.ajax({
            url: '{{ route("shop.add_to_cart") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                quantity: quantity,
            },
            success: function (response) {
                alert(response.message);

                // Update the cart display
                if (response.cart) {
                    updateCartTable(response.cart);

                }
            },
            error: function (response) {
                alert(response.responseJSON.message);
            }
        });
    });

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
        $('#cart-table').toggle(cartHtml !== '');

        // Bind the remove event to dynamically added buttons
        bindRemoveFromCart();
    }

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
                    alert(response.message);

                    // Update the cart display
                    if (response.cart) {
                        updateCartTable(response.cart);
                    }
                },
                error: function (response) {
                    alert(response.message);
                }
            });
        });
    }

    // Initial binding
    bindRemoveFromCart();
</script>
@endsection
