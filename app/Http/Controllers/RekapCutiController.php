<?php

namespace App\Http\Controllers;

use App\Models\Cuti;  // Assuming you have a Presensi model for attendance data
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;


class RekapCutiController extends Controller
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
    
        // Build Cuti query
        if ($user->role == 'user') {
            $cutiQuery = Cuti::where('user_id', $user->id);
        } else {
            $cutiQuery = Cuti::query();
    
            if (!empty($selectedUserId)) {
                $cutiQuery->where('user_id', $selectedUserId);
            }
        }
    
        // Apply month filter
        if ($month) {
            $cutiQuery->whereMonth('created_at', $month);
        }
    
        // Apply date range filter
        if ($startDate && $endDate) {
            $cutiQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
    
        // Fetch data
        $cuti = $cutiQuery->orderBy('created_at', 'desc')->get();
    
        // Count total Cuti
        $totalCuti = $cuti->count();
    
        // Optionally count per status
        $cutiApproved = $cuti->where('status_admin', 'disetujui')->where('status_superadmin', 'disetujui')->count();
        $cutiRejected = $cuti->where('status_admin', 'ditolak')->where('status_superadmin', 'ditolak')->count();
        $cutiPending = $cuti->where('status_admin', 'belum divalidasi')->where('status_superadmin', 'belum divalidasi')->count();
        $countIzin = $cuti->where('jenis', 'Izin')->count();
        $countCuti = $cuti->where('jenis', 'Cuti')->count();
    
        // Fetch users (for dropdown)
        $users = DB::table('users')
            ->select('*')
            ->where('role', '=', 'user')
            ->whereNull('deleted_at')
            ->orderBy('id')
            ->get();
    
        // Return view
        return view('rekap.rekap_cuti_view', compact(
            'cuti', 'month', 'startDate', 'endDate', 'users', 'selectedUserId',
            'totalCuti', 'cutiApproved', 'cutiRejected', 'cutiPending','countIzin','countCuti'
        ));
    }
    
    public function exportPDF(Request $request)
    {
       // Get the logged-in user
       $user = Auth::user();
    
       // Get filters from request
       $month = $request->get('month', Carbon::now()->month);
       $startDate = $request->get('start_date');
       $endDate = $request->get('end_date');
       $selectedUserId = $request->get('user_id');

       
        if ($user->role == 'user') {
            $cutiQuery = Cuti::where('user_id', $user->id);
        } else {
            $cutiQuery = Cuti::query();
    
            if (!empty($selectedUserId)) {
                $cutiQuery->where('user_id', $selectedUserId);
            }
        }
    
        // Apply month filter
        if ($month) {
            $cutiQuery->whereMonth('created_at', $month);
        }
    
        // Apply date range filter
        if ($startDate && $endDate) {
            $cutiQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
    
        // Fetch data
        $cuti = $cutiQuery->orderBy('created_at', 'desc')->get();
    
        // Count total Cuti
        $totalCuti = $cuti->count();
    
        // Optionally count per status
        $cutiApproved = $cuti->where('status_admin', 'disetujui')->where('status_superadmin', 'disetujui')->count();
        $cutiRejected = $cuti->where('status_admin', 'ditolak')->where('status_superadmin', 'ditolak')->count();
        $cutiPending = $cuti->where('status_admin', 'belum divalidasi')->where('status_superadmin', 'belum divalidasi')->count();
    
        // Fetch users (for dropdown)
        $users = DB::table('users')
            ->select('*')
            ->where('role', '=', 'user')
            ->whereNull('deleted_at')
            ->orderBy('id')
            ->get();
    
        // Load view
        $html = view('rekap.rekap_cuti_pdf', compact(
            'cuti', 'month', 'startDate', 'endDate', 'totalCuti', 'cutiApproved', 'cutiRejected', 'cutiPending'
        ))->render();
    
        // Create PDF
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);
        return $mpdf->Output('rekap_cuti_presensi.pdf', 'D'); // Force download
    }

}
