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
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Tanggal</th>
                                    <th>Dibuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jadwals as $jadwal)
                                <tr>
                                    <td>{{ $jadwal->user->name }}</td>
                                    <td>{{ $jadwal->tgl }}</td>
                                    <td>{{ $jadwal->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                'Tanggal': ''            // Include an empty field for 'Tanggal'
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
        const json = XLSX.utils.sheet_to_json(worksheet);

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
            alert('Import berhasil!');
            console.log(result);
        })
        .catch(err => {
            alert('Gagal mengimport.');
            console.error(err);
        });
    };

    reader.readAsArrayBuffer(file);
});


</script>

@endsection
