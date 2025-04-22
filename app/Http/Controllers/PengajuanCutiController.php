<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengajuanCutiController extends Controller
{
    // Menambahkan middleware auth agar hanya user yang login yang bisa mengakses controller ini
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Ambil data cuti berdasarkan user yang sedang login
        $cuti = Cuti::where('user_id', auth()->id())->get();

        // Kembalikan data ke view
        return view('cuti.cuti_view', compact('cuti'));
    }
    public function create()
    {
        return view('cuti.create_view');
    }

    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'tgl_mulai_cuti' => 'required|date',
            'tgl_selesai_cuti' => 'required|date|after_or_equal:tgl_mulai_cuti',
            'jumlah_hari' => 'required|integer',
            'keterangan' => 'required|string',
        ]);

        // Membuat pengajuan cuti baru
        Cuti::create([
            'user_id' => auth()->id(), // Menyimpan user_id dari user yang sedang login
            'tgl_mulai_cuti' => $request->tgl_mulai_cuti,
            'tgl_selesai_cuti' => $request->tgl_selesai_cuti,
            'jumlah_hari' => $request->jumlah_hari,
            'keterangan' => $request->keterangan,
            'status_admin' => 'belum divalidasi',
            'status_superadmin' => 'belum divalidasi',
        ]);

        // Redirect ke halaman pengajuan cuti dengan pesan sukses
        return redirect()->route('cuti.index')->with('status', 'Pengajuan cuti berhasil!');
    }

    public function edit($id)
    {
        $cuti = Cuti::findOrFail($id);

        // Return view with the leave request data for editing
        return view('cuti.edit_view', compact('cuti'));
    }

    /**
     * Update the specified leave request in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cuti  $cuti
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cuti = Cuti::findOrFail($id);

        // Validation rules for the update
        $validator = Validator::make($request->all(), [
            'tgl_mulai_cuti' => 'required|date',
            'tgl_selesai_cuti' => 'required|date|after_or_equal:tgl_mulai_cuti',  // Make sure the end date is not before the start date
            'jumlah_hari' => 'required|integer',
            'keterangan' => 'required|string',
        ]);

        // If validation fails, redirect back with the errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        // Update the leave request data
        $cuti->update($request->all());

        // Redirect to the cuti index or a success page
        return redirect()->route('cuti.index')->with('success', 'updated successfully');
    }

    /**
     * Remove the specified leave request from storage.
     *
     * @param  \App\Models\Cuti  $cuti
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the Cuti record by ID
        $cuti = Cuti::find($id);

        // If not found, return an error
        if (!$cuti) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        // Delete the record
        $cuti->delete();

        // Return a success response
        return response()->json(['success' => 'Data deleted successfully']);
    }


    /**
     * Print the specified leave request.
     *
     * @param  \App\Models\Cuti  $cuti
     * @return \Illuminate\Http\Response
     */
    public function print($id)
    {
        $cuti = Cuti::findOrFail($id);

        // Generate a printable version, you can use DomPDF or a simple view for now
        // Return a view to display the printable leave request
        return view('cuti.print', compact('cuti'));
    }

    public function getItemDetails($id)
    {
        // Find the Cuti record by ID
        $cuti = Cuti::with('user')->findOrFail($id);  // Adjust to your model relationships

        // Return the Cuti record as a JSON response
        return response()->json($cuti);
    }
}
