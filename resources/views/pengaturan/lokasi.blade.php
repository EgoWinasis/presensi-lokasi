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
    // Get initial marker location from JSON/PHP (e.g. lokasi from DB)
    var initialLat = {{ $lokasi->latitude ?? '-6.912112310764233' }};
    var initialLng = {{ $lokasi->longitude ?? '109.13200378417969' }};

    // Initialize the map centered at the JSON location
    var map = L.map('map').setView([initialLat, initialLng], 13);

    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Marker 1: from JSON/database (draggable)
    var marker1 = L.marker([initialLat, initialLng], { draggable: true }).addTo(map)
        .bindPopup("Lokasi dari database").openPopup();

    // Update hidden input fields when Marker 1 is dragged
    marker1.on('dragend', function (e) {
        var latlng = e.target.getLatLng();
        document.getElementById('latitude').value = latlng.lat;
        document.getElementById('longitude').value = latlng.lng;
    });

    // Update Marker 1 location on map click
    map.on('click', function (e) {
        marker1.setLatLng(e.latlng);
        document.getElementById('latitude').value = e.latlng.lat;
        document.getElementById('longitude').value = e.latlng.lng;
    });

    // Marker 2: get current location from browser
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                var userLat = position.coords.latitude;
                var userLng = position.coords.longitude;
                var userLatLng = [userLat, userLng];

                // Marker 2: current location (blue icon)
                var marker2 = L.marker(userLatLng, {
                    icon: L.icon({
                        iconUrl: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
                        iconSize: [32, 32],
                        iconAnchor: [16, 32]
                    })
                }).addTo(map).bindPopup("Now Location").openPopup();
            },
            function (error) {
                console.warn("Geolocation error:", error.message);
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    } else {
        console.warn("Geolocation is not supported.");
    }
</script>


@endsection

