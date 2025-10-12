<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewCaseOrder;
use App\Models\Patient;
use App\Models\Dentist;
use App\Models\Clinic;

class NewCaseOrderController extends Controller
{
   public function index()
{
    $clinicId = auth()->user()->clinic_id;

    $caseOrders = NewCaseOrder::whereHas('patient.dentist', function ($query) use ($clinicId) {
        $query->where('clinic_id', $clinicId);
    })
    ->with(['patient.dentist'])
    ->orderBy('created_at', 'desc')
    ->get();

    $patients = \App\Models\Patient::whereHas('dentist', function ($query) use ($clinicId) {
        $query->where('clinic_id', $clinicId);
    })->get();

    $dentists = \App\Models\Dentist::where('clinic_id', $clinicId)->get();

    $clinic = \App\Models\Clinic::where('clinic_id', $clinicId)->first();

    $caseTypes = ['Denture plastic', 'Jacket crowns/Porcelain', 'Retainers', 'Valplast Flexible'];
    $caseStatuses = ['initial', 'adjustment', 'repair'];

    return view('clinic.new-case-orders.index', compact(
        'caseOrders',
        'patients',
        'dentists',
        'clinic',
        'caseTypes',
        'caseStatuses'
    ));
}


    public function store(Request $request)
    {
        $clinicId = auth()->user()->clinic_id;

        $request->validate([
            'patient_id' => 'required|exists:patient,patient_id',
            'case_type'  => 'required|string',
            'case_status' => 'required|string',
        ]);

        NewCaseOrder::create([
            'patient_id'  => $request->patient_id,
            'case_type'   => $request->case_type,
            'notes'       => $request->notes,
            'case_status' => $request->case_status,
            'clinic_id'   => $clinicId, 
        ]);

        return redirect()->route('clinic.new-case-orders.index')
                         ->with('success', 'Case order added successfully.');
    }

    public function edit($id)
    {
        $clinicId = auth()->user()->clinic_id;

        $caseOrder = NewCaseOrder::with(['patient', 'patient.dentist'])
            ->where('clinic_id', $clinicId)
            ->findOrFail($id); 

        $caseTypes = ['Denture plastic', 'Jacket crowns/Porcelain', 'Retainers', 'Valplast Flexible'];
        $caseStatuses = ['initial', 'adjustment', 'repair'];

        return view('clinic.new-case-orders.edit', [
            'caseOrder' => [
                'id' => $caseOrder->id,
                'patient_id' => $caseOrder->patient_id,
                'patient_name' => $caseOrder->patient->name ?? '',
                'dentist_id' => $caseOrder->patient->dentist->id ?? '',
                'dentist_name' => $caseOrder->patient->dentist->name ?? '',
                'case_type' => $caseOrder->case_type,
                'case_status' => $caseOrder->case_status,
                'notes' => $caseOrder->notes,
            ],
            'caseTypes' => $caseTypes,
            'caseStatuses' => $caseStatuses,
        ]);
    }

    public function update(Request $request, $id)
    {
        $clinicId = auth()->user()->clinic_id;

        $order = NewCaseOrder::where('clinic_id', $clinicId)->findOrFail($id); 

        $request->validate([
            'patient_id' => 'required|exists:patient,patient_id',
            'case_type'  => 'required|string',
            'case_status' => 'required|string',
        ]);

        $order->update([
            'patient_id'  => $request->patient_id,
            'case_type'   => $request->case_type,
            'notes'       => $request->notes,
            'case_status' => $request->case_status,
        ]);

        return redirect()->route('clinic.new-case-orders.index')
                         ->with('success', 'Case order updated successfully.');
    }

    public function destroy($id)
    {
        $clinicId = auth()->user()->clinic_id;

        $order = NewCaseOrder::where('clinic_id', $clinicId)->findOrFail($id); 
        $order->delete();

        return redirect()->route('clinic.new-case-orders.index')
                         ->with('success', 'Case order deleted successfully.');
    }
}
