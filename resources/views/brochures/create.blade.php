@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Create New Brochure</h1>
    <form id="createBrochureForm" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="file">File</label>
            <input type="file" name="file" id="file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Submit</button>
    </form>
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