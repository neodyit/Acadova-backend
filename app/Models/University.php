<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'state', 'city', 'status'];

    public function colleges()
    {
        return $this->hasMany(College::class);
    }
}
