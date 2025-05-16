<!DOCTYPE html>
<html>
<head>
    <title>Rekap Cuti / Izin</title>
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

    <h3>Rekap Cuti / Izin</h3>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <tr>
            <td style="text-align: center;">
                <div style=" padding: 10px; width: 200px; margin: auto;">
                    <strong>Total Pengajuan</strong>
                    <div>{{ $totalCuti }}</div>
                </div>
            </td>
            <td style="text-align: center;">
                <div style=" padding: 10px; width: 200px; margin: auto;">
                    <strong>Disetujui</strong>
                    <div>{{ $cutiApproved }} ({{ $totalCuti > 0 ? round(($cutiApproved / $totalCuti) * 100, 2) : 0 }}%)</div>
                </div>
            </td>
            <td style="text-align: center;">
                <div style=" padding: 10px; width: 200px; margin: auto;">
                    <strong>Ditolak</strong>
                    <div>{{ $cutiRejected }} ({{ $totalCuti > 0 ? round(($cutiRejected / $totalCuti) * 100, 2) : 0 }}%)</div>
                </div>
            </td>
            <td style="text-align: center;">
                <div style=" padding: 10px; width: 200px; margin: auto;">
                    <strong>Menunggu</strong>
                    <div>{{ $cutiPending }} ({{ $totalCuti > 0 ? round(($cutiPending / $totalCuti) * 100, 2) : 0 }}%)</div>
                </div>
            </td>
        </tr>
    </table>
    
    
    
    

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Nama</th>
                <th class="text-center">Jenis</th>
                <th class="text-center">Tanggal Mulai Cuti</th>
                <th class="text-center">Tanggal Selesai Cuti</th>
                <th class="text-center">Jumlah Hari</th>
                <th class="text-center">Keterangan</th>
                <th class="text-center">Status Admin</th>
                <th class="text-center">Status SuperAdmin</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cuti as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->user->name ?? '-' }}</td>
                    <td>{{ $item->jenis ?? '-' }}</td>
                    <td>{{ $item->tanggal_mulai_cuti ?? '-' }}</td>
                    <td>{{ $item->tanggal_selesai_cuti ?? '-' }}</td>
                    <td>{{ $item->jumlah_hari ?? '-' }}</td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                    <td>{{ ucfirst($item->status_admin) ?? '-' }}</td>
                    <td>{{ ucfirst($item->status_superadmin) ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    

</body>
</html>
