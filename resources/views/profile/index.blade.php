@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')
<div id="appCapsule">
    <div class="section mt-3 text-center">
        <div class="avatar-section">
            <input type="file" class="upload" name="foto" id="avatar" accept=".jpg, .jpeg, .gif, .png" capture="camera">
            <a href="#">
            @if(Auth::user()->foto == '')
                <img src="{{ asset('content/avatar.jpg') }}" alt="image" class="imaged w100 rounded">
            @else
                <img src="{{ asset('photo/' . Auth::user()->foto ) }}" alt="avatar" class="imaged w100 rounded">
            @endif
                <span class="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24"><path fill="currentColor" d="M20.71 7.04c.39-.39.39-1.04 0-1.41l-2.34-2.34c-.37-.39-1.02-.39-1.41 0l-1.84 1.83l3.75 3.75M3 17.25V21h3.75L17.81 9.93l-3.75-3.75z"/></svg>
                </span>
            </a>
        </div>
    </div>

    <div class="section mt-2 mb-2">
        <div class="section-title">Profil</div>
        <div class="card">
            <div class="card-body">
                <form id="update-profile" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label" for="nik">No Hp</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ Auth::user()->no_hp }}" >
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label" for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" required>
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                
                    <hr>
                    <button type="submit" class="btn btn-purple mr-1 btn-block btn-profile">Simpan</button>
                </form>
                
            </div>
        </div>
    </div>

    <div class="section mt-2 mb-2">
        <div class="section-title">Update Password</div>
        <div class="card">
            <div class="card-body">
                <form id="update-password" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label" for="email">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" style="background:#eeeeee" readonly>
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label" for="password">Password baru</label>
                            <input type="password" class="form-control" name="password" id="password" autocomplete="new-password">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-purple mr-1 btn-block">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/sweet-alert.js') }}"></script>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        function loading(){
        $(".loading").show();
        $(".loading").delay(1000).fadeOut(600);
    }
        $('#update-profile').submit(function (e) {
            e.preventDefault();
            if ($('#name').val() == '') {
                swal.fire({
                    title: 'Oops!',
                    text: 'Harap bidang inputan tidak boleh ada yang kosong.!',
                    icon: 'error',
                    timer: 1500,
                });
                return false;
            } else {
                loading();
                $.ajax({
                    url: '{{ route('profile.update') }}',
                    type: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    async: false,
                    beforeSend: function () { 
                        loading();
                    },
                    success: function (response) {
                        if (response.status == 'success') {
                            swal.fire({
                                title: 'Berhasil!',
                                text: 'Profil berhasil di perbaharui!',
                                icon: 'success',
                                timer: 2000,
                            });
                            // Reload the page to reflect changes, if needed
                            console.log(response.data);
                            $(".btn-profile").text('Simpan');
                        } else {
                            swal.fire({
                                title: 'Oops!',
                                text: response.message,
                                icon: 'error',
                                timer: 1500,
                            });
                        }
                    },
                    error: function (xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = '';
                    if (errors) {
                        Object.keys(errors).forEach(function (key) {
                            errorMessage += errors[key] + '\n';
                        });
                    } else {
                        errorMessage = xhr.responseJSON.message || 'Terjadi kesalahan.';
                    }
                    swal.fire({ title: 'Oops!', text: errorMessage, icon: 'error', timer: 2000 });
                },
                    complete: function () {
                        $(".loading").hide();
                    },
                });
            }
        });

        $('#update-password').submit(function (e) {
        e.preventDefault();

        // Validasi password
        if ($('#password').val() === '') {
            swal.fire({ title: 'Oops!', text: 'Password tidak boleh kosong!', icon: 'error', timer: 1500 });
            return;
        }

        // Mulai loading
        loading();

        $.ajax({
            url: '{{route('profile.updatepassword')}}',
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function () {
                loading();
            },
            success: function (response) {
                if (response.status === 'success') {
                    swal.fire({ title: 'Berhasil!', text: 'Password berhasil diperbaharui!', icon: 'success', timer: 2000 });
                    setTimeout(function () { location.reload(); }, 2500);
                } else {
                    swal.fire({ title: 'Oops!', text: response.message, icon: 'error', timer: 2000 });
                }
            },
            error: function (xhr) {
                let errors = xhr.responseJSON.errors;
                let errorMessage = '';
                if (errors) {
                    Object.keys(errors).forEach(function (key) {
                        errorMessage += errors[key] + '\n';
                    });
                } else {
                    errorMessage = xhr.responseJSON.message || 'Terjadi kesalahan.';
                }
                swal.fire({ title: 'Oops!', text: errorMessage, icon: 'error', timer: 2000 });
            },
            complete: function () {
                $(".loading").hide();
            }
        });
    });

    //     /* --------- UPDATE PHOTO PROFILE ---------------*/
$(document).on('change','#avatar',function(){
    var file_data = $('#avatar').prop('files')[0];  
    var image_name = file_data.name;
    var image_extension = image_name.split('.').pop().toLowerCase();

    if(jQuery.inArray(image_extension, ['gif', 'jpg', 'jpeg', 'png']) == -1){
        swal({title: 'Oops!', text: 'File yang diunggah tidak sesuai dengan format, File harus jpg, jpeg, gif, png.!', icon: 'error', timer: 2000});
        return; // Stop further execution if the file format is incorrect
    }

    var form_data = new FormData();
    form_data.append("foto", file_data); // Ganti "file" menjadi "photo"
    
    $.ajax({
        url: '{{route('profile.updatephoto')}}',
        method: 'POST',
        data: form_data,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function() {
            loading();
        },
        success: function(response) {
            if (response.status === 'success') {
                console.log(response.data);
                swal.fire({title: 'Berhasil!', text: 'Photo Profil berhasil diperbaharui.!', icon: 'success', timer: 1500});
                setTimeout(function() { location.reload(); }, 1600);
            } else {
                console.log(response.message);
                swal.fire({title: 'Oops!', text: response.message, icon: 'error', timer: 2000});
            }
        },
        error: function(xhr) {
            console.log("Error response: ", xhr.responseText);
            swal({title: 'Error!', text: 'Terjadi kesalahan saat menyimpan data. Status: ' + xhr.status, icon: 'error', timer: 1500});
        }
    });
});

    });
    
    </script>
@endsection



