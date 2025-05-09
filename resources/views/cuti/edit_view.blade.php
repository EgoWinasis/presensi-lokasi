@extends('adminlte::page')

@section('title', 'Edit Pengajuan Cuti')
@section('content_header')
<h1>Edit Pengajuan Cuti</h1>
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

                
                <div class="row">
                    <div class="col-md-12">
                        <!-- Form for editing leave request -->
                        <form action="{{ route('cuti.update', $cuti->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="jenis">Jenis</label>
                                <select name="jenis" id="jenis" class="form-control" required>
                                    <option value="Cuti" {{ old('jenis', $data->jenis) == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                                    <option value="Izin" {{ old('jenis', $data->jenis) == 'Izin' ? 'selected' : '' }}>Izin</option>
                                </select>
                            </div>

                            <!-- Start Date Field -->
                            <div class="form-group">
                                <label for="tgl_mulai_cuti">Tanggal Mulai Cuti</label>
                                <input type="date" name="tgl_mulai_cuti" id="tgl_mulai_cuti" class="form-control" value="{{ $cuti->tgl_mulai_cuti }}" required>
                            </div>

                            <!-- End Date Field -->
                            <div class="form-group">
                                <label for="tgl_selesai_cuti">Tanggal Selesai Cuti</label>
                                <input type="date" name="tgl_selesai_cuti" id="tgl_selesai_cuti" class="form-control" value="{{ $cuti->tgl_selesai_cuti }}" required>
                            </div>

                            <!-- Total Days Field -->
                            <div class="form-group">
                                <label for="jumlah_hari">Jumlah Hari</label>
                                <input type="number" name="jumlah_hari" id="jumlah_hari" class="form-control" value="{{ $cuti->jumlah_hari }}" required readonly>
                            </div>

                            <!-- Notes Field -->
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" rows="4" class="form-control" required>{{ $cuti->keterangan }}</textarea>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                        <br><br>
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

@section('js')
<script type="text/javascript">
    // JavaScript to calculate the number of days between start and end dates
    document.getElementById('tgl_selesai_cuti').addEventListener('change', function() {
        var mulai = document.getElementById('tgl_mulai_cuti').value;
        var selesai = document.getElementById('tgl_selesai_cuti').value;

        if (mulai && selesai) {
            var tglMulai = new Date(mulai);
            var tglSelesai = new Date(selesai);
            var diffTime = Math.abs(tglSelesai - tglMulai);
            var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // Adding 1 to count the last day
            document.getElementById('jumlah_hari').value = diffDays;
        }
    });
    document.getElementById('tgl_mulai_cuti').addEventListener('change', function() {
        var mulai = document.getElementById('tgl_mulai_cuti').value;
        var selesai = document.getElementById('tgl_selesai_cuti').value;

        if (mulai && selesai) {
            var tglMulai = new Date(mulai);
            var tglSelesai = new Date(selesai);
            var diffTime = Math.abs(tglSelesai - tglMulai);
            var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // Adding 1 to count the last day
            document.getElementById('jumlah_hari').value = diffDays;
        }
    });
</script>
@stop
