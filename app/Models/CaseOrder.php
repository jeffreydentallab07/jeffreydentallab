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

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }
    
   
    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id', 'clinic_id');
    }
    
  
    public function dentist()
    {
        return $this->belongsTo(Dentist::class, 'dentist_id', 'dentist_id');
    }
    
    
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
public function material()
{
    return $this->belongsTo(Material::class, 'material_id', 'id'); 
   
}


    
}