<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSessionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token_id',
        'login_method',
        'login_at',
        'logout_at',
        'ip_address',
        'user_agent',
        'device_info',
        'status',
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
    ];

    /**
     * Get the user that owns the session log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
