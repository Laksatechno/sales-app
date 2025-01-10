@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')
<div class="container mt-5">
    <h1>{{ $brochure->title }}</h1>
    <p>{{ $brochure->description }}</p>
    {{-- <a href="{{ route('brochures.download', $brochure) }}" class="btn btn-success">Download File</a> --}}
</div>
<script> 
document.querySelector('.download-btn').addEventListener('click', function () {
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
</script>
@endsection