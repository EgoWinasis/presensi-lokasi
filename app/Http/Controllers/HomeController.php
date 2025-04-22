<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use App\Models\Presensi; // Assuming you have a Presensi model for attendance data

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

        // Default to the current month
        $month = $request->get('month', Carbon::now()->month);

        // Fetch total users and admins
        $userCount = User::where('role', 'user')->count();
        $adminCount = User::where('role', 'admin')->count();

        // Fetch leave applications and statuses
        $pendingCount = Presensi::where('status_admin', 'belum divalidasi')->count();
        $adminApprovedCount = Presensi::where('status_admin', 'disetujui')->count();
        $superuserApprovedCount = Presensi::where('status_superadmin', 'disetujui')->count();
        $rejectedCount = Presensi::where('status_admin', 'ditolak')->count();

        return view('home', compact(
            'userCount',
            'adminCount',
            'pendingCount',
            'adminApprovedCount',
            'superuserApprovedCount',
            'rejectedCount'
        ));
    }

}
