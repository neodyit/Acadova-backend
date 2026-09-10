<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acadova Admin Portal - Quiz Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6C5CE7;
            --primary-hover: #5B4BC4;
            --primary-light: #EEF2FF;
            --secondary: #00B894;
            --danger: #FF7675;
            --dark: #2D3436;
            --bg: #F8F9FA;
            --card-bg: #FFFFFF;
            --border: #E2E8F0;
            --text-muted: #64748B;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--dark);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: #FFFFFF;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            padding: 24px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 36px;
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), #a29bfe);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(108, 92, 231, 0.3);
        }

        .brand-text h2 {
            font-size: 20px;
            font-weight: 800;
            color: var(--dark);
            letter-spacing: -0.5px;
        }

        .brand-text span {
            font-size: 11px;
            font-weight: 600;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-menu {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .nav-item.active a, .nav-item a:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        /* Main Content */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .header {
            height: 70px;
            background: #FFFFFF;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 36px;
        }

        .header-title {
            font-size: 18px;
            font-weight: 700;
        }

        .user-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 14px;
            background: var(--bg);
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
        }

        .user-avatar {
            width: 28px;
            height: 28px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        .content {
            padding: 36px;
            flex: 1;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
        }

        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }

        .btn {
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 14px rgba(108, 92, 231, 0.3);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #EDF2F7;
            color: var(--dark);
        }

        .btn-secondary:hover {
            background: #E2E8F0;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            opacity: 0.9;
        }

        /* Quiz Cards Grid */
        .quiz-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 24px;
        }

        .quiz-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .quiz-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.06);
        }

        .quiz-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-active { background: #E6FFFA; color: #047857; }
        .status-upcoming { background: #FEF3C7; color: #B45309; }
        .status-completed { background: #EDF2F7; color: #475569; }

        .quiz-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 6px;
        }

        .quiz-subject {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 500;
            margin-bottom: 16px;
        }

        .quiz-meta {
            display: flex;
            gap: 16px;
            font-size: 13px;
            color: var(--text-muted);
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }

        .quiz-actions {
            display: flex;
            gap: 10px;
            margin-top: 16px;
        }

        .quiz-actions button {
            flex: 1;
            padding: 8px 12px;
            font-size: 13px;
        }

        /* Modal styling */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-container {
            background: white;
            border-radius: 20px;
            width: 100%;
            max-width: 650px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 32px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 800;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 20px;
            color: var(--text-muted);
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 6px;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 14px;
            outline: none;
            transition: border 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary);
        }

        .form-row {
            display: flex;
            gap: 16px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .options-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 8px;
        }

        .option-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .option-radio {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
        }

        .question-box {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 12px;
            position: relative;
        }

        .question-box .delete-q {
            position: absolute;
            top: 12px;
            right: 12px;
            color: var(--danger);
            cursor: pointer;
            background: none;
            border: none;
        }

        /* Toast notification */
        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: var(--dark);
            color: white;
            padding: 14px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 2000;
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }
    </style>
</head>
<body>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <div class="brand">
            <div class="brand-icon">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div class="brand-text">
                <h2>Acadova</h2>
                <span>Admin Portal</span>
            </div>
        </div>

        <ul class="nav-menu">
            <li class="nav-item active">
                <a href="#"><i class="fa-solid fa-list-check"></i> Quizzes & Exams</a>
            </li>
            <li class="nav-item">
                <a href="#"><i class="fa-solid fa-user-graduate"></i> Student Records</a>
            </li>
            <li class="nav-item">
                <a href="#"><i class="fa-solid fa-chart-line"></i> Analytics & Scores</a>
            </li>
            <li class="nav-item">
                <a href="#"><i class="fa-solid fa-gear"></i> Settings</a>
            </li>
        </ul>
    </div>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <div class="header">
            <div class="header-title">Quiz & Question Management</div>
            <div class="user-badge">
                <div class="user-avatar">A</div>
                <span>Administrator</span>
            </div>
        </div>

        <div class="content">
            <div class="action-bar">
                <div>
                    <h1 style="font-size: 24px; font-weight: 800; color: var(--dark);">Manage Quizzes</h1>
                    <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Create, edit, and publish dynamic quizzes for students.</p>
                </div>
                <button class="btn btn-primary" onclick="openCreateQuizModal()">
                    <i class="fa-solid fa-plus"></i> Create New Quiz
                </button>
            </div>

            <!-- Quiz Cards Grid -->
            <div class="quiz-grid" id="quizGrid">
                <!-- Rendered dynamically via JS -->
            </div>
        </div>
    </div>

    <!-- Create Quiz Modal -->
    <div class="modal-overlay" id="createQuizModal">
        <div class="modal-container">
            <div class="modal-header">
                <div class="modal-title">Create New Quiz</div>
                <button class="close-btn" onclick="closeModal('createQuizModal')">&times;</button>
            </div>
            <form id="createQuizForm" onsubmit="handleCreateQuiz(event)">
                <div class="form-group">
                    <label>Quiz Title</label>
                    <input type="text" id="quizTitle" class="form-control" placeholder="e.g. Operating Systems Scheduling Quiz" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Subject / Category</label>
                        <input type="text" id="quizSubject" class="form-control" placeholder="e.g. Computer Science" required>
                    </div>
                    <div class="form-group">
                        <label>Duration (Minutes)</label>
                        <input type="number" id="quizDuration" class="form-control" value="15" min="1" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select id="quizStatus" class="form-control">
                        <option value="active">Active (Available for Students)</option>
                        <option value="upcoming">Upcoming</option>
                        <option value="completed">Completed / Archived</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea id="quizDescription" class="form-control" rows="3" placeholder="Brief instructions for students..."></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createQuizModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save & Continue</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View & Add Questions Modal -->
    <div class="modal-overlay" id="manageQuestionsModal">
        <div class="modal-container" style="max-width: 750px;">
            <div class="modal-header">
                <div>
                    <div class="modal-title" id="manageModalQuizTitle">Manage Questions</div>
                    <span style="font-size: 13px; color: var(--text-muted);" id="manageModalQuizSub">General Science</span>
                </div>
                <button class="close-btn" onclick="closeModal('manageQuestionsModal')">&times;</button>
            </div>

            <!-- Existing Questions List -->
            <div id="questionsList"></div>

            <hr style="margin: 24px 0; border: none; border-top: 1px solid var(--border);">

            <!-- Add Question Form -->
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--primary);">
                <i class="fa-solid fa-plus-circle"></i> Add New Question
            </h3>
            <form id="addQuestionForm" onsubmit="handleAddQuestion(event)">
                <input type="hidden" id="activeQuizId">
                <div class="form-group">
                    <label>Question Text</label>
                    <input type="text" id="qText" class="form-control" placeholder="Enter question..." required>
                </div>

                <div class="form-group">
                    <label>Options (Select radio for correct answer)</label>
                    <div class="options-list">
                        <div class="option-item">
                            <input type="radio" name="correctOpt" value="0" class="option-radio" checked>
                            <input type="text" class="form-control q-opt" placeholder="Option 1" required>
                        </div>
                        <div class="option-item">
                            <input type="radio" name="correctOpt" value="1" class="option-radio">
                            <input type="text" class="form-control q-opt" placeholder="Option 2" required>
                        </div>
                        <div class="option-item">
                            <input type="radio" name="correctOpt" value="2" class="option-radio">
                            <input type="text" class="form-control q-opt" placeholder="Option 3" required>
                        </div>
                        <div class="option-item">
                            <input type="radio" name="correctOpt" value="4" class="option-radio">
                            <input type="text" class="form-control q-opt" placeholder="Option 4" required>
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Add Question</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast">Operation completed successfully!</div>

    <script>
        const API_BASE = '/api';
        let currentActiveQuiz = null;

        // Fetch Quizzes on load
        document.addEventListener('DOMContentLoaded', fetchQuizzes);

        async function fetchQuizzes() {
            try {
                const res = await fetch(`${API_BASE}/quizzes?status=all`);
                const json = await res.json();

                if (json.success) {
                    renderQuizzes(json.data);
                }
            } catch (err) {
                showToast('Failed to load quizzes');
            }
        }

        function renderQuizzes(quizzes) {
            const grid = document.getElementById('quizGrid');
            if (quizzes.length === 0) {
                grid.innerHTML = '<p style="color: var(--text-muted); font-size: 14px;">No quizzes found. Create your first quiz!</p>';
                return;
            }

            grid.innerHTML = quizzes.map(q => `
                <div class="quiz-card">
                    <div class="quiz-header">
                        <span class="status-badge status-${q.status}">${q.status}</span>
                        <button style="background:none; border:none; color:var(--danger); cursor:pointer;" onclick="deleteQuiz(${q.id})">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                    <div class="quiz-title">${escapeHtml(q.title)}</div>
                    <div class="quiz-subject">${escapeHtml(q.subject || 'General')}</div>
                    <div class="quiz-meta">
                        <span><i class="fa-solid fa-circle-question"></i> ${q.questions_count || 0} Questions</span>
                        <span><i class="fa-solid fa-clock"></i> ${q.duration_minutes} Mins</span>
                    </div>
                    <div class="quiz-actions">
                        <button class="btn btn-secondary" onclick="openManageQuestionsModal(${q.id})">
                            <i class="fa-solid fa-list"></i> Manage Questions
                        </button>
                    </div>
                </div>
            `).join('');
        }

        async function handleCreateQuiz(e) {
            e.preventDefault();
            const data = {
                title: document.getElementById('quizTitle').value,
                subject: document.getElementById('quizSubject').value,
                duration_minutes: parseInt(document.getElementById('quizDuration').value),
                status: document.getElementById('quizStatus').value,
                description: document.getElementById('quizDescription').value,
            };

            try {
                const res = await fetch(`${API_BASE}/quizzes`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const json = await res.json();

                if (json.success) {
                    showToast('Quiz created successfully!');
                    closeModal('createQuizModal');
                    document.getElementById('createQuizForm').reset();
                    fetchQuizzes();
                }
            } catch (err) {
                showToast('Error creating quiz');
            }
        }

        async function openManageQuestionsModal(quizId) {
            currentActiveQuiz = quizId;
            document.getElementById('activeQuizId').value = quizId;
            openModal('manageQuestionsModal');

            try {
                const res = await fetch(`${API_BASE}/quizzes/${quizId}`);
                const json = await res.json();

                if (json.success) {
                    const quiz = json.data;
                    document.getElementById('manageModalQuizTitle').innerText = quiz.title;
                    document.getElementById('manageModalQuizSub').innerText = quiz.subject || 'General';
                    renderQuestionsList(quiz.questions || []);
                }
            } catch (err) {
                showToast('Failed to load questions');
            }
        }

        function renderQuestionsList(questions) {
            const container = document.getElementById('questionsList');
            if (questions.length === 0) {
                container.innerHTML = '<p style="color: var(--text-muted); font-size: 13px;">No questions added to this quiz yet.</p>';
                return;
            }

            container.innerHTML = questions.map((q, idx) => `
                <div class="question-box">
                    <button class="delete-q" onclick="deleteQuestion(${q.id})"><i class="fa-solid fa-trash"></i></button>
                    <div style="font-weight: 700; font-size: 14px; margin-bottom: 8px;">Q${idx + 1}. ${escapeHtml(q.question)}</div>
                    <div style="font-size: 13px; color: var(--text-muted);">
                        ${(Array.isArray(q.options) ? q.options : JSON.parse(q.options)).map(opt => `
                            <span style="display:inline-block; margin-right:12px; padding: 2px 8px; background:white; border-radius:6px; border:1px solid #E2E8F0; ${opt === q.correct_option ? 'font-weight:bold; color:var(--secondary); border-color:var(--secondary);' : ''}">
                                ${escapeHtml(opt)} ${opt === q.correct_option ? '✓' : ''}
                            </span>
                        `).join('')}
                    </div>
                </div>
            `).join('');
        }

        async function handleAddQuestion(e) {
            e.preventDefault();
            const quizId = document.getElementById('activeQuizId').value;
            const qText = document.getElementById('qText').value;
            const optInputs = document.querySelectorAll('.q-opt');
            const options = Array.from(optInputs).map(inp => inp.value.trim()).filter(v => v !== '');
            const selectedRadioIdx = document.querySelector('input[name="correctOpt"]:checked').value;
            const correctOption = optInputs[selectedRadioIdx].value.trim();

            if (options.length < 2) {
                showToast('Please provide at least 2 options');
                return;
            }

            try {
                const res = await fetch(`${API_BASE}/quizzes/${quizId}/questions`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ question: qText, options: options, correct_option: correctOption })
                });
                const json = await res.json();

                if (json.success) {
                    showToast('Question added!');
                    document.getElementById('addQuestionForm').reset();
                    openManageQuestionsModal(quizId);
                    fetchQuizzes();
                }
            } catch (err) {
                showToast('Failed to add question');
            }
        }

        async function deleteQuestion(questionId) {
            if (!confirm('Delete this question?')) return;
            try {
                const res = await fetch(`${API_BASE}/questions/${questionId}`, { method: 'DELETE' });
                const json = await res.json();

                if (json.success) {
                    showToast('Question deleted');
                    openManageQuestionsModal(currentActiveQuiz);
                    fetchQuizzes();
                }
            } catch (err) {
                showToast('Error deleting question');
            }
        }

        async function deleteQuiz(quizId) {
            if (!confirm('Are you sure you want to delete this quiz and all its questions?')) return;
            try {
                const res = await fetch(`${API_BASE}/quizzes/${quizId}`, { method: 'DELETE' });
                const json = await res.json();

                if (json.success) {
                    showToast('Quiz deleted');
                    fetchQuizzes();
                }
            } catch (err) {
                showToast('Error deleting quiz');
            }
        }

        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }

        function showToast(msg) {
            const toast = document.getElementById('toast');
            toast.innerText = msg;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        function escapeHtml(text) {
            return String(text).replace(/[&<>"']/g, function(m) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
            });
        }
    </script>
</body>
</html>
