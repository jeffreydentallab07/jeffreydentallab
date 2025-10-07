<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Billing;

class ClinicBillingController extends Controller
{
    public function index(Request $request)
    {
        try {
            $clinic = Auth::guard('clinic')->user();
            
        
            $billings = Billing::with([
                'appointment.caseOrder.patient',
                'appointment.caseOrder.clinic',
                'appointment.caseOrder.dentist',
                'appointment.caseOrder.material', 
                'rider'
            ])
            ->whereHas('appointment.caseOrder', function($query) use ($clinic) {
                $query->where('clinic_id', $clinic->clinic_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

       
            if ($request->has('search')) {
                $search = $request->input('search');
                $billings = $billings->filter(function ($billing) use ($search) {
                    return str_contains($billing->appointment->caseOrder->patient->name ?? '', $search) ||
                           str_contains($billing->appointment->caseOrder->case_type ?? '', $search) ||
                           str_contains($billing->appointment_id ?? '', $search);
                });
            }

        } catch (\Exception $e) {
            Log::error('Billing query failed: ' . $e->getMessage(), [
                'clinic_id' => $clinic->clinic_id ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            
            $billings = collect();
        }

        return view('clinic.billing.index', compact('billings'));
    }
}
