<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Appointment; 
use App\Models\Material;
use App\Models\CaseOrder;
use App\Models\Clinic;
use App\Models\Dentist;

class AuthController extends Controller
{
  
    public function showLogin()
    {
        return view('auth.login'); 
    }

public function login(Request $request)
{
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    config(['session.cookie' => env('SESSION_COOKIE_WEB', config('session.cookie'))]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        $user = Auth::user();

        $redirect = match ($user->role) {
            'admin' => route('dashboard'),
            'technician' => route('technician.dashboard'),
            'rider' => route('rider.dashboard'),
            'clinic' => route('clinic.dashboard'),
            default => null,
        };

        if (!$redirect) {
            Auth::logout();
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Invalid role.']);
            }
            return redirect()->route('login')->withErrors(['role' => 'Invalid role.']);
        }

    
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect' => $redirect,
                'message' => 'Login successful!',
            ]);
        }

        return redirect()->to($redirect)->with('success', 'Login successful!');
    }

    if ($request->ajax()) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password.',
        ], 401);
    }

    return redirect()->route('login')->with('error', 'Invalid email or password.');
}

   
    public function showSignup()
    {
        return view('auth.signup'); 
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

       $user = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => Hash::make($request->password),
    'role' => 'admin', 
]);

      
        config(['session.cookie' => env('SESSION_COOKIE_WEB', config('session.cookie'))]);
        Auth::login($user);

       if ($user->role === 'admin') {
    return redirect()->route('dashboard');
} elseif ($user->role === 'technician') {
    return redirect()->route('technician.dashboard');
} elseif ($user->role === 'rider') {
    return redirect()->route('rider.dashboard');
} else {
    Auth::logout();
    return redirect()->route('login')->withErrors(['role' => 'Invalid role.']);
}
    }

    
   public function dashboard()
{
 
    $clinics      = Clinic::all();
    $appointments = Appointment::with(['caseOrder.clinic', 'technician'])->get();
    $materials    = Material::all();
    $caseOrders   = CaseOrder::with('clinic')->get();

 
    $clinicCount      = $clinics->count();
    $appointmentCount = $appointments->count();
    $materialCount    = $materials->count();
    $caseOrderCount   = $caseOrders->count();


    $recentAppointments = Appointment::with(['caseOrder.dentist.clinic', 'technician'])
        ->orderBy('schedule_datetime', 'desc')
        ->take(5)
        ->get();

   
    return view('admin.dashboard', [
        'clinicCount'        => $clinicCount,
        'appointmentCount'   => $appointmentCount,
        'materialCount'      => $materialCount,
        'caseOrderCount'     => $caseOrderCount,
        'clinics'            => $clinics,
        'appointments'       => $appointments,
        'materials'          => $materials,
        'caseOrders'         => $caseOrders,
        'recentAppointments' => $recentAppointments,
    ]);

    }


   
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing');
    }
     public function getLiveCounts()
    {
        return response()->json([
            'clinicCount'      => Clinic::count(),
            'appointmentCount' => Appointment::count(),
            'materialCount'    => Material::count(),
            'caseOrderCount'   => CaseOrder::count(),
        ]);
    }

}
