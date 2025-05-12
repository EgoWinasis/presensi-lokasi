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
                                <label for="tanggal">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" required>
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
                            <a href="{{ route('jadwal-karyawan.template') }}" class="btn btn-info my-2">Download Template</a>
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
                                    <td>{{ $jadwal->tanggal }}</td>
                                    <td>{{ $jadwal->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $jadwals->links() }} <!-- pagination -->
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
@endsection
