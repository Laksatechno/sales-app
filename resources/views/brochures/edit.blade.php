@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')
<div class="container mt-5">
    <h1>Edit Brochure</h1>
    <form id="editBrochureForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $brochure->title }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ $brochure->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="file">File</label>
            <input type="file" name="file" id="file" class="form-control">
            {{-- <small>Current file: <a href="{{ route('brochures.download', $brochure) }}">{{ $brochure->file }}</a></small> --}}
        </div>
        <button type="submit" class="btn btn-primary mt-3">Update</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('editBrochureForm').addEventListener('submit', function (e) {
        e.preventDefault();

        // Tampilkan loading Swal.fire
        Swal.fire({
            title: 'Updating...',
            text: 'Please wait while we update your brochure.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Kirim data menggunakan AJAX
        const formData = new FormData(this);

        fetch("{{ route('brochures.update', $brochure) }}", {
            method: 'POST', // Method POST karena FormData tidak mendukung PUT
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-HTTP-Method-Override': 'PUT' // Override method menjadi PUT
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
                text: 'An error occurred while updating the brochure.',
            });
        });
    });
</script>
@endsection