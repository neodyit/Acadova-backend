<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    protected $fillable = [
        'user_id',
        'created_by',
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Check if the quiz is targeted to a given student.
     * If no target scope (departments, courses, branches, sections, target groups, etc.) is specified at all,
     * or if target departments / courses are left empty without target pairs,
     * the quiz is NOT shown to any student or any batch.
     */
    public function isTargetedToStudent($user): bool
    {
        if (!$user || strtolower($user->role) !== 'student') {
            return true;
        }

        $hasTargetGroups = !empty($this->target_groups) && is_array($this->target_groups) && count($this->target_groups) > 0;

        // Priority 1: Explicit Target Combinations (target_groups)
        if ($hasTargetGroups) {
            foreach ($this->target_groups as $group) {
                if (!is_array($group)) continue;

                $branchOk = empty($group['branch_id']) || $group['branch_id'] === 'all' ||
                    ((string)$user->branch_id === (string)$group['branch_id']) ||
                    (!empty($group['branch_db_id']) && (string)$user->branch_id === (string)$group['branch_db_id']) ||
                    ($user->branch && (string)$user->branch->id === (string)$group['branch_id']) ||
                    ($user->branch && (string)$user->branch->name === (string)$group['branch_id']) ||
                    ($user->branch && (string)$user->branch->code === (string)$group['branch_id']) ||
                    ((string)$user->branch === (string)$group['branch_id']);

                $secList = [];
                if (!empty($group['section_ids']) && is_array($group['section_ids'])) {
                    $secList = $group['section_ids'];
                } elseif (!empty($group['section_id'])) {
                    $secList = [$group['section_id']];
                }

                $sectionOk = empty($secList) || in_array('all', $secList) ||
                    in_array((string)$user->section_id, $secList) ||
                    ($user->section && in_array((string)$user->section->id, $secList)) ||
                    ($user->section && in_array((string)$user->section->name, $secList)) ||
                    in_array((string)$user->section, $secList);

                $deptOk = empty($group['department_id']) || $group['department_id'] === 'all' ||
                    ((string)$user->department_id === (string)$group['department_id']) ||
                    ($user->departmentModel && (string)$user->departmentModel->id === (string)$group['department_id']) ||
                    ($user->departmentModel && (string)$user->departmentModel->name === (string)$group['department_id']) ||
                    ((string)$user->department === (string)$group['department_id']);

                $courseOk = empty($group['course_id']) || $group['course_id'] === 'all' ||
                    ((string)$user->course_id === (string)$group['course_id']) ||
                    ($user->course && (string)$user->course->id === (string)$group['course_id']) ||
                    ($user->course && (string)$user->course->name === (string)$group['course_id']);

                $userSemDigits = preg_replace('/[^0-9]/', '', (string)$user->semester);
                $groupSemDigits = preg_replace('/[^0-9]/', '', (string)($group['semester'] ?? ''));

                $semOk = empty($group['semester']) || $group['semester'] === 'all' ||
                    ($userSemDigits !== '' && $groupSemDigits !== '' && $userSemDigits === $groupSemDigits) ||
                    ((string)$user->semester === (string)$group['semester']);

                if ($branchOk && $sectionOk && $deptOk && $courseOk && $semOk) {
                    return true;
                }
            }
            return false;
        }

        // Single academic target legacy format
        if (!empty($this->target_academic_type) && !empty($this->target_academic_id)) {
            $type = strtolower($this->target_academic_type);
            $id = (int)$this->target_academic_id;

            if ($type === 'branch' && (int)$user->branch_id !== $id) return false;
            if ($type === 'department' && (int)$user->department_id !== $id) return false;
            if ($type === 'course' && (int)$user->course_id !== $id) return false;
            if ($type === 'section' && (int)$user->section_id !== $id) return false;
            return true;
        }

        // Priority 2: General Scope Checkboxes (department_ids, course_ids, branch_ids, section_ids)
        // If any of department_ids, course_ids, branch_ids, or section_ids is explicitly an empty array [],
        // it means 0 options were selected for that filter -> quiz should NOT be shown to any student.
        if (is_array($this->department_ids) && count($this->department_ids) === 0) {
            return false;
        }
        if (is_array($this->course_ids) && count($this->course_ids) === 0) {
            return false;
        }
        if (is_array($this->branch_ids) && count($this->branch_ids) === 0) {
            return false;
        }
        if (is_array($this->section_ids) && count($this->section_ids) === 0) {
            return false;
        }

        // If department_ids is completely null/missing and course_ids is completely null/missing, no target scope was defined -> return false
        if (empty($this->department_ids) && empty($this->course_ids) && empty($this->branch_ids) && empty($this->section_ids)) {
            return false;
        }

        // 1. Check Department Filter
        if (!empty($this->department_ids) && is_array($this->department_ids) && !in_array('all', $this->department_ids)) {
            $deptMatch = false;
            if ($user->department_id && (in_array($user->department_id, $this->department_ids) || in_array((string)$user->department_id, $this->department_ids))) {
                $deptMatch = true;
            }
            if ($user->department && in_array($user->department, $this->department_ids)) {
                $deptMatch = true;
            }
            if (!$deptMatch) return false;
        }

        // 2. Check Course Filter
        if (!empty($this->course_ids) && is_array($this->course_ids) && !in_array('all', $this->course_ids)) {
            $courseMatch = false;
            if ($user->course_id && (in_array($user->course_id, $this->course_ids) || in_array((string)$user->course_id, $this->course_ids))) {
                $courseMatch = true;
            }
            if (!$courseMatch) return false;
        }

        // 3. Check Branch Filter
        if (!empty($this->branch_ids) && is_array($this->branch_ids) && !in_array('all', $this->branch_ids)) {
            $branchMatch = false;
            if ($user->branch_id && (in_array($user->branch_id, $this->branch_ids) || in_array((string)$user->branch_id, $this->branch_ids))) {
                $branchMatch = true;
            }
            if (!$branchMatch) return false;
        }

        // 4. Check Section Filter
        if (!empty($this->section_ids) && is_array($this->section_ids) && !in_array('all', $this->section_ids)) {
            $secMatch = false;
            if ($user->section_id && (in_array($user->section_id, $this->section_ids) || in_array((string)$user->section_id, $this->section_ids))) {
                $secMatch = true;
            }
            if (!$secMatch) return false;
        }

        return true;
    }
}
