<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Clinic;

class ClinicAuthController extends Controller
{
   
    public function showSignup()
    {
        return view('clinic_signup');
    }

    
   public function signup(Request $request)
{
    $request->validate([
        'clinic_name'    => 'required|string|max:255',
        'email'          => 'required|email|unique:tbl_clinic,email',
        'password'       => 'required|string|min:8|confirmed',
        'address'        => 'nullable|string|max:255',
        'contact_number' => ['nullable', 'regex:/^[0-9+\-\(\)\s]+$/', 'min:7', 'max:20'],
        'owner_name'     => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\s\-\.]+$/'],
    ]);

    $clinic = Clinic::create([
        'clinic_name'    => strip_tags(trim($request->clinic_name)),
        'email'          => strtolower(trim($request->email)),
        'password'       => Hash::make($request->password),
        'address'        => strip_tags(trim($request->address)),
        'contact_number' => strip_tags(trim($request->contact_number)),
        'owner_name'     => strip_tags(trim($request->owner_name)),
    ]);

    
   return redirect()->back()->with('signup_success', 'Account created successfully! Please log in.');

}


    /**
     * Show Login Form
     */
    public function showLogin()
    {
        return view('clinic_index');
    }

    /**
     * Handle Login
     */
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string'
    ]);

    $remember = $request->boolean('remember');

    if (Auth::guard('clinic')->attempt($credentials, $remember)) {
        $request->session()->regenerate();
        // Add this line
        $request->session()->flash('login_success', 'Successfuly Login!, ' . Auth::guard('clinic')->user()->clinic_name . '!');
        return redirect()->route('clinic.dashboard');
    }

    return back()
        ->withErrors(['email' => 'Invalid credentials.'])
        ->withInput();
}

    /**
     * Logout Clinic
     */
    public function logout(Request $request)
    {
        Auth::guard('clinic')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('clinic.login');
    }

  
    public function index()
    {
        return view('clinic.dashboard');
    }

   
    public function profile()
    {
        $clinic = Auth::guard('clinic')->user();
        return view('clinic_profile', compact('clinic'));
    }

   
    public function updateProfile(Request $request)
    {
        $clinic = Auth::guard('clinic')->user();

        $request->validate([
            'clinic_name'   => 'required|string|max:255',
            'email'         => 'required|email|unique:tbl_clinic,email,' . $clinic->clinic_id,
            'password'      => 'nullable|string|min:8|confirmed',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $clinic->clinic_name = strip_tags(trim($request->clinic_name));
        $clinic->email = strtolower(trim($request->email));

        if ($request->password) {
            $clinic->password = Hash::make($request->password);
        }

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('uploads/clinic_photos', 'public');
            $clinic->profile_photo = basename($path);
        }

        $clinic->save();

        return back()->with('success', 'Profile updated successfully!');
    }
    
}