<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lokasi;
use App\Models\JamAbsen;
use App\Models\Holiday;

class PengaturanController extends Controller
{
    public function pengaturanLokasi()
    {
        // Ambil data lokasi dari database atau konfigurasi
        $lokasi = Lokasi::first(); // Misalnya model Lokasi

        return view('pengaturan.lokasi', compact('lokasi'));
    }

    public function updateLokasi(Request $request)
    {

        // Validasi input
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Update data lokasi di database
        $lokasi = Lokasi::first(); // Ambil data lokasi yang ada
        if (!$lokasi) {
            // Jika tidak ada lokasi, buat data baru
            $lokasi = new Lokasi();
        }

        // Update latitude and longitude
        $lokasi->latitude = $request->latitude;
        $lokasi->longitude = $request->longitude;

        // Simpan data lokasi (baik yang baru atau yang sudah ada)
        $lokasi->save();

        // Kembali ke halaman pengaturan dengan pesan sukses
        return redirect()->route('pengaturan.lokasi')->with('success', 'Lokasi berhasil diperbarui');
    }

    public function pengaturanJamAbsen()
    {
        // Get the current working hours settings from the database (assuming JamAbsen is the model)
        $jamAbsen = JamAbsen::first();  // Assuming only one setting record, adjust as needed

        return view('pengaturan.jam_absen', compact('jamAbsen'));
    }

    // Update working hours settings
    public function updateJamAbsen(Request $request)
    {
        $request->validate([
            'jam_masuk' => 'required|date_format:H:i', // Time format for clock-in
            'jam_keluar' => 'required|date_format:H:i', // Time format for clock-out
        ]);

        // Fetch the current setting or create a new one if it doesn't exist
        $jamAbsen = JamAbsen::first();

        if (!$jamAbsen) {
            // If no record exists, create a new one
            $jamAbsen = new JamAbsen();
        }

        // Update the settings
        $jamAbsen->jam_masuk = $request->jam_masuk;
        $jamAbsen->jam_keluar = $request->jam_keluar;

        // Save the new settings
        $jamAbsen->save();

        return redirect()->route('pengaturan.jam-absen')->with('success', 'Jam Absen berhasil diperbarui');
    }


    public function pengaturanHariLibur()
    {
        $holidays = Holiday::all();
        return view('pengaturan.hari_libur', compact('holidays'));
    }

    // Handle the creation or update of a holiday
    public function updateHariLibur(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date|unique:holidays,date,' . $request->id,
        ]);

        // If an ID is passed, update an existing holiday
        if ($request->id) {
            $holiday = Holiday::findOrFail($request->id);
            $holiday->update([
                'name' => $request->name,
                'date' => $request->date,
            ]);
        } else {
            // Otherwise, create a new holiday
            Holiday::create([
                'name' => $request->name,
                'date' => $request->date,
            ]);
        }

        return redirect()->route('pengaturan.hari-libur')->with('success', 'Hari libur berhasil disimpan!');
    }

    // Handle the deletion of a holiday
    public function deleteHariLibur($id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();

        return response()->json(['success' => 'Hari libur berhasil dihapus!']);
    }
}
