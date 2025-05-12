<?php
// app/Http/Controllers/JadwalKaryawanController.php
namespace App\Http\Controllers;

use App\Models\JadwalKaryawan;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\JadwalImport;

class JadwalKaryawanController extends Controller
{
    public function index()
    {
        $jadwals = JadwalKaryawan::with('user')->latest()->paginate(10);
        return view('jadwal-karyawan.index', compact('jadwals'));
    }

    public function create()
    {
        $users = User::all();
        return view('jadwal-karyawan.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'tanggal' => 'required|date',
            'shift' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        JadwalKaryawan::create($request->all());
        return redirect()->route('jadwal-karyawan.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new JadwalImport, $request->file('file'));

        return back()->with('success', 'Jadwal berhasil diimport.');
    }

    public function downloadTemplate()
    {
        return response()->download(public_path('template-jadwal.xlsx'));
    }
}
