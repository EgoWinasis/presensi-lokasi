<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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
            if (Auth::user()->role == 'user' || Auth::user()->role == 'superadmin') {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Akses Ditolak ');
            }
        $users = DB::table('users')
            ->select('id', 'name', 'email', 'role', 'foto', 'isActive')
            ->where('role', '=', 'user')
            ->whereNull('deleted_at')
            ->orderBy('id')
            ->get();

        return view('user.user_view')->with(compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'required' => 'Kolom :attribute harus diisi.',
            'max' => 'Kolom :attribute tidak boleh lebih dari :max karakter.',
            'min' => 'Kolom :attribute tidak boleh kurang dari :min karakter.',
            'confirmed' => 'Kolom :attribute Kata Sandi tidak sama.',
            'unique' => 'Kolom :attribute email sudah digunakan',
        ]);

        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);
        return redirect()->route('user.index')->with('success', 'Akun Berhasil Dibuat');
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
       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id); // Find the record by ID

        if (isset($request->role)) {
            // Update the role
            $user->role = $request->role;
        } else {
            // Update the isActive field to 1
            $user->isActive = 1;
        }

        // Save the changes to the database
        $user->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if ($user) {
            $user->isActive = 0;
            $user->save();
            $user->delete();
        }
    }

    public function aktivasi()
{
    $users = User::select('id', 'name', 'email', 'role', 'foto', 'isActive')
    ->where('role', '=', 'user')
    ->where('isActive', '=', 0)
    ->whereNull('deleted_at')
    ->orderBy('id')
    ->get();
    return view('user.aktivasi_view', compact('users'));
}

public function aktivasiUser($id)
{
    $user = User::findOrFail($id);
    $user->isActive = 1;
    $user->save();

    return response()->json(['message' => 'User Berhasil diaktivasi']);
}

public function aktivasiSemua()
{
    \App\Models\User::where('role', 'user')
        ->where('isActive', 0)
        ->whereNull('deleted_at')
        ->update(['isActive' => 1]);

    return response()->json(['message' => 'Semua pengguna berhasil diaktivasi!']);
}


public function kelolaAdmin()
{
    $users = \App\Models\User::where('role', 'admin')
                ->whereNull('deleted_at')
                ->get();

    return view('user.kelola_admin_view', compact('users'));
}

public function createAdmin()
{
    return view('user.create_admin');
}

public function storeAdmin(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
    ]);

    \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => 'admin',
        'isActive' => 1, // langsung aktif
    ]);

    return redirect()->route('user.kelolaAdmin')->with('success', 'Admin berhasil ditambahkan.');
}

}
