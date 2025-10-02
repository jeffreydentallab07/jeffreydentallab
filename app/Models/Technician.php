<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;

class Technician extends Authenticatable
{
    use Notifiable;

    protected $table = 'tbl_technician'; 
    protected $primaryKey = 'technician_id'; 
    public $timestamps = false;

    protected $fillable = [
        'full_name',
        'email',
        'password',
        'contact_number',
    ];

    protected $hidden = [
        'password',
    ];
    public function caseOrders() {
        return $this->belongsToMany(CaseOrder::class, 'technician_case_order', 'technician_id', 'case_order_id');
    }
    
}
