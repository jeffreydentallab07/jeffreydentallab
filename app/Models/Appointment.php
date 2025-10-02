<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'tbl_appointment';
    protected $primaryKey = 'appointment_id';
    
    public $timestamps = false;
    
    protected $fillable = [
        'co_id',
        'technician_id',
        'schedule_datetime',
        'material_id',
        'priority_level',
        'purpose',
        'work_status',
    ];

    protected $casts = [
        'schedule_datetime' => 'datetime',
    ];

    // Existing relationships (huwag baguhin)
    public function material()
{
    return $this->belongsTo(Material::class, 'material_id', 'material_id');
}

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id', 'id');
    }

    public function caseOrder()
{
    return $this->belongsTo(CaseOrder::class, 'co_id', 'co_id');
}
   public function delivery()
{
    return $this->hasOne(Delivery::class, 'appointment_id', 'appointment_id');
}

    public function billing()
    {
        return $this->hasOne(Billing::class, 'appointment_id', 'appointment_id');
    }

    // BAGONG ADDITION: Indirect relationship to Patient via CaseOrder
    public function patient()
    {
        return $this->belongsToThrough(
            Patient::class,  // Target model (Patient)
            CaseOrder::class,  // Intermediate model (CaseOrder)
            'co_id',  // Foreign key sa Appointment (co_id → CaseOrder.co_id)
            'patient_id',  // Foreign key sa CaseOrder (CaseOrder.patient_id → Patient.patient_id)
            'co_id',  // Local key sa Appointment (co_id)
            'co_id'  // Local key sa CaseOrder (co_id)
        );
    }

    // Helper method para sa patient name (optional, para madaling i-access)
   // Appointment.php
public function getPatientNameAttribute()
{
    return $this->caseOrder && $this->caseOrder->patient
        ? $this->caseOrder->patient->patient_name
        : 'N/A';
}


    // Iba pang helper methods (huwag baguhin)
    public function getMaterialNameAttribute()
    {
        return $this->material ? $this->material->material_name : 'Not Selected';
    }

    public function getIsActiveAttribute()
    {
        return in_array($this->work_status, ['pending', 'in progress']);
    }

    public function scopeFinished($query)
    {
        return $query->where('work_status', 'finished');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('work_status', ['pending', 'in progress']);
    }
    
}
