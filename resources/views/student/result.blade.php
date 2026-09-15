@extends('layouts.student_layout')

@section('title', 'Exam Result - Acadova')

@section('styles')
<style>
    .result-header-card {
        background: linear-gradient(135deg, #1E1B4B, #4C1D95);
        color: white;
        border-radius: 20px;
        padding: 40px;
        text-align: center;
        margin-bottom: 36px;
        box-shadow: 0 10px 30px rgba(76, 29, 149, 0.3);
    }
    .score-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: white;
        color: var(--primary);
        font-size: 36px;
        font-weight: 900;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    }
    .result-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 36px;
    }
    .result-box {
        background: white;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid var(--border);
        text-align: center;
    }
    .qa-card {
        background: white;
        border-radius: 16px;
        border: 1px solid var(--border);
        padding: 24px;
        margin-bottom: 18px;
    }
</style>
@endsection

@section('content')
<div class="result-header-card">
    <div class="score-circle">
        {{ round(($attempt->score / max(1, $attempt->total_questions)) * 100) }}%
    </div>
    <h1 style="font-size: 26px; font-weight: 900; margin-bottom: 6px;">
        @if(($attempt->score / max(1, $attempt->total_questions)) >= 0.5)
            🎉 Outstanding Performance!
        @else
            👍 Assessment Completed
        @endif
    </h1>
    <p style="font-size: 15px; opacity: 0.9;">{{ $quiz->title }}</p>
</div>

<div class="result-grid">
    <div class="result-box">
        <div style="font-size: 12px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Score Achieved</div>
        <div style="font-size: 24px; font-weight: 900; color: var(--primary); margin-top: 4px;">{{ $attempt->score }} / {{ $attempt->total_questions }}</div>
    </div>
    <div class="result-box">
        <div style="font-size: 12px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Total Questions</div>
        <div style="font-size: 24px; font-weight: 900; color: var(--dark); margin-top: 4px;">{{ $attempt->total_questions }}</div>
    </div>
    <div class="result-box">
        <div style="font-size: 12px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Submission Mode</div>
        <div style="font-size: 18px; font-weight: 800; color: var(--secondary); margin-top: 8px;">{{ strtoupper($attempt->submission_type ?? 'MANUAL') }}</div>
    </div>
</div>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <h2 style="font-size: 20px; font-weight: 800; color: var(--dark);">Detailed Question Key</h2>
    <a href="{{ route('student.dashboard') }}" class="btn btn-primary">
        Return to Dashboard <i class="fa-solid fa-arrow-right"></i>
    </a>
</div>

<div>
    @foreach($quiz->questions as $idx => $q)
        <div class="qa-card">
            <div style="font-size: 13px; font-weight: 800; color: var(--primary); margin-bottom: 8px;">Question {{ $idx + 1 }}</div>
            <h3 style="font-size: 16.5px; font-weight: 800; color: var(--dark); margin-bottom: 14px;">{{ $q->question }}</h3>
            <div style="background: #F8FAFC; border: 1px solid var(--border); padding: 14px 18px; border-radius: 12px; font-size: 14px; font-weight: 700; color: #047857;">
                <i class="fa-solid fa-circle-check"></i> Correct Answer: {{ $q->correct_option }}
            </div>
        </div>
    @endforeach
</div>
@endsection
