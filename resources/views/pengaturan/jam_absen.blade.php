@extends('adminlte::page')

@section('title', 'Pengaturan Jam Absen')

@section('content_header')
<h1>Pengaturan Jam Absen</h1>
@stop

@section('content')
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <!-- Success Message Section -->
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

            <!-- Start Row -->
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('pengaturan.jam-absen.update') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="jam_masuk">Jam Masuk</label>
                            <input type="time" name="jam_masuk" class="form-control" id="jam_masuk"
                                value="{{ old('jam_masuk', substr($jamAbsen->jam_masuk ?? '', 0, 5)) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="jam_keluar">Jam Keluar</label>
                            <input type="time" name="jam_keluar" class="form-control" id="jam_keluar"
                                value="{{ old('jam_keluar', substr($jamAbsen->jam_keluar ?? '', 0, 5)) }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>

                </div>
            </div>
            <!-- End Row -->

        </div>
    </main>
</div>
@stop

@section('footer')
@include('footer')
@stop
