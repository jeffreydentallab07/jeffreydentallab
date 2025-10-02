<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $table = 'deliveries'; 
    protected $primaryKey = 'delivery_id';

    protected $fillable = [
        'appointment_id',
        'rider_id',
        'delivery_status',
        'delivery_date',
    ];

    protected $casts = [
        'delivery_date' => 'datetime',
    ];

    // Relationships
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'appointment_id');
    }

    public function rider()
    {
        return $this->belongsTo(User::class, 'rider_id', 'id');
    }

    // Fixed constants to match your controller usage
    const STATUS_READY_TO_DELIVER = 'ready to deliver';
    const STATUS_IN_TRANSIT = 'in transit';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

// In Delivery.php
public function caseOrder()
{
    return $this->hasOneThrough(
        \App\Models\CaseOrder::class, // final model
        \App\Models\Appointment::class, // intermediate model
        'appointment_id', // foreign key on Appointment table pointing to Delivery
        'co_id', // foreign key on CaseOrder table
        'appointment_id', // local key on Delivery table
        'co_id' // local key on Appointment table
    );
}
}