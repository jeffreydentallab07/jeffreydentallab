<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function appointments()
    {
        $appointments = DB::table('appointments')
            ->join('case_orders', 'appointments.co_id', '=', 'case_orders.co_id')
            ->join('materials', 'appointments.material_id', '=', 'materials.material_id')
            ->join('departments', 'appointments.dept_id', '=', 'departments.dept_id')
            ->join('technicians', 'appointments.tech_id', '=', 'technicians.tech_id')
            ->select(
                'appointments.*',
                'case_orders.co_id',
                'materials.name as material',
                'departments.name as department',
                'technicians.name as technician'
            )
            ->get();
        return view('appointments', compact('appointments'));
    }

    
}
