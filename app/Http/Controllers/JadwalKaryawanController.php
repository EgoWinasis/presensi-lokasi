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
        ]);

        JadwalKaryawan::create([
            'user_id' => $request->user_id,
            'tgl' => $request->tgl,
        ]);

        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function import(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        // Process the file using SheetJS or Laravel Excel package if needed
        // For example, you could use Laravel Excel package for import
        // Here we assume you handle the import logic or add it later

        // Return to the previous page with success message after handling the import
        return back()->with('success', 'File imported successfully.');
    }

    public function template()
    {
        return response()->download(public_path('template-jadwal.xlsx'));
    }
}
