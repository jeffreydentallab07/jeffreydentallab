<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewCaseOrder;
use App\Models\Patient;
use App\Models\Dentist;

class NewCaseOrderController extends Controller
{
 public function index()
{
    $clinicId = auth()->user()->clinic_id;

    
    $caseOrders = NewCaseOrder::where('clinic_id', $clinicId)
                              ->with(['patient.dentist'])
                              ->get();

    $patients = Patient::all();
    $dentists = Dentist::all();

    
    $clinic = \App\Models\Clinic::where('clinic_id', $clinicId)->first();
   

    $caseTypes = ['Denture plastic', 'Jacket crowns/Porcelain', 'Retainers', 'Valpast Flexible'];
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

    // Store a new case order
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patient,patient_id',
            'case_type' => 'required|string',
            'case_status' => 'required|string',
        ]);

        NewCaseOrder::create([
            'patient_id' => $request->patient_id,
            'case_type' => $request->case_type,
            'notes' => $request->notes,
            'case_status' => $request->case_status,
            'clinic_id' => auth()->user()->clinic_id,
        ]);

        return redirect()->route('clinic.new-case-orders.index')
                         ->with('success', 'Case order added successfully.');
    }

    // Update a case order
    public function update(Request $request, $id)
    {
        $request->validate([
            'patient_id' => 'required|exists:patient,patient_id',
            'case_type' => 'required|string',
            'case_status' => 'required|string',
        ]);

        $order = NewCaseOrder::findOrFail($id);

        $order->update([
            'patient_id'  => $request->patient_id,
            'case_type'   => $request->case_type,
            'notes'       => $request->notes,
            'case_status' => $request->case_status,
        ]);

        return redirect()->route('clinic.new-case-orders.index')
                         ->with('success', 'Case order updated.');
    }

    // Delete a case order
    public function destroy($id)
    {
        NewCaseOrder::findOrFail($id)->delete();

        return redirect()->route('clinic.new-case-orders.index')
                         ->with('success', 'Case order deleted.');
    }
    public function edit($id)
{
    $caseOrder = NewCaseOrder::with(['patient', 'patient.dentist'])->findOrFail($id);

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

}
