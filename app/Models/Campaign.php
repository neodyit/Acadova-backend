<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'badge',
        'description',
        'link_url',
        'banner_color',
        'status',
        'is_featured',
    ];
}
