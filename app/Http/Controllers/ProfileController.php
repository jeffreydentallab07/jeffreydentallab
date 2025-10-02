<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Update the authenticated user's profile.
     * This method handles updates for a user logged in with the 'web' guard.
     * This is typically for admins or lab users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
    
     
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'contact_number' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
    
       
        $user->name = $request->name;
        $user->email = $request->email;
        $user->contact_number = $request->contact_number;
    
      
        if ($request->hasFile('photo')) {
         
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            
          
            $path = $request->file('photo')->store('profile_photos', 'public');
            
           
            $user->photo = $path;
        }
    
        
        $user->save();
        
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}