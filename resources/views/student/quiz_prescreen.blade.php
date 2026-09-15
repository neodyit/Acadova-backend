@extends('layouts.student_layout')

@section('title', $quiz->title . ' - Pre-Screen - Acadova')

@section('styles')
<style>
    .prescreen-card {
        background: white;
        border-radius: 20px;
        border: 1px solid var(--border);
        padding: 36px;
        max-width: 800px;
        margin: 0 auto;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        background: var(--light-bg);
        padding: 20px;
        border-radius: 14px;
        margin: 24px 0;
        border: 1px solid var(--border);
    }
    .rule-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 14px;
    }
    .rule-icon {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #EEF2FF;
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
        margin-top: 2px;
    }
</style>
@endsection

@section('content')
<div class="prescreen-card">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
        <span style="background: #DEF7EC; color: #03543F; font-size: 12px; font-weight: 800; padding: 4px 12px; border-radius: 20px;">● Active Quiz</span>
        <a href="{{ route('student.dashboard') }}" style="color: var(--text-muted); font-size: 14px; text-decoration: none; font-weight: 700;">
            <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <h1 style="font-size: 26px; font-weight: 900; color: var(--dark); margin-bottom: 8px;">{{ $quiz->title }}</h1>
    <p style="font-size: 15px; color: var(--text-muted); line-height: 1.6;">{{ $quiz->description ?? 'Please read all examination guidelines carefully before launching your attempt.' }}</p>

    <div class="info-grid">
        <div>
            <div style="font-size: 12px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Duration</div>
            <div style="font-size: 18px; font-weight: 800; color: var(--dark); margin-top: 2px;">{{ $quiz->duration_minutes ?? 15 }} Minutes</div>
        </div>
        <div>
            <div style="font-size: 12px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Questions</div>
            <div style="font-size: 18px; font-weight: 800; color: var(--dark); margin-top: 2px;">{{ $quiz->questions_count }} Total</div>
        </div>
        <div>
            <div style="font-size: 12px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Pass Mark</div>
            <div style="font-size: 18px; font-weight: 800; color: var(--dark); margin-top: 2px;">50%</div>
        </div>
    </div>

    <h3 style="font-size: 16px; font-weight: 800; margin-bottom: 16px;">Examination Instructions & Anti-Cheat Policy</h3>
    <div style="margin-bottom: 28px;">
        <div class="rule-item">
            <div class="rule-icon"><i class="fa-solid fa-stopwatch"></i></div>
            <div style="font-size: 14px; color: var(--dark);"><strong>Strict Timer:</strong> The countdown timer will start immediately upon clicking "Launch Examination". Once expired, your test will auto-submit automatically.</div>
        </div>
        <div class="rule-item">
            <div class="rule-icon"><i class="fa-solid fa-window-restore"></i></div>
            <div style="font-size: 14px; color: var(--dark);"><strong>Tab-Switch Monitor:</strong> Do not switch tabs or minimize your browser. Anti-cheat protocols monitor tab visibility state.</div>
        </div>
        <div class="rule-item">
            <div class="rule-icon"><i class="fa-solid fa-floppy-disk"></i></div>
            <div style="font-size: 14px; color: var(--dark);"><strong>Instant Evaluation:</strong> Your answers will be submitted directly to Acadova servers for instant evaluation.</div>
        </div>
    </div>

    <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid var(--border); padding-top: 24px;">
        <span style="font-size: 13.5px; font-weight: 700; color: var(--text-muted);">Attempts Recorded: {{ $existingAttempts }}</span>
        <a href="{{ route('student.quiz.take', $quiz->id) }}" class="btn btn-primary" style="padding: 14px 28px; font-size: 15px;">
            <i class="fa-solid fa-play"></i> Launch Examination Now
        </a>
    </div>
</div>
@endsection
