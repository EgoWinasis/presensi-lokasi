@extends('adminlte::page')

@section('title', 'Kelola Akun')
@section('content_header')
<h1>Kelola Akun</h1>
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
                        <x-adminlte-button onclick="return add();" label="Buat Akun" theme="primary"
                            icon="fas fa-plus" />
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-12">

                        <div class="table-responsive">

                        <table id="table_user" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Peran</th>
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
                                        @if ($user->isActive == 0)

                                        <span class="badge badge-warning">{{ $user->role }}</span>
                                        @else

                                        <span class="badge badge-success">{{ $user->role }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <img src="{{asset('storage/images/profile/'.$user->foto)}}" width="50px"
                                            alt="Foto">
                                    </td>
                                    <td class="text-center">
                                        <a class="btn btn-danger btn-delete" data-id="{{ $user->id }}"><i
                                                class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>

                        </table>
                    </div>

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
    function add() {
        window.location = "{{ route('user.create') }}";
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

    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var nama = $(this).parent().parent().find('.nama').text();
        var token = $("meta[name='csrf-token']").attr("content");

        Swal.fire({
            title: 'Hapus data pengguna ' + nama + ' ?',
            text: "Semua data pengguna akan hilang!",
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "DELETE",
                    url: "/user/" + id,
                    data: {
                        'id': id,
                        '_token': token,
                    },
                    success: function (data) {
                        Swal.fire(
                            'Terhapus!',
                            'Data pengguna ' + nama + ' berhasil dihapus!',
                            'success'
                        )
                        window.location.reload();
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: error
                        })
                    }
                });

            }
        })

    });


    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            type: 'info',
            title: 'Informasi',
            html: `<div style="text-align: left; line-height: 1.8; margin:auto;">
                        <span class="badge badge-warning">user</span> &nbsp; pengguna <b>belum diaktivasi</b> oleh Superadmin.<br>
                        <span class="badge badge-success">user</span> &nbsp; pengguna <b>sudah aktif</b>.
                    </div>`,
            confirmButtonText: 'Mengerti'
        });
    });

</script>
@stop
