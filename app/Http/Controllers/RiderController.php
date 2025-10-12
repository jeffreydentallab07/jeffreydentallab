<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Delivery;
use Illuminate\Support\Facades\Log;

class RiderController extends Controller
{
    public function index()
    {
        $riders = User::where('role', 'rider')->get();
        return view('admin.riders.index', compact('riders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'contact_number' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'photo' => 'nullable|image|max:2048',
        ]);

        $photoPath = $request->file('photo') ? $request->file('photo')->store('riders', 'public') : null;

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'password' => Hash::make($request->password),
            'photo' => $photoPath,
            'role' => 'rider',
        ]);

        return redirect()->route('riders.index')->with('success', 'Rider added successfully.');
    }

    public function update(Request $request, User $rider)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $rider->id,
            'contact_number' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($rider->photo) {
                Storage::disk('public')->delete($rider->photo);
            }
            $rider->photo = $request->file('photo')->store('riders', 'public');
        }

        $rider->name = $request->name;
        $rider->email = $request->email;
        $rider->contact_number = $request->contact_number;

        if ($request->password) {
            $rider->password = Hash::make($request->password);
        }

        $rider->save();

        return redirect()->route('riders.index')->with('success', 'Rider updated successfully.');
    }

    public function destroy(User $rider)
    {
        if ($rider->photo) {
            Storage::disk('public')->delete($rider->photo);
        }

        $rider->delete();
        return redirect()->route('riders.index')->with('success', 'Rider deleted successfully.');
    }
     public function dashboard()
    {
        $riderId = Auth::id();

       
        $deliveries = Delivery::with([
            'appointment.material', 
            'appointment.caseOrder.clinic'
        ])
        ->where('rider_id', $riderId)
        ->orderBy('updated_at', 'desc') 
        ->get();

        return view('rider.dashboard', compact('deliveries'));
    }

   
    public function updateStatus(Request $request, Delivery $delivery)
    {
        $request->validate([
            'delivery_status' => 'required|string|in:ready to deliver,in transit,delivered',
        ]);

    
        if ($delivery->rider_id !== Auth::id()) {
            Log::warning('Unauthorized attempt to update delivery status', [
                'delivery_id' => $delivery->id,
                'authenticated_rider_id' => Auth::id(),
                'delivery_owner_id' => $delivery->rider_id
            ]);
            return redirect()->route('rider.dashboard')->with('error', 'Unauthorized action.');
        }
        
       
        if ($delivery->delivery_status === 'delivered' && $request->delivery_status !== 'delivered') {
             return redirect()->route('rider.dashboard')->with('error', 'Cannot change status of a delivered item.');
        }

        $delivery->delivery_status = $request->delivery_status;
        $delivery->save();

        return redirect()->route('rider.dashboard')->with('success', 'Delivery status updated to ' . ucfirst($delivery->delivery_status) . '.');
    }

}
