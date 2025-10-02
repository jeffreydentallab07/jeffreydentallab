<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewCaseOrder extends Model
{
    use HasFactory;

    protected $table = 'tbl_case_order';
    protected $primaryKey = 'co_id';
    public $timestamps = true; // kung may created_at/updated_at

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'dentist_id',
        'user_id',
        'notes',
        'case_type',
        'case_status',
        'status',
        'recieve_by',
        'recieve_at',
    ];

    // Relationships
    public function clinic() {
        return $this->belongsTo(Clinic::class, 'clinic_id', 'clinic_id');
    }

    public function patient() {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

   public function dentist() {
    return $this->hasOneThrough(Dentist::class, Patient::class, 'patient_id', 'dentist_id', 'patient_id', 'dentist_id');
}

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
