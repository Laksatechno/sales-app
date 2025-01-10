@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')
<div class="section mt-2 p-2 mb-5">
    <div class="card">
    <h1>Brochures</h1>
    <a href="{{ route('brochures.create') }}" class="btn btn-primary mb-3">Create New Brochure</a>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>File</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($brochures as $brochure)
                <tr>
                    <td>{{ $brochure->title }}</td>
                    <td>{{ $brochure->description }}</td>
                    <td>
                        <a href="{{ route('brochures.download', $brochure) }}" class="btn btn-success btn-sm">Download</a>
                    </td>
                    <td>
                        <a href="{{ route('brochures.edit', $brochure) }}" class="btn btn-warning btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $brochure->id }}">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Handle delete button click
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            const brochureId = this.getAttribute('data-id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/brochures/${brochureId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: data.message,
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message,
                            });
                        }
                    });
                }
            });
        });
    });

    document.querySelectorAll('.download-btn').forEach(button => {
        button.addEventListener('click', function () {
            const brochureId = this.getAttribute('data-id');

            Swal.fire({
                title: 'Download File',
                text: "Are you sure you want to download this file?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, download it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/brochures/${brochureId}/download`;
                }
            });
        });
    });
</script>
@endsection