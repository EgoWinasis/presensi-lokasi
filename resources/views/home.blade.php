@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('content')
    <!-- Check if the logged-in user is not a 'user' role -->
    <div class="row">
            @if(Auth::user()->role !== 'user')
            <!-- Total Users Card -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $userCount }}</h3>
                        <p>Total User</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <!-- Total Admins Card -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $adminCount }}</h3>
                        <p>Total Admin</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
            </div>
            @else
            <!-- Total Leave Applications Card -->      
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $pengajuanCuti }}</h3>
                        <p>Total Pengajuan Cuti</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
                </div>
            @endif

            <!-- Pending Leave Applications Card -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $pendingCount }}</h3>
                        <p>Pengajuan Cuti Menunggu Admin</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $pendingCountSuper }}</h3>
                        <p>Pengajuan Cuti Menunggu Super Admin</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>

            <!-- Approved by Admin Leave Applications -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $adminApprovedCount }}</h3>
                        <p>Disetujui Admin</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-thumbs-up"></i>
                    </div>
                </div>
            </div>

            <!-- Approved by Superuser Leave Applications -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $superuserApprovedCount }}</h3>
                        <p>Disetujui Superuser</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>

            <!-- Rejected Leave Applications -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $rejectedCount }}</h3>
                        <p>Ditolak Admin</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $rejectedCountSuper }}</h3>
                        <p>Ditolak Super Admin</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    
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
