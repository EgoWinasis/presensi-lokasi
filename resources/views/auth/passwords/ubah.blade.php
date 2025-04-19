@extends('adminlte::page')
@section('title', 'Ubah Kata Sandi')
@section('content_header')
    <h1>Ubah Kata Sandi</h1>
@stop
@section('content')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('password.update', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-8">
                                <x-adminlte-card title="Ubah Kata Sandi" theme="dark" icon="fas fa-lg fa-lock">

                                    <x-adminlte-input type="password" name="old_password" label="Kata Sandi Lama"
                                        placeholder="Kata Sandi Lama" :value="old('Old_password')" />
                                    <x-adminlte-input type="password" name="password" label="Kata Sandi Baru"
                                        placeholder="Kata Sandi Baru" :value="old('password')" />
                                    <x-adminlte-input type="password" name="password_confirmation"
                                        label="Konfirmasi Kata Sandi" placeholder="Konfirmasi Kata Sandi" value="" />

                                    <div class="row p-4">
                                        <div class="col-sm-12 text-center">
                                            <x-adminlte-button class="btn-flat col-sm-4" type="submit" label="Simpan"
                                                :value="old('password_confirmation')" theme="success" icon="fas fa-lg fa-save" />
                                        </div>
                                    </div>

                                </x-adminlte-card>
                            </div>
                        </div>
                    </form>

                </div><!-- /.container-fluid -->
            </main>
        </div>
    </div>
    <!-- /.content -->
@stop
@section('footer')
   @include('footer')
@stop
