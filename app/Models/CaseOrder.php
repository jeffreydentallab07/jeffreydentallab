<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseOrder extends Model
{
    protected $table = 'tbl_case_order';
    protected $primaryKey = 'co_id';
    public $timestamps = true;
    
    protected $fillable = [
        'notes',
        'case_type',
        'recieve_by',
        'recieve_at',
        'clinic_id',
        'patient_id',
        'status',
        'case_status',
        'user_id'
    ];
    
    // Relationship: Case order belongs to a patient
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }
    
    // Relationship: Case order belongs to a clinic
    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id', 'clinic_id');
    }
    
    // Relationship: Case order belongs to a dentist
    public function dentist()
    {
        return $this->belongsTo(Dentist::class, 'dentist_id', 'dentist_id');
    }
    
    // Relationship: One case order can have many appointments
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'co_id', 'co_id');
    }
   public function delivery()
{
    return $this->hasOneThrough(
        Delivery::class,       
        Appointment::class, 
        'co_id',              
        'appointment_id',     
        'co_id',              
        'appointment_id'    
    );
}
public function appointment() {
    return $this->hasMany(Appointment::class, 'co_id', 'co_id');
}


    
}