<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CaseOrder;
use App\Models\Appointment;
use App\Models\Dentist;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;

class ClinicDashboardController extends Controller
{
  public function index()
{
    $clinic = auth()->user();

  
    $caseOrdersCount = DB::table('tbl_case_order')
        ->where('clinic_id', $clinic->clinic_id)
        ->count();


    $appointmentsCount = DB::table('tbl_appointment')
        ->join('tbl_case_order', 'tbl_appointment.co_id', '=', 'tbl_case_order.co_id')
        ->where('tbl_case_order.clinic_id', $clinic->clinic_id)
        ->count();

  $dentistsCount = DB::table('tbl_dentist')
    ->where('clinic_id', $clinic->clinic_id)
    ->count();

   
    $patientsCount = DB::table('patient')
    ->join('tbl_dentist', 'patient.dentist_id', '=', 'tbl_dentist.dentist_id')
    ->where('tbl_dentist.clinic_id', $clinic->clinic_id)
    ->count();



   $dentistReports = DB::table('tbl_dentist')
    ->select(
        'dentist_id',
        'name as dentist_name',
        DB::raw('(
            SELECT COUNT(*) 
            FROM tbl_case_order 
            WHERE tbl_case_order.user_id = tbl_dentist.dentist_id
        ) as total_cases'),
        DB::raw('(
            SELECT COUNT(*) 
            FROM tbl_case_order 
            WHERE tbl_case_order.user_id = tbl_dentist.dentist_id 
              AND tbl_case_order.status = "completed"
        ) as completed_cases'),
        DB::raw('(
            SELECT COUNT(*) 
            FROM tbl_case_order 
            WHERE tbl_case_order.user_id = tbl_dentist.dentist_id 
              AND tbl_case_order.status = "pending"
        ) as pending_cases')
    )
    ->where('clinic_id', $clinic->clinic_id)
    ->get();


$recentCases = DB::table('tbl_case_order')
    ->join('patient', 'tbl_case_order.patient_id', '=', 'patient.patient_id') 
    ->where('tbl_case_order.clinic_id', $clinic->clinic_id)
    ->select(
        'tbl_case_order.co_id',
        'tbl_case_order.status',
        'tbl_case_order.created_at',
        'patient.patient_name'
    )
    ->orderBy('tbl_case_order.created_at', 'desc')
    ->limit(5)
    ->get();

    return view('clinic.dashboard', [
        'caseOrdersCount' => $caseOrdersCount,
        'appointmentsCount' => $appointmentsCount,
        'dentistsCount' => $dentistsCount,
        'patientsCount' => $patientsCount,
        'dentistReports' => $dentistReports,
        'recentCases' => $recentCases,
    ]);
}


    
    public function liveCounts()
    {
        $clinicId = Auth::guard('clinic')->id();

        $caseOrdersCount = CaseOrder::where('clinic_id', $clinicId)->count();
        $appointmentsCount = Appointment::where('clinic_id', $clinicId)->count();
        $dentistsCount = Dentist::where('clinic_id', $clinicId)->count();
        $patientsCount = Patient::where('clinic_id', $clinicId)->count();

        $dentistReports = Dentist::where('clinic_id', $clinicId)
            ->leftJoin('tbl_caseorder', 'tbl_caseorder.dentist_id', '=', 'tbl_dentist.dentist_id')
            ->select(
                'tbl_dentist.dentist_id',
                'tbl_dentist.dentist_name',
                DB::raw('COUNT(tbl_caseorder.co_id) as total_cases'),
                DB::raw("SUM(CASE WHEN tbl_caseorder.status = 'completed' THEN 1 ELSE 0 END) as completed_cases"),
                DB::raw("SUM(CASE WHEN tbl_caseorder.status = 'pending' THEN 1 ELSE 0 END) as pending_cases")
            )
            ->groupBy('tbl_dentist.dentist_id', 'tbl_dentist.dentist_name')
            ->get();

        return response()->json([
            'caseOrdersCount' => $caseOrdersCount,
            'appointmentsCount' => $appointmentsCount,
            'dentistsCount' => $dentistsCount,
            'patientsCount' => $patientsCount,
            'dentistReports' => $dentistReports
        ]);
    }
}
