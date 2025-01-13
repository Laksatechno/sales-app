@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')
<div class="section mt-2">
    <div class="section-heading">
        <h2 class="title">Brosur</h2>
        @if (Auth:: user()->role == 'superadmin')
        <a href="{{ route('brochures.create') }}" class="btn btn-primary">Tambah Brosur</a>
        @endif
    </div>
    <div class="transactions">
        <!-- item -->
        @foreach ($brochures as $brochure)

        <div class="item">
            <div class="detail">
                {{-- <img src="assets/img/sample/brand/1.jpg" alt="img" class="image-block imaged w48"> --}}
                <div>
                    <strong>{{ $brochure->title }}</strong>
                    <p>{{ $brochure->description }}</p>
                </div>
            </div>
            <div class="right">
                <a href="{{ route('brochures.download', $brochure) }}" class="btn btn-success btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path fill="currentColor" fill-opacity="0" stroke-dasharray="20" stroke-dashoffset="20" d="M12 4h2v6h2.5l-4.5 4.5M12 4h-2v6h-2.5l4.5 4.5"><animate attributeName="d" begin="0.5s" dur="1.5s" repeatCount="indefinite" values="M12 4h2v6h2.5l-4.5 4.5M12 4h-2v6h-2.5l4.5 4.5;M12 4h2v3h2.5l-4.5 4.5M12 4h-2v3h-2.5l4.5 4.5;M12 4h2v6h2.5l-4.5 4.5M12 4h-2v6h-2.5l4.5 4.5"/><animate fill="freeze" attributeName="fill-opacity" begin="0.7s" dur="0.5s" values="0;1"/><animate fill="freeze" attributeName="stroke-dashoffset" dur="0.4s" values="20;0"/></path><path stroke-dasharray="14" stroke-dashoffset="14" d="M6 19h12"><animate fill="freeze" attributeName="stroke-dashoffset" begin="0.5s" dur="0.2s" values="14;0"/></path></g></svg>
                </a>
                @if (Auth:: user()->role == 'superadmin')
                <a href="{{ route('brochures.edit', $brochure) }}" class="btn btn-warning btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24"><path fill="currentColor" d="M20.71 7.04c.39-.39.39-1.04 0-1.41l-2.34-2.34c-.37-.39-1.02-.39-1.41 0l-1.84 1.83l3.75 3.75M3 17.25V21h3.75L17.81 9.93l-3.75-3.75z"/></svg>
                </a>
                <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $brochure->id }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 4h-3.5l-1-1h-5l-1 1H5v2h14M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6z"/></svg>
                </button>
                @endif
            </div>
        </div>

        @endforeach
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