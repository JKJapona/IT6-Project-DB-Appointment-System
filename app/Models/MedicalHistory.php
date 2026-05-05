<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalHistory extends Model
{
    protected $table = 'medical_histories'; // Manually define because of the 'ies' plural
    protected $primaryKey = 'history_id';
    protected $fillable = ['patient_id', 'condition_name', 'notes', 'date_recorded'];

    public function patient() {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
