<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Technician;
use App\Models\CaseOrder;
use App\Models\Rider;
use App\Models\Material;

class AdminSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        if (!$query) {
            return response()->json([]);
        }

        $results = [];

        // Search from Case Orders
        $caseOrders = CaseOrder::where('co_id', 'like', "%$query%")
            ->orWhereHas('clinic', fn($q) => $q->where('clinic_name', 'like', "%$query%"))
            ->take(3)->get(['co_id', 'clinic_id']);
        foreach ($caseOrders as $co) {
            $results[] = [
                'type' => 'Case Order',
                'label' => 'CASE-' . str_pad($co->co_id, 5, '0', STR_PAD_LEFT),
                'url' => route('case-orders.index') . '?search=' . $co->co_id,
            ];
        }

        // Appointments
        $appointments = Appointment::where('patient_name', 'like', "%$query%")->take(3)->get();
        foreach ($appointments as $a) {
            $results[] = [
                'type' => 'Appointment',
                'label' => $a->patient_name,
                'url' => route('appointments.index') . '?search=' . urlencode($a->patient_name),
            ];
        }

        // Clinics
        $clinics = Clinic::where('clinic_name', 'like', "%$query%")->take(3)->get();
        foreach ($clinics as $c) {
            $results[] = [
                'type' => 'Clinic',
                'label' => $c->clinic_name,
                'url' => route('clinics.index') . '?search=' . urlencode($c->clinic_name),
            ];
        }

        // Technicians
        $techs = Technician::where('name', 'like', "%$query%")->take(3)->get();
        foreach ($techs as $t) {
            $results[] = [
                'type' => 'Technician',
                'label' => $t->name,
                'url' => route('technicians.index') . '?search=' . urlencode($t->name),
            ];
        }

        // Riders
        $riders = Rider::where('name', 'like', "%$query%")->take(3)->get();
        foreach ($riders as $r) {
            $results[] = [
                'type' => 'Rider',
                'label' => $r->name,
                'url' => route('riders.index') . '?search=' . urlencode($r->name),
            ];
        }

        // Materials
        $materials = Material::where('material_name', 'like', "%$query%")->take(3)->get();
        foreach ($materials as $m) {
            $results[] = [
                'type' => 'Material',
                'label' => $m->material_name,
                'url' => url('materials') . '?search=' . urlencode($m->material_name),
            ];
        }

        return response()->json($results);
    }
}
