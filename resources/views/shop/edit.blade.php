@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')
    <div class="section mt-2">
        <div class="card">
            <div class="card-body">
                <h2 id="invoiceNumber">Edit Invoice</h2>
                {{-- <h2 id="saleId"></h2> --}}

                <div id="alertContainer"></div>

                <form id="updateForm">
                    <div class="form-group">
                        <label for="sale_id">Sale ID</label>
                        <input id="saleId" name="saleId" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="product_id">Produk</label>
                        <select id="product_id" class="form-control" name="product_id">
                            <option value="">Pilih Produk</option>
                        </select>
                        <div class="text-danger" id="productError"></div>
                    </div>
                
                    <div class="form-group">
                        <label for="price">Harga</label>
                        <input type="number" id="price" class="form-control" name="price" disabled>
                    </div>

                    <div class="form-group">
                        <label for="quantity">Jumlah</label>
                        <input type="number" id="quantity" class="form-control" name="quantity" min="1">
                    </div>
                    <button type="button" id="saveButton" class="btn btn-primary">Simpan</button>
                </form>
                <hr>
            <h3>Keranjang Anda</h3>
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="cartDetails">
                </tbody>
            </table>
            </div>

        </div>
    </div>

    <script>
        const id = "{{ $id }}"; // ID dari URL

        // Muat data dari API
        $(document).ready(function () {
            fetchEditData();
        });

        function fetchEditData() {
            $('#price').val('');
            $('#quantity').val('');
            $.ajax({
                url: `editjson/${id}`,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Pastikan token CSRF ada di halaman
                },
                success: function (response) {
                    console.log("product" + response.productsprice);
                    // Update header
                    $('#invoiceNumber').text(`Edit ${response.invoice_number}`);
                    $('#saleId').val(response.id);

                    // Populate produk dropdown
                    const productSelect = $('#product_id');
                    productSelect.empty();
                    productSelect.append('<option value="">Pilih Produk</option>');
                    response.productsprice.forEach(product => {
                        productSelect.append(`<option value="${product.product.id}" data-price="${product.price}">${product.product.name} | Stock : ${product.product.stock} | Harga : ${product.price}</option>`);
                    });

                    // Populate keranjang
                    const cartDetails = $('#cartDetails');
                    cartDetails.empty();
                    response.details.forEach(detail => {
                        console.log("detail" + response.details);
                        cartDetails.append(`
                            <tr>
                                <td>${detail.product_name}</td>
                                <td>${detail.price.toLocaleString('id-ID')}</td>
                                <td>${detail.quantity}</td>
                                <td>${detail.total.toLocaleString('id-ID')}</td>
                                <td>
                                    <button class="btn btn-danger remove" data-id="${detail.id}">Hapus</button>
                                </td>
                            </tr>
                        `);
                    });
                },
                error: function (response) {
                    alert('Gagal memuat data. Coba lagi.');
                    console.log(response);
                }
            });
        }

        // Event listener untuk mengisi harga secara otomatis
        $('#product_id').on('change', function () {
            const selectedProduct = $(this).find(':selected');
            const price = selectedProduct.data('price');
            $('#price').val(price);
        });

        // Hapus item dari keranjang
        $(document).on('click', '.remove', function () {
            const id = $(this).data('id');
            $.ajax({
                url: 'shop/delete-detail/' + id,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function (response) {
                    // alert(response.message);
                    fetchEditData(); // Refresh data
                    if (response.status == 'success') {
                            swal.fire({
                                title: 'Berhasil!',
                                text: 'Pesanan berhasil di perbarui!',
                                icon: 'success',
                                timer: 2000,
                            });
                        } else {
                            swal.fire({
                                title: 'Oops!',
                                text: response.message,
                                icon: 'error',
                                timer: 1500,
                            });
                        }
                },
                error: function (response) {
                    alert(response.responseJSON.message || 'Terjadi kesalahan.');
                }
            });
        });

        // Simpan perubahan
        $('#saveButton').on('click', function () {
            const data = {
                _token: '{{ csrf_token() }}',
                product_id: $('#product_id').val(),
                quantity: $('#quantity').val(),
                price: $('#price').val(),// Pastikan elemen input dengan ID 'price' ada di form
                id : $('#saleId').val() 
            };
            console.log(data);
            // Cek jika product_id dan quantity tidak kosong
            if (data.product_id && data.quantity) {
                $.ajax({
                    url: "{{ route('shop.update')}}",
                    method: 'POST',
                    data: data,
                    success: function (response) {
                        // alert(response.message);
                        console.log('success ' + response.message)
                        fetchEditData(); // Refresh data
                        if (response.status == 'success') {
                            swal.fire({
                                title: 'Berhasil!',
                                text: 'Pesanan berhasil di perbarui!',
                                icon: 'success',
                                timer: 2000,
                            });
                        } else {
                            swal.fire({
                                title: 'Oops!',
                                text: response.message,
                                icon: 'error',
                                timer: 1500,
                            });
                        }
                    },
                    error: function (response) {
                        const errors = response.responseJSON.errors;
                        // if (errors.product_id) {
                        //     $('#productError').text(errors.product_id[0]);
                        // }
                    }
                });
            } else {
                alert('Harap pilih produk dan masukkan jumlah.');
            }
        });

    </script>
@endsection