@extends('adminlte::page')

@section('title', 'Pengaturan Hari Libur')

@section('content_header')
<h1>Pengaturan Hari Libur</h1>
@stop

@section('content')
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">

            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('pengaturan.hari-libur.update') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama Hari Libur</label>
                            <input type="text" name="name" class="form-control" id="name" required>
                        </div>

                        <div class="form-group">
                            <label for="date">Tanggal Hari Libur</label>
                            <input type="date" name="date" class="form-control" id="date" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>

                    <hr>

                    <h3>Daftar Hari Libur</h3>
                    <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="table_holiday">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Hari Libur</th>
                                <th>Tanggal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($holidays as $index => $holiday)
                            <tr id="holiday-{{ $holiday->id }}">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $holiday->name }}</td>
                                <td>{{ $holiday->date }}</td>
                                <td class="text-center">
                                    <!-- Edit Button -->
                                    <button class="btn btn-warning btn-sm btn-edit m-2" data-id="{{ $holiday->id }}" 
                                        data-name="{{ $holiday->name }}" data-date="{{ $holiday->date }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <button class="btn btn-danger btn-sm btn-delete m-2" data-id="{{ $holiday->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
            </div>

        </div>
    </main>
</div>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.Sweetalert2', true)

@section('js')
<script type="text/javascript">
    // DataTables Initialization
    $(function () {
        $("#table_holiday").DataTable({
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

    // Handle Delete via AJAX
    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var token = $("meta[name='csrf-token']").attr("content");

        Swal.fire({
            title: 'Hapus hari libur?',
            text: "Data hari libur ini akan hilang!",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "DELETE",
                    url: "/pengaturan-hari-libur/" + id,
                    data: {
                        'id': id,
                        '_token': token,
                    },
                    success: function (data) {
                        Swal.fire(
                            'Terhapus!',
                            'Hari libur berhasil dihapus!',
                            'success'
                        )
                        $("#holiday-" + id).remove(); // Remove the row from the table
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

    // Handle Edit via SweetAlert2 Modal
    $(document).on('click', '.btn-edit', function () {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var date = $(this).data('date');

        Swal.fire({
            title: 'Edit Hari Libur',
            html: `
                <input type="text" id="edit-name" class="swal2-input" value="${name}" required>
                <input type="date" id="edit-date" class="swal2-input" value="${date}" required>
            `,
            showCancelButton: true,
            confirmButtonText: 'Update',
            cancelButtonText: 'Cancel',
            preConfirm: () => {
                var updatedName = $('#edit-name').val();
                var updatedDate = $('#edit-date').val();

                return { name: updatedName, date: updatedDate };
            }
        }).then((result) => {
            if (result.value) {
                var updatedData = result.value;
                
                $.ajax({
                    type: "POST",
                    url: "/pengaturan-hari-libur",
                    data: {
                        _token: $("meta[name='csrf-token']").attr("content"),
                        id: id,
                        name: updatedData.name,
                        date: updatedData.date
                    },
                    success: function (data) {
                        Swal.fire(
                            'Updated!',
                            'Hari libur berhasil diperbarui!',
                            'success'
                        ).then(() => {
                            window.location.reload();
                        });
                        // Update the table row with the new data
                        
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
        });
    });
</script>
@stop
