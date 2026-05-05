<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $primaryKey = 'department_id';
    protected $fillable = ['department_name', 'description'];

    public function staff() {
        return $this->hasMany(Staff::class, 'department_id');
    }

    public function services() {
        return $this->hasMany(Service::class, 'department_id');
    }

    public function schedules() {
        return $this->hasMany(Schedule::class, 'department_id');
    }
}
