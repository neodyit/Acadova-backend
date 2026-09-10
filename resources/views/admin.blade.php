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
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 24px;
        }

        .quiz-card {
            background: white;
            border-radius: 18px;
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

        .quiz-schedule-badge {
            font-size: 12px;
            font-weight: 600;
            color: #D97706;
            background: #FFFBEB;
            padding: 6px 12px;
            border-radius: 8px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
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

        .option-radio, .option-checkbox {
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

        .q-type-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .q-type-single { background: #EEF2FF; color: #6C5CE7; }
        .q-type-multiple { background: #E6FFFA; color: #047857; }

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
                    <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Create, edit, and schedule dynamic single/multiple choice quizzes.</p>
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

    <!-- Create / Edit Quiz Modal -->
    <div class="modal-overlay" id="createQuizModal">
        <div class="modal-container">
            <div class="modal-header">
                <div class="modal-title" id="quizModalTitleText">Create New Quiz</div>
                <button class="close-btn" onclick="closeModal('createQuizModal')">&times;</button>
            </div>
            <form id="createQuizForm" onsubmit="handleSaveQuiz(event)">
                <input type="hidden" id="editingQuizId">
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
                        <label>Instructor Name</label>
                        <input type="text" id="quizInstructor" class="form-control" placeholder="e.g. Dr. Aman" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Duration (Minutes)</label>
                        <input type="number" id="quizDuration" class="form-control" value="15" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select id="quizStatus" class="form-control" onchange="toggleScheduledDateInput()">
                            <option value="active">Active (Live for Students)</option>
                            <option value="upcoming">Upcoming (Scheduled)</option>
                            <option value="completed">Completed / Archived</option>
                        </select>
                    </div>
                </div>

                <!-- AM / PM Date & Time Picker for Upcoming Quizzes -->
                <div class="form-group" id="scheduledGroup" style="display: none;">
                    <label><i class="fa-regular fa-clock"></i> Schedule Date & Time (12-Hour AM/PM)</label>
                    <input type="datetime-local" id="quizScheduledAt" class="form-control">
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea id="quizDescription" class="form-control" rows="3" placeholder="Brief instructions for students..."></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createQuizModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Quiz</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View & Add Questions Modal -->
    <div class="modal-overlay" id="manageQuestionsModal">
        <div class="modal-container" style="max-width: 800px; max-height: 90vh; overflow-y: auto;">
            <div class="modal-header">
                <div>
                    <div class="modal-title" id="manageModalQuizTitle">Manage Questions</div>
                    <span style="font-size: 13px; color: var(--text-muted);" id="manageModalQuizSub">General Science</span>
                </div>
                <button class="close-btn" onclick="closeModal('manageQuestionsModal')">&times;</button>
            </div>

            <!-- CSV Import Action Box -->
            <div style="background: var(--bg-light); border: 1px dashed var(--primary); border-radius: 12px; padding: 16px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                <div>
                    <div style="font-weight: 700; font-size: 14px; color: var(--dark);"><i class="fa-solid fa-file-csv" style="color: var(--primary);"></i> Bulk Import Questions via CSV</div>
                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">Upload a CSV file to add questions instantly. <a href="/sample-csv" download="sample_questions.csv" style="color: var(--primary); font-weight: 600; text-decoration: underline;"><i class="fa-solid fa-download"></i> Download Sample CSV</a></div>
                </div>
                <form id="csvImportForm" style="display: flex; gap: 8px; align-items: center;" onsubmit="handleImportCsv(event)">
                    <input type="file" id="csvFileInput" accept=".csv" class="form-control" style="padding: 6px 12px; font-size: 12px; max-width: 220px;" required>
                    <button type="submit" class="btn btn-secondary" style="font-size: 12px; padding: 8px 16px;"><i class="fa-solid fa-upload"></i> Import CSV</button>
                </form>
            </div>

            <!-- Existing Questions List -->
            <div id="questionsList"></div>

            <hr style="margin: 24px 0; border: none; border-top: 1px solid var(--border);">

            <!-- Add Question Form -->
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--primary);" id="questionFormHeader">
                <i class="fa-solid fa-plus-circle"></i> Add New Question
            </h3>
            <form id="addQuestionForm" onsubmit="handleAddOrUpdateQuestion(event)">
                <input type="hidden" id="activeQuizId">
                <input type="hidden" id="editingQuestionId">

                <div class="form-row">
                    <div class="form-group" style="flex: 2;">
                        <label>Question Text</label>
                        <input type="text" id="qText" class="form-control" placeholder="Enter question..." required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Question Type</label>
                        <select id="qType" class="form-control" onchange="renderOptionInputs()">
                            <option value="single">Single Choice (1 Correct)</option>
                            <option value="multiple">Multiple Choice (Multiple Correct)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label id="optionsLabelText">Options (Select radio for correct answer)</label>
                    <div class="options-list" id="optionsContainer">
                        <!-- Option Inputs Rendered via JS -->
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px;">
                    <button type="button" id="cancelEditQBtn" class="btn btn-secondary" style="display: none;" onclick="resetQuestionForm()"><i class="fa-solid fa-xmark"></i> Cancel Edit</button>
                    <button type="submit" id="saveQBtn" class="btn btn-primary"><i class="fa-solid fa-check"></i> Add Question</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast">Operation completed successfully!</div>

    <script>
        const API_BASE = 'https://acadova.neodyit.com/api';
        let currentActiveQuiz = null;
        let allFetchedQuizzes = [];

        document.addEventListener('DOMContentLoaded', () => {
            fetchQuizzes();
            renderOptionInputs();
        });

        function toggleScheduledDateInput() {
            const status = document.getElementById('quizStatus').value;
            const group = document.getElementById('scheduledGroup');
            group.style.display = status === 'upcoming' ? 'block' : 'none';
        }

        async function fetchQuizzes() {
            try {
                const res = await fetch(`${API_BASE}/quizzes?status=all`);
                const json = await res.json();

                if (json.success) {
                    allFetchedQuizzes = json.data;
                    renderQuizzes(json.data);
                }
            } catch (err) {
                showToast('Failed to load quizzes');
            }
        }

        function formatDateTimeAmPm(dateStr) {
            if (!dateStr) return '';
            // Parse UTC or local string properly
            const d = new Date(dateStr.includes('Z') || dateStr.includes('+') ? dateStr : dateStr.replace(' ', 'T') + 'Z');
            let hours = d.getHours();
            const minutes = (d.getMinutes() < 10 ? '0' : '') + d.getMinutes();
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            const day = d.getDate();
            const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            return `${day} ${monthNames[d.getMonth()]} ${d.getFullYear()}, ${hours}:${minutes} ${ampm}`;
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
                        <div style="display:flex; gap:8px;">
                            <button style="background:none; border:none; color:var(--primary); cursor:pointer;" onclick="openEditQuizModal(${q.id})">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button style="background:none; border:none; color:var(--danger); cursor:pointer;" onclick="deleteQuiz(${q.id})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="quiz-title">${escapeHtml(q.title)}</div>
                    <div class="quiz-subject">${escapeHtml(q.subject || 'General')} • ${escapeHtml(q.instructor || 'Faculty')}</div>

                    ${q.scheduled_at ? `
                        <div class="quiz-schedule-badge">
                            <i class="fa-regular fa-calendar-check"></i> ${formatDateTimeAmPm(q.scheduled_at)}
                        </div>
                    ` : ''}

                    <div class="quiz-meta">
                        <span><i class="fa-solid fa-circle-question"></i> ${q.questions_count || 0} Qs</span>
                        <span><i class="fa-solid fa-clock"></i> ${q.duration_minutes} Mins</span>
                    </div>
                    <div class="quiz-actions">
                        <button class="btn btn-secondary" onclick="openManageQuestionsModal(${q.id})">
                            <i class="fa-solid fa-list"></i> Questions
                        </button>
                    </div>
                </div>
            `).join('');
        }

        function openCreateQuizModal() {
            document.getElementById('editingQuizId').value = '';
            document.getElementById('quizModalTitleText').innerText = 'Create New Quiz';
            document.getElementById('createQuizForm').reset();
            toggleScheduledDateInput();
            openModal('createQuizModal');
        }

        function openEditQuizModal(quizId) {
            const quiz = allFetchedQuizzes.find(q => q.id === quizId);
            if (!quiz) return;

            document.getElementById('editingQuizId').value = quiz.id;
            document.getElementById('quizModalTitleText').innerText = 'Edit Quiz';
            document.getElementById('quizTitle').value = quiz.title;
            document.getElementById('quizSubject').value = quiz.subject || '';
            document.getElementById('quizInstructor').value = quiz.instructor || '';
            document.getElementById('quizDuration').value = quiz.duration_minutes;
            document.getElementById('quizStatus').value = quiz.status;
            document.getElementById('quizDescription').value = quiz.description || '';

            if (quiz.scheduled_at) {
                const d = new Date(quiz.scheduled_at);
                const year = d.getFullYear();
                const month = String(d.getMonth() + 1).padStart(2, '0');
                const day = String(d.getDate()).padStart(2, '0');
                const hours = String(d.getHours()).padStart(2, '0');
                const minutes = String(d.getMinutes()).padStart(2, '0');
                document.getElementById('quizScheduledAt').value = `${year}-${month}-${day}T${hours}:${minutes}`;
            } else {
                document.getElementById('quizScheduledAt').value = '';
            }

            toggleScheduledDateInput();
            openModal('createQuizModal');
        }

        async function handleSaveQuiz(e) {
            e.preventDefault();
            const editId = document.getElementById('editingQuizId').value;
            const scheduledVal = document.getElementById('quizScheduledAt').value;

            let formattedScheduled = null;
            if (scheduledVal) {
                // Convert datetime-local value (YYYY-MM-DDTHH:mm) to MySQL DATETIME format (YYYY-MM-DD HH:mm:ss)
                formattedScheduled = scheduledVal.replace('T', ' ') + ':00';
            }

            const data = {
                title: document.getElementById('quizTitle').value,
                subject: document.getElementById('quizSubject').value,
                instructor: document.getElementById('quizInstructor').value,
                duration_minutes: parseInt(document.getElementById('quizDuration').value),
                status: document.getElementById('quizStatus').value,
                scheduled_at: formattedScheduled,
                description: document.getElementById('quizDescription').value,
            };

            const url = editId ? `${API_BASE}/quizzes/${editId}` : `${API_BASE}/quizzes`;
            const method = editId ? 'PUT' : 'POST';

            try {
                const res = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const json = await res.json();

                if (json.success) {
                    showToast(editId ? 'Quiz updated successfully!' : 'Quiz created successfully!');
                    closeModal('createQuizModal');
                    document.getElementById('createQuizForm').reset();
                    fetchQuizzes();
                }
            } catch (err) {
                showToast('Error saving quiz');
            }
        }

        function renderOptionInputs() {
            const qType = document.getElementById('qType').value;
            const container = document.getElementById('optionsContainer');
            const label = document.getElementById('optionsLabelText');

            if (qType === 'multiple') {
                label.innerText = 'Options (Check checkbox for all correct answers)';
                container.innerHTML = [0, 1, 2, 3].map(i => `
                    <div class="option-item">
                        <input type="checkbox" name="correctOptMulti" value="${i}" class="option-checkbox">
                        <input type="text" class="form-control q-opt" placeholder="Option ${i + 1}" required>
                    </div>
                `).join('');
            } else {
                label.innerText = 'Options (Select radio for correct answer)';
                container.innerHTML = [0, 1, 2, 3].map(i => `
                    <div class="option-item">
                        <input type="radio" name="correctOptSingle" value="${i}" class="option-radio" ${i === 0 ? 'checked' : ''}>
                        <input type="text" class="form-control q-opt" placeholder="Option ${i + 1}" required>
                    </div>
                `).join('');
            }
        }

        let currentQuizQuestions = [];

        async function openManageQuestionsModal(quizId) {
            currentActiveQuiz = quizId;
            document.getElementById('activeQuizId').value = quizId;
            resetQuestionForm();
            openModal('manageQuestionsModal');

            try {
                const res = await fetch(`${API_BASE}/quizzes/${quizId}`);
                const json = await res.json();

                if (json.success) {
                    const quiz = json.data;
                    document.getElementById('manageModalQuizTitle').innerText = quiz.title;
                    document.getElementById('manageModalQuizSub').innerText = `${quiz.subject || 'General'} • ${quiz.instructor || 'Faculty'}`;
                    currentQuizQuestions = quiz.questions || [];
                    renderQuestionsList(currentQuizQuestions);
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

            container.innerHTML = questions.map((q, idx) => {
                const isMulti = q.type === 'multiple';
                const opts = Array.isArray(q.options) ? q.options : JSON.parse(q.options);
                
                let correctArr = [];
                if (Array.isArray(q.correct_option)) {
                    correctArr = q.correct_option;
                } else if (typeof q.correct_option === 'string') {
                    try {
                        const parsed = JSON.parse(q.correct_option);
                        correctArr = Array.isArray(parsed) ? parsed : [q.correct_option];
                    } catch (_) {
                        correctArr = [q.correct_option];
                    }
                } else {
                    correctArr = [q.correct_option];
                }

                return `
                    <div class="question-box">
                        <div style="position: absolute; top: 16px; right: 16px; display: flex; gap: 8px;">
                            <button style="background:none; border:none; color:var(--primary); cursor:pointer; font-size: 14px;" onclick="populateEditQuestion(${q.id})">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>
                            <button class="delete-q" style="position:static;" onclick="deleteQuestion(${q.id})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                        <span class="q-type-badge ${isMulti ? 'q-type-multiple' : 'q-type-single'}">
                            ${isMulti ? 'Multiple Choice' : 'Single Choice'}
                        </span>
                        <div style="font-weight: 700; font-size: 14px; margin-bottom: 8px; padding-right: 90px;">Q${idx + 1}. ${escapeHtml(q.question)}</div>
                        <div style="font-size: 13px; color: var(--text-muted);">
                            ${opts.map(opt => {
                                const isCorrect = correctArr.includes(opt);
                                return `
                                    <span style="display:inline-block; margin-right:10px; margin-bottom:6px; padding: 3px 10px; background:white; border-radius:6px; border:1px solid #E2E8F0; ${isCorrect ? 'font-weight:bold; color:var(--secondary); border-color:var(--secondary); background:#E6FFFA;' : ''}">
                                        ${escapeHtml(opt)} ${isCorrect ? '✓' : ''}
                                    </span>
                                `;
                            }).join('')}
                        </div>
                    </div>
                `;
            }).join('');
        }

        function populateEditQuestion(questionId) {
            const q = currentQuizQuestions.find(item => item.id === questionId);
            if (!q) return;

            document.getElementById('editingQuestionId').value = q.id;
            document.getElementById('questionFormHeader').innerHTML = '<i class="fa-solid fa-pen-to-square"></i> Edit Question';
            document.getElementById('qText').value = q.question;
            document.getElementById('qType').value = q.type;
            document.getElementById('cancelEditQBtn').style.display = 'inline-block';
            document.getElementById('saveQBtn').innerHTML = '<i class="fa-solid fa-check"></i> Update Question';

            const opts = Array.isArray(q.options) ? q.options : JSON.parse(q.options);
            let correctArr = [];
            if (Array.isArray(q.correct_option)) {
                correctArr = q.correct_option;
            } else if (typeof q.correct_option === 'string') {
                try {
                    const parsed = JSON.parse(q.correct_option);
                    correctArr = Array.isArray(parsed) ? parsed : [q.correct_option];
                } catch (_) {
                    correctArr = [q.correct_option];
                }
            } else {
                correctArr = [q.correct_option];
            }

            renderOptionInputs();

            const optInputs = document.querySelectorAll('.q-opt');
            opts.forEach((optVal, idx) => {
                if (optInputs[idx]) {
                    optInputs[idx].value = optVal;
                }
            });

            if (q.type === 'multiple') {
                const checkboxes = document.querySelectorAll('input[name="correctOptMulti"]');
                checkboxes.forEach((cb) => {
                    const optVal = optInputs[cb.value] ? optInputs[cb.value].value.trim() : '';
                    cb.checked = correctArr.includes(optVal);
                });
            } else {
                const radios = document.querySelectorAll('input[name="correctOptSingle"]');
                radios.forEach((r) => {
                    const optVal = optInputs[r.value] ? optInputs[r.value].value.trim() : '';
                    r.checked = correctArr.includes(optVal) || (correctArr.length > 0 && correctArr[0] === optVal);
                });
            }

            // Scroll form into view
            document.getElementById('addQuestionForm').scrollIntoView({ behavior: 'smooth' });
        }

        function resetQuestionForm() {
            document.getElementById('editingQuestionId').value = '';
            document.getElementById('questionFormHeader').innerHTML = '<i class="fa-solid fa-plus-circle"></i> Add New Question';
            document.getElementById('addQuestionForm').reset();
            document.getElementById('cancelEditQBtn').style.display = 'none';
            document.getElementById('saveQBtn').innerHTML = '<i class="fa-solid fa-check"></i> Add Question';
            renderOptionInputs();
        }

        async function handleAddOrUpdateQuestion(e) {
            e.preventDefault();
            const quizId = document.getElementById('activeQuizId').value;
            const editQId = document.getElementById('editingQuestionId').value;
            const qText = document.getElementById('qText').value;
            const qType = document.getElementById('qType').value;
            const optInputs = document.querySelectorAll('.q-opt');
            const options = Array.from(optInputs).map(inp => inp.value.trim()).filter(v => v !== '');

            let correctOption;
            if (qType === 'multiple') {
                const checkedBoxes = document.querySelectorAll('input[name="correctOptMulti"]:checked');
                if (checkedBoxes.length === 0) {
                    showToast('Please select at least 1 correct answer for multiple choice');
                    return;
                }
                correctOption = Array.from(checkedBoxes).map(cb => optInputs[cb.value].value.trim());
            } else {
                const selectedRadio = document.querySelector('input[name="correctOptSingle"]:checked');
                correctOption = optInputs[selectedRadio.value].value.trim();
            }

            if (options.length < 2) {
                showToast('Please provide at least 2 options');
                return;
            }

            const url = editQId ? `${API_BASE}/questions/${editQId}` : `${API_BASE}/quizzes/${quizId}/questions`;
            const method = editQId ? 'PUT' : 'POST';

            try {
                const res = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        question: qText,
                        type: qType,
                        options: options,
                        correct_option: correctOption,
                    })
                });
                const json = await res.json();

                if (json.success) {
                    showToast(editQId ? 'Question updated successfully!' : 'Question added successfully!');
                    resetQuestionForm();
                    openManageQuestionsModal(quizId);
                    fetchQuizzes();
                }
            } catch (err) {
                showToast('Failed to save question');
            }
        }

        async function handleImportCsv(e) {
            e.preventDefault();
            const quizId = document.getElementById('activeQuizId').value;
            const fileInput = document.getElementById('csvFileInput');
            if (!fileInput.files || fileInput.files.length === 0) {
                showToast('Please select a CSV file first');
                return;
            }

            const formData = new FormData();
            formData.append('csv_file', fileInput.files[0]);

            try {
                showToast('Uploading and parsing CSV...');
                const res = await fetch(`${API_BASE}/quizzes/${quizId}/import-csv`, {
                    method: 'POST',
                    body: formData
                });
                const json = await res.json();

                if (json.success) {
                    showToast(json.message || 'Questions imported successfully!');
                    document.getElementById('csvImportForm').reset();
                    openManageQuestionsModal(quizId);
                    fetchQuizzes();
                } else {
                    showToast(json.message || 'Failed to import CSV');
                }
            } catch (err) {
                showToast('Error importing CSV file');
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
