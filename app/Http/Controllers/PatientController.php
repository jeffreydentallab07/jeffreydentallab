<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Dentist;
use App\Models\Clinic;

class PatientController extends Controller
{
    public function index()
    {
        $clinicId = auth()->user()->clinic_id;

        $patients = \DB::table('patient')
            ->join('tbl_dentist', 'patient.dentist_id', '=', 'tbl_dentist.dentist_id')
            ->where('tbl_dentist.clinic_id', $clinicId)
            ->select('patient.*', 'tbl_dentist.name as dentist_name')
            ->get();

        $dentists = \DB::table('tbl_dentist')
            ->where('clinic_id', $clinicId)
            ->get();

        $clinic = Clinic::where('clinic_id', $clinicId)->first();

        return view('clinic.patients.index', compact('patients', 'dentists', 'clinic'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_name'   => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:255',
            'email'          => 'nullable|email|max:255',
            'dentist_id'     => 'nullable|exists:tbl_dentist,dentist_id',
        ]);

        Patient::create([
            'patient_name'   => $request->patient_name,
            'contact_number' => $request->contact_number,
            'address'        => $request->address,
            'email'          => $request->email,
            'dentist_id'     => $request->dentist_id,
        ]);

        return redirect()->route('clinic.patients.index')
                         ->with('success', 'Patient added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'patient_name'   => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:255',
            'email'          => 'nullable|email|max:255',
            'dentist_id'     => 'nullable|exists:tbl_dentist,dentist_id',
        ]);

        $patient = Patient::findOrFail($id);

 
        $dentist = Dentist::find($patient->dentist_id);
        if (!$dentist || $dentist->clinic_id !== auth()->user()->clinic_id) {
            abort(403, 'Unauthorized action.');
        }

        $patient->update([
            'patient_name'   => $request->patient_name,
            'contact_number' => $request->contact_number,
            'address'        => $request->address,
            'email'          => $request->email,
            'dentist_id'     => $request->dentist_id,
        ]);

        return redirect()->route('clinic.patients.index')
                         ->with('success', 'Patient updated successfully.');
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);

   
        $dentist = Dentist::find($patient->dentist_id);
        if (!$dentist || $dentist->clinic_id !== auth()->user()->clinic_id) {
            abort(403, 'Unauthorized action.');
        }

        $patient->delete();

        return redirect()->route('clinic.patients.index')
                         ->with('success', 'Patient deleted successfully.');
    }
}
