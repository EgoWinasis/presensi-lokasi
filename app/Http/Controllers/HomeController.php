<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use App\Models\Cuti; // Assuming you have a Presensi model for attendance data

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user->isActive == 0) {
            Auth::logout();
    
            return redirect()->route('login')->withErrors([
                'email' => 'Akun belum diaktivasi.',
            ]);
        }
           
        // Default to the current month
        $month = $request->get('month', Carbon::now()->month);

        // If the role is "admin" or "superadmin", fetch all presensi data
        $userCount = User::where('role', 'user')->count();
        $adminCount = User::where('role', 'admin')->count();
        if ($user->role == 'user') {
            $pengajuanCuti = Cuti::where('user_id', $user->id)->count();
            $pendingCount = Cuti::where('status_admin', 'belum divalidasi')->where('user_id', $user->id)->count();
            $pendingCountSuper = Cuti::where('status_superadmin', 'belum divalidasi')->where('user_id', $user->id)->count();
            $adminApprovedCount = Cuti::where('status_admin', 'disetujui')->where('user_id', $user->id)->count();
            $superuserApprovedCount = Cuti::where('status_superadmin', 'disetujui')->where('user_id', $user->id)->count();
            $rejectedCount = Cuti::where('status_admin', 'ditolak')->where('user_id', $user->id)->count();
            $rejectedCountSuper = Cuti::where('status_superadmin', 'ditolak')->where('user_id', $user->id)->count();
        } else {



            // Fetch leave applications and statuses
            $pendingCount = Cuti::where('status_admin', 'belum divalidasi')->count();
            $pendingCountSuper = Cuti::where('status_superadmin', 'belum divalidasi')->count();
            $adminApprovedCount = Cuti::where('status_admin', 'disetujui')->count();
            $superuserApprovedCount = Cuti::where('status_superadmin', 'disetujui')->count();
            $rejectedCount = Cuti::where('status_admin', 'ditolak')->count();
            $rejectedCountSuper = Cuti::where('status_superadmin', 'ditolak')->count();
            $pengajuanCuti = 0;
        }
        // Fetch total users and admins


        return view('home', compact(
            'userCount',
            'adminCount',
            'pendingCount',
            'adminApprovedCount',
            'superuserApprovedCount',
            'rejectedCount',
            'rejectedCountSuper',
            'pendingCountSuper',
            'rejectedCountSuper',
            'pengajuanCuti',
        ));
    }

}
