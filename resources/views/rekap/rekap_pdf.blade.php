<!DOCTYPE html>
<html>
<head>
    <title>Rekap Presensi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h3>Rekap Presensi</h3>
    <p><strong>Total Presensi:</strong> {{ $totalPresensi }}</p>
    <p><strong>Tepat Waktu:</strong> {{ $tepatWaktu }}</p>
    <p><strong>Terlambat:</strong> {{ $terlambat }}</p>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($presensi as $row)
                <tr>
                    <td>{{ $row->user->name ?? '-' }}</td>
                    <td>{{ $row->created_at->format('Y-m-d') }}</td>
                    <td>{{ $row->jam_masuk ?? '-' }}</td>
                    <td>{{ $row->jam_keluar ?? '-' }}</td>
                    <td>{{ $row->status ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
