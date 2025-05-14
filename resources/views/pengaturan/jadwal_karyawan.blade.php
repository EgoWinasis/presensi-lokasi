@extends('adminlte::page')

@section('title', 'Jadwal Karyawan')

@section('content_header')
<h1>Jadwal Karyawan</h1>
@stop

@section('content')
<div id="layoutSidenav">
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

                    <div class="col-md-6">
                        <h4>Tambah Jadwal Manual</h4>
                        <form action="{{ route('jadwal-karyawan.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="user_id">Nama Karyawan</label>
                                <select name="user_id" class="form-control" required>
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tgl">Tanggal</label>
                                <input type="date" name="tgl" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <select name="keterangan" class="form-control" required>
                                    <option value="Masuk">Masuk</option>
                                    <option value="Libur">Libur</option>
                            </div>
                            <button type="submit" class="btn btn-primary my-2">Simpan Jadwal</button>
                        </form>
                    </div>

                    <div class="col-md-6">
                        <h4>Import dari Excel</h4>
                        <form id="excelForm">
                            <div class="form-group">
                                <label for="excelFile">Upload Excel File</label>
                                <input type="file" id="excelFile" accept=".xlsx, .xls" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success">Import</button>
                        </form>
                            <a href="#" id="download-template" class="btn btn-info my-2">Download Template</a>
                        
                    </div>

                </div>

                <hr>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <h4>Daftar Jadwal</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="table_data">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Dibuat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($jadwals as $jadwal)
                                <tr>
                                    <td>{{ $jadwal->user->name }}</td>
                                    <td>{{ $jadwal->tgl }}</td>
                                    <td>{{ $jadwal->keterangan }}</td>
                                    <td>{{ $jadwal->created_at->format('Y-m-d H:i') }}</td>
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
</div>
@stop

@section('footer')
@include('footer')
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.Sweetalert2', true)
@section('css')
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>

<script>
    // Prepare users data from the Blade template
    var users = @json($users);

    // Handle download of the Excel template
    document.getElementById('download-template').addEventListener('click', function(event) {
        event.preventDefault();

        // Prepare data for the Excel template, including 'User ID' and 'Tanggal'
        var templateData = users.map(function(user) {
            return {
                'User ID': user.id,      // Include User ID
                'User Name': user.name,      // Include User ID
                'Tanggal (YYYY-MM-DD)': '',            // Include an empty field for 'Tanggal'
                'Keterangan (Masuk/Libur)': ''            // Include an empty field for 'Tanggal'
            };
        });

        // Create a new workbook
        var wb = XLSX.utils.book_new();

        // Convert JSON data to a worksheet
        var ws = XLSX.utils.json_to_sheet(templateData);

        // Append the worksheet to the workbook
        XLSX.utils.book_append_sheet(wb, ws, 'Template');

        // Download the workbook as an Excel file
        XLSX.writeFile(wb, 'template_jadwal_karyawan.xlsx');
    });


    document.getElementById('excelForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const fileInput = document.getElementById('excelFile');
    const file = fileInput.files[0];

    if (!file) return alert("Please select a file.");

    const reader = new FileReader();

    reader.onload = function(e) {
        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type: 'array' });
        const sheetName = workbook.SheetNames[0];
        const worksheet = workbook.Sheets[sheetName];
        const rawData = XLSX.utils.sheet_to_json(worksheet);
        const json = rawData.map(row => {
            const tgl = row['Tanggal (YYYY-MM-DD)'];
            const parsedDate = typeof tgl === 'number'
                ? new Date((tgl - 25569) * 86400 * 1000).toISOString().split('T')[0]
                : tgl;

            return {
                user_id: row['User ID'],
                tgl: parsedDate,
                keterangan: row['Keterangan (Masuk/Libur)']
            };
        });
        
        // Send JSON to Laravel
        fetch('/jadwal-karyawan/import-json', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ data: json })
        })
        .then(response => response.json())
        .then(result => {
            Swal.fire({
                icon: 'success',
                title: 'Import Berhasil',
                text: 'Data jadwal karyawan berhasil diimport!',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        })
        .catch(error => {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal Mengimport',
                text: 'Terjadi kesalahan saat mengirim data ke server.'
            });
        });
    };

    reader.readAsArrayBuffer(file);
});

$("#table_data").DataTable({
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
                        columns: [0, 1, 2,3]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [0, 1, 2,3]
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [0, 1, 2,3]
                    }
                }
            ]
        }).buttons().container().appendTo('#table_data_wrapper .col-md-6:eq(0)');
</script>

@endsection
