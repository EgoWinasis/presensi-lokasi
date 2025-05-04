<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profile = DB::table('users')
            ->select('id', 'nik', 'name', 'email', 'role', 'foto')
            ->where('id', '=', Auth::user()->id)
            ->get();
        return view('profile.profile_view')->with(compact('profile'));
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
        $profile = DB::table('users')
            ->where('id', '=', $id)
            ->get();
        return view('profile.profile_edit_view')->with(compact('profile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        $validatedData = $request->validate([
            'nik' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'jabatan' => ['required', 'string', 'max:255'],
            'hp' => ['required', 'string', 'max:16'],
            'status_karyawan' => ['required', 'string', 'max:255'],
            // foto
            'foto' => 'image|file|mimes:png,jpg,jpeg',
            // ttd
        ]);


        if ($request->file('foto')) {
            // Delete the old file if it's not the default image (user.png)
            if ($user['foto'] != 'user.png') {
                // Absolute path to the old file in public_html/storage/images/profile
                $oldFilePath = base_path('../public_html/storage/images/profile/' . $user['foto']);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);  // Delete old image
                }
            }

            // Get the uploaded file
            $file = $request->file('foto');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension(); // Generate a unique file name

            // Absolute path to public_html/storage/images/profile folder
            $destinationPath = base_path('../public_html/storage/images/profile');  // Adjust path to your project structure

            // Move the file to the specified folder
            $file->move($destinationPath, $fileName);

            // Save the new file name in the database
            $validatedData['foto'] = $fileName;
        } else {
            // If no file is uploaded, keep the existing image
            $validatedData['foto'] = $user['foto'];
        }





        User::where('id', $id)->update($validatedData);
        return redirect()->route('profile.index')
        ->with('success', 'Berhasil Update Profile');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
