<?php

namespace App\Http\Controllers;

use App\Models\CaseOrder;
use App\Models\Appointment;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CaseOrderController extends Controller
{
    public function index()
{
   $caseOrders = CaseOrder::with(['clinic','patient.dentist'])
                        ->orderBy('created_at', 'desc')
                        ->get();

    $newCasesCount = CaseOrder::where('status', 'initial')->count();

    return view('admin.case-orders.index', compact('caseOrders', 'newCasesCount'));
}

 public function approve($id)
{
    $caseOrder = CaseOrder::findOrFail($id);

   
    if (\App\Models\Appointment::where('co_id', $id)->exists()) {
        return redirect()->back()->with('error', 'Appointment already exists for this case order.');
    }

    
    \App\Models\Appointment::create([
        'co_id'             => $caseOrder->co_id,
        'technician_id'     => null,
        'schedule_datetime' => now(),
        'material_id'       => null,
        'priority_level'    => 'Normal',
        'purpose'           => 'initial',
        'work_status'       => 'pending',
    ]);

    
    $caseOrder->status = 'approved';
    $caseOrder->save();

    return redirect()->route('appointments.index')
                     ->with('success', 'Case order approved and moved to appointments.');
}

public function patient()
{
    return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
}

public function dentist()
{
   
    return $this->hasOneThrough(
        Dentist::class,    
        Patient::class,     
        'patient_id',        
        'dentist_id',         
        'patient_id',        
        'dentist_id'          
    );
}



}
