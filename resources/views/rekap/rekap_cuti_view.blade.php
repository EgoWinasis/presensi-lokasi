@extends('adminlte::page')

@section('title', 'Histori Rekap Presensi')
@section('content_header')
<h1>Histori Rekap Presensi</h1>
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
                        <form method="GET" action="{{ route('rekap-cuti.index') }}" class="form-inline mb-3">
                            <label for="start_date" class="mr-2">Pilih Rentang Tanggal:</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                            <span class="mx-2">-</span>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                            @auth
                                @if(auth()->user()->role !== 'user')
                                    <select name="user_id" class="form-control mx-2">
                                        <option value="">Semua Karyawan</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            @endauth
                        
                            <button type="submit" class="btn btn-primary ml-2">Filter</button>
                            <a href="{{ route('rekap-cuti.index') }}" class="btn btn-secondary ml-2">Reset</a>
                        </form>
                        
                        <div class="row">
                            
                            <!-- Total Users Card -->
                            <div class="col-lg-2 col-2">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>{{$totalCuti}}</h3>
                                        <p>Total Pengajuan</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-2">
                                <div class="small-box bg-primary">
                                    <div class="inner">
                                        <h3>{{$totalCuti}}</h3>
                                        <p>Izin</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-user-slash"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-2">
                                <div class="small-box bg-secondary">
                                    <div class="inner">
                                        <h3>{{$totalCuti}}</h3>
                                        <p>Sakit</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-user-md"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-2">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{$cutiApproved}}</h3>
                                        <p>Disetujui</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </div>
                            </div>
                
                            <!-- Total Admins Card -->
                            <div class="col-lg-2 col-2">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3>{{$cutiRejected}}</h3>
                                        <p>Ditolak</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-user-times"></i>
                                    </div>
                                </div>
                            </div>
                
                            <!-- Pending Leave Applications Card -->
                            <div class="col-lg-2 col-2">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>{{$cutiPending}}</h3>
                                        <p>Menunggu</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-user-edit"></i>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">
                            
                            <div class="col-lg-4 col-4">
                                <div class="d-flex my-3">
                                    <button id="exportPdfBtn" class="btn btn-danger mr-2">
                                        <i class="fas fa-file-pdf"></i> Export PDF
                                    </button>
                                </div>
                            </div>
                            
                        </div>
                        <div class="table-responsive">

                        <table id="table_user" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    @if (Auth::user()->role != 'user')
                                    <th>Nama</th>
                                    @endif
                                    <th class="text-center">Jenis</th>
                                    <th class="text-center">Tanggal Mulai Cuti</th>
                                    <th class="text-center">Tanggal Selesai Cuti</th>
                                    <th class="text-center">Jumlah Hari</th>
                                    <th class="text-center">Keterangan</th>
                                    <th class="text-center">Status Admin</th>
                                    <th class="text-center">Status SuperAdmin</th>
                                </tr>
                            </thead>
                          
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($cuti as $item)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    @if (Auth::user()->role != 'user')
                                    <td class="text-center">{{ $item->user->name ?? '-' }}</td>
                                    @endif
                                    <td class="text-center">{{ $item->jenis ?? '-' }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($item->tgl_mulai_cuti)->format('d-m-Y') }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($item->tgl_selesai_cuti)->format('d-m-Y') }}</td>
                                    <td class="text-center">{{ $item->jumlah_hari ?? '-' }}</td>
                                    <td class="text-center">{{ $item->keterangan ?? '-' }}</td>
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
        window.location = "{{ route('user.create') }}";
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

           
        });

    });

    document.getElementById('exportPdfBtn').addEventListener('click', function () {
        const start = document.getElementById('start_date').value;
        const end = document.getElementById('end_date').value;
        const userId = document.querySelector('select[name="user_id"]')?.value ?? '';

        let url = new URL("{{ url('/rekap-cuti/export-pdf') }}");

        if (start) url.searchParams.append('start_date', start);
        if (end) url.searchParams.append('end_date', end);
        if (userId) url.searchParams.append('user_id', userId);

        window.open(url.toString(), '_blank');
    });
</script>
@stop
