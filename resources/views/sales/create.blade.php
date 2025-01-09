@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')

<div class="section mt-2">
    <div class="card">
        <div class="card-body">
            <h2>Tambah Penjualan</h2>

            <form action="{{ route('sales.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="customer_id">Customer</label>
                    <select name="customer_id" id="customer_id" class="form-control ">
                        <option value="">Pilih Customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="product_id">Produk</label>
                    <select id="product_id" class="form-control">
                        <option value="">Pilih Produk</option>
                    </select>
                    @error('product_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="stock">Stock</label>
                            <input type="number" id="stock" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="price">Harga</label>
                            <input type="number" id="price" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="quantity">Jumlah</label>
                    <input type="number" id="quantity" class="form-control">
                </div>
                <button type="button" id="addItem" class="btn btn-primary">Tambah Barang</button>

                <table class="table mt-3" id="itemsTable">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <input type="hidden" name="items" id="itemsInput">
                <div class="form-group">
                    <label for="due_date">Jenis Tempo</label>
                    <select name="due_date" id="due_date" class="form-control">
                        <option value="1">1 Bulan</option>
                        <option value="2">2 Bulan</option>
                        <option value="3">COD (Bayar di Tempat)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tax_status">Tax Status</label>
                    <select name="tax_status" id="tax_status" class="form-control">
                        <option value="non-ppn">Non-PPN</option>
                        <option value="ppn">PPN</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Diskon (%) </label>
                    <input type="text" name="diskon" class="form-control" placeholder="Misal: 10" value="0">
                </div>

                <button type="submit" class="btn btn-success mt-3">Buat Penjualan</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let items = [];

    function updateItemsTable() {
        const tableBody = $('#itemsTable tbody');
        tableBody.empty();
        items.forEach((item, index) => {
            const row = `<tr>
                <td>${item.name}</td>
                <td>${item.quantity}</td>
                <td>${item.price}</td>
                <td>${item.total}</td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeItem(${index})">Hapus</button></td>
            </tr>`;
            tableBody.append(row);
        });

        $('#itemsInput').val(JSON.stringify(items));
    }

    function removeItem(index) {
        // Ambil item yang akan dihapus
        const removedItem = items[index];

        // Kembalikan stok ke dropdown
        const productOption = $(`#product_id option[value="${removedItem.product_id}"]`);
        const currentStock = parseInt(productOption.data('stock'), 10);
        const newStock = currentStock + removedItem.quantity;

        // Perbarui stok di atribut data dan teks dropdown
        productOption.data('stock', newStock);
        productOption.text(`${removedItem.name} - Stok: ${newStock} - ${removedItem.price.toLocaleString()}`);

        // Hapus item dari array items
        items.splice(index, 1);

        // Perbarui tabel
        updateItemsTable();
        $('#quantity').val('');
        $('#price').val('');
        $('#stock').val('');
        $('#addItem').prop('disabled', true);

    }


    $('#product_id').change(function () {
        const selectedOption = $(this).find(':selected');
        const price = selectedOption.data('price') || 0;
        const stock = selectedOption.data('stock') || 0;
        $('#price').val(price);
        $('#stock').val(stock);
        $('#addItem').prop('disabled', stock === 0);
        $('#quantity').attr('max', stock); // Batasi input jumlah sesuai stok
        $('#quantity').val(''); // Reset jika ada input sebelumnya
    });

    $('#addItem').click(function () {
        const productId = $('#product_id').val();
        const productName = $('#product_id option:selected').data('name');
        const quantity = parseInt($('#quantity').val(), 10);
        const price = parseFloat($('#price').val());
        let stock = parseInt($('#product_id option:selected').data('stock'), 10);

        if (!productId || !quantity || !price || quantity > stock) {
            alert('Lengkapi data barang dengan benar atau jumlah melebihi stok.');
            return;
        }

        const total = quantity * price;

        // Update stok di dropdown
        stock -= quantity;
        $(`#product_id option[value="${productId}"]`).data('stock', stock);
        $(`#product_id option[value="${productId}"]`).text(`${productName} - Stok: ${stock} - ${price.toLocaleString()}`);

        items.push({ product_id: productId, name: productName, quantity, price, total });
        updateItemsTable();

        // Reset input fields
        $('#product_id').val('');
        $('#quantity').val('');
        $('#price').val('');
        $('#stock').val('');
    });

    $('#customer_id').change(function () {
        const customerId = $(this).val();
        const productDropdown = $('#product_id');

        productDropdown.empty().append('<option value="">Pilih Produk</option>').select2({
            placeholder: 'Pilih Produk',
            allowClear: true,
        });

        if (!customerId) {
            return;
        }

        productDropdown.change(function () {
            const selectedOption = $(this).find(':selected');
            const price = selectedOption.data('price') || 0;
            const stock = selectedOption.data('stock') || 0;
            $('#price').val(price);
            $('#stock').val(stock);
            $('#addItem').prop('disabled', stock === 0);
            $('#quantity').attr('max', stock); // Batasi input jumlah sesuai stok
        })
            $.ajax({
                url: `/customers/${customerId}/products`,
                type: 'GET',
                success: function (products) {
                    products.forEach(product => {
                        productDropdown.append(`
                            <option value="${product.product_id}" data-name="${product.product.name}" data-price="${product.price}" data-stock="${product.product.stock}">
                                ${product.product.name} - Stok: ${product.product.stock} - ${product.price.toLocaleString()}
                            </option>
                        `);
                    });
                },
                error: function () {
                    alert('Gagal memuat produk.');
                }
            });

        // if (customerId) {
        //     $.ajax({
        //         url: `/customers/${customerId}/products`,
        //         type: 'GET',
        //         success: function (products) {
        //             products.forEach(product => {
        //                 productDropdown.append(`
        //                     <option value="${product.product_id}" data-name="${product.product.name}" data-price="${product.price}" data-stock="${product.product.stock}">
        //                         ${product.product.name} - Stok: ${product.product.stock} - ${product.price.toLocaleString()}
        //                     </option>
        //                 `);
        //             });
                    
        //         },
        //         error: function () {
        //             alert('Gagal memuat produk.');
        //         }
        //     });
        // }
    });
</script>
@endsection
