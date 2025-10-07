<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dentist extends Model
{
    use HasFactory;
    protected $table = 'tbl_dentist';

    protected $primaryKey = 'dentist_id';
   
    public $timestamps = false;

    protected $fillable = [
        'clinic_id',
        'name',
        'address',
        'contact_number',
        'email',
        'photo',
    ];

 
    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id', 'clinic_id');
    }
    public function patients()
    {
        return $this->hasMany(Patient::class, 'dentist_id', 'dentist_id');
    }
}
