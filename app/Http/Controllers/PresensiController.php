<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Lokasi;
use App\Models\Holiday;
use App\Models\JamAbsen;

class PresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the authenticated user
        $userId = Auth::id();

        // Get the location data from the 'lokasi' table
        $lokasi = Lokasi::first(); // Assuming you only have one location in the table

        // Get the holidays from the 'holidays' table
        $holidays = Holiday::where('date', '>=', now()->toDateString())->get(); // Get future holidays

        // Check if today is a holiday
        $today = now()->toDateString(); // Get today's date in Y-m-d format
        $isHoliday = $holidays->contains('date', $today); // Check if today's date is in the holidays table

        // Get the attendance time settings from the 'jam_absen' table
        $jamAbsen = JamAbsen::first(); // Assuming there is one record for attendance times

        // Get today's attendance record for the authenticated user
        $presensiToday = Presensi::where('user_id', $userId)
                                 ->whereDate('tgl', $today) // Filter by today's date
                                 ->first(); // Get the first record

        // Get the current time
        $currentTime = now();

        // Check if it's before 'jam_masuk' or after 'jam_pulang'
        $canMasuk = $currentTime->lt($jamAbsen->jam_masuk); // true if before jam_masuk
        $canPulang = $currentTime->gt($jamAbsen->jam_pulang); // true if after jam_pulang

        // Return the data to the view
        return view('presensi.presensi', compact('lokasi', 'holidays', 'jamAbsen', 'presensiToday', 'isHoliday', 'canMasuk', 'canPulang'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // This can return a view to add a new presensi record
        return view('presensi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'tgl' => 'required|date',
            'jam_masuk' => 'required|date_format:H:i',
            'foto_masuk' => 'required|image|mimes:jpeg,png,jpg,gif',
            'lokasi_masuk' => 'required|array',
            'lokasi_masuk.lat' => 'required|numeric',
            'lokasi_masuk.lng' => 'required|numeric',
            'ket_masuk' => 'nullable|string',
            'jam_keluar' => 'required|date_format:H:i',
            'foto_keluar' => 'required|image|mimes:jpeg,png,jpg,gif',
            'lokasi_keluar' => 'required|array',
            'lokasi_keluar.lat' => 'required|numeric',
            'lokasi_keluar.lng' => 'required|numeric',
            'ket_keluar' => 'nullable|string',
        ]);

        // Handle Foto Masuk upload
        if ($request->hasFile('foto_masuk')) {
            $fotoMasukPath = $request->file('foto_masuk')->store('public/fotos');
            $fotoMasukName = basename($fotoMasukPath);
        } else {
            $fotoMasukName = null;
        }

        // Handle Foto Keluar upload
        if ($request->hasFile('foto_keluar')) {
            $fotoKeluarPath = $request->file('foto_keluar')->store('public/fotos');
            $fotoKeluarName = basename($fotoKeluarPath);
        } else {
            $fotoKeluarName = null;
        }

        // Store the presensi record in the database
        Presensi::create([
            'user_id' => Auth::id(),
            'tgl' => $validated['tgl'],
            'jam_masuk' => $validated['jam_masuk'],
            'foto_masuk' => $fotoMasukName,
            'lokasi_masuk' => json_encode($validated['lokasi_masuk']),
            'ket_masuk' => $validated['ket_masuk'] ?? null,
            'jam_keluar' => $validated['jam_keluar'],
            'foto_keluar' => $fotoKeluarName,
            'lokasi_keluar' => json_encode($validated['lokasi_keluar']),
            'ket_keluar' => $validated['ket_keluar'] ?? null,
        ]);

        // Redirect back to the presensi index with a success message
        return redirect()->route('presensi.index')->with('success', 'Presensi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $presensi = Presensi::findOrFail($id);

        return view('presensi.show', compact('presensi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $presensi = Presensi::findOrFail($id);

        return view('presensi.edit', compact('presensi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $presensi = Presensi::findOrFail($id);

        // Validate the incoming request data
        $validated = $request->validate([
            'tgl' => 'required|date',
            'jam_masuk' => 'required|date_format:H:i',
            'foto_masuk' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'lokasi_masuk' => 'required|array',
            'lokasi_masuk.lat' => 'required|numeric',
            'lokasi_masuk.lng' => 'required|numeric',
            'ket_masuk' => 'nullable|string',
            'jam_keluar' => 'required|date_format:H:i',
            'foto_keluar' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'lokasi_keluar' => 'required|array',
            'lokasi_keluar.lat' => 'required|numeric',
            'lokasi_keluar.lng' => 'required|numeric',
            'ket_keluar' => 'nullable|string',
        ]);

        // Handle Foto Masuk upload (if any)
        if ($request->hasFile('foto_masuk')) {
            // Delete old file if exists
            if ($presensi->foto_masuk) {
                Storage::delete('public/fotos/' . $presensi->foto_masuk);
            }

            $fotoMasukPath = $request->file('foto_masuk')->store('public/fotos');
            $fotoMasukName = basename($fotoMasukPath);
        } else {
            $fotoMasukName = $presensi->foto_masuk; // Keep existing file
        }

        // Handle Foto Keluar upload (if any)
        if ($request->hasFile('foto_keluar')) {
            // Delete old file if exists
            if ($presensi->foto_keluar) {
                Storage::delete('public/fotos/' . $presensi->foto_keluar);
            }

            $fotoKeluarPath = $request->file('foto_keluar')->store('public/fotos');
            $fotoKeluarName = basename($fotoKeluarPath);
        } else {
            $fotoKeluarName = $presensi->foto_keluar; // Keep existing file
        }

        // Update the presensi record in the database
        $presensi->update([
            'tgl' => $validated['tgl'],
            'jam_masuk' => $validated['jam_masuk'],
            'foto_masuk' => $fotoMasukName,
            'lokasi_masuk' => json_encode($validated['lokasi_masuk']),
            'ket_masuk' => $validated['ket_masuk'] ?? null,
            'jam_keluar' => $validated['jam_keluar'],
            'foto_keluar' => $fotoKeluarName,
            'lokasi_keluar' => json_encode($validated['lokasi_keluar']),
            'ket_keluar' => $validated['ket_keluar'] ?? null,
        ]);

        // Redirect back to the presensi index with a success message
        return redirect()->route('presensi.index')->with('success', 'Presensi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $presensi = Presensi::findOrFail($id);

        // Delete files if they exist
        if ($presensi->foto_masuk) {
            Storage::delete('public/fotos/' . $presensi->foto_masuk);
        }
        if ($presensi->foto_keluar) {
            Storage::delete('public/fotos/' . $presensi->foto_keluar);
        }

        // Delete the presensi record
        $presensi->delete();

        // Redirect back with a success message
        return redirect()->route('presensi.index')->with('success', 'Presensi berhasil dihapus.');
    }
}
