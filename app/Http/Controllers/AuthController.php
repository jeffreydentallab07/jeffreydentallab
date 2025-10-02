<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

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

        return back()->withErrors(['email' => 'Invalid credentials.']);
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
    'role' => 'technician', 
]);

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
        return view('admin.dashboard');
    }

   
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
