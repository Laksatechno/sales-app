@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')
<div class="section mt-2">
    <div class="section-heading">
        <h2 class="title">Brosur</h2>
        <a href="{{ route('brochures.index') }}" class="btn btn-primary">Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
    <h1>Tambah Brosur</h1>
    <form id="createBrochureForm" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title">Nama Brosur</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Misal : Blood Bag" required>
        </div>
        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea name="description" id="description" class="form-control " placeholder="Misal : Kantong untuk menyimpan darah." required rows="5"></textarea>
        </div>
        <div class="form-group">
            <label for="file">File Brosur</label>
            <input type="file" name="file" id="file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">SIMPAN</button>
    </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('createBrochureForm').addEventListener('submit', function (e) {
        e.preventDefault();

        // Tampilkan loading Swal.fire
        Swal.fire({
            title: 'Uploading...',
            text: 'Please wait while we save your brochure.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Kirim data menggunakan AJAX
        const formData = new FormData(this);

        fetch("{{ route('brochures.store') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            Swal.close(); // Tutup loading

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                }).then(() => {
                    window.location.href = "{{ route('brochures.index') }}"; // Redirect ke halaman index
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message,
                });
            }
        })
        .catch(error => {
            Swal.close(); // Tutup loading
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'An error occurred while uploading the file.',
            });
        });
    });
</script>
@endsection