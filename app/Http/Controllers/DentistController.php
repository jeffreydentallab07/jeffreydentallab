<?php

namespace App\Http\Controllers;

use App\Models\Dentist;
use Illuminate\Http\Request;

class DentistController extends Controller
{
    public function index()
    {
        $clinicId = auth()->user()->clinic_id;

    
        $dentists = Dentist::where('clinic_id', $clinicId)
            ->orderBy('dentist_id', 'desc')
            ->paginate(10);

        return view('clinic.dentists.index', compact('dentists'));
    }

    public function create()
    {
        return view('clinic.dentists.create');
    }

    public function store(Request $request)
    {
        $clinicId = auth()->user()->clinic_id;

        $request->validate([
            'name'           => 'required|string|max:255',
            'address'        => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['name', 'address', 'contact_number', 'email']);
        $data['clinic_id'] = $clinicId; 

      
        if ($request->hasFile('photo')) {
            $fileName = time() . '_' . $request->file('photo')->getClientOriginalName();
            $path = $request->file('photo')->storeAs('dentists', $fileName, 'public');
            $data['photo'] = $path;
        }

        Dentist::create($data);

        return redirect()->route('clinic.dentists.index')->with('success', 'Dentist added successfully!');
    }

    public function edit(Dentist $dentist)
    {
        
        if ($dentist->clinic_id !== auth()->user()->clinic_id) {
            abort(403, 'Unauthorized access');
        }

        return view('clinic.dentists.edit', compact('dentist'));
    }

    public function update(Request $request, Dentist $dentist)
    {
        if ($dentist->clinic_id !== auth()->user()->clinic_id) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'name'           => 'required|string|max:255',
            'address'        => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['name', 'address', 'contact_number', 'email']);

       
        if ($request->hasFile('photo')) {
            $fileName = time() . '_' . $request->file('photo')->getClientOriginalName();
            $path = $request->file('photo')->storeAs('dentists', $fileName, 'public');
            $data['photo'] = $path;
        }

        $dentist->update($data);

        return redirect()->route('clinic.dentists.index')->with('success', 'Dentist updated successfully!');
    }

    public function destroy(Dentist $dentist)
    {
        if ($dentist->clinic_id !== auth()->user()->clinic_id) {
            abort(403, 'Unauthorized access');
        }

        if ($dentist->photo && file_exists(public_path('storage/' . $dentist->photo))) {
            unlink(public_path('storage/' . $dentist->photo));
        }

        $dentist->delete();

        return redirect()->route('clinic.dentists.index')->with('success', 'Dentist deleted successfully!');
    }
}
