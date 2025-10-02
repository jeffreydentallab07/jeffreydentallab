<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'patient';
    protected $primaryKey = 'patient_id';
    public $timestamps = true;
    
    protected $fillable = [
        'dentist_id',
        'patient_name',
        'address',
        'contact_number',
        'email'
    ];
    
    // Relationship: One patient can have many case orders
    public function caseOrders()
    {
        return $this->hasMany(CaseOrder::class, 'patient_id', 'patient_id');
    }
    
    // Relationship: Patient belongs to a dentist
    public function dentist()
    {
        return $this->belongsTo(Dentist::class, 'dentist_id', 'dentist_id');
    }
}