<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Rider extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id'; 
    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'contact_number',
        'password',
        'role',
        'photo',
    ];

    public function deliveries()
    {
        return $this->hasMany(\App\Models\Delivery::class, 'rider_id', 'id');
    }

    // Scope to only riders
    public function scopeRiders($query)
    {
        return $query->where('role', 'rider');
    }
}
