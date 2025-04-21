@extends('adminlte::page')

@section('title', 'Pengaturan Lokasi')

@section('content_header')
<h1>Pengaturan Lokasi</h1>
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

                    <div class="col-md-12">

                        <form action="{{ route('pengaturan.lokasi.update') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="latitude">Latitude</label>
                                <input type="text" name="latitude" class="form-control" id="latitude"
                                    value="{{ old('latitude', $lokasi->latitude ?? '-6.912112310764233') }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="longitude">Longitude</label>
                                <input type="text" name="longitude" class="form-control" id="longitude"
                                    value="{{ old('longitude', $lokasi->longitude ?? '109.13200378417969') }}" readonly>
                            </div>
                            <button type="submit" class="btn btn-primary my-2">Simpan Lokasi</button>
                        </form>

                        <!-- Map container -->
                        <div id="map" style="height: 400px; width: 100%;" class="my-5"></div>


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
@section('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endsection

@section('js')

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script type="text/javascript">
    // Get the latitude and longitude values from the PHP variables
    var latitude = {{ $lokasi->latitude ?? '-6.912112310764233' }};
    var longitude = {{ $lokasi->longitude ?? '109.13200378417969' }};

    // Initialize the map
    var map = L.map('map').setView([latitude, longitude], 13);

    // Add a tile layer (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Add a marker at the current location
    var marker = L.marker([latitude, longitude], {
        draggable: true
    }).addTo(map);

    // Update latitude and longitude input fields when the marker is dragged
    marker.on('dragend', function (event) {
        var latlng = event.target.getLatLng();
        document.getElementById('latitude').value = latlng.lat;
        document.getElementById('longitude').value = latlng.lng;
    });

    // Enable user to select a location on the map
    map.on('click', function (e) {
        var latlng = e.latlng;
        marker.setLatLng(latlng);
        document.getElementById('latitude').value = latlng.lat;
        document.getElementById('longitude').value = latlng.lng;
    });

</script>

@endsection

