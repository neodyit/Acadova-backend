@extends('layouts.admin_layout')

@section('title', 'Quizzes Management - Acadova Admin')
@section('page_title', 'Quizzes Management')

@section('styles')
<style>
    .quiz-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 24px; }
    .quiz-card { background: white; border-radius: 18px; padding: 24px; border: 1px solid var(--border); display: flex; flex-direction: column; box-shadow: 0 2px 10px rgba(0,0,0,0.03); }
    .quiz-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px; }
    .quiz-title { font-size: 17px; font-weight: 800; color: var(--dark); margin-bottom: 6px; }
    .quiz-sub { font-size: 13px; color: var(--text-muted); margin-bottom: 18px; font-weight: 500; }
    .quiz-meta { display: flex; gap: 16px; font-size: 13px; color: var(--text-muted); margin-bottom: 20px; padding: 12px; background: var(--bg); border-radius: 12px; }
    .quiz-actions { margin-top: auto; display: flex; gap: 10px; }
    .quiz-actions .btn { flex: 1; justify-content: center; padding: 10px 14px; font-size: 13px; }
</style>
@endsection

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 800;">Quiz Assessments</h1>
        <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Create, update, and manage student quizzes and question banks.</p>
    </div>
    <button class="btn btn-primary" onclick="openCreateQuizModal()"><i class="fa-solid fa-plus"></i> Create New Quiz</button>
</div>

<div class="quiz-grid">
    @forelse($quizzes as $q)
        <div class="quiz-card">
            <div class="quiz-header">
                <span class="badge" style="padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 11px; {{ $q->status === 'active' ? 'background: #DEF7EC; color: #03543F;' : ($q->status === 'upcoming' ? 'background: #FEF08A; color: #713F12;' : 'background: #EDF2F7; color: #4A5568;') }}">{{ strtoupper($q->status) }}</span>
                <span style="font-size: 12px; font-weight: 700; color: var(--primary);">{{ $q->subject ?: 'General' }}</span>
            </div>
            <div class="quiz-title">{{ $q->title }}</div>
            <div class="quiz-sub">{{ $q->description ?: 'No description provided.' }}</div>
            <div class="quiz-meta">
                <span><i class="fa-regular fa-clock"></i> {{ $q->duration_minutes }} mins</span>
                <span><i class="fa-solid fa-list"></i> {{ $q->questions_count }} Questions</span>
            </div>
            <div class="quiz-actions">
                <button class="btn btn-secondary" onclick="openManageQuestionsModal({{ $q->id }}, '{{ addslashes($q->title) }}', '{{ addslashes($q->subject ?: 'General') }}')">Questions</button>
                <button class="btn btn-secondary" onclick="editQuiz({{ json_encode($q) }})"><i class="fa-solid fa-pen"></i></button>
                <button class="btn btn-danger" onclick="deleteQuiz({{ $q->id }})"><i class="fa-solid fa-trash"></i></button>
            </div>
        </div>
    @empty
        <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--text-muted);">No quizzes found. Click "Create New Quiz" to add one.</div>
    @endforelse
</div>

<!-- Modal: Create / Edit Quiz -->
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
                <input type="text" id="quizTitle" class="form-control" placeholder="e.g. Midterm Examination 2026" required>
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
                    <select id="quizStatus" class="form-control">
                        <option value="active">Active (Live for Students)</option>
                        <option value="upcoming">Upcoming (Scheduled)</option>
                        <option value="completed">Completed / Archived</option>
                    </select>
                </div>
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

<!-- Modal: Manage Questions & CSV Import -->
<div class="modal-overlay" id="manageQuestionsModal">
    <div class="modal-container" style="max-width: 800px;">
        <div class="modal-header">
            <div>
                <div class="modal-title" id="manageModalQuizTitle">Manage Quiz Questions</div>
                <div style="font-size: 13px; color: var(--primary); font-weight: 700; margin-top: 2px;" id="manageModalQuizSub">Subject</div>
            </div>
            <button class="close-btn" onclick="closeModal('manageQuestionsModal')">&times;</button>
        </div>

        <input type="hidden" id="activeQuizId">

        <!-- CSV Bulk Import Section -->
        <div style="background: #F8FAFC; border: 1px dashed var(--primary); border-radius: 14px; padding: 18px; margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <div style="font-weight: 800; font-size: 14px; color: var(--dark);"><i class="fa-solid fa-file-csv" style="color: var(--primary);"></i> Bulk Import Questions (CSV)</div>
                <a href="/sample-csv" class="btn btn-secondary" style="font-size: 11px; padding: 4px 10px;"><i class="fa-solid fa-download"></i> Sample CSV</a>
            </div>
            <form id="importCsvForm" onsubmit="handleImportCsv(event)" style="display: flex; gap: 10px; align-items: center;">
                <input type="file" id="csvFileInput" accept=".csv" class="form-control" style="padding: 8px; background: white;" required>
                <button type="submit" class="btn btn-primary" style="white-space: nowrap; font-size: 13px;"><i class="fa-solid fa-upload"></i> Import</button>
            </form>
        </div>

        <!-- Questions List Container -->
        <div id="questionsList" style="max-height: 350px; overflow-y: auto; margin-bottom: 24px;"></div>

        <!-- Add Question Form -->
        <div style="border-top: 1px solid var(--border); padding-top: 20px;">
            <h3 style="font-size: 15px; font-weight: 800; margin-bottom: 16px;" id="questionFormTitle">Add Single Question</h3>
            <form id="addQuestionForm" onsubmit="handleAddOrUpdateQuestion(event)">
                <input type="hidden" id="editingQuestionId">
                <div class="form-group">
                    <label>Question Text</label>
                    <textarea id="qText" class="form-control" rows="2" placeholder="e.g. What is the output of print(2**3) in Python?" required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Question Type</label>
                        <select id="qType" class="form-control" onchange="toggleQuestionTypeUI()">
                            <option value="single">Single Choice (1 Correct Option)</option>
                            <option value="multiple">Multiple Choice (Multiple Correct Options)</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group"><label>Option 1</label><input type="text" id="qOpt1" class="form-control" required></div>
                    <div class="form-group"><label>Option 2</label><input type="text" id="qOpt2" class="form-control" required></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>Option 3</label><input type="text" id="qOpt3" class="form-control" required></div>
                    <div class="form-group"><label>Option 4</label><input type="text" id="qOpt4" class="form-control" required></div>
                </div>

                <div class="form-group" id="singleChoiceGroup">
                    <label>Correct Option Number</label>
                    <select id="qCorrectSingle" class="form-control">
                        <option value="1">Option 1</option>
                        <option value="2">Option 2</option>
                        <option value="3">Option 3</option>
                        <option value="4">Option 4</option>
                    </select>
                </div>

                <div class="form-group" id="multiChoiceGroup" style="display: none;">
                    <label>Select All Correct Options</label>
                    <div style="display: flex; gap: 16px; margin-top: 8px;">
                        <label><input type="checkbox" value="1" class="multi-check"> Option 1</label>
                        <label><input type="checkbox" value="2" class="multi-check"> Option 2</label>
                        <label><input type="checkbox" value="3" class="multi-check"> Option 3</label>
                        <label><input type="checkbox" value="4" class="multi-check"> Option 4</label>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px;">
                    <button type="button" id="cancelEditQuestionBtn" class="btn btn-secondary" style="display: none;" onclick="cancelEditQuestion()">Cancel Edit</button>
                    <button type="submit" id="submitQuestionBtn" class="btn btn-primary">Add Question</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    window.currentQuestions = [];

    function openCreateQuizModal() {
        document.getElementById('editingQuizId').value = '';
        document.getElementById('createQuizForm').reset();
        document.getElementById('quizModalTitleText').innerText = 'Create New Quiz';
        openModal('createQuizModal');
    }

    function editQuiz(q) {
        document.getElementById('editingQuizId').value = q.id;
        document.getElementById('quizTitle').value = q.title || '';
        document.getElementById('quizSubject').value = q.subject || '';
        document.getElementById('quizInstructor').value = q.instructor || '';
        document.getElementById('quizDuration').value = q.duration_minutes || 15;
        document.getElementById('quizStatus').value = q.status || 'active';
        document.getElementById('quizDescription').value = q.description || '';
        document.getElementById('quizModalTitleText').innerText = 'Edit Quiz Details';
        openModal('createQuizModal');
    }

    async function handleSaveQuiz(e) {
        e.preventDefault();
        const quizId = document.getElementById('editingQuizId').value;
        const payload = {
            title: document.getElementById('quizTitle').value,
            subject: document.getElementById('quizSubject').value,
            instructor: document.getElementById('quizInstructor').value,
            duration_minutes: parseInt(document.getElementById('quizDuration').value),
            status: document.getElementById('quizStatus').value,
            description: document.getElementById('quizDescription').value,
        };

        const url = quizId ? `/api/quizzes/${quizId}` : '/api/quizzes';
        const method = quizId ? 'PUT' : 'POST';

        try {
            const res = await fetch(url, {
                method: method,
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN 
                },
                body: JSON.stringify(payload)
            });
            const json = await res.json();
            if (res.ok || json.id || json.success) {
                showToast(quizId ? 'Quiz updated successfully!' : 'Quiz created successfully!');
                closeModal('createQuizModal');
                location.reload();
            } else {
                showToast('Failed to save quiz');
            }
        } catch (err) {
            showToast('Error saving quiz');
        }
    }

    async function deleteQuiz(id) {
        if (!confirm('Are you sure you want to delete this quiz?')) return;
        try {
            const res = await fetch(`/api/quizzes/${id}`, { 
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN }
            });
            if (res.ok) {
                showToast('Quiz deleted');
                location.reload();
            }
        } catch (e) {
            showToast('Failed to delete quiz');
        }
    }

    async function openManageQuestionsModal(quizId, title, subject) {
        document.getElementById('activeQuizId').value = quizId;
        document.getElementById('manageModalQuizTitle').innerText = title;
        document.getElementById('manageModalQuizSub').innerText = subject;
        cancelEditQuestion();
        openModal('manageQuestionsModal');
        loadQuestionsList(quizId);
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    async function loadQuestionsList(quizId) {
        const container = document.getElementById('questionsList');
        container.innerHTML = '<div style="text-align: center; color: var(--text-muted);">Loading questions...</div>';
        try {
            const res = await fetch(`/api/quizzes/${quizId}`);
            const json = await res.json();
            const data = json.data || json;
            const questions = data.questions || [];
            window.currentQuestions = questions;

            if (questions.length === 0) {
                container.innerHTML = '<div style="text-align: center; color: var(--text-muted); padding: 20px;">No questions added yet.</div>';
                return;
            }

            container.innerHTML = questions.map((q, idx) => {
                const qText = q.question || q.question_text || 'No question text';
                let opts = q.options;
                if (typeof opts === 'string') {
                    try { opts = JSON.parse(opts); } catch(e) { opts = []; }
                }
                if (!Array.isArray(opts)) {
                    opts = [q.option_1, q.option_2, q.option_3, q.option_4].filter(Boolean);
                }

                let correctStr = '';
                if (Array.isArray(q.correct_option)) {
                    correctStr = q.correct_option.join(', ');
                } else if (typeof q.correct_option === 'object' && q.correct_option !== null) {
                    correctStr = Object.values(q.correct_option).join(', ');
                } else {
                    correctStr = q.correct_option || 'N/A';
                }

                return `
                    <div style="background: var(--bg); border-radius: 12px; padding: 16px; margin-bottom: 12px; border: 1px solid var(--border);">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                            <span style="font-weight: 800; font-size: 14px;">Q${idx + 1}. ${escapeHtml(qText)}</span>
                            <div style="display: flex; gap: 6px;">
                                <button class="btn btn-secondary" style="padding: 4px 8px; font-size: 11px;" onclick="editQuestion(${q.id})"><i class="fa-solid fa-pen"></i> Edit</button>
                                <button class="btn btn-danger" style="padding: 4px 8px; font-size: 11px;" onclick="deleteQuestion(${q.id})"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 6px;">Type: <strong>${q.type === 'multiple' ? 'Multiple Answers' : 'Single Answer'}</strong></div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 12.5px;">
                            ${opts.map((opt, i) => `<div>${i + 1}. ${escapeHtml(opt)}</div>`).join('')}
                        </div>
                        <div style="margin-top: 8px; font-weight: 700; color: var(--secondary); font-size: 12px;">Correct Option: ${escapeHtml(correctStr)}</div>
                    </div>
                `;
            }).join('');
        } catch (e) {
            container.innerHTML = '<div style="color: var(--danger);">Failed to load questions.</div>';
        }
    }

    function editQuestion(qId) {
        const q = window.currentQuestions ? window.currentQuestions.find(item => item.id == qId) : null;
        if (!q) return;

        document.getElementById('editingQuestionId').value = q.id;
        document.getElementById('qText').value = q.question || q.question_text || '';
        document.getElementById('qType').value = q.type || 'single';
        toggleQuestionTypeUI();

        let opts = q.options;
        if (typeof opts === 'string') {
            try { opts = JSON.parse(opts); } catch(e) { opts = []; }
        }
        if (!Array.isArray(opts)) {
            opts = [q.option_1, q.option_2, q.option_3, q.option_4];
        }

        document.getElementById('qOpt1').value = opts[0] || '';
        document.getElementById('qOpt2').value = opts[1] || '';
        document.getElementById('qOpt3').value = opts[2] || '';
        document.getElementById('qOpt4').value = opts[3] || '';

        if (q.type === 'multiple') {
            let correctArr = Array.isArray(q.correct_option) ? q.correct_option : [];
            if (typeof q.correct_option === 'string') {
                try { correctArr = JSON.parse(q.correct_option); } catch(e) { correctArr = [q.correct_option]; }
            }
            document.querySelectorAll('.multi-check').forEach(cb => {
                const val = cb.value; // '1', '2', '3', '4'
                const optText = opts[parseInt(val) - 1];
                cb.checked = correctArr.includes(val) || correctArr.includes(optText) || correctArr.includes(parseInt(val));
            });
        } else {
            let corr = q.correct_option;
            if (Array.isArray(corr)) corr = corr[0];
            let foundIdx = opts.findIndex(o => o === corr);
            if (foundIdx !== -1) {
                document.getElementById('qCorrectSingle').value = (foundIdx + 1).toString();
            } else if (['1','2','3','4'].includes(String(corr))) {
                document.getElementById('qCorrectSingle').value = String(corr);
            } else {
                document.getElementById('qCorrectSingle').value = '1';
            }
        }

        document.getElementById('questionFormTitle').innerText = 'Edit Question & Options';
        document.getElementById('submitQuestionBtn').innerText = 'Update Question';
        document.getElementById('cancelEditQuestionBtn').style.display = 'inline-block';
    }

    function cancelEditQuestion() {
        document.getElementById('editingQuestionId').value = '';
        document.getElementById('addQuestionForm').reset();
        document.getElementById('questionFormTitle').innerText = 'Add Single Question';
        document.getElementById('submitQuestionBtn').innerText = 'Add Question';
        document.getElementById('cancelEditQuestionBtn').style.display = 'none';
        toggleQuestionTypeUI();
    }

    async function handleAddOrUpdateQuestion(e) {
        e.preventDefault();
        const quizId = document.getElementById('activeQuizId').value;
        const qId = document.getElementById('editingQuestionId').value;
        const qType = document.getElementById('qType').value;

        const opt1 = document.getElementById('qOpt1').value.trim();
        const opt2 = document.getElementById('qOpt2').value.trim();
        const opt3 = document.getElementById('qOpt3').value.trim();
        const opt4 = document.getElementById('qOpt4').value.trim();
        const options = [opt1, opt2, opt3, opt4].filter(o => o !== '');

        if (options.length < 2) {
            showToast('Please enter at least 2 options');
            return;
        }

        let correctOption = null;
        if (qType === 'multiple') {
            const selectedIndices = Array.from(document.querySelectorAll('.multi-check:checked')).map(c => parseInt(c.value) - 1);
            if (selectedIndices.length === 0) { showToast('Select at least one correct option'); return; }
            correctOption = selectedIndices.map(idx => [opt1, opt2, opt3, opt4][idx]).filter(Boolean);
        } else {
            const singleVal = parseInt(document.getElementById('qCorrectSingle').value) - 1;
            correctOption = [opt1, opt2, opt3, opt4][singleVal] || opt1;
        }

        const payload = {
            question: document.getElementById('qText').value,
            type: qType,
            options: [opt1, opt2, opt3, opt4],
            correct_option: correctOption,
        };

        const url = qId ? `/api/questions/${qId}` : `/api/quizzes/${quizId}/questions`;
        const method = qId ? 'PUT' : 'POST';

        try {
            const res = await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: JSON.stringify(payload)
            });
            const json = await res.json();
            if (res.ok || json.success) {
                showToast(qId ? 'Question updated successfully!' : 'Question added successfully!');
                cancelEditQuestion();
                loadQuestionsList(quizId);
            } else {
                showToast(json.message || 'Failed to save question');
            }
        } catch (e) { showToast('Error saving question'); }
    }

    async function deleteQuestion(qId) {
        if (!confirm('Delete this question?')) return;
        const quizId = document.getElementById('activeQuizId').value;
        try {
            const res = await fetch(`/api/questions/${qId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF_TOKEN } });
            if (res.ok) {
                showToast('Question deleted');
                loadQuestionsList(quizId);
            }
        } catch (e) { showToast('Failed to delete question'); }
    }

    async function handleImportCsv(e) {
        e.preventDefault();
        const quizId = document.getElementById('activeQuizId').value;
        const csvInput = document.getElementById('csvFileInput');
        if (!csvInput.files || csvInput.files.length === 0) return;

        const formData = new FormData();
        formData.append('csv_file', csvInput.files[0]);

        try {
            const res = await fetch(`/api/quizzes/${quizId}/import-csv`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: formData
            });
            const json = await res.json();
            if (json.success) {
                showToast(`Imported ${json.imported_count} questions!`);
                csvInput.value = '';
                loadQuestionsList(quizId);
            } else {
                showToast(json.message || 'CSV import failed');
            }
        } catch (e) { showToast('CSV import error'); }
    }

    function toggleQuestionTypeUI() {
        const type = document.getElementById('qType').value;
        document.getElementById('singleChoiceGroup').style.display = (type === 'single') ? 'block' : 'none';
        document.getElementById('multiChoiceGroup').style.display = (type === 'multiple') ? 'block' : 'none';
    }
</script>
@endsection
