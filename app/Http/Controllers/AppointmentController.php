<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\CaseOrder;
use App\Models\User;
use App\Models\Delivery;
use App\Models\Billing;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
{
    $appointments = Appointment::with(['technician', 'material', 'delivery', 'billing', 'caseOrder'])
        ->orderByDesc('appointment_id')
        ->get();
    
    $technicians = User::where('role', 'technician')->get()->map(function ($tech) {
        $tech->is_busy = Appointment::where('technician_id', $tech->id)
            ->whereIn('work_status', ['pending', 'in progress'])
            ->exists();
        return $tech;
    });

    return view('admin.appointments.index', compact('appointments', 'technicians'));

    }

    public function approve($co_id)
    {
        $caseOrder = CaseOrder::with(['patient', 'dentist'])->find($co_id);
        if (!$caseOrder) {
            return redirect()->back()->with('error', 'Case order not found.');
        }

        if (Appointment::where('co_id', $co_id)->exists()) {
            return redirect()->back()->with('error', 'Appointment already exists for this case order.');
        }

        Appointment::create([
            'co_id' => $caseOrder->co_id,
            'technician_id' => null,
            'schedule_datetime' => now(),
            'material_id' => null,
            'priority_level' => 'Normal',
            'purpose' => 'initial',
            'work_status' => 'pending',
        ]);

        $caseOrder->status = 'approved';
        $caseOrder->save();

        return redirect()->route('admin.appointments.index')->with('success', 'Case order approved and moved to appointments.');
    }

    public function assignTechnician(Request $request, Appointment $appointment)
    {
        $request->validate([
            'technician_id' => 'required|exists:users,id',
        ]);

        
        if (Appointment::where('technician_id', $request->technician_id)
            ->whereIn('work_status', ['in progress', 'pending'])
            ->where('appointment_id', '!=', $appointment->appointment_id)
            ->exists()) {
            return redirect()->back()->with('error', 'Technician is currently busy with another appointment.');
        }

        $appointment->technician_id = $request->technician_id;
        $appointment->work_status = 'in progress'; 
        $appointment->save();

        return redirect()->route('appointments.index')->with('success', 'Technician assigned successfully!');
    }

    public function markAsFinished($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->work_status = 'finished';
        $appointment->save();

       
        if (!$appointment->delivery) {
            try {
                Delivery::create([
                    'appointment_id' => $appointment->appointment_id,
                    'rider_id' => null,
                    'delivery_status' => 'ready to deliver', 
                    'delivery_date' => now(), 
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
               
                if (str_contains($e->getMessage(), 'Unknown column \'delivery_date\'')) {
                    Delivery::create([
                        'appointment_id' => $appointment->appointment_id,
                        'rider_id' => null,
                        'delivery_status' => 'ready to deliver',
                       
                    ]);
                } else {
                    throw $e; 
                }
            }
        }

        return redirect()->back()->with('success', 'Appointment marked as finished.');
    }

    public function createBilling($id)
    {
        $appointment = Appointment::with('material')->findOrFail($id);

        if (Billing::where('appointment_id', $appointment->appointment_id)->exists()) {
            return redirect()->back()->with('error', 'Billing already exists for this appointment.');
        }

        
        if (!$appointment->material) {
            return redirect()->back()->with('error', 'Cannot create billing: Material not selected for this appointment.');
        }
        
        $totalAmount = $appointment->material->price ?? 0;

        Billing::create([
            'appointment_id' => $appointment->appointment_id,
            'total_amount' => $totalAmount,
        ]);

        return redirect()->route('billing.index')->with('success', 'Billing created successfully!');
    }
}