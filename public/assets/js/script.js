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
    
    /* ----------- LOGIN ------------*/
    $('#form-login').submit(function (e) {
        e.preventDefault();
    
        // Basic validation
        if ($('#email').val() === '' || $('#password').val() === '') {
            swal({
                title: 'Oops!',
                text: 'Harap bidang inputan tidak boleh ada yang kosong!',
                icon: 'error',
                timer: 1500
            });
            return;
        }
    
        loading(); // Show loading spinner
    
        $.ajax({
            url: "/login",  // The correct URL for your login route
            type: "POST",
            data: new FormData(this),  // Use FormData to handle form submission
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function () {
                loading();
            },
            success: function (data) {
                if (data.status === 'success') {
                    swal({
                        title: 'Berhasil!',
                        text: 'Selamat datang!',
                        icon: 'success',
                        timer: 1500,
                    });
    
                    // Redirect after success
                    setTimeout(function () {
                        window.location.href = '/home';
                    }, 2000);
                } else if (data.status === 'error') {
                    swal({
                        title: 'Oops!',
                        text: data.message,
                        icon: 'error',
                        timer: 1500,
                    });
                }
            },
            error: function(xhr, status, error) {
                var response = JSON.parse(xhr.responseText); // Mengubah respons menjadi objek JSON
                swal({
                    title: 'Error!',
                    text: 'Gagal login. ' + response.message, // Hanya menampilkan isi dari response.message
                    icon: 'error',
                    timer: 1500,
                });
            },            
            complete: function () {
                $(".loading").hide();  // Hide loading spinner
            }
        });
    });

    
    
    
//     /* ----------- REGISTRASI ------------*/
//     $('#form-registrasi').submit(function (e) {
//         e.preventDefault();
//         if($('#email').val()=='' && $('#password').val()=='' && $('#position_id').val()=='' && $('#shift_id').val()=='' && $('#building').val()==''){    
//              swal({title:'Oops!', text: 'Harap bidang inputan tidak boleh ada yang kosong.!', icon: 'error', timer: 1500,});
//             return false;
//             loading();
//         }
//         else{
//             loading();
//             $.ajax({
//                 url:"",
//                 type: "POST",
//                 data: new FormData(this),
//                 processData: false,
//                 contentType: false,
//                 cache: false,
//                 async: false,
//                 beforeSend: function () { 
//                   loading();
//                 },
//                 success: function (data) {
//                     if (data == 'success') {
//                         swal({title: 'Berhasil!', text: 'Selamat Anda berhasil mendaftar!', icon: 'success', timer: 2500,});
//                         setTimeout("location.href = './';",2600);
//                     } else {
//                         swal({title: 'Oops!', text: data, icon: 'error', timer: 1500,});
//                     }
    
//                 },
//                 complete: function () {
//                     $(".loading").hide();
//                 },
//             });
//         }
//     });
    
    
//     /* ----------- FORGOT ------------*/
//     $('#form-forgot').submit(function (e) {
//         e.preventDefault();
//         if($('#email').val()==''){    
//              swal({title:'Oops!', text: 'Harap bidang inputan tidak boleh ada yang kosong.!', icon: 'error', timer: 1500,});
//             return false;
//             loading();
//         }
//         else{
//             loading();
//             $.ajax({
//                 url:"",
//                 type: "POST",
//                 data: new FormData(this),
//                 processData: false,
//                 contentType: false,
//                 cache: false,
//                 async: false,
//                 beforeSend: function () { 
//                   loading();
//                 },
//                 success: function (data) {
//                     if (data == 'success') {
//                         swal({title: 'Berhasil!', text: 'Password baru berhasil disetel ulang, silahkan cek email masuk/spam!', icon: 'success', timer: 2000,});
//                         //setTimeout(function(){ location.reload(); }, 1500);
//                         setTimeout("location.href = './';",3000);
//                     } else {
//                         swal({title: 'Oops!', text: data, icon: 'error', timer: 1500,});
//                     }
    
//                 },
//                 complete: function () {
//                     $(".loading").hide();
//                 },
//             });
//         }
//     });
    
    
//     /* ---------- UPDATE PROFILE -----------------*/
    $('#update-profile').submit(function (e) {
        e.preventDefault();
        if($('#employees_name').val()==''){    
             swal({title:'Oops!', text: 'Harap bidang inputan tidak boleh ada yang kosong.!', icon: 'error', timer: 1500,});
            return false;
            loading();
        }
        else{
            loading();
            $.ajax({
                url:updateprofileUrl,
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
                        swal({title: 'Berhasil!', text:  'Profil berhasil di perbaharui!', icon: 'success', timer: 2000,});
                        // setTimeout(function(){ location.reload(); }, 2500);
                        console.log(response.data );
                        $(".btn-profile").text('Simpan');
                    } else {
                        swal({title: 'Oops!', text: response.message, icon: 'error', timer: 1500,});
                    }
                },
                error: function (xhr) {
                    console.log("Error response: ", xhr.responseText); // Tambahkan log untuk mengetahui error dari server
                    swal({title: 'Error!', text: 'Terjadi kesalahan saat menyimpan data. Status: ' + xhr.status, icon: 'error', timer: 1500,});
                },
                complete: function () {
                    $(".loading").hide();
                },
            });
        }
    });
    
    
    
//     /* ---------- UPDATE PASSWORD-----------------*/
    $('#update-password').submit(function (e) {
        e.preventDefault();

        // Validasi password
        if ($('#employees_password').val() === '') {
            swal({ title: 'Oops!', text: 'Password tidak boleh kosong!', icon: 'error', timer: 1500 });
            return;
        }

        // Mulai loading
        loading();

        $.ajax({
            url: updatepassUrl,
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
                    swal({ title: 'Berhasil!', text: 'Password berhasil diperbaharui!', icon: 'success', timer: 2000 });
                    setTimeout(function () { location.reload(); }, 2500);
                } else {
                    swal({ title: 'Oops!', text: response.message, icon: 'error', timer: 2000 });
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
                swal({ title: 'Oops!', text: errorMessage, icon: 'error', timer: 2000 });
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
    form_data.append("photo", file_data); // Ganti "file" menjadi "photo"
    
    $.ajax({
        url: updatephotoprofileUrl,
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
                swal({title: 'Berhasil!', text: 'Photo Profil berhasil diperbaharui.!', icon: 'success', timer: 1500});
                setTimeout(function() { location.reload(); }, 1600);
            } else {
                console.log(response.message);
                swal({title: 'Oops!', text: response.message, icon: 'error', timer: 2000});
            }
        },
        error: function(xhr) {
            console.log("Error response: ", xhr.responseText);
            swal({title: 'Error!', text: 'Terjadi kesalahan saat menyimpan data. Status: ' + xhr.status, icon: 'error', timer: 1500});
        }
    });
});

    
//     /* --------- LOAD DATA HISTORY ---------------*/
loadData();
    function loadData() {
        $.ajax({
            url: loadhistoryUrl,
            type: 'POST',
            _token: "{{ csrf_token() }}",
            success: function(data) {
                $('.loaddata').html(data);
            }
        });
    }

    loadDataKunjunganRiwayat();

    function loadDataKunjunganRiwayat() {

        $.ajax({
            url: loadhistoryKunjunganUrl,
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}", // Menambahkan CSRF token di sini
            },
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                $('.loaddatakunjunganriwayat').html(data);
            }
        });
    }

    $('.btn-clear').click(function (e) {
        loadData();
        $('.start_date').val('');
        // $('.end_date').val('');
    });

    $('.btn-sortir').click(function (e) {
        var from = $('.start_date').val();
        var to   = $('.end_date').val();

        $.ajax({
            url: loadhistoryUrl ,
            method: "POST",
            data:{from:from,to:to},
            dataType:"text",
            cache: false,
            async: false,
            beforeSend: function () { 
                loading();
            },
            success: function (data) {
                $('.loaddata').html(data);
            },
            complete: function () {
                $(".loading").hide();
            },
        });
    });

    $('.btn-print').click(function (e) {
        var from = $('.start_date').val();
        var to   = $('.end_date').val();
        var type = $('.type').val();
        var url  = "{{ url('/print') }}?action=" + type;

        if (from && to) {
            url += "&from=" + from + "&to=" + to;
        }

        window.open(url, '_blank');
    });

    /* ------------------- UPDATE DATA HISTORY ------------------------- */
    $(document).on('click', '.modal-update', function(){
        $('#modal-show').modal('show');
        var presence_id = $(this).data("id");
        $('#presence_id').val(presence_id);

        var status = $(this).data("status");
        $('#status').val(status);

        var information = $(this).data("information");
        $('#information').val(information);

        var tanggal = $(this).data("date");
        $('.status-date').html(tanggal);
    });

    /* ---------- UPDATE HISTORY-----------------*/
    $('#update-history').submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: updatehistory,
            type: "POST",
            data: $(this).serialize(),
            beforeSend: function () { 
                loading();
            },
            success: function (response) {
                if (response.status == 'success') {
                    swal({ title: 'Berhasil!', text: 'Absensi berhasil diperbarui!', icon: 'success', timer: 2000 });
                    $('#modal-show').modal('hide');
                    loadData();
                } else {
                    swal({ title: 'Oops!', text: 'Terjadi kesalahan.', icon: 'error', timer: 2000 });
                }
            },
            complete: function () {
                $(".loading").hide();
                $('#modal-show').modal('hide');
            },
        });
    });

     /* ------------------- UPDATE DATA HISTORY KUNJUNGAN ------------------------- */
     $(document).on('click', '.modal-update-history-kunjungan', function () {
        // Tampilkan modal
        $('#modal-show-kunjungan').modal('show');
    
        // Ambil data dari tombol
        var kunjungan_id = $(this).data("id");
        var status = $(this).data("status");
        var information = $(this).data("informasi");
        var tanggal = $(this).data("tanggal-kunjungan");
    
        // Set nilai ke dalam form modal
        $('#kunjungan_id').val(kunjungan_id); // Pastikan ada input dengan ID ini
        $('#status_kunjungan').val(status); // Pastikan ada input dengan ID ini
        $('#informasi').val(information); // Pastikan ada input dengan ID ini
        $('.kunjungan-tanggal').html(tanggal); // Pastikan ada elemen dengan class ini
    });
    

    /* ---------- UPDATE HISTORY-----------------*/
    $('#update-history-kunjungan').submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: updatehistory,
            type: "POST",
            data: $(this).serialize(),
            beforeSend: function () { 
                loading();
            },
            success: function (response) {
                if (response.status == 'success') {
                    swal({ title: 'Berhasil!', text: 'Absensi berhasil diperbarui!', icon: 'success', timer: 2000 });
                    $('#modal-show-kunjungan').modal('hide');
                    loadData();
                } else {
                    swal({ title: 'Oops!', text: 'Terjadi kesalahan.', icon: 'error', timer: 2000 });
                }
            },
            complete: function () {
                $(".loading").hide();
                $('#modal-show-kunjungan').modal('hide');
            },
        });
    });


    
    
//         /* --------------------------------
//             LOAD DATA CUTY
//         ----------------------------------*/
        loadDataCuty();
        function loadDataCuty() {
            $.ajax({
                url: loadcutyUrl,
                type: 'POST',
                _token: "{{ csrf_token() }}",
                success: function(data) {
                  $('.loaddatacuty').html(data);
                }
            });
        }
    
        $('.btn-clear-cuty').click(function (e) {
            loadDataCuty();
            $('.start_date').val('');
            $('.start_date').val();
        });
    
    
        $('.btn-sortir-cuty').click(function (e) {
                var from = $('.start_date').val();
                var to   = $('.end_date').val();
    
               $.ajax({
                  url:loadcutyUrl,
                  method:"POST",
                  data:{from:from,to:to},
                  dataType:"text",
                  cache: false,
                  async: false,
                    beforeSend: function () { 
                     loading();
                    },
                    success: function (data) {
                        $('.loaddatacuty').html(data);
                    },
                complete: function () {
                    $(".loading").hide();
                },
            });
        });
    
//         /* ----------- ADD DATA CUTY ------------*/
        function formatDateToYMD(dateStr) {
            // Pisahkan tanggal yang memiliki format DD-MM-YYYY
            const parts = dateStr.split('-');
            // Pastikan terdapat 3 bagian pada tanggal yang dipisah
            if (parts.length === 3) {
                // Kembalikan tanggal dalam format YYYY-MM-DD
                return `${parts[2]}-${parts[1]}-${parts[0]}`;
            }
            // Jika format tidak sesuai, kembalikan nilai asli
            return dateStr;
        }


        $('#form-add-cuty').submit(function (e) {
            e.preventDefault();
                        // Ambil nilai dari input dan konversi format tanggal ke Y-m-d
                        const cutyStart = formatDateToYMD($('#cutystart').val());
                        const cutyEnd = formatDateToYMD($('#cutyend').val());
                        const dateWork = formatDateToYMD($('input[name="date_work"]').val());
                        console.log("Cuty Start:", cutyStart);
                        console.log("Cuty End:", cutyEnd);
                        console.log("Date Work:", dateWork);
                        var formData = new FormData(this);
                        formData.set('cuty_start', cutyStart);
                        formData.set('cuty_end', cutyEnd);
                        formData.set('date_work', dateWork);
            if($("#cutystart").val()=="" || $("#cutyend").val()=="" || $("input[type=number]").val()=="" || $("textarea.cuty_description").val()==""){  
                 swal({title:'Oops!', text: 'Harap bidang inputan tidak boleh ada yang kosong.!', icon: 'error', timer: 1500,});
                return false;
                loading();
            }
            else{
                loading();
                $.ajax({
                    url: storecutyUrl,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    async: true,
                    success: function (response) {
                        if (response.status === 'success') {
                            swal({ title: 'Berhasil!', text: response.message.toString(), icon: 'success', timer: 2500 });
                            loadDataCuty();
                            $('#modal-add').modal('hide');
                            $('#form-add-cuty').trigger("reset");
                        } else {
                            console.log(response);
                            swal({ title: 'Oops!', text: response.message ? response.message.toString() : 'Unknown error occurred.', icon: 'error', timer: 1500 });
                        }
                    },
                    error: function (xhr) {
                        var errors = xhr.responseJSON ? xhr.responseJSON.errors : null;
                        var errorMessage = errors ? Object.values(errors).join('\n') : 'Server error occurred. Please try again later.';
                        console.error('AJAX request failed: ', errorMessage);
                        swal({ title: 'Error!', text: errorMessage.toString(), icon: 'error', timer: 2500 });
                    }
                }).catch(function (error) {
                    console.error('AJAX request failed: ', error);
                });
                
            }
        });
    
       $(document).on('click', '.btn-update-cuty', function(){
            $('#modal-update').modal('show');
            var id = $(this).attr("data-id"); 
            document.getElementById('cuty-id').value = id;
    
            var start = $(this).attr("data-start"); 
            document.getElementById('cuty-start').value = start;
    
            var end = $(this).attr("data-end"); 
            document.getElementById('cuty-end').value = end;
    
            var work = $(this).attr("data-work"); 
            document.getElementById('date-work').value = work;
    
            var total = $(this).attr("data-total"); 
            document.getElementById('total').value = total;
    
            var cuty_description = $(this).attr("data-description"); 
            document.getElementById('cuty_description').value = cuty_description;
            /*var cuty_description = $(this).attr("data-date"); 
            $('.status-date').html(tanggal);*/
        });
    
//         /* ----------- UPDATE DATA CUTY ------------*/
            function formatDateToYMD(dateStr) {
                // Pisahkan tanggal yang memiliki format DD-MM-YYYY
                const parts = dateStr.split('-');
                // Pastikan terdapat 3 bagian pada tanggal yang dipisah
                if (parts.length === 3) {
                    // Kembalikan tanggal dalam format YYYY-MM-DD
                    return `${parts[2]}-${parts[1]}-${parts[0]}`;
                }
                // Jika format tidak sesuai, kembalikan nilai asli
                return dateStr;
            }
        $('#form-update-cuty').submit(function (e) {
            e.preventDefault();

            if ($("#cuty-start").val() == "" || $("#cuty-end").val() == "" || $("#total").val() == "" || $("textarea#cuty_description").val() == "") {
                swal({title: 'Oops!', text: 'Harap bidang inputan tidak boleh ada yang kosong.!', icon: 'error', timer: 1500});
                return false;
            } else {
                var formData = new FormData(this);



                $.ajax({
                    url: updatecutyUrl,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Tambahkan token CSRF
                    },
                    beforeSend: function () {
                        loading();
                    },
                    success: function (response) {
                        if (response.status == 'success') {
                            swal({title: 'Berhasil!', text: 'Data Cuti berhasil disimpan!', icon: 'success', timer: 2500});
                            loadDataCuty();
                            $('#modal-update').modal('hide');
                            $('#form-update-cuty').trigger("reset");
                        } else {
                            swal({title: 'Oops!', text: response.message, icon: 'error', timer: 1500});
                        }
                    },
                    complete: function () {
                        $(".loading").hide();
                    },
                    error: function (xhr) {
                        console.log(xhr);
                        swal({title: 'Oops!', text: 'Something went wrong: ' + xhr.responseText, icon: 'error', timer: 1500});
                    }
                });
            }
        });

    
    
    
//     /* ------------------ LOAD DATA CALL PLAN ------------------*/
    
       loadDataCallPlan();
        function loadDataCallPlan() {
            $.ajax({
                url: loadcallplanUrl,
                type: 'POST',
                _token: "{{ csrf_token() }}",
                success: function(data) {
                  $('.loaddatacallplan').html(data);
                }
            });
        }
        
        
    
        $('.btn-clear-callplan').click(function (e) {
            loadDataCallPlan();
            $('.start_date').val();
            $('.start_date').val();
        });
    
    
        $('.btn-sortir-callplan').click(function (e) {
                var from = $('.start_date').val();
                var to   = $('.end_date').val();
    
               $.ajax({
                  url: loadcallplanUrl,
                  method:"POST",
                  data:{from:from,to:to},
                  dataType:"text",
                  cache: false,
                  async: false,
                    beforeSend: function () { 
                     loading();
                    },
                    success: function (data) {
                        $('.loaddatacallplan').html(data);
                    },
                complete: function () {
                    $(".loading").hide();
                },
            });
        });

        function formatDateToYMD(dateStr) {
            const parts = dateStr.split('-');
            if (parts.length === 3) {
                return `${parts[2]}-${parts[1]}-${parts[0]}`;
            }
            return dateStr;
        }
        
        $('#form-add-callplan').submit(function (e) {
            e.preventDefault();
            const tanggalCP = formatDateToYMD($('#tanggal_cp').val());
            var formData = new FormData(this);
            formData.set('tanggal_cp', tanggalCP);
        
            if ($("#tanggal_cp").val().trim() === "" || $("input[name='nama_outlet']").val().trim() === "" || $("textarea[name='description']").val().trim() === "") {
                swal({ title: 'Oops!', text: 'Harap bidang inputan tidak boleh ada yang kosong!', icon: 'error', timer: 1500 });
                return false;
            } else {
                loading();
                $.ajax({
                    url: storecallplanUrl,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    async: false,
                    beforeSend: function () {
                        loading();
                    },
                    success: function (data) {
                        if (data.status === 'success') {
                            swal({ title: 'Berhasil!', text: data.message, icon: 'success', timer: 2500 });
                            loadDataCallPlan();
                            $('#modal-add').modal('hide');
                            $('#form-add-callplan')[0].reset();
                        } else {
                            swal({ title: 'Oops!', text: data.message, icon: 'error', timer: 1500 });
                        }
                    },
                    complete: function () {
                        $(".loading").hide();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        swal({ title: 'Error!', text: 'Terjadi kesalahan. Silakan coba lagi.', icon: 'error', timer: 1500 });
                    }
                });
            }
        });
        
    
       $(document).on('click', '.btn-update-callplan', function(){
            $('#modal-update').modal('show');
            var id = $(this).attr("data-id"); 
            document.getElementById('callplan-id').value = id;
    
            var start = $(this).attr("data-start"); 
            document.getElementById('tanggal-cp').value = start;
    
            var nama_outlet = $(this).attr("data-outlet"); 
            document.getElementById('nama_outlet').value = nama_outlet;

            var description = $(this).attr("data-description"); 
            document.getElementById('description').value = description;
            /*var cuty_description = $(this).attr("data-date"); 
            $('.status-date').html(tanggal);*/
        });


//         // $(document).on('click', '.btn-delete-callplan', function confirmDelete(id){

//         //     var result = confirm("Apakah Anda yakin ingin menghapus?");
//         //     if (result) {
//         //         // Redirect to delete action or perform AJAX delete here
//         //         window.location.href = "delete_callplan.php?id=" + id; // Example: Redirect to delete action
//         //     }
//         //     /*var cuty_description = $(this).attr("data-date"); 
//         //     $('.status-date').html(tanggal);*/
//         // });
            $(document).on('click', '.btn-delete-callplan', function() { 
                var callplan_id = $(this).data("id");

                swal({
                    text: "Anda yakin ingin menghapus data ini?",
                    icon: "warning",
                    buttons: {
                        cancel: true,
                        confirm: true,
                    },
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        loading();
                        $.ajax({
                            url: deletecallplanUrl,
                            type: 'POST',
                            data: { callplan_id: callplan_id },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    swal({ title: 'Berhasil!', text: response.message, icon: 'success', timer: 1500 });
                                    setTimeout(function() { location.reload(); }, 1500);
                                } else {
                                    swal({ title: 'Gagal!', text: response.message, icon: 'error', timer: 1500 });
                                }
                            },
                            error: function(xhr) {
                                swal({ title: 'Gagal!', text: xhr.responseJSON?.message || 'Terjadi kesalahan', icon: 'error', timer: 1500 });
                            },
                            complete: function() {
                                $(".loading").hide();
                            }
                        });
                    } else {
                        return false;
                    }
                });
            });

//     }); 

//         /* ----------- UPDATE DATA CALLPLAN ------------*/
            $('#form-update-callplan').submit(function (e) {
                e.preventDefault();
                const tanggalCP = formatDateToYMD($('#tanggal_cp').val());
                var formData = new FormData(this);
                formData.set('tanggal-cp', tanggalCP);
                if ($("#tanggal-cp").val().trim() === "") { // Periksa tanggal berdasarkan ID yang benar
                    swal({title: 'Oops!', text: 'Harap semua bidang input diisi.', icon: 'error', timer: 1500});
                    return false;
                } else {
                    loading();
                    $.ajax({
                        url: updatecallplanUrl,
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        cache: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {
                            loading();
                        },
                        success: function (response) {
                            if (response.status === 'success') {
                                swal({title: 'Berhasil!', text: 'Data Call Plan berhasil diubah!', icon: 'success', timer: 2500});
                                loadDataCallPlan();
                                $('#modal-update').modal('hide');
                                $('#form-update-callplan').trigger("reset");
                            } else {
                                swal({title: 'Oops!', text: response.message || 'Terjadi kesalahan', icon: 'error', timer: 1500});
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            swal({title: 'Oops!', text: errorThrown, icon: 'error', timer: 1500});
                        },
                        complete: function () {
                            $(".loading").hide();
                        }
                    });
                }
            });

        
//             $('.btn-printcallplan').click(function (e) {
//         var from        = $('.start_date').val();
//         var to          = $('.end_date').val();
//         var type        = $('.type').val();
//         if(type =='pdfcallplan'){
//             // cek berdasarkan bulan
//             if(from==''){    
//                 var url = "./print?action=pdfcallplan";
//             }else{
//                 var url = "./print?action=pdfcallplan&from="+from+"&to="+to+"";
//             }
//         }else{
//             if(from==''){    
//                 var url = "./print?action=excelcallplan";
//             }else{
//                 var url = "./print?action=excelcallplan&from="+from+"&to="+to+"";
//             }
//         }
//         window.open(url, '_blank');
// });
        



//     /* --------- LOAD DATA HISTORY Marketing---------------*/
        loadDataKunjungan();
        function loadDataKunjungan() {
            $.ajax({
                url: loadkunjunganUrl,
                type: 'POST',
                _token: "{{ csrf_token() }}",
                success: function(data) {
                $('.loaddatakunjungan').html(data);
                }
            });
        }

 

        $(document).on('click', '.btn-update-kunjungan', function() { 
            var kunjungan_id = $(this).data("kunjungan-id");
            var outlet = $(this).data("nama-outlet");
        
            swal({
                text: `Anda yakin ingin Presensi di Tempat ${outlet} dengan ID Kunjungan ${kunjungan_id}?`,
                icon: "warning",
                buttons: {
                    cancel: true,
                    confirm: true,
                },
                dangerMode: true,
            })
            .then((value) => {
                if (value) {
                    loading(); // Assume this triggers a loading indication
                    $.ajax({
                        url: selfie, // Replace 'selfie' with the actual URL for the update action
                        type: 'POST',
                        data: { kunjungan_id: kunjungan_id },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                // Redirect to the provided URL if kunjungan_id exists
                                window.location.href = response.redirect_url;
                            } else {
                                // Show error if response indicates failure
                                swal({ title: 'Gagal!', text: response.message, icon: 'error', timer: 1500 });
                            }
                        },
                        error: function(xhr) {
                            swal({ title: 'Gagal!', text: xhr.responseJSON?.message || 'Terjadi kesalahan', icon: 'error', timer: 1500 });
                        },
                        complete: function() {
                            $(".loading").hide(); // Hide loading indication
                        }
                    });
                }
            });
        });

        
        
        
        

//         document.addEventListener('DOMContentLoaded', function() {
//         var outletInput = document.querySelector('.outlet');
    
//         outletInput.addEventListener('input', function() {
//             this.value = this.value.toUpperCase();
//         });
//     });

//             document.addEventListener('DOMContentLoaded', function() {
//             var outletInput = document.querySelector('.information');
        
//             outletInput.addEventListener('input', function() {
//                 this.value = this.value.toUpperCase();
//             });
//     });

//     $('.btn-clear').click(function (e) {
//         loadDataMkt();
//         $('.start_date').val();
//         $('.start_date').val();
//     });



//     $('.btn-print').click(function (e) {
//             var from        = $('.start_date').val();
//             var to          = $('.end_date').val();
//             var type        = $('.type').val();
//             if(type =='pdf'){
//                 // cek berdasarkan bulan
//                 if(from==''){    
//                     var url = "./print?action=pdf";
//                 }else{
//                     var url = "./print?action=pdf&from="+from+"&to="+to+"";
//                 }
//             }else{
//                 if(from==''){    
//                     var url = "./print?action=excel";
//                 }else{
//                     var url = "./print?action=excel&from="+from+"&to="+to+"";
//                 }
//             }
//             window.open(url, '_blank');
//     });

//     /* ------------------- UPDATE DATA HISTORY ------------------------- */
//         $(document).on('click', '.modal-update1', function(){
//             $('#modal-show1').modal('show');
//             var presensi_id = $(this).attr("data-id"); 
//             document.getElementById('presensi_id').value = presensi_id;

//             /*var masuk = $(this).attr("data-masuk"); 
//             document.getElementById('timein').value = masuk;

//             var pulang = $(this).attr("data-pulang"); 
//             document.getElementById('timeout').value = pulang;*/

//             // var status = $(this).attr("data-status"); 
//             // document.getElementById('status').value = status;

//             var information = $(this).attr("data-information"); 
//             document.getElementById('information').value = information;
        
//             var callplans_id = $(this).attr("data-callplans_id"); 
//             document.getElementById('callplans_id').value = callplans_id;

//             var catatan = $(this).attr("data-catatan"); 
//             document.getElementById('catatan').value = catatan;

//             var tanggal = $(this).attr("data-date"); 
//             $('.status-date').html(tanggal);
//         });

//         /* ---------- UPDATE HISTORY-----------------*/
//         $('#update-historymkt').submit(function (e) {
//             e.preventDefault();
//             if($('#timein').val()=='' && $('#timeout').val()==''){    
//                  swal({title:'Oops!', text: 'Harap bwidang inputan tidak boleh ada yang kosong.!', icon: 'error', timer: 1500,});
//                 return false;
//                 loading();
//             }
//             else{
//                 //  loading();
//                 $.ajax({
//                     url:"./sw-proses?action=update-historymkt",
//                     type: "POST",
//                     data: new FormData(this),
//                     processData: false,
//                     contentType: false,
//                     cache: false,
//                     async: false,
//                     beforeSend: function () { 
//                     //    loading();
//                     },
//                     success: function (data) {
//                         if (data == 'success') {
//                             swal({title: 'Berhasil!', text: 'Presensi Kunjungan berhasil di perbaharui!', icon: 'success', timer: 2000,});
//                             //setTimeout(function(){ location.reload(); }, 2500);
//                             $('#modal-show1').modal('hide');
                        
//                             loadDataMkt();
//                         } else {
//                             swal({title: 'Oops!', text: data, icon: 'error', timer: 2000,});
                        
//                         }
//                     },

//                     complete: function () {
//                          $(".loading").hide();
//                          $('#modal-show1').modal('hide');
//                     },
//                 });
//             }
//         });

//             $(document).on('click', '.btn-modal', function(){
//                 $('#modal-location').modal();
//                 var latitude  = $(this).attr("data-latitude");
//                 var longitude = $(this).attr("data-longitude");
//                 var name = $('.employees_name').html();
//                 $(".modal-title-name").html(name);
//                 document.getElementById("iframe-map").innerHTML ='<iframe src="./map.php?latitude='+latitude+'&longitude='+longitude+'&name='+name+'" frameborder="0" width="100%" height="400px" marginwidth="0" marginheight="0" scrolling="no">';
//             });



    
    
//         /* ------------------ LOAD DATA COUNT ABSENSI HOME ------------------*/
        function loadDataCounter() {
            $.ajax({
                url: loadhomeUrl,
                type: 'POST',
                success: function(data) {
                    $('.load-home').html(data);
                }
            });
        }
        loadDataCounter();
        $('.select-change').on('change', function() {
            var month_filter = this.value;
            $.ajax({
                url: loadhomeUrl,
                method: "POST",
                data: { month_filter: month_filter },
                dataType: "text",
                cache: false,
                async: false,

                beforeSend: function() {
                    loading();
                },
                success: function(data) {
                    $('.load-home').html(data);
                },
                complete: function() {
                    $(".loading").hide();
                }
            });
        });
    
        /* ------------------ FAILED ACCESS ------------------*/
       $(document).on("click", ".access-failed", function(){ 
          swal({title:"Error!", text: "Anda tidak memiliki hak akses lagi!", icon:"error",timer:2500,});  
        });
    
    
    
//     });
    
    
    
    
    
    jQuery(function($) {
      setInterval(function() {
        var date = new Date(),
            time = date.toLocaleTimeString();
        $(".clock").html(time);
      }, 1000);
    });
    
    
//     /* ---------- Print -----------------*/
//     function nWin(context,title) {
//         var printWindow = window.open('', '');
//         var doc = printWindow.document;
//         doc.write("<html><head>");
//         doc.write("<title>"+title+" - Print Mode</title>");    
//         doc.write("<link href='sw-mod/sw-assets/css/sw-print.css' rel='stylesheet' type='text/css' media='print'>");
//         doc.write("</head><body>");
//         doc.write(context);
//         doc.write("</body></html>");
//         doc.close();
//         function show() {
//             if (doc.readyState === "complete") {
//                 printWindow.focus();
//                 printWindow.print();
//                 printWindow.close();
//             } else {
//                 setTimeout(show, 100);
//             }
//         };
//         show();
//     };
    
//     $(function() {
//         $(".print").click(function(){nWin($("#divToPrint").html(),$("#pagename").html());});
//     });
    
//     function printData(){
//        var divToPrint=document.getElementById("printArea");
//        newWin= window.open("");
//        newWin.document.write(divToPrint.outerHTML);
//        newWin.print();
//        newWin.close();
//     }
    
//     /*$('.btn-print').on('click',function(){
//         printData();
//     })*/
    
//     // document.addEventListener("contextmenu", event => event.preventDefault());
//     // document.onkeydown = function (e) {
//     //     if(e.keyCode == 123) {
//     //         return false;
//     //     }
//     // };
//     // var base_url ="' . $base_url . '"
    
//     //Menampilkan POP UP Map History Presensi Kunjungan
//         $(document).on('click', '.btn-modal', function(){
//         $('#modal-location').modal();
//         var latitude  = $(this).attr("data-latitude");
//         var longitude = $(this).attr("data-longitude");
//         var name = $('.employees_name').html();
//         $(".modal-title-name").html(name);
//         document.getElementById("iframea-map").innerHTML ='<iframe src="./action/map.php?latitude='+latitude+'&longitude='+longitude+'&name='+name+'" frameborder="0" width="100%" height="400px" marginwidth="0" marginheight="0" scrolling="no">';
//     });
});