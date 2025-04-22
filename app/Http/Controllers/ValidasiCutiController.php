<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidasiCutiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Check if the logged-in user has the 'user' role
        if (Auth::user()->role == 'user') {
            // Log out the user and redirect to the login page with an error message
            Auth::logout();
            return redirect()->route('login')->with('error', 'Akses Ditolak');
        }

        // Handle different user roles
        if (Auth::user()->isSuper()) {
            // If the user is a Superadmin, retrieve cuti records with 'disetujui' status
            $cuti = Cuti::with('user')
                        ->where('status_admin', 'disetujui')
                        ->where('status_superadmin', 'belum divalidasi')
                        ->get();
            return view('validasi.validasi_super_view', compact('cuti'));
        } elseif (Auth::user()->isAdmin()) {
            // If the user is an Admin, retrieve cuti records with 'belum divalidasi' status
            $cuti = Cuti::with('user')
                        ->where('status_admin', 'belum divalidasi')
                        ->get();
            return view('validasi.validasi_view', compact('cuti'));
        } else {
            // If the user doesn't have any recognized role, redirect to the login page
            return redirect()->route('login')->with('error', 'Akses Ditolak');
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function validateByAdmin(Request $request, $id)
    {
        // Find the Cuti request by ID
        $cuti = Cuti::findOrFail($id);

        // Update the status and catatan (note)
        $cuti->status_admin = $request->status;
        $cuti->catatan_admin = $request->catatan;

        // Optionally, handle other logic (such as notifying the user)

        // Save the changes
        $cuti->save();

        // Return a response to indicate success
        return response()->json(['success' => true, 'message' => 'Status cuti telah diperbarui']);
    }

    public function validateBySuperadmin(Request $request, $id)
    {
        // Find the Cuti request by ID
        $cuti = Cuti::findOrFail($id);

        // Update the status and catatan (note)
        $cuti->status_superadmin = $request->status;
        $cuti->catatan_superadmin = $request->catatan;

        // Optionally, handle other logic (such as notifying the user)

        // Save the changes
        $cuti->save();

        // Return a response to indicate success
        return response()->json(['success' => true, 'message' => 'Status cuti telah diperbarui']);
    }

}
