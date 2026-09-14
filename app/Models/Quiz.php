<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    protected $fillable = [
        'title',
        'subject',
        'description',
        'duration_minutes',
        'status',
        'scheduled_at',
        'starts_at',
        'ends_at',
        'instructor',
        'passing_marks',
        'department_ids',
        'course_ids',
        'branch_ids',
        'section_ids',
        'subject_ids',
        'target_groups',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'department_ids' => 'array',
        'course_ids' => 'array',
        'branch_ids' => 'array',
        'section_ids' => 'array',
        'subject_ids' => 'array',
        'target_groups' => 'array',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
