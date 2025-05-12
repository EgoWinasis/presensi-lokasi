<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Cuti</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
        }
        .heading {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="heading">
        <h2>Detail Cuti</h2>
    </div>

    <table>
        <tr>
            <th>Nama</th>
            <td>{{ $cuti->user->name }}</td>
        </tr>
        <tr>
            <th>Jenis</th>
            <td>{{ $cuti->jenis }}</td>
        </tr>
        <tr>
            <th>Tanggal Mulai</th>
            <td>{{ \Carbon\Carbon::parse($cuti->tgl_mulai_cuti)->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <th>Tanggal Selesai</th>
            <td>{{ \Carbon\Carbon::parse($cuti->tgl_selesai_cuti)->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <th>Jumlah Hari</th>
            <td>{{ $cuti->jumlah_hari }}</td>
        </tr>
        <tr>
            <th>Keterangan</th>
            <td>{{ $cuti->keterangan }}</td>
        </tr>
        <tr>
            <th>Status Admin</th>
            <td>{{ $cuti->status_admin }}</td>
        </tr>
        <tr>
            <th>Catatan Admin</th>
            <td>{{ $cuti->catatan_admin }}</td>
        </tr>
        <tr>
            <th>Status Superadmin</th>
            <td>{{ $cuti->status_superadmin }}</td>
        </tr>
        <tr>
            <th>Catatan Superadmin</th>
            <td>{{ $cuti->catatan_superadmin }}</td>
        </tr>
    </table>

</body>
</html>
