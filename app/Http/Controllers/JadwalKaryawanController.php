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
         // Fetch users with specific conditions: role = 'user', isActive = 1, deleted_at is NULL
    $users = User::where('role', 'user')
    ->where('isActive', 1)
    ->whereNull('deleted_at')
    ->get();

    // Fetch all jadwal data with the related user, ordered by 'tgl' in descending order
    $jadwals = JadwalKaryawan::with('user')->orderByDesc('tgl')->get();

        return view('pengaturan.jadwal_karyawan', compact('users', 'jadwals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tgl' => 'required|date',
            'keterangan' => 'required|string',
        ]);

        JadwalKaryawan::create([
            'user_id' => $request->user_id,
            'tgl' => $request->tgl,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function importJson(Request $request)
{
    $data = $request->input('data'); // expects array of rows

    foreach ($data as $row) {
        \App\Models\JadwalKaryawan::create([
            'user_id' => $row['user_id'] ?? null,
            'tgl'     => $row['tgl'] ?? null,
            'keterangan'     => $row['keterangan'] ?? null,
        ]);
    }

    return response()->json(['message' => 'Data berhasil diimport.']);
}

}
