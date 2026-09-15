<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Exam: {{ $quiz->title }} - Acadova</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6C5CE7;
            --primary-hover: #5B4BC4;
            --secondary: #00B894;
            --dark: #0F172A;
            --light-bg: #F8FAFC;
            --card-bg: #FFFFFF;
            --border: #E2E8F0;
            --text-muted: #64748B;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }

        body {
            background-color: var(--light-bg);
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            user-select: none;
        }

        /* Top Bar */
        .exam-header {
            height: 70px;
            background: #FFFFFF;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 4%;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .timer-badge {
            background: #FEF3C7;
            color: #D97706;
            border: 1px solid #FCD34D;
            padding: 8px 18px;
            border-radius: 20px;
            font-size: 16px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .timer-warning {
            background: #FEE2E2;
            color: #DC2626;
            border-color: #FCA5A5;
            animation: pulse 1s infinite alternate;
        }

        @keyframes pulse { from { opacity: 1; } to { opacity: 0.6; } }

        /* Main Exam Layout */
        .exam-container {
            max-width: 1200px;
            margin: 32px auto;
            width: 100%;
            padding: 0 24px;
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 28px;
            flex: 1;
        }

        @media (max-width: 900px) {
            .exam-container { grid-template-columns: 1fr; }
        }

        .question-card {
            background: white;
            border-radius: 20px;
            border: 1px solid var(--border);
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .option-item {
            background: #F8FAFC;
            border: 2px solid var(--border);
            border-radius: 14px;
            padding: 16px 20px;
            margin-bottom: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 15px;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .option-item:hover { border-color: var(--primary); background: #F3F0FF; }
        .option-item.selected { border-color: var(--primary); background: #EEF2FF; color: var(--primary); }

        .palette-card {
            background: white;
            border-radius: 20px;
            border: 1px solid var(--border);
            padding: 24px;
            height: fit-content;
        }

        .palette-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin-top: 16px;
        }

        .palette-btn {
            aspect-ratio: 1;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: #F8FAFC;
            font-weight: 800;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
        }

        .palette-btn.active { border-color: var(--primary); background: var(--primary); color: white; }
        .palette-btn.answered { background: var(--secondary); border-color: var(--secondary); color: white; }

        /* Anti cheat alert */
        .anti-cheat-alert {
            position: fixed;
            top: 20px; left: 50%;
            transform: translateX(-50%);
            background: #DC2626;
            color: white;
            padding: 12px 24px;
            border-radius: 30px;
            font-size: 13.5px;
            font-weight: 800;
            display: none;
            align-items: center;
            gap: 10px;
            z-index: 9999;
            box-shadow: 0 10px 30px rgba(220, 38, 38, 0.4);
        }
    </style>
</head>
<body>

    <div class="anti-cheat-alert" id="antiCheatAlert">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span>WARNING: Tab switch detected! Anti-cheat penalty logged.</span>
    </div>

    <header class="exam-header">
        <div>
            <h1 style="font-size: 18px; font-weight: 900; color: var(--dark);">{{ $quiz->title }}</h1>
            <span style="font-size: 12px; color: var(--text-muted); font-weight: 600;">Student Exam Player</span>
        </div>

        <div class="timer-badge" id="timerBadge">
            <i class="fa-solid fa-stopwatch"></i>
            <span id="timeRemaining">--:--</span>
        </div>
    </header>

    <div class="exam-container">
        <!-- Main Question Area -->
        <div class="question-card">
            <div>
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                    <span style="font-size: 13px; font-weight: 800; color: var(--primary);" id="questionProgress">Question 1 of {{ count($questions) }}</span>
                    <span style="font-size: 12.5px; font-weight: 700; color: var(--text-muted);" id="questionMarks">Marks: 1</span>
                </div>

                <h2 style="font-size: 20px; font-weight: 800; color: var(--dark); margin-bottom: 24px; line-height: 1.5;" id="questionText">Loading question...</h2>

                <div id="optionsContainer">
                    <!-- Options injected by JS -->
                </div>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 32px; border-top: 1px solid var(--border); padding-top: 20px;">
                <button type="button" class="btn" style="background: #EDF2F7; color: var(--dark);" id="prevBtn" onclick="prevQuestion()">
                    <i class="fa-solid fa-arrow-left"></i> Previous
                </button>
                <button type="button" class="btn" style="background: var(--primary); color: white;" id="nextBtn" onclick="nextQuestion()">
                    Next <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Right Side Palette -->
        <div class="palette-card">
            <h3 style="font-size: 15px; font-weight: 800; color: var(--dark);">Question Navigation</h3>
            <p style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">Click any number to jump to question.</p>

            <div class="palette-grid" id="paletteGrid">
                <!-- Palette buttons injected by JS -->
            </div>

            <button type="button" class="btn" style="width: 100%; margin-top: 28px; background: var(--secondary); color: white; justify-content: center;" onclick="confirmSubmit()">
                <i class="fa-solid fa-paper-plane"></i> Submit Examination
            </button>
        </div>
    </div>

    <script>
        const QUIZ_ID = {{ $quiz->id }};
        const DURATION_SECONDS = {{ ($quiz->duration_minutes ?? 15) * 60 }};
        const QUESTIONS = @json($questions);
        const CSRF_TOKEN = '{{ csrf_token() }}';

        let currentIndex = 0;
        let userAnswers = {}; // question_id -> selected option text
        let secondsRemaining = DURATION_SECONDS;
        let violationsCount = 0;

        // Init Exam Player
        document.addEventListener('DOMContentLoaded', () => {
            renderQuestion();
            renderPalette();
            startTimer();
            setupAntiCheat();
        });

        function startTimer() {
            const timerBadge = document.getElementById('timerBadge');
            const display = document.getElementById('timeRemaining');

            const interval = setInterval(() => {
                secondsRemaining--;
                if (secondsRemaining <= 0) {
                    clearInterval(interval);
                    autoSubmit('Time Up');
                    return;
                }

                const mins = Math.floor(secondsRemaining / 60);
                const secs = secondsRemaining % 60;
                display.innerText = `${mins}:${secs < 10 ? '0' : ''}${secs}`;

                if (secondsRemaining < 120) {
                    timerBadge.classList.add('timer-warning');
                }
            }, 1000);
        }

        function setupAntiCheat() {
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    violationsCount++;
                    const alert = document.getElementById('antiCheatAlert');
                    alert.style.display = 'flex';
                    setTimeout(() => { alert.style.display = 'none'; }, 4000);
                }
            });
        }

        function renderQuestion() {
            const q = QUESTIONS[currentIndex];
            if (!q) return;

            document.getElementById('questionProgress').innerText = `Question ${currentIndex + 1} of ${QUESTIONS.length}`;
            document.getElementById('questionMarks').innerText = `Marks: ${q.marks || 1}`;
            document.getElementById('questionText').innerText = q.question;

            const container = document.getElementById('optionsContainer');
            const selectedOpt = userAnswers[q.id];

            container.innerHTML = q.options.map((opt, idx) => `
                <div class="option-item ${selectedOpt === opt ? 'selected' : ''}" onclick="selectOption('${opt.replace(/'/g, "\\'")}')">
                    <div style="width: 24px; height: 24px; border-radius: 50%; border: 2px solid ${selectedOpt === opt ? 'var(--primary)' : 'var(--border)'}; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 12px; background: ${selectedOpt === opt ? 'var(--primary)' : 'white'}; color: ${selectedOpt === opt ? 'white' : 'var(--text-muted)'};">
                        ${String.fromCharCode(65 + idx)}
                    </div>
                    <span>${opt}</span>
                </div>
            `).join('');

            document.getElementById('prevBtn').style.visibility = currentIndex === 0 ? 'hidden' : 'visible';
            document.getElementById('nextBtn').innerText = currentIndex === QUESTIONS.length - 1 ? 'Finish & Review' : 'Next';
        }

        function selectOption(opt) {
            const q = QUESTIONS[currentIndex];
            userAnswers[q.id] = opt;
            renderQuestion();
            renderPalette();
        }

        function renderPalette() {
            const grid = document.getElementById('paletteGrid');
            grid.innerHTML = QUESTIONS.map((q, idx) => {
                const isAnswered = !!userAnswers[q.id];
                const isActive = idx === currentIndex;
                let cls = 'palette-btn';
                if (isActive) cls += ' active';
                else if (isAnswered) cls += ' answered';

                return `<button type="button" class="${cls}" onclick="jumpToQuestion(${idx})">${idx + 1}</button>`;
            }).join('');
        }

        function jumpToQuestion(idx) {
            currentIndex = idx;
            renderQuestion();
            renderPalette();
        }

        function prevQuestion() {
            if (currentIndex > 0) {
                currentIndex--;
                renderQuestion();
                renderPalette();
            }
        }

        function nextQuestion() {
            if (currentIndex < QUESTIONS.length - 1) {
                currentIndex++;
                renderQuestion();
                renderPalette();
            }
        }

        function confirmSubmit() {
            if (confirm('Are you sure you want to submit your examination answers?')) {
                submitExam('manual');
            }
        }

        function autoSubmit(reason) {
            alert(`Assessment auto-submitted: ${reason}`);
            submitExam('auto', reason);
        }

        async function submitExam(type = 'manual', reason = '') {
            const answersArray = Object.keys(userAnswers).map(qId => ({
                question_id: parseInt(qId),
                selected_option: userAnswers[qId]
            }));

            const payload = {
                answers: answersArray,
                submission_type: type,
                auto_submit_reason: reason,
                violations_count: violationsCount,
            };

            try {
                const res = await fetch(`/api/quizzes/${QUIZ_ID}/submit`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify(payload)
                });
                const json = await res.json();
                if (json.success && json.data && json.data.attempt) {
                    window.location.href = `/student/attempts/${json.data.attempt.id}/result`;
                } else {
                    alert(json.message || 'Submission failed');
                }
            } catch (e) {
                alert('Network error while submitting exam answers');
            }
        }
    </script>
</body>
</html>
