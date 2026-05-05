<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The primary key associated with the table.
     * Overriding 'id' to match your SQL schema.
     */
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     * Reflecting the split name fields from your 3NF design.
     */
    protected $fillable = [
        'first_name', 
        'middle_name', 
        'last_name', 
        'email', 
        'password', 
        'role', 
        'patient_id', 
        'staff_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship to the Patients table.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

    /**
     * Relationship to the Staff table.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'staff_id');
    }
}