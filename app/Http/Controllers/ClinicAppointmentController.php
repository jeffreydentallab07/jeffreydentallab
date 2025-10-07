<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class ClinicAppointmentController extends Controller
{
    public function index()
    {
       
        $clinic_id = Auth::guard('clinic')->id();

        
        $appointments = Appointment::with(['technician', 'caseOrder.patient', 'delivery'])
            ->join('tbl_case_order', 'tbl_case_order.co_id', '=', 'tbl_appointment.co_id')
            ->where('tbl_case_order.clinic_id', $clinic_id)
            ->orderBy('tbl_appointment.schedule_datetime', 'desc')
            ->select('tbl_appointment.*')
            ->get();

        return view('clinic.appointments.index', compact('appointments'));
    }
}
