<?php

namespace App\Http\Controllers;

use App\Models\Presensi;  // Assuming you have a Presensi model for attendance data
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        // Get the logged-in user
        $user = Auth::user();

        // Default to the current month
        $month = $request->get('month', Carbon::now()->month);

        // Get the start and end date from the request, if they exist
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // If the role is "user", only fetch data for the logged-in user
        if ($user->role == 'user') {
            $presensiQuery = Presensi::where('user_id', $user->id);
        } else {
            // If the role is "admin" or "superadmin", fetch all presensi data
            $presensiQuery = Presensi::query();
        }

        // Apply month filter if a month is selected
        if ($month) {
            $presensiQuery->whereMonth('created_at', $month);
        }

        // Apply date range filter if start_date and end_date are provided
        if ($startDate && $endDate) {
            $presensiQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Get the presensi records
        $presensi = $presensiQuery->get();

        // Pass the data to the view
        return view('rekap.rekap_view', compact('presensi', 'month', 'startDate', 'endDate'));
    }

}
