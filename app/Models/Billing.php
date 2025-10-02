<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    protected $table = 'tbl_billing';
    protected $primaryKey = 'billing_id';
    
    public $timestamps = true;
    const UPDATED_AT = null; // Only track created_at, not updated_at

    protected $fillable = [
        'appointment_id',
        'total_amount',
        'rider_id', // Add this if you have rider assignment
        'material_id', // Add this if you have material assignment
        'created_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    // One-to-One relationship with Appointment
   public function appointment()
{
    return $this->belongsTo(Appointment::class, 'appointment_id', 'appointment_id')
                ->with('caseOrder'); // eager load the case order
}

    // Relationship with Rider (if applicable)
    public function rider()
    {
        return $this->belongsTo(User::class, 'rider_id', 'id');
    }

    // Relationship with Material (direct if you have material_id in billing)
    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id', 'material_id');
    }

    // Helper method to get material name
    public function getMaterialNameAttribute()
    {
        return $this->material ? $this->material->material_name : 'Not Set';
    }

    // Helper method to get formatted total amount
    public function getFormattedTotalAttribute()
    {
        return '₱' . number_format($this->total_amount, 2);
    }

    // Scope for billings with materials
    public function scopeWithMaterial($query)
    {
        return $query->with('appointment.material');
    }

    // Accessor for patient name through appointment
    public function getPatientNameAttribute()
    {
        return $this->appointment ? $this->appointment->caseOrder->patient->patient_name ?? '-' : '-';
    }

    // Accessor for case type through appointment
    public function getCaseTypeAttribute()
    {
        return $this->appointment ? $this->appointment->caseOrder->case_type ?? '-' : '-';
    }

    // Accessor for dentist name through appointment
    public function getDentistNameAttribute()
    {
        return $this->appointment ? $this->appointment->caseOrder->dentist->name ?? '-' : '-';
    }

    // Accessor for clinic address through appointment
    public function getClinicAddressAttribute()
    {
        return $this->appointment ? $this->appointment->caseOrder->clinic->address ?? 'N/A' : 'N/A';
    }

    // Accessor for rider name
    public function getRiderNameAttribute()
    {
        return $this->rider ? $this->rider->name : 'Not Assigned';
    }
    // In App\Models\Billing.php
public function caseOrder()
{
    return $this->hasOneThrough(
        \App\Models\CaseOrder::class,  // Final model
        \App\Models\Appointment::class, // Intermediate model
        'appointment_id',               // Foreign key on Appointment table pointing to Billing
        'co_id',                        // Foreign key on CaseOrder table pointing to Appointment
        'appointment_id',               // Local key on Billing table
        'appointment_id'                // Local key on Appointment table
    );
}

}