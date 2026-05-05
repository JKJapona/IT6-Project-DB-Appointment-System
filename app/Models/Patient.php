<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

    // Ensure Laravel knows the primary key is patient_id
    protected $primaryKey = 'patient_id';

    protected $fillable = [
        'philhealth_id', 'first_name', 'middle_name', 'last_name', 
        'suffix', 'date_of_birth', 'gender', 'fathers_first_name', 
        'fathers_middle_name', 'fathers_last_name', 'fathers_suffix', 
        'mothers_first_name', 'mothers_middle_name', 'mothers_last_name', 
        'mothers_suffix'
    ];

    /**
     * Relationship to Medical Histories
     */
    public function medicalHistories()
    {
        // One patient has many history records
        return $this->hasMany(MedicalHistory::class, 'patient_id', 'patient_id');
    }
}