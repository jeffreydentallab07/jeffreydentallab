<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Delivery;
use Illuminate\Support\Facades\Log; // Added for logging

class RiderController extends Controller
{
    public function index()
{
    $riders = User::where('role', 'rider')->get();
    return view('admin.riders.index', compact('riders'));
}
    // ... (index, create, store, edit, update, destroy methods remain unchanged) ...

    public function dashboard()
    {
        $riderId = Auth::id();

        // Eager load necessary relationships: appointment, then material and caseOrder.clinic through it
        $deliveries = Delivery::with([
            'appointment.material', 
            'appointment.caseOrder.clinic' // To get clinic name
        ])
        ->where('rider_id', $riderId)
        ->orderBy('updated_at', 'desc') 
        ->get();

        return view('rider.dashboard', compact('deliveries'));
    }

    /**
     * Handle the update of a delivery's status.
     * This method will be used for the dropdown changes.
     */
    public function updateStatus(Request $request, Delivery $delivery)
    {
        $request->validate([
            'delivery_status' => 'required|string|in:ready to deliver,in transit,delivered',
        ]);

        // Authorization check: Ensure the delivery belongs to the authenticated rider
        if ($delivery->rider_id !== Auth::id()) {
            Log::warning('Unauthorized attempt to update delivery status', [
                'delivery_id' => $delivery->id,
                'authenticated_rider_id' => Auth::id(),
                'delivery_owner_id' => $delivery->rider_id
            ]);
            return redirect()->route('rider.dashboard')->with('error', 'Unauthorized action.');
        }
        
        // Prevent changing status if it's already delivered
        if ($delivery->delivery_status === 'delivered' && $request->delivery_status !== 'delivered') {
             return redirect()->route('rider.dashboard')->with('error', 'Cannot change status of a delivered item.');
        }

        $delivery->delivery_status = $request->delivery_status;
        $delivery->save();

        return redirect()->route('rider.dashboard')->with('success', 'Delivery status updated to ' . ucfirst($delivery->delivery_status) . '.');
    }
}