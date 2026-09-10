@extends('layouts.admin_layout')

@section('title', 'Dashboard Overview - Acadova Admin')
@section('page_title', 'Dashboard Overview')

@section('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }
    .stat-card {
        background: white;
        border-radius: 18px;
        padding: 22px;
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }
    .stat-info h3 { font-size: 24px; font-weight: 800; color: var(--dark); }
    .stat-info p { font-size: 13px; color: var(--text-muted); font-weight: 600; margin-top: 2px; }
</style>
@endsection

@section('content')
<div class="action-bar" style="margin-bottom: 28px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 800;">Analytics & Activity</h1>
        <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Live summary of student enrollment, active quizzes, and performance metrics.</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: #EEF2FF; color: var(--primary);">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['total_students'] }}</h3>
            <p>Registered Students</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #E6FFFA; color: var(--secondary);">
            <i class="fa-solid fa-layer-group"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['total_quizzes'] }}</h3>
            <p>Total Quizzes</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #FFFBEB; color: var(--warning);">
            <i class="fa-solid fa-bullhorn"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['active_campaigns'] }}</h3>
            <p>Active Campaigns</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #FFF5F5; color: var(--danger);">
            <i class="fa-solid fa-clipboard-check"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['total_attempts'] }}</h3>
            <p>Submissions Logged</p>
        </div>
    </div>
</div>

<div class="table-card">
    <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
        <h2 style="font-size: 16px; font-weight: 800;">Recent Quiz Submissions</h2>
        <a href="{{ route('admin.quizzes') }}" class="btn btn-secondary" style="font-size: 12px; padding: 6px 12px;">View All Quizzes</a>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Quiz Title</th>
                    <th>Score</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentAttempts as $att)
                    <tr>
                        <td>
                            <strong>{{ $att->user->name ?? 'Unknown Student' }}</strong><br>
                            <span style="font-size: 11.5px; color: var(--text-muted);">{{ $att->user->email ?? 'N/A' }}</span>
                        </td>
                        <td><strong>{{ $att->quiz->title ?? 'Quiz #'.$att->quiz_id }}</strong></td>
                        <td><span class="badge" style="background: #DEF7EC; color: #03543F; padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 12px;">{{ $att->score }} / {{ $att->total_questions }}</span></td>
                        <td>{{ $att->created_at ? $att->created_at->format('M d, Y H:i') : 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 30px;">No student submissions logged yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
