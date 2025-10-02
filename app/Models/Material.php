<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'tbl_materials'; // Adjust table name as needed
    protected $primaryKey = 'material_id';
    
    protected $fillable = [
        'material_name',
        'price',
        'description',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // One-to-Many relationship with Appointments (if multiple appointments can use same material)
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'material_id', 'material_id');
    }

    // One-to-One inverse relationship (optional, for convenience)
    public function appointment()
    {
        return $this->hasOne(Appointment::class, 'material_id', 'material_id');
    }

    // Accessor for formatted price
    public function getFormattedPriceAttribute()
    {
        return '₱' . number_format($this->price, 2);
    }

    // Scope for active materials
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}