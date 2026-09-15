@extends('layouts.student_layout')

@section('title', 'Student Dashboard - Acadova')

@section('styles')
<style>
    .welcome-card {
        background: linear-gradient(135deg, #4C1D95, #6C5CE7);
        color: white;
        border-radius: 20px;
        padding: 32px;
        margin-bottom: 36px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
        box-shadow: 0 10px 30px rgba(108, 92, 231, 0.25);
    }
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 36px;
    }
    .stat-box {
        background: white;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }
    .quiz-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }
    .quiz-card {
        background: white;
        border-radius: 18px;
        border: 1px solid var(--border);
        padding: 24px;
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }
    .quiz-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.08);
        border-color: var(--primary);
    }
    .badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
    }
    .badge-active { background: #DEF7EC; color: #03543F; }
    .table-card {
        background: white;
        border-radius: 18px;
        border: 1px solid var(--border);
        overflow: hidden;
    }
    table { width: 100%; border-collapse: collapse; text-align: left; }
    th { background: #F8FAFC; padding: 16px 20px; font-size: 12.5px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; border-bottom: 1px solid var(--border); }
    td { padding: 18px 20px; font-size: 14px; border-bottom: 1px solid var(--border); }
</style>
@endsection

@section('content')
<div class="welcome-card">
    <div>
        <h1 style="font-size: 28px; font-weight: 900; margin-bottom: 6px;">Welcome back, {{ $user->name }}! 👋</h1>
        <p style="font-size: 15px; opacity: 0.9;">Ready to test your knowledge today? Browse active departmental quizzes below.</p>
    </div>
    <div style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); padding: 12px 20px; border-radius: 14px; font-size: 13.5px; font-weight: 700;">
        📍 {{ $user->branch->name ?? $user->department ?? 'General Student' }}
    </div>
</div>

<div class="stats-row">
    <div class="stat-box">
        <div class="stat-icon" style="background: #EEF2FF; color: var(--primary);">
            <i class="fa-solid fa-list-check"></i>
        </div>
        <div>
            <div style="font-size: 24px; font-weight: 900; color: var(--dark);">{{ $stats['active_quizzes'] }}</div>
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 600;">Active Quizzes</div>
        </div>
    </div>
    <div class="stat-box">
        <div class="stat-icon" style="background: #E6FFFA; color: var(--secondary);">
            <i class="fa-solid fa-square-check"></i>
        </div>
        <div>
            <div style="font-size: 24px; font-weight: 900; color: var(--dark);">{{ $stats['quizzes_completed'] }}</div>
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 600;">Quizzes Attempted</div>
        </div>
    </div>
    <div class="stat-box">
        <div class="stat-icon" style="background: #FEF3C7; color: #D97706;">
            <i class="fa-solid fa-trophy"></i>
        </div>
        <div>
            <div style="font-size: 24px; font-weight: 900; color: var(--dark);">{{ $stats['avg_score'] }}</div>
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 600;">Avg. Score</div>
        </div>
    </div>
</div>

<!-- Active Quizzes Section -->
<div style="margin-bottom: 24px;">
    <h2 style="font-size: 20px; font-weight: 800; color: var(--dark);">🟢 Available Online Quizzes</h2>
    <p style="font-size: 14px; color: var(--text-muted); margin-top: 2px;">Select a quiz to view instructions and start your timed assessment.</p>
</div>

<div class="quiz-grid">
    @forelse($activeQuizzes as $q)
        <div class="quiz-card">
            <div>
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                    <span class="badge badge-active">Active Now</span>
                    <span style="font-size: 12px; font-weight: 700; color: var(--text-muted);">⏱️ {{ $q->duration_minutes ?? 15 }} Mins</span>
                </div>
                <h3 style="font-size: 18px; font-weight: 800; color: var(--dark); margin-bottom: 8px;">{{ $q->title }}</h3>
                <p style="font-size: 13.5px; color: var(--text-muted); margin-bottom: 18px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                    {{ $q->description ?? 'No description provided.' }}
                </p>
            </div>
            <div style="border-top: 1px solid var(--border); padding-top: 16px; display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 13px; font-weight: 700; color: var(--dark);">{{ $q->questions_count }} Questions</span>
                <a href="{{ route('student.quiz.prescreen', $q->id) }}" class="btn btn-primary">
                    Start Exam <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; background: white; border-radius: 18px; border: 1px solid var(--border); padding: 40px; text-align: center;">
            <i class="fa-solid fa-clipboard-check" style="font-size: 44px; color: var(--text-muted); margin-bottom: 14px;"></i>
            <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 6px;">No Active Quizzes Right Now</h3>
            <p style="font-size: 14px; color: var(--text-muted);">Check back later or consult your faculty for scheduled assessments.</p>
        </div>
    @endforelse
</div>

<!-- My Recent Attempts Section -->
<div style="margin-bottom: 20px;">
    <h2 style="font-size: 20px; font-weight: 800; color: var(--dark);">📊 My Quiz Attempt History</h2>
</div>

<div class="table-card">
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Quiz Title</th>
                    <th>Score</th>
                    <th>Total Questions</th>
                    <th>Submission</th>
                    <th>Date & Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($myAttempts as $att)
                    <tr>
                        <td><strong>{{ $att->quiz->title ?? 'Quiz #'.$att->quiz_id }}</strong></td>
                        <td><span class="badge" style="background: #EEF2FF; color: var(--primary); font-size: 13px;">{{ $att->score }}</span></td>
                        <td><strong>{{ $att->total_questions }}</strong></td>
                        <td>
                            @if($att->submission_type === 'auto')
                                <span style="background: #FFF5F5; color: #E53E3E; padding: 4px 10px; border-radius: 12px; font-weight: 700; font-size: 11.5px;">AUTO</span>
                            @else
                                <span style="background: #E6FFFA; color: #047857; padding: 4px 10px; border-radius: 12px; font-weight: 700; font-size: 11.5px;">MANUAL</span>
                            @endif
                        </td>
                        <td>{{ $att->created_at ? $att->created_at->format('M d, Y H:i') : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('student.attempt.result', $att->id) }}" class="btn btn-secondary" style="padding: 6px 14px; font-size: 12.5px;">
                                View Result
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">You haven't attempted any quizzes yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
