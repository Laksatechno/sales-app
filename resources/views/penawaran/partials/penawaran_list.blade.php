
@forelse ($penawarans as $data)
    <li>
            <div class="item">
                <div class="in">
                    <div class="text-muted">{{ $data->customer }} {{ $data->perihal }} {{ \Carbon\Carbon::parse($data->created_at )->locale('id_ID')->isoFormat('dddd, D MMM YYYY') }}</div>

                </div>
                               
                <div class="status"> 
                    {{-- button detail dropdown --}}
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            Detail
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <!-- Item Dropdown -->
                            <li>
                                <a class="dropdown-item" href="{{ url('/penawaran/detail/' . $data->id) }}">Lihat</a>
                            </li>
                            <li>
                            <li><a class="dropdown-item" href="{{ route('print.penawaran', $data->id) }}">Print PDF</a></li>
                            <li>
                                <form id="delete-form-{{ $data->id }}" action="{{ url('/penawaran/delete/' . $data->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <a class="dropdown-item" onclick="confirmDelete({{ $data->id }})">
                                        Hapus
                                    </a>
                                </form>
                            </li>

                        </ul>
                    </div>


                    {{-- <a href="{{ url('/penawaran/detail/' . $data->id) }}" class="btn btn-warning btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24"><g fill="none" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M7 7H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-1"/><path d="M20.385 6.585a2.1 2.1 0 0 0-2.97-2.97L9 12v3h3zM16 5l3 3"/></g></svg>
                    </a> 
                
                    <a href="{{ route('print.penawaran', $data->id) }}" class="btn btn-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24"><g fill="none" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M17 17h2a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h2m10-8V5a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v4"/><path d="M7 15a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2z"/></g></svg>
                    </a> --}}
                

                </div>
            </div>
    </li>
@empty
    <p style="text-align: center">Data Penawaran tidak ditemukan.</p>
@endforelse
<!-- Tambahkan ini di file Blade Anda -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Anda yakin menghapus penawaran ini?',
            text: "Anda tidak akan dapat mengembalikan data ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
