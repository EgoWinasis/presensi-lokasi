@extends('adminlte::page')

@section('title', 'Aktivasi Akun')
@section('content_header')
<h1>Aktivasi Akun</h1>
@stop

@section('content')
<div id="layoutSidenav">
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
                @endif
                <div class="row my-3">
                    <div class="col-md-12">
                        <x-adminlte-button onclick="return aktivasi();" label="Aktivasi Semua Akun" theme="primary"
                            icon="fas fa-plus" />
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-12">

                        <table id="table_user" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Foto</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $i = 1;
                                @endphp
                                @foreach ($users as $user)
                                @if (!(Auth::user()->id == $user->id))
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td class="nama">{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td class="text-center">
                                        <img src="{{asset('storage/images/profile/'.$user->foto)}}" width="50px"
                                            alt="Foto">
                                    </td>
                                    <td class="text-center">
                                        <a class="btn btn-success btn-aktivasi" data-id="{{ $user->id }}"><i
                                                class="fas fa-user-check"></i></a>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>

                        </table>
                        <br><br>

                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

</section>
<!-- /.content -->
@stop
@section('footer')
@include('footer')
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.Sweetalert2', true)

@section('js')

<script type="text/javascript">
   function aktivasi() {
        Swal.fire({
            title: 'Aktivasi Semua Akun?',
            text: "Semua pengguna yang belum aktif akan diaktivasi.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Aktifkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                // Kirim request ke route aktivasi massal
                fetch('/aktivasi-user-all', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire(
                        'Berhasil!',
                        data.message || 'Semua akun telah diaktivasi.',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                })
                .catch(error => {
                    Swal.fire('Gagal!', 'Terjadi kesalahan saat mengaktivasi.', 'error');
                });
            }
        });

        return false; // Mencegah reload default
    }
    $(function () {
        $("#table_user").DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "responsive": true,

            "buttons": [{
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                }
            ]
        }).buttons().container().appendTo('#table_user_wrapper .col-md-6:eq(0)');

    });

    $(document).on('click', '.btn-aktivasi', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var nama = $(this).closest('tr').find('.nama').text();
    var token = $("meta[name='csrf-token']").attr("content");

    Swal.fire({
        title: 'Aktivasi pengguna ' + nama + '?',
        text: "Pengguna akan diaktifkan dan bisa login.",
        type: 'question',
        showCancelButton: true,
        cancelButtonText: 'Batal',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, aktivasi'
    }).then((result) => {
        
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "/user/aktivasi/" + id,
                data: {
                    '_token': token,
                },
                success: function (data) {
                    Swal.fire(
                        'Berhasil!',
                        'Pengguna ' + nama + ' telah diaktifkan.',
                        'success'
                    ).then(() => {
                        window.location.reload();
                    });
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat aktivasi!'
                    });
                }
            });
        }
    });
});



    

</script>
@stop
