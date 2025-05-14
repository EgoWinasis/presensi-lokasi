<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalKaryawan;
use Illuminate\Support\Facades\Auth;

class JadwalKerjaController extends Controller
{
    public function index()
    {
        return view('jadwal_kerja.index');
    }

    public function getJadwal()
    {
        $userId = Auth::id();
        $jadwal = JadwalKaryawan::where('user_id', $userId)->get();

        $events = $jadwal->map(function ($item) {
            return [
                'title' => $item->keterangan, // e.g., "Masuk" or "Libur"
                'start' => $item->tgl,
                'color' => $item->keterangan === 'Libur' ? '#e74c3c' : '#2ecc71',
            ];
        });

        return response()->json($events);
    }
}
