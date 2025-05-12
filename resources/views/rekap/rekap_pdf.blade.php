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

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <tr>
            <td style="text-align: center;">
                <div style=" padding: 10px; width: 200px; margin: auto;">
                    <strong>Total Presensi</strong>
                    <div>{{ $totalPresensi }}</div>
                </div>
            </td>
            <td style="text-align: center;">
                <div style=" color: white; padding: 10px; width: 200px; margin: auto;">
                    <strong>Tepat Waktu</strong>
                    <div>{{ $tepatWaktu }} ({{ $totalPresensi > 0 ? round(($tepatWaktu / $totalPresensi) * 100, 2) : 0 }}%)</div>
                </div>
            </td>
            <td style="text-align: center;">
                <div style=" color: white; padding: 10px; width: 200px; margin: auto;">
                    <strong>Terlambat</strong>
                    <div>{{ $terlambat }} ({{ $totalPresensi > 0 ? round(($terlambat / $totalPresensi) * 100, 2) : 0 }}%)</div>
                </div>
            </td>
        </tr>
    </table>
    
    
    
    

    <table>
        <thead>
            <tr>
                <th>No</th>
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
            @foreach ($presensi as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->user->name ?? '-' }}</td>
                    <td>{{ $item->created_at->format('Y-m-d') }}</td>
                    <td>{{ $item->jam_masuk ?? '-' }}</td>
                    <td>
                        @if (!empty($item->foto_masuk))
                        <img src="https://teti-presensi.my.id/storage/presensi_images/{{ $item->foto_masuk }}" alt="Foto Masuk" width="50" height="50">
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $item->ket_masuk ?? '-' }}</td>
                    <td>{{ $item->jam_keluar ?? '-' }}</td>
                    <td>
                        @if (!empty($item->foto_keluar))
                        <img src="https://teti-presensi.my.id/storage/presensi_images/{{ $item->foto_keluar }}" alt="Foto Keluar" width="50" height="50">
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
