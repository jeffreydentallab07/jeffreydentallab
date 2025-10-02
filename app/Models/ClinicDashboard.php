<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ClinicDashboard extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tbl_clinic'; 
    protected $primaryKey = 'clinic_id'; 
    public $timestamps = false;

    protected $fillable = [
        'clinic_name',
        'address',
        'contact_number',
        'email',
        'owner_name',
        'password',
        'profile_photo',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

  
    public function dentists()
    {
        return $this->hasMany(Dentist::class, 'clinic_id');
    }

   
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo && \Illuminate\Support\Facades\Storage::exists($this->profile_photo)) {
            return asset(str_replace('public/', 'storage/', $this->profile_photo));
        }
        return asset('images/user.png'); 
    }
}
