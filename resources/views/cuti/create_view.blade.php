@extends('adminlte::page')

@section('title', 'Pengajuan Cuti')
@section('content_header')
<h1>Pengajuan Cuti</h1>
@stop

@section('content')
<div id="layoutSidenav">
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @elseif(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
        
              
                <div class="row">

                    <div class="col-md-12">

                        <form action="{{ route('cuti.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="jenis">Jenis</label>
                                <select name="jenis" id="jenis" class="form-control" required>
                                    <option value="Cuti">Cuti</option>
                                    <option value="Izin">Izin</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tgl_mulai_cuti">Tanggal Mulai</label>
                                <input type="date" name="tgl_mulai_cuti" id="tgl_mulai_cuti" class="form-control" required>
                            </div>
                    
                            <div class="form-group">
                                <label for="tgl_selesai_cuti">Tanggal Selesai</label>
                                <input type="date" name="tgl_selesai_cuti" id="tgl_selesai_cuti" class="form-control" required>
                            </div>
                    
                            <div class="form-group">
                                <label for="jumlah_hari">Jumlah Hari</label>
                                <input type="number" name="jumlah_hari" id="jumlah_hari" class="form-control" readonly>
                            </div>
                    
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" rows="4" class="form-control" required></textarea>
                            </div>
                    
                            <button type="submit" class="btn btn-primary mt-3">Ajukan</button>
                        </form>
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
  
   

  document.getElementById('tgl_selesai_cuti').addEventListener('change', function() {
    var mulai = document.getElementById('tgl_mulai_cuti').value;
    var selesai = document.getElementById('tgl_selesai_cuti').value;

    if (mulai && selesai) {
        var tglMulai = new Date(mulai);
        var tglSelesai = new Date(selesai);
        var diffTime = Math.abs(tglSelesai - tglMulai);
        var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // Menambahkan 1 untuk menghitung hari terakhir
        document.getElementById('jumlah_hari').value = diffDays;
    }
});


   

</script>
@stop
