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
@if ($isHoliday || $isLibur)
    <div class="alert alert-warning">
        <strong>Hari ini Hari Libur!</strong> Anda tidak bisa melakukan presensi hari ini.
    </div>
@else
    @if ($presensiToday === null)
        <div class="mt-4">
            <!-- Disable Masuk button if current time is before jam_masuk -->
            @php
                $currentTime = \Carbon\Carbon::now()->format('H:i'); // Current time
            @endphp
            @if ($currentTime < $jamMasuk) 
                <button id="btnMasuk" class="btn btn-success w-100">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>
            @else
                <button id="btnMasuk" class="btn btn-success w-100" disabled>
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>
            @endif
        </div>
        <div class="mt-4">
            <!-- Disable Pulang button if current time is after jam_pulang or if user hasn't marked Masuk -->
            <button id="btnPulang" class="btn btn-danger w-100" disabled>
                <i class="fas fa-sign-out-alt"></i> Pulang
            </button>
        </div>
    @else     
        <div class="mt-4">
            <!-- Disable Masuk button if current time is before jam_masuk -->
            @if ($currentTime < $jamMasuk) 
                <button id="btnMasuk" class="btn btn-success w-100" disabled>
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>
            @else
                <button id="btnMasuk" class="btn btn-success w-100" disabled>
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>
            @endif
        </div>
        @if ($presensiToday->jam_keluar !== '-')
            <div class="mt-4">
                <!-- Disable Pulang button if current time is after jam_pulang -->
                @if ($currentTime >= $jamKeluar)
                    <button id="btnPulang" class="btn btn-danger w-100" disabled>
                        <i class="fas fa-sign-out-alt"></i> Pulang
                    </button>
                @else
                    <button id="btnPulang" class="btn btn-danger w-100">
                        <i class="fas fa-sign-out-alt"></i> Pulang
                    </button>
                @endif
            </div>
        @else
            <div class="mt-4">
                <!-- Enable Pulang button only if jam_keluar is not marked as '-' -->
                <button id="btnPulang" class="btn btn-danger w-100">
                    <i class="fas fa-sign-out-alt"></i> Pulang
                </button>
            </div>
        @endif
    @endif
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
                                        @if($presensiToday)
                                        <tr>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($presensiToday->tgl)->format('d-m-Y') }}</td>
                                            <td class="text-center">{{ $presensiToday->jam_masuk ?? '-' }}</td>

                                            <!-- Handle Foto Masuk -->
                                            <td class="text-center">
                                                @if($presensiToday->foto_masuk)
                                                <a href="{{ asset('storage/presensi_images/'.$presensiToday->foto_masuk) }}"
                                                    target="_blank">
                                                    <img src="{{ asset('storage/presensi_images/'.$presensiToday->foto_masuk) }}"
                                                        width="50" height="50" />
                                                </a>
                                                @else
                                                <span>-</span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if($presensiToday->lokasi_masuk)
                                                    @php
                                                        // Extract latitude and longitude from the lokasi_masuk string
                                                        preg_match('/lat:\s*(-?\d+\.\d+),\s*lng:\s*(-?\d+\.\d+)/', $presensiToday->lokasi_masuk, $matches);
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
                                            
                                            
                                            <td class="text-center">{{ $presensiToday->ket_masuk ?? '-' }}</td>

                                            <td class="text-center">{{ $presensiToday->jam_keluar ?? '-' }}</td>

                                            <!-- Handle Foto Keluar -->
                                            <td class="text-center">
                                                @if($presensiToday->foto_keluar)
                                                <a href="{{ asset('storage/presensi_images/'.$presensiToday->foto_keluar) }}"
                                                    target="_blank">
                                                    <img src="{{ asset('storage/presensi_images/'.$presensiToday->foto_keluar) }}"
                                                        width="50" height="50" />
                                                </a>
                                                @else
                                                <span>-</span>
                                                @endif
                                            </td>

                                            <!-- Handle Lokasi Keluar (Map Icon) -->
                                            <td class="text-center">
                                                @if($presensiToday->lokasi_keluar)
                                                    @php
                                                        // Extract latitude and longitude from the lokasi_keluar string
                                                        preg_match('/lat:\s*(-?\d+\.\d+),\s*lng:\s*(-?\d+\.\d+)/', $presensiToday->lokasi_keluar, $matches);
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

                                            <td class="text-center">{{ $presensiToday->ket_keluar ?? '-' }}</td>
                                        </tr>

                                        @else
                                        <tr>
                                            <td colspan="9">Belum ada presensi hari ini.</td>
                                        </tr>
                                        @endif


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
<!-- Modal for Camera Capture -->
<div class="modal fade" id="cameraModal" tabindex="-1" aria-labelledby="cameraModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cameraModalLabel">Ambil Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Camera container -->
                <video id="video" width="100%" height="auto" autoplay></video>
                <button id="captureBtn" class="btn btn-primary mt-3">Ambil</button>
                <canvas id="canvas" style="display: none;"></canvas>

                <!-- Image preview of captured photo -->
                <img id="capturedImage" src="" style="display: none; width: 100%; height: auto;" />
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
var latitude ;
var longitude;
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
         latitude = position.coords.latitude;
         longitude = position.coords.longitude;

        // Get the location from the server side (use Blade syntax to echo PHP variables into JS)
        var lokasiLatitude = @json($lokasi -> latitude ?? '-6.912112310764233'); // Blade echo with fallback
        var lokasiLongitude = @json($lokasi -> longitude ?? '109.13200378417969'); // Blade echo with fallback
        var radius = 100; // Set the radius in meters (maximum distance)

        // Get the map and loading spinner elements
        const mapContainer = document.getElementById('map');


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

        // Add a circle (radius) around the location from 'lokasi' with a maximum radius of 100 meters
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
            radius: radius // Same radius of 100 meters
        }).addTo(map);

        // Calculate the distance between the user's location and the predefined location
        var distance = calculateDistance(latitude, longitude, lokasiLatitude, lokasiLongitude);
        if (distance > 50) {
            Swal.fire({
                type: 'warning',
                title: 'Perhatian!',
                text: 'Anda berada di luar radius presensi. Tidak bisa melakukan presensi.',
                showConfirmButton: false,
                timer: 3000
            });
            // Hide the presensi buttons if the user is out of radius
            document.getElementById("btnMasuk").style.display = 'none';
            document.getElementById("btnPulang").style.display = 'none';
        } else {
            // Show the presensi buttons if the user is within the radius
            document.getElementById("btnMasuk").style.display = 'inline-block'; // or 'block' based on your layout
            document.getElementById("btnPulang").style.display = 'inline-block'; // or 'block' based on your layout
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
    navigator.geolocation.getCurrentPosition(onLocationFound, onLocationError, {
        enableHighAccuracy: true,
        timeout: 10000, // 10 detik, supaya kalau gagal nggak nunggu lama
        maximumAge: 0 // jangan pakai cache lokasi lama
    });
} else {
    alert("Geolocation is not supported by this browser.");
}


    document.addEventListener("DOMContentLoaded", function () {
        const btnMasuk = document.getElementById("btnMasuk");
        const btnPulang = document.getElementById("btnPulang");
        const cameraModalElement = document.getElementById('cameraModal');
        const cameraModal = new bootstrap.Modal(cameraModalElement);
        const video = document.getElementById("video");
        const captureBtn = document.getElementById("captureBtn");
        const canvas = document.getElementById("canvas");
        const capturedImage = document.getElementById("capturedImage");
        let stream; // Store the stream reference
        let actionType = ''; // Store the current action type (Masuk or Pulang)
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        // Check if capturedImage exists to avoid null reference
        if (!capturedImage) {
            console.error('capturedImage element not found!');
            return;
        }

        // Start the camera
        function startCamera() {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({
                        video: true
                    })
                    .then(function (userStream) {
                        stream = userStream;
                        video.srcObject = stream;
                    })
                    .catch(function (err) {
                        console.log("Error accessing webcam: ", err);
                    });
            } else {
                alert("Your browser does not support webcam access.");
            }
        }

        // Stop the camera
        function stopCamera() {
            if (stream) {
                const tracks = stream.getTracks();
                tracks.forEach(track => track.stop());
            }
        }

        // Capture the image
        function captureImage() {
            const ctx = canvas.getContext("2d");
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            capturedImage.src = canvas.toDataURL("image/png");
            capturedImage.style.display = "block";
        }

        // Handle "Masuk" button click
        btnMasuk.addEventListener("click", function () {
            actionType = 'masuk';
            document.getElementById('cameraModalLabel').innerText = "Capture Masuk Image";
            captureBtn.innerText = "Capture Masuk";
            cameraModal.show();
            startCamera();
        });

        // Handle "Pulang" button click
        btnPulang.addEventListener("click", function () {
            actionType = 'pulang';
            document.getElementById('cameraModalLabel').innerText = "Capture Pulang Image";
            captureBtn.innerText = "Capture Pulang";
            cameraModal.show();
            startCamera();
        });

        // Handle capture button click
        captureBtn.addEventListener("click", function () {
            captureImage();
            const currentTime = new Date();
            const currentTimeString = currentTime.toLocaleTimeString('en-GB', { 
    timeZone: 'Asia/Jakarta', 
    hour12: false, 
    hour: '2-digit', 
    minute: '2-digit', 
    second: '2-digit' 
});
           


            const formData = new FormData();
            formData.append('action_type', actionType);
            formData.append('captured_image', canvas.toDataURL("image/png"));
            formData.append('user_time', currentTimeString); // Add current time
            formData.append('user_id', {{Auth::id()}}); // User ID from Blade template

            const lokasiString = `lat: ${latitude}, lng: ${longitude}`;
            formData.append('lokasi', lokasiString);  // Just a plain string

            
            
            // Send AJAX request to store presensi
            fetch('/presensi', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken, // Add CSRF token to the request header
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    cameraModal.hide();
                    Swal.fire({
                        type: 'success',
                        title: 'Presensi Berhasil',
                        text: 'Presensi Anda telah berhasil disimpan.',
                    }).then(() => {
                        window.location.reload(); // Reload the page to see updated data
                    });
                })
                .catch(error => {
                    console.error("Error storing presensi:", error);
                    Swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat menyimpan data presensi.',
                    });
                });
        });

        // Stop camera when modal is hidden
        cameraModalElement.addEventListener('hidden.bs.modal', function () {
            stopCamera();
        });
    });

</script>
@endsection
