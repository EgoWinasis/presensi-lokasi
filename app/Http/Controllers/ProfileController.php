<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


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
            ->select('id','nik', 'name', 'email', 'role', 'foto')
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
            // foto
            'foto' => 'image|file|mimes:png,jpg,jpeg',
            // ttd
        ]);

        
        if ($request->file('foto')) {
            if ($user['foto'] != 'user.png') {
                Storage::delete('public/images/profile/' . $user['foto']);
            }
            $file = $request->file('foto');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $request->file('foto')->storeAs('public/images/profile/', $fileName);
            $validatedData['foto'] = $fileName;
        } else {
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
