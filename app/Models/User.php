<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'show_ads',
        'roll_number',
        'faculty_id',
        'department',
        'bio',
        'avatar',
        'google_id',
        'password',
        'university_id',
        'college_id',
        'department_id',
        'course_id',
        'branch_id',
        'section_id',
        'subsection_id',
        'semester',
        'fcm_token',
        'last_active_at',
        'current_app_version',
        'device_platform',
        'device_model',
        'os_version',
    ];

    /**
     * The attributes that should be appended to JSON arrays.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'is_online',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_active_at' => 'datetime',
            'show_ads' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Accessor to check if user is online (active in last 3 minutes)
     */
    public function getIsOnlineAttribute(): bool
    {
        if (!$this->last_active_at) {
            return false;
        }
        return $this->last_active_at->gt(now()->subMinutes(3));
    }

    /**
     * Scope for querying online users
     */
    public function scopeOnline($query, int $minutes = 3)
    {
        return $query->where('last_active_at', '>=', now()->subMinutes($minutes));
    }

    /**
     * Get the session logs for the user.
     */
    public function sessionLogs()
    {
        return $this->hasMany(UserSessionLog::class);
    }

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function departmentModel()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function subsection()
    {
        return $this->belongsTo(Subsection::class);
    }

    public function facultyAllocations()
    {
        return $this->hasMany(FacultySubjectAllocation::class, 'faculty_id');
    }

    /**
     * Convert legacy storage URLs to secure API media streaming URLs
     */
    public function getAvatarAttribute($value)
    {
        if (!$value) return null;
        if (str_contains($value, '/storage/')) {
            return str_replace('/storage/', '/api/media/file/', $value);
        }
        return $value;
    }
}
