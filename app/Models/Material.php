<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'tbl_materials'; 
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

   
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'material_id', 'material_id');
    }

  
    public function appointment()
    {
        return $this->hasOne(Appointment::class, 'material_id', 'material_id');
    }

   
    public function getFormattedPriceAttribute()
    {
        return '₱' . number_format($this->price, 2);
    }

   
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}