@extends('adminlte::page')

@section('title', 'Pengaturan Lokasi')

@section('content_header')
<h1>Presensi</h1>
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

                        <h5>Lokasi Saat Ini : </h5>
                        <!-- Map container -->
                        <div id="map" style="height: 400px; width: 100%;" class="mb-5 mt-2"></div>

                        <!-- Buttons for Presensi -->
                        @if ($isHoliday)
                        <div class="alert alert-warning">
                            <strong>Today is a holiday!</strong> You cannot mark your attendance today.
                        </div>
                        @else
                        <div class="mt-4">
                            <!-- Disable Masuk button if current time is before jam_masuk -->
                            <button id="btnMasuk" class="btn btn-success w-100" @if($canMasuk || $presensiToday)
                                disabled @endif>
                                <i class="fas fa-sign-in-alt"></i> Masuk
                            </button>
                        </div>
                        <div class="mt-4">
                            <!-- Disable Pulang button if current time is after jam_pulang or if user hasn't marked Masuk -->
                            <button id="btnPulang" class="btn btn-danger w-100" @if($canPulang || !$presensiToday)
                                disabled @endif>
                                <i class="fas fa-sign-out-alt"></i> Pulang
                            </button>
                        </div>
                        @endif

                        <div class="mt-4">
                            <h5>Presensi History</h5>
                            <div class="table-responsive">

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jam Masuk</th>
                                            <th>Foto Masuk</th>
                                            <th>Lokasi Masuk</th>
                                            <th>Keterangan Masuk</th>
                                            <th>Jam Pulang</th>
                                            <th>Foto Pulang</th>
                                            <th>Lokasi Pulang</th>
                                            <th>Keterangan Pulang</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($presensiToday as $presensi)
                                        <tr>
                                            <td>{{ $presensi->tgl }}</td>
                                            <td>{{ $presensi->jam_masuk ?? '-' }}</td>
                                            <td><img src="{{ asset('storage/'.$presensi->foto_masuk) }}" width="50"
                                                    height="50" /></td>
                                            <td>{{ $presensi->lokasi_masuk ?? '-' }}</td>
                                            <td>{{ $presensi->ket_masuk ?? '-' }}</td>
                                            <td>{{ $presensi->jam_keluar ?? '-' }}</td>
                                            <td><img src="{{ asset('storage/'.$presensi->foto_keluar) }}" width="50"
                                                    height="50" /></td>
                                            <td>{{ $presensi->lokasi_keluar ?? '-' }}</td>
                                            <td>{{ $presensi->ket_keluar ?? '-' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9">Belum ada presensi.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<!-- Modal for Camera -->
<div class="modal fade" id="cameraModal" tabindex="-1" aria-labelledby="cameraModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cameraModalLabel">Capture Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Camera container -->
                <video id="video" width="100%" height="auto" autoplay></video>
                <button id="captureBtn" class="btn btn-primary mt-3">Capture</button>
                <canvas id="canvas" style="display: none;"></canvas>
            </div>
        </div>
    </div>
</div>

@stop

@section('footer')
@include('footer')
@stop


@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.Sweetalert2', true)


@section('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endsection

@section('js')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- Bootstrap 5 Modal JS -->
<script type="text/javascript">
    // Function to calculate distance between two coordinates (in meters) using Haversine formula
    function calculateDistance(lat1, lon1, lat2, lon2) {
        var R = 6371000; // Earth's radius in meters
        var φ1 = lat1 * Math.PI / 180;
        var φ2 = lat2 * Math.PI / 180;
        var Δφ = (lat2 - lat1) * Math.PI / 180;
        var Δλ = (lon2 - lon1) * Math.PI / 180;

        var a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
            Math.cos(φ1) * Math.cos(φ2) *
            Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        var distance = R * c; // Distance in meters
        return distance;
    }

    // Function to handle geolocation and update the map
    function onLocationFound(position) {
        // Get the latitude and longitude from the geolocation API
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;

        // Get the location from the server side (you can retrieve this dynamically from your PHP controller)
        var lokasiLatitude = {
            {
                $lokasi - > latitude ? ? '-6.912112310764233'
            }
        };
        var lokasiLongitude = {
            {
                $lokasi - > longitude ? ? '109.13200378417969'
            }
        };
        var radius = 100; // Set the radius in meters (maximum distance)

        // Initialize the map centered on the user's location
        var map = L.map('map').setView([latitude, longitude], 13);

        // Add a tile layer (OpenStreetMap)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Add a marker at the user's location
        var marker = L.marker([latitude, longitude], {
            draggable: true
        }).addTo(map);

        // Add a circle (radius) around the location from 'lokasi' with a maximum radius of 10 meters
        var locationCircle = L.circle([lokasiLatitude, lokasiLongitude], {
            color: 'green',
            fillColor: '#30ff00',
            fillOpacity: 0.3,
            radius: radius // Radius in meters (maximum distance allowed)
        }).addTo(map);

        // Add a circle for the user's current location (optional)
        var userCircle = L.circle([latitude, longitude], {
            color: 'blue',
            fillColor: '#0000ff',
            fillOpacity: 0.3,
            radius: radius // Same radius of 10 meters
        }).addTo(map);

        // Calculate the distance between the user's location and the predefined location
        var distance = calculateDistance(latitude, longitude, lokasiLatitude, lokasiLongitude);
        if (distance > 10) {
            Swal.fire({
                type: 'warning',
                title: 'Perhatian!',
                text: 'Anda berada di luar radius presensi. Tidak bisa melakukan presensi.',
                showConfirmButton: false,
                timer: 3000
            });
            // Disable the presensi buttons if the user is out of radius
            document.getElementById("btnMasuk").disabled = true;
            document.getElementById("btnPulang").disabled = true;
        } else {
            // Enable the presensi buttons if the user is within the radius
            document.getElementById("btnMasuk").disabled = false;
            document.getElementById("btnPulang").disabled = false;
        }

        // Optionally, update the map's zoom level based on the circle's bounds
        map.fitBounds(locationCircle.getBounds());

        // If the user drags the marker, update the circle's position and the input fields
        marker.on('dragend', function (event) {
            var latlng = event.target.getLatLng();
            document.getElementById('latitude').value = latlng.lat;
            document.getElementById('longitude').value = latlng.lng;

            // Update circle position
            userCircle.setLatLng(latlng);
        });

        // Enable the user to select a location on the map by clicking
        map.on('click', function (e) {
            var latlng = e.latlng;
            marker.setLatLng(latlng);
            document.getElementById('latitude').value = latlng.lat;
            document.getElementById('longitude').value = latlng.lng;

            // Update circle position
            userCircle.setLatLng(latlng);
        });
    }

    // Function to handle geolocation errors
    function onLocationError(error) {
        alert('Unable to retrieve your location. Please enable location services.');
    }

    // If the browser supports geolocation, get the user's location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(onLocationFound, onLocationError);
    } else {
        alert("Geolocation is not supported by this browser.");
    }

</script>

@endsection
