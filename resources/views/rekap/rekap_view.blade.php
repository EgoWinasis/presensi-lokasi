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
                        <form method="GET" action="{{ route('rekap.index') }}" class="form-inline mb-3">
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
                            <a href="{{ route('rekap.index') }}" class="btn btn-secondary ml-2">Reset</a>
                        </form>
                        
                        <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="card text-white bg-primary">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Total Hari Hadir</h5>
                                    <p class="card-text display-6">6</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-success">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Tepat Waktu</h5>
                                    <p class="card-text display-6">
                                        5
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-danger">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Terlambat</h5>
                                    <p class="card-text display-6">
                                        6
                                    </p>
                                </div>
                            </div>
                        </div>
                        </div>

                        <div class="table-responsive">

                        <table id="table_user" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Tanggal</th>
                                    @if (Auth::user()->role != 'user')
                                    <th>Nama</th>
                                    @endif
                                    <th class="text-center">Jam Masuk</th>
                                    <th class="text-center">Foto Masuk</th>
                                    <th class="text-center">Lokasi Masuk</th>
                                    <th class="text-center">Keterangan Masuk</th>
                                    <th class="text-center">Jam Keluar</th>
                                    <th class="text-center">Foto Keluar</th>
                                    <th class="text-center">Lokasi Keluar</th>
                                    <th class="text-center">Keterangan Keluar</th>
                                </tr>
                            </thead>
                          
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($presensi as $item)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($item->tgl)->format('d-m-Y') }}</td>
                                    @if (Auth::user()->role != 'user')
                                    <td class="text-center">{{ $item->user->name ?? '-' }}</td>
                                    @endif
                                    <td class="text-center">{{ $item->jam_masuk ?? '-' }}</td>

                                    <!-- Handle Foto Masuk -->
                                    <td class="text-center">
                                        @if($item->foto_masuk)
                                        <a href="{{ asset('storage/presensi_images/'.$item->foto_masuk) }}"
                                            target="_blank">
                                            <img src="{{ asset('storage/presensi_images/'.$item->foto_masuk) }}"
                                                width="50" height="50" />
                                        </a>
                                        @else
                                        <span>-</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @if($item->lokasi_masuk)
                                            @php
                                                // Extract latitude and longitude from the lokasi_masuk string
                                                preg_match('/lat:\s*(-?\d+\.\d+),\s*lng:\s*(-?\d+\.\d+)/', $item->lokasi_masuk, $matches);
                                                $latitude = $matches[1] ?? null;
                                                $longitude = $matches[2] ?? null;
                                            @endphp
                                    
                                            @if($latitude && $longitude)
                                                <a href="https://www.google.com/maps?q={{ $latitude }},{{ $longitude }}" target="_blank">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @else
                                                <span>-</span>
                                            @endif
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>
                                    
                                    
                                    <td class="text-center">{{ $item->ket_masuk ?? '-' }}</td>

                                    <td class="text-center">{{ $item->jam_keluar ?? '-' }}</td>

                                    <!-- Handle Foto Keluar -->
                                    <td class="text-center">
                                        @if($item->foto_keluar)
                                        <a href="{{ asset('storage/presensi_images/'.$item->foto_keluar) }}"
                                            target="_blank">
                                            <img src="{{ asset('storage/presensi_images/'.$item->foto_keluar) }}"
                                                width="50" height="50" />
                                        </a>
                                        @else
                                        <span>-</span>
                                        @endif
                                    </td>

                                    <!-- Handle Lokasi Keluar (Map Icon) -->
                                    <td class="text-center">
                                        @if($item->lokasi_keluar)
                                            @php
                                                // Extract latitude and longitude from the lokasi_keluar string
                                                preg_match('/lat:\s*(-?\d+\.\d+),\s*lng:\s*(-?\d+\.\d+)/', $item->lokasi_keluar, $matches);
                                                $latitude = $matches[1] ?? null;
                                                $longitude = $matches[2] ?? null;
                                            @endphp
                                    
                                            @if($latitude && $longitude)
                                                <a href="https://www.google.com/maps?q={{ $latitude }},{{ $longitude }}" target="_blank">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @else
                                                <span>-</span>
                                            @endif
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>

                                    <td class="text-center">{{ $item->ket_keluar ?? '-' }}</td>
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

            "buttons": [{
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [0, 1, 2,3,4,5,6,7,8,9]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [0, 1, 2,3,4,5,6,7,8,9]
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [0, 1, 2,3,4,5,6,7,8,9]
                    }
                }
            ]
        }).buttons().container().appendTo('#table_user_wrapper .col-md-6:eq(0)');

    });

 
</script>
@stop
