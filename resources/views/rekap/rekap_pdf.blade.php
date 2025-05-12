<!DOCTYPE html>
<html>
<head>
    <title>Rekap Presensi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h3 { text-align: center; margin-bottom: 20px; }

        .summary {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .card {
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            padding: 10px;
            margin: 0 10px;
            width: 150px;
            text-align: center;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #ddd;
        }

        img {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
    </style>
</head>
<body>

    <h3>Rekap Presensi</h3>

    <div class="summary">
        <div class="card">
            <strong>Total Presensi</strong>
            <div>{{ $totalPresensi }}</div>
        </div>
        <div class="card">
            <strong>Tepat Waktu</strong>
            <div>{{ $tepatWaktu }}</div>
        </div>
        <div class="card">
            <strong>Terlambat</strong>
            <div>{{ $terlambat }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Foto Masuk</th>
                <th>Keterangan Masuk</th>
                <th>Jam Keluar</th>
                <th>Foto Keluar</th>
                <th>Keterangan Keluar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($presensi as $item)
                <tr>
                    <td>{{ $item->user->name ?? '-' }}</td>
                    <td>{{ $item->created_at->format('Y-m-d') }}</td>
                    <td>{{ $item->jam_masuk ?? '-' }}</td>
                    <td>
                        @if (!empty($item->foto_masuk))
                            <img src="{{ public_path('storage/presensi_images/'.$item->foto_masuk) }}" alt="Foto Masuk">
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $item->ket_masuk ?? '-' }}</td>
                    <td>{{ $item->jam_keluar ?? '-' }}</td>
                    <td>
                        @if (!empty($item->foto_keluar))
                            <img src="{{ public_path('storage/presensi_images/'.$item->foto_keluar) }}" alt="Foto Keluar">
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $item->ket_keluar ?? '-' }}</td>
                    <td>{{ $item->status ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
