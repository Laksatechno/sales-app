@extends('layouts.app')
@section ('header')
<div class="appHeader bg-purple text-light">
    <div class="left">
        <a href="#" class="headerButton goBack">
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="2em" viewBox="0 0 12 24"><path fill="currentColor" fill-rule="evenodd" d="m3.343 12l7.071 7.071L9 20.485l-7.778-7.778a1 1 0 0 1 0-1.414L9 3.515l1.414 1.414z"/></svg>
        </a>
    </div>
    <div class="pageTitle ">Search</div>
    <div class="right">
        <a href="#" class="headerButton toggle-searchbox">
            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24"><path fill="currentColor" d="M15.5 14h-.79l-.28-.27a6.5 6.5 0 0 0 1.48-5.34c-.47-2.78-2.79-5-5.59-5.34a6.505 6.505 0 0 0-7.27 7.27c.34 2.8 2.56 5.12 5.34 5.59a6.5 6.5 0 0 0 5.34-1.48l.27.28v.79l4.25 4.25c.41.41 1.08.41 1.49 0s.41-1.08 0-1.49zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14"/></svg>
        </a>
    </div>
</div>
<div id="search" class="appHeader">
    <form class="search-form" action="{{ route('penawaran.index') }}" method="GET">
        <div class="form-group searchbox">
            <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search Customer" value="{{ request()->input('search') }}">
            <input type="hidden" name="user_id" id="userIdInput" value="{{ auth()->user()->id }}">
            
            <a href="#" class="ms-1 close toggle-searchbox">
                <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 1024 1024"><path fill="currentColor" d="M512 0C229.232 0 0 229.232 0 512c0 282.784 229.232 512 512 512c282.784 0 512-229.216 512-512C1024 229.232 794.784 0 512 0m0 961.008c-247.024 0-448-201.984-448-449.01c0-247.024 200.976-448 448-448s448 200.977 448 448s-200.976 449.01-448 449.01m181.008-630.016c-12.496-12.496-32.752-12.496-45.248 0L512 466.752l-135.76-135.76c-12.496-12.496-32.752-12.496-45.264 0c-12.496 12.496-12.496 32.752 0 45.248L466.736 512l-135.76 135.76c-12.496 12.48-12.496 32.769 0 45.249c12.496 12.496 32.752 12.496 45.264 0L512 557.249l135.76 135.76c12.496 12.496 32.752 12.496 45.248 0c12.496-12.48 12.496-32.769 0-45.249L557.248 512l135.76-135.76c12.512-12.512 12.512-32.768 0-45.248"/></svg>
            </i></a>
        </div>
    </form>
</div>
@endsection
@section('content')
<div class="section mt-2">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                {{-- <div class="card-header">
                    <div class="search-bar">
                        <form id="searchForm" action="{{ route('penawaran.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search Customer" value="{{ request()->input('search') }}">
                                <input type="hidden" name="user_id" id="userIdInput" value="{{ auth()->user()->id }}">
                            </div>
                        </form>
                    </div>
                </div> --}}


                {{-- create button tambah penawaran --}}
                <div class="card-body">
                    <a class="btn btn-primary btn-sm btn-block" href="{{ url('/penawaran/new') }}" role="button">Tambah Penawaran</a>
                </div>

                
                <ul id="penawaranContainer" class= "listview image-listview media inset mb-2">
                @if (session('success'))
                <div class="alert alert-success mb-1" role="alert">
                    {!! session('success') !!}
                </div>
                
                @endif  
                    @include('penawaran.partials.penawaran_list')
                </ul>

            </div>

        </div>
    </div>
</div>

<div class="fab-button animate bottom-right dropdown" style="margin-bottom:50px">
    <a href="#" class="fab bg-primary" data-toggle="dropdown">
        <ion-icon name="add-outline" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item bg-primary" href="{{ url('/penawaran/new') }}">
            <ion-icon name="document-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
            <p>Penawaran</p>
        </a>
    </div>
</div>
@endsection
@section('custom-script')


<script>
    $(document).ready(function() {

        
        // Function to load initial data
        function loadInitialData() {
            var userId = $('#userIdInput').val();

            // Show the loading spinner
            $('#penawaranContainer').html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');

            $.ajax({
                url: '{{ route("penawaran.index") }}',
                type: 'GET',
                data: {
                    user_id: userId
                },
                success: function(response) {
                    $('#penawaranContainer').html(response);
                }
            });
        }

        // Call loadInitialData function when page loads
        loadInitialData();

        // Function to handle search input
        var delayTimer;
        $('#searchInput').on('keyup', function() {
            clearTimeout(delayTimer);
            var searchQuery = $(this).val();
            var userId = $('#userIdInput').val();

            // Show the loading spinner
            $('#penawaranContainer').html('<div class="loading-data" role="status"><span class="sr-only">Loading...</span></div>');

            delayTimer = setTimeout(function() {
                $.ajax({
                    url: '{{ route("penawaran.index") }}',
                    type: 'GET',
                    data: {
                        search: searchQuery,
                        user_id: userId
                    },
                    success: function(response) {
                        $('#penawaranContainer').html(response);
                        console.log(response);
                    }
                });
            }, 500); // Delay the request to avoid sending too many requests
        });
    });
</script>


@endsection
