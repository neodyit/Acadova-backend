<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['branch_id', 'name', 'code', 'semester', 'status'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
