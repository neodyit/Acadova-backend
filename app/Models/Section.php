<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['branch_id', 'name', 'academic_year', 'status'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function subsections()
    {
        return $this->hasMany(Subsection::class);
    }
}
