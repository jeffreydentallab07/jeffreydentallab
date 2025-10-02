<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Clinic extends Authenticatable
{
    use HasFactory, Notifiable;
    

    protected $table = 'tbl_clinic';
    protected $primaryKey = 'clinic_id';
       public $timestamps = false;
       

    protected $fillable = [
        'clinic_name',
        'address',
        'contact_number',
        'owner_name',
        'email',
        'password',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'email_verified_at' => 'datetime',
        ];
    }
  public function appointments()
{
    return $this->hasManyThrough(
        \App\Models\Appointment::class, // The final model
        \App\Models\CaseOrder::class,   // The intermediate model
        'clinic_id',                     // Foreign key on CaseOrder table
        'co_id',                         // Foreign key on Appointment table
        'clinic_id',                     // Local key on Clinic table
        'co_id'                          // Local key on CaseOrder table
    );
}
public function dentists()
{
    return $this->hasMany(Dentist::class, 'clinic_id', 'clinic_id');
}


public function patients()
{
    return $this->hasManyThrough(
        Patient::class,
        Dentist::class,
        'clinic_id',    // Foreign key sa Dentist table
        'dentist_id',   // Foreign key sa Patient table
        'clinic_id',    // Local key sa Clinic table
        'dentist_id'    // Local key sa Dentist table
    );
}
  public function caseOrders()
    {
        return $this->hasMany(\App\Models\CaseOrder::class, 'clinic_id', 'clinic_id');
    }

}
