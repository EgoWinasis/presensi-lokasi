<?php
namespace App\Http\Controllers;

use App\Models\JadwalKaryawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JadwalKaryawanController extends Controller
{
    public function index()
    {
        // Ambil semua data jadwal tanpa pagination
        $users = User::all();
        $jadwals = JadwalKaryawan::with('user')->orderByDesc('tgl')->get();  // Ambil semua data jadwal

        return view('pengaturan.jadwal_karyawan', compact('users', 'jadwals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tgl' => 'required|date',
        ]);

        JadwalKaryawan::create([
            'user_id' => $request->user_id,
            'tgl' => $request->tgl,
        ]);

        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        $data = \PhpOffice\PhpSpreadsheet\IOFactory::load($file)->getActiveSheet()->toArray();

        $successCount = 0;

        foreach (array_slice($data, 1) as $row) {
            $userId = $row[0];
            $tgl = $row[1];

            if (is_numeric($userId) && $tgl) {
                $validator = Validator::make([
                    'user_id' => $userId,
                    'tgl' => $tgl,
                ], [
                    'user_id' => 'required|exists:users,id',
                    'tgl' => 'required|date',
                ]);

                if (!$validator->fails()) {
                    JadwalKaryawan::create([
                        'user_id' => $userId,
                        'tgl' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tgl)->format('Y-m-d'),
                    ]);
                    $successCount++;
                }
            }
        }

        return redirect()->back()->with('success', "$successCount jadwal berhasil diimport.");
    }

    public function template()
    {
        return response()->download(public_path('template-jadwal.xlsx'));
    }
}
