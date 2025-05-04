<?php

namespace App\Http\Controllers;

use App\Models\Presensi;  // Assuming you have a Presensi model for attendance data
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class RekapController extends Controller
{
    public function index(Request $request)
{
    // Get the logged-in user
    $user = Auth::user();

    // Get filters from request
    $month = $request->get('month', Carbon::now()->month);
    $startDate = $request->get('start_date');
    $endDate = $request->get('end_date');
    $selectedUserId = $request->get('user_id');

    // Build presensi query based on role
    if ($user->role == 'user') {
        $presensiQuery = Presensi::where('user_id', $user->id);
    } else {
        $presensiQuery = Presensi::query();

        // Apply user filter if selected (not "Semua Karyawan")
        if (!empty($selectedUserId)) {
            $presensiQuery->where('user_id', $selectedUserId);
        }
    }

    // Apply month filter
    if ($month) {
        $presensiQuery->whereMonth('created_at', $month);
    }

    // Apply date range filter
    if ($startDate && $endDate) {
        $presensiQuery->whereBetween('created_at', [$startDate, $endDate]);
    }

    // Fetch data
    $presensi = $presensiQuery->orderBy('created_at', 'desc')->get();

    // Fetch list of users (for dropdown)
    $users = DB::table('users')
        ->select('*')
        ->where('role', '=', 'user')
        ->whereNull('deleted_at')
        ->orderBy('id')
        ->get();

    // Return view with all variables
    return view('rekap.rekap_view', compact(
        'presensi', 'month', 'startDate', 'endDate', 'users', 'selectedUserId'
    ));
}


}
