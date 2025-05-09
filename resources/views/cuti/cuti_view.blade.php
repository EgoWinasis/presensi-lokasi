@extends('adminlte::page')

@section('title', 'Pengajuan Cuti')
@section('content_header')
<h1>Pengajuan Cuti</h1>
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
                        <x-adminlte-button onclick="return add();" label="Pengajuan" theme="primary"
                            icon="fas fa-plus" />
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-12">

                        <div class="table-responsive">

                        <table id="table_user" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama Karyawan</th>
                                    <th class="text-center">Jenis</th>
                                    <th class="text-center">Tanggal Mulai Cuti</th>
                                    <th class="text-center">Tanggal Selesai Cuti</th>
                                    <th class="text-center">Jumlah Hari</th>
                                    <th class="text-center">Keterangan</th>
                                    <th class="text-center">Status Admin</th>
                                    <th class="text-center">Status SuperAdmin</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $i = 1;
                                @endphp
                                @foreach($cuti as $item)
                                <tr>
                                    <td class="text-center">{{ $i++ }}</td> <!-- Menampilkan ID -->
                                    <td class="text-center">{{ $item->user->name }}</td> <!-- Menampilkan nama karyawan -->
                                    <td class="text-center">{{ $item->jenis }}</td> <!-- Menampilkan nama karyawan -->
                                    <td class="text-center">{{ \Carbon\Carbon::parse($item->tgl_mulai_cuti)->format('d-m-Y') }}</td> <!-- Tanggal Cuti -->
                                    <td class="text-center">{{ \Carbon\Carbon::parse($item->tgl_selesai_cuti)->format('d-m-Y') }}</td> <!-- Tanggal Cuti -->
                                    <td class="text-center">{{ $item->jumlah_hari }}</td> <!-- Keterangan -->
                                    <td class="text-center">{{ $item->keterangan }}</td> <!-- Keterangan -->
                                    <td class="text-center">
                                        <!-- Status Admin -->
                                        @if($item->status_admin == 'belum divalidasi')
                                            <span class="badge badge-warning">{{ ucfirst($item->status_admin) }}</span>
                                        @elseif($item->status_admin == 'disetujui')
                                            <span class="badge badge-success">{{ ucfirst($item->status_admin) }}</span>
                                        @elseif($item->status_admin == 'ditolak')
                                            <span class="badge badge-danger">{{ ucfirst($item->status_admin) }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($item->status_admin) }}</span>
                                        @endif
                                    </td>
                                    
                                    <td class="text-center">
                                        <!-- Status Superadmin -->
                                        @if($item->status_superadmin == 'belum divalidasi')
                                            <span class="badge badge-warning">{{ ucfirst($item->status_superadmin) }}</span>
                                        @elseif($item->status_superadmin == 'disetujui')
                                            <span class="badge badge-success">{{ ucfirst($item->status_superadmin) }}</span>
                                        @elseif($item->status_superadmin == 'ditolak')
                                            <span class="badge badge-danger">{{ ucfirst($item->status_superadmin) }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($item->status_superadmin) }}</span>
                                        @endif
                                    </td>
                                    
                                    <td class="text-center">
                                        <!-- Conditional Buttons -->
                                        <button class="btn btn-primary btn-sm m-1 btn-view" data-id="{{ $item->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Button to Edit & Delete (Visible only when both statuses are 'belum divalidasi') -->
                                        @if($item->status_admin == 'belum divalidasi' && $item->status_superadmin == 'belum divalidasi')
                                            <a href="{{ route('cuti.edit', $item->id) }}" class="btn btn-warning btn-sm m-1">
                                                <i class="fas fa-edit"></i> 
                                            </a>
                                           
                                                <button class="btn btn-danger btn-sm m-1 btn-delete" data-id="{{ $item->id }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                          
                                        @endif
                                    
                                        <!-- Button to Print (Visible only when both statuses are 'disetujui') -->
                                        @if($item->status_admin == 'disetujui' && $item->status_superadmin == 'disetujui')
                                            <a href="{{ route('cuti.print', $item->id) }}" target="_blank" class="btn btn-info btn-sm m-1">
                                                <i class="fas fa-print"></i> 
                                            </a>
                                        @endif
                                    </td>
                                </tr>
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
        window.location = "{{ route('cuti.create') }}";
    }
    $(function () {
        $("#table_user").DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
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
        var token = $("meta[name='csrf-token']").attr("content");
        
        Swal.fire({
            title: 'Hapus data ?',
            text: "Data akan hilang!",
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
                    url: "/cuti/" + id,
                    data: {
                        'id': id,
                        '_token': token,
                    },
                    success: function (data) {
                        Swal.fire(
                            'Terhapus!',
                            'Data  berhasil dihapus!',
                            'success'
                        ).then(function() {
                            window.location.reload();
                        });
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: error
                        })
                    }
                });

            }
        })

    });


   $(document).on('click', '.btn-view', function (e) {
    e.preventDefault();
    
    var itemId = $(this).data('id'); // Get the ID from the data-id attribute
    
    var token = $("meta[name='csrf-token']").attr("content"); // CSRF token
    
    $.ajax({
        type: "GET",
        url: "/cuti/details/" + itemId,  // Change this to your route to fetch data
        data: {
            '_token': token
        },
        success: function (response) {
            function formatDate(dateString) {
                var date = new Date(dateString);
                var day = String(date.getDate()).padStart(2, '0');
                var month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
                var year = date.getFullYear();
                return day + '-' + month + '-' + year;
            }
            
            // Format the dates
            var tglMulai = formatDate(response.tgl_mulai_cuti);
            var tglSelesai = formatDate(response.tgl_selesai_cuti);
            
            // On success, show SweetAlert modal with the fetched data
            Swal.fire({
                title: 'Detail Cuti',
                html: `
                    <table class="table table-bordered">
                        
                        <tr>
                            <th class="text-left">Jenis</th>
                            <td>${jenis}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Tanggal Mulai</th>
                            <td>${tglMulai}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Tanggal Selesai</th>
                            <td>${tglSelesai}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Jumlah Hari</th>
                            <td>${response.jumlah_hari} Hari</td>
                        </tr>
                        <tr>
                            <th class="text-left">Keterangan</th>
                            <td>${response.keterangan}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Status Admin</th>
                            <td>${response.status_admin}</td>
                            </tr>
                            <tr>
                                <th class="text-left">Catatan</th>
                                <td>${response.catatan_admin}</td>
                            </tr>
                            <tr>
                                <th class="text-left">Status Superadmin</th>
                                <td>${response.status_superadmin}</td>
                                </tr>
                                <tr>
                                    <th class="text-left">Catatan</th>
                                    <td>${response.catatan_superadmin}</td>
                                </tr>
                    </table>
                `,
                type: 'info',
                showCloseButton: true,
                focusConfirm: false,
                confirmButtonText: 'Tutup'
            });
        },
        error: function (xhr, status, error) {
            // If an error occurs, show a SweetAlert error message
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan saat memuat data.'
            });
        }
    });
});


</script>
@stop
