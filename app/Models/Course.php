<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['department_id', 'name', 'code', 'duration_years', 'status'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
