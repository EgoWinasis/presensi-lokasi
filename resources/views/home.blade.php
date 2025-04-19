@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('content')

    <!-- /.content -->
@stop
@section('footer')
   @include('footer')
@stop
@section('plugins.Sweetalert2', true)

@section('js')
    <script>
        $(document).ready(function() {
            Swal.fire({
                type: 'info',
                title: 'Info',
                html: 'Selamat Datang'
            });
        });
    </script>
@endsection
