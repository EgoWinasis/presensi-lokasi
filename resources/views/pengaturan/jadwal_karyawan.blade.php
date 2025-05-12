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
                        <form method="POST" action="{{ route('jadwal-karyawan.import') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="file">Pilih File Excel (.xlsx)</label>
                                <input type="file" name="file" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success my-2">Import</button>
                            <a href="#" id="download-template" class="btn btn-info my-2">Download Template</a>
                        </form>
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

        // Prepare data for the Excel template
        var templateData = users.map(function(user) {
            return {
                'User ID': user.id  // Only include the user ID for the template
            };
        });

        // Create a new workbook
        var wb = XLSX.utils.book_new();
        var ws = XLSX.utils.json_to_sheet(templateData);

        // Append the worksheet to the workbook
        XLSX.utils.book_append_sheet(wb, ws, 'Template');

        // Download the workbook as an Excel file
        XLSX.writeFile(wb, 'template_jadwal_karyawan.xlsx');
    });
</script>
@endsection
