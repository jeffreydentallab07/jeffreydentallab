<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dentist extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'tbl_dentist';

    // Primary key
    protected $primaryKey = 'dentist_id';

    // Kung walang created_at at updated_at sa table mo
    public $timestamps = false;

    // Fillable fields (pwede mong i-mass assign)
    protected $fillable = [
        'clinic_id',
        'name',
        'address',
        'contact_number',
        'email',
        'photo', // path ng photo ng dentist
    ];

    // Relationship: Dentist belongs to Clinic
    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id', 'clinic_id');
    }

    // Relationship: Dentist has many Patients
    public function patients()
    {
        return $this->hasMany(Patient::class, 'dentist_id', 'dentist_id');
    }
}
