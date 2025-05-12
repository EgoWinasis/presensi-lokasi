<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Lokasi;
use App\Models\Holiday;
use App\Models\JadwalKaryawan;
use App\Models\JamAbsen;
use Carbon\Carbon;

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
        
        // Get future schedules for the logged-in user
        $libur = JadwalKaryawan::where('user_id', $userId)
        ->where('tgl', '>=', $today)
        ->get();

        // Check if today is a scheduled date
        $isLibur = $libur->contains('tgl', $today);

        // Get the attendance time settings from the 'jam_absen' table
        $jamAbsen = JamAbsen::first(); // Assuming there is one record for attendance times

        // Get today's attendance record for the authenticated user
        $presensiToday = Presensi::where('user_id', $userId)
                                 ->whereDate('tgl', $today) // Filter by today's date
                                 ->first(); // Get the first record




        // Return the data to the view
        return view('presensi.presensi', compact('lokasi', 'holidays', 'jamAbsen', 'presensiToday', 'isHoliday', 'isLibur'));
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
            'action_type' => 'required|in:masuk,pulang',
            'captured_image' => 'required|string', // Base64 encoded image
            'user_time' => ['required', 'regex:/^(?:[01]\d|2[0-3]):(?:[0-5]\d):(?:[0-5]\d)$/'],
            'user_id' => 'required|exists:users,id',
            'lokasi' => 'required|string'
        ]);

        // Handle the image upload (Base64 to file)
        $imageData = $validated['captured_image'];
        $image = str_replace('data:image/png;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);
        $imageName = 'presensi_' . uniqid() . '.png';
        Storage::disk('public')->put('presensi_images/' . $imageName, base64_decode($image));

        $lokasiString = $validated['lokasi'];

        $jamAbsen = JamAbsen::first(); // Assuming this fetches the record with your threshold times

        if ($jamAbsen) {
            // Define threshold times based on the fetched values from the JamAbsen table
            $thresholdMasuk = Carbon::createFromFormat('H:i:s', $jamAbsen->jam_masuk); // Clock-in time
            $thresholdPulang = Carbon::createFromFormat('H:i:s', $jamAbsen->jam_keluar); // Clock-out time
        } else {
            // Set default times if no record is found (fallback behavior)
            $thresholdMasuk = Carbon::createFromFormat('H:i:s', '08:00:00'); // Default 08:00 AM
            $thresholdPulang = Carbon::createFromFormat('H:i:s', '17:00:00'); // Default 05:00 PM
        }

        $dateOnly = Carbon::parse($validated['user_time'])->format('Y-m-d');

        // Check if the user already has a 'masuk' record for today
        $presensiToday = Presensi::where('user_id', $validated['user_id'])
                                 ->where('tgl', $dateOnly)
                                 ->first();

        // Create or update the presensi record based on action type
        if (!$presensiToday) {
            // If no 'masuk' record exists, create a new one
            $presensiData = [
                'user_id' => $validated['user_id'],
                'tgl' => $dateOnly,
                'jam_masuk' => null,
                'foto_masuk' => null,
                'lokasi_masuk' => null,
                'ket_masuk' => null,
                'jam_keluar' => null,
                'foto_keluar' => null,
                'lokasi_keluar' => null,
                'ket_keluar' => null,
            ];
        } else {
            // If there's an existing 'masuk' record, just update it based on action type
            $presensiData = $presensiToday->toArray();
        }

        if ($validated['action_type'] === 'masuk') {
            $presensiData['jam_masuk'] = $validated['user_time'];
            $presensiData['foto_masuk'] = $imageName;
            $presensiData['lokasi_masuk'] = $lokasiString;
            $toleransi = Carbon::parse($thresholdMasuk)->addMinutes(30);

            if (Carbon::parse($presensiData['jam_masuk'])->greaterThan($toleransi)) {
                $presensiData['ket_masuk'] = 'Telat';
            } else {
                $presensiData['ket_masuk'] = 'Tepat Waktu';
            }
            // If no existing record, insert new
            if (!$presensiToday) {
                Presensi::create($presensiData);
            } else {
                // If record exists, just update
                $presensiToday->update($presensiData);
            }
        } elseif ($validated['action_type'] === 'pulang' && $presensiToday) {
            // Update the 'pulang' time
            $presensiData['jam_keluar'] = $validated['user_time'];
            $presensiData['foto_keluar'] = $imageName;
            $presensiData['lokasi_keluar'] = $lokasiString;
            if (Carbon::parse($presensiData['jam_keluar'])->lessThan($thresholdPulang)) {
                $presensiData['ket_keluar'] = 'Pulang Cepat';
            } else {
                $presensiData['ket_keluar'] = 'Tepat Waktu';
            }

            // Update the existing 'pulang' record
            $presensiToday->update($presensiData);
        }

        // Return a response
        return response()->json(['success' => true, 'message' => 'Presensi berhasil disimpan']);
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
