@extends('adminlte::page')

@section('title', 'Validasi Cuti')
@section('content_header')
<h1>Validasi Cuti</h1>
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
               
                <div class="row">

                    <div class="col-md-12">
                        <div class="table-responsive">

                            <table id="table_user" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">User</th>
                                        <th class="text-center">Tanggal Mulai </th>
                                        <th class="text-center">Tanggal Selesai </th>
                                        <th class="text-center">Jumlah Hari </th>
                                        <th class="text-center">Keterangan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $i = 1;
                                    @endphp
                                    @foreach ($cuti as $item)
                                    <tr>
                                        <td class="text-center">{{ $i++ }}</td> <!-- Menampilkan ID -->
                                        <td class="text-center">{{ $item->user->name }}</td>
                                        <!-- Menampilkan nama karyawan -->
                                        <td class="text-center">
                                            {{ \Carbon\Carbon::parse($item->tgl_mulai_cuti)->format('d-m-Y') }}</td>
                                        <!-- Tanggal Cuti -->
                                        <td class="text-center">
                                            {{ \Carbon\Carbon::parse($item->tgl_selesai_cuti)->format('d-m-Y') }}</td>
                                        <!-- Tanggal Cuti -->
                                        <td class="text-center">{{ $item->jumlah_hari }}</td> <!-- Keterangan -->
                                        <td class="text-center">{{ $item->keterangan }}</td> <!-- Keterangan -->
                                        <td class="text-center">
                                            <a class="btn btn-success btn-aktivasi" data-id="{{ $item->id }}"><i
                                                    class="fas fa-info"></i></a>
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
   
    
    $(function () {
        $("#table_user").DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "responsive": true,

           
        });

    });

    $(document).on('click', '.btn-aktivasi', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var token = $("meta[name='csrf-token']").attr("content");

    // SweetAlert to display options
    Swal.fire({
        title: 'Setujui atau Tolak Cuti?',
        html: `
            <select id="status" class="form-control">
                <option value="disetujui">Setujui</option>
                <option value="ditolak">Tolak</option>
            </select>
            <br>
            <textarea id="catatan" class="form-control" placeholder="Tulis catatan (optional)" rows="3"></textarea>
        `,
        type: 'question',
        showCancelButton: true,
        cancelButtonText: 'Batal',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Kirim'
    }).then((result) => {
        if (result.value) {
            var status = $('#status').val(); // Get the selected status
            var catatan = $('#catatan').val(); // Get the text input value

            // Make the AJAX request to activate or reject the leave
            $.ajax({
                type: "POST",
                url: "/cuti/validate/superadmin/" + id,
                data: {
                    '_token': token,
                    'status': status,
                    'catatan': catatan,
                },
                success: function (data) {
                    Swal.fire(
                        'Berhasil!',
                        `Cuti telah ${status === 'disetujui' ? 'disetujui' : 'ditolak'}.`,
                        'success'
                    ).then(() => {
                        window.location.reload();
                    });
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        type: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat mengubah status cuti!'
                    });
                }
            });
        }
    });
});


</script>
@stop
