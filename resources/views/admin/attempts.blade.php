@extends('layouts.admin_layout')

@section('title', 'Quiz Attempts & Re-attempts Management - Acadova Admin')
@section('page_title', 'Quiz Attempts & Re-attempts Management')

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 style="font-size: 24px; font-weight: 800; color: var(--dark); margin-bottom: 4px;">Student Attempts & Re-attempt Authorization</h2>
        <p style="color: var(--text-muted); font-size: 14px;">View student quiz submissions, inspect anti-cheat logs, and export structured Excel marksheets per quiz.</p>
    </div>
    <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
        <select id="exportQuizSelect" class="form-control" style="width: 220px; font-weight: 600; font-size: 13px;">
            <option value="">-- Select Quiz to Export --</option>
        </select>
        <button class="btn btn-primary" onclick="exportSelectedQuizResults()" style="background: #10B981; border-color: #10B981; font-size: 13px;">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </button>
        <input type="text" id="attemptsSearchInput" onkeyup="filterAttempts()" class="form-control" placeholder="Search student or quiz..." style="width: 220px;">
        <button class="btn btn-secondary" onclick="loadAttempts()"><i class="fa-solid fa-rotate"></i> Refresh</button>
    </div>
</div>

<!-- Attempts Log Data Table Card -->
<div class="table-card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student Name & Email</th>
                    <th>Quiz Title</th>
                    <th>Score / Total</th>
                    <th>Type</th>
                    <th>Auto Reason / Violations</th>
                    <th>IP & Location</th>
                    <th>Submitted Date</th>
                    <th style="text-align: right;">Allow Re-attempt</th>
                </tr>
            </thead>
            <tbody id="attemptsTableBody">
                <tr>
                    <td colspan="9" style="text-align: center; padding: 40px; color: var(--text-muted);">
                        <i class="fa-solid fa-spinner fa-spin fa-2x"></i><br><br>Loading quiz attempts...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    let allAttempts = [];

    document.addEventListener('DOMContentLoaded', () => {
        loadAttempts();
        loadQuizOptions();
    });

    async function loadQuizOptions() {
        try {
            const res = await fetch('/api/quizzes');
            const json = await res.json();
            const quizzes = json.data || json || [];
            const select = document.getElementById('exportQuizSelect');
            if (select) {
                let html = '<option value="">-- Select Quiz to Export --</option>';
                quizzes.forEach(q => {
                    html += `<option value="${q.id}">${q.title} (${q.subject || 'General'})</option>`;
                });
                select.innerHTML = html;
            }
        } catch (e) {}
    }

    function exportSelectedQuizResults() {
        const select = document.getElementById('exportQuizSelect');
        const quizId = select ? select.value : '';
        if (!quizId) {
            alert('Please select a quiz from the dropdown to export its Excel report.');
            return;
        }
        window.location.href = `/neodyit/quizzes/${quizId}/export-excel`;
    }

    async function loadAttempts() {
        const tbody = document.getElementById('attemptsTableBody');
        tbody.innerHTML = `<tr><td colspan="9" style="text-align: center; padding: 40px; color: var(--text-muted);"><i class="fa-solid fa-spinner fa-spin fa-2x"></i><br><br>Loading quiz attempts...</td></tr>`;

        try {
            const res = await fetch('/api/admin/attempts');
            const json = await res.json();
            if (json.success) {
                allAttempts = json.data || [];
                renderAttemptsTable(allAttempts);
            } else {
                tbody.innerHTML = `<tr><td colspan="9" style="text-align: center; color: var(--danger); padding: 30px;">Failed to load attempts log.</td></tr>`;
            }
        } catch (e) {
            tbody.innerHTML = `<tr><td colspan="9" style="text-align: center; color: var(--danger); padding: 30px;">Network error loading attempts.</td></tr>`;
        }
    }

    function renderAttemptsTable(data) {
        const tbody = document.getElementById('attemptsTableBody');
        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="9" style="text-align: center; padding: 40px; color: var(--text-muted);">No student quiz attempts recorded yet.</td></tr>`;
            return;
        }

        tbody.innerHTML = data.map(att => {
            const isAuto = att.submission_type === 'auto';
            const typeBadge = isAuto 
                ? `<span style="background: #FFF5F5; color: #E53E3E; padding: 4px 10px; border-radius: 12px; font-weight: 800; font-size: 11px;">AUTO</span>`
                : `<span style="background: #E6FFFA; color: #047857; padding: 4px 10px; border-radius: 12px; font-weight: 800; font-size: 11px;">MANUAL</span>`;
            
            const reasonHtml = isAuto
                ? `<strong style="color: #E53E3E; font-size: 12px;">${att.auto_submit_reason || 'Time Up / Violation'}</strong><br><span style="font-size: 11px; color: var(--text-muted);">Switches / Violations: ${att.violations_count}</span>`
                : `<span style="color: var(--text-muted); font-size: 12px;">Standard Submission</span>`;

            const locationHtml = `
                <span style="font-size: 12px; font-weight: 600;">${att.ip_address || 'N/A'}</span><br>
                <span style="font-size: 11px; color: var(--text-muted);">📍 ${att.location || 'Unknown Location'}</span>
            `;

            return `
                <tr>
                    <td>#${att.id}</td>
                    <td>
                        <strong>${att.student_name}</strong><br>
                        <span style="font-size: 11.5px; color: var(--text-muted);">${att.student_email}</span>
                    </td>
                    <td><strong>${att.quiz_title}</strong></td>
                    <td>
                        <span style="background: #DEF7EC; color: #03543F; padding: 4px 10px; border-radius: 20px; font-weight: 800; font-size: 12px;">
                            ${att.score} / ${att.total_questions}
                        </span>
                    </td>
                    <td>${typeBadge}</td>
                    <td>${reasonHtml}</td>
                    <td>${locationHtml}</td>
                    <td>${att.created_at}</td>
                    <td style="text-align: right;">
                        <button class="btn btn-primary" style="padding: 6px 12px; font-size: 12px; background: #6C5CE7;" onclick="resetAttemptForUser(${att.id}, '${escapeQuotes(att.student_name)}', '${escapeQuotes(att.quiz_title)}')">
                            <i class="fa-solid fa-rotate-left"></i> Allow Re-attempt
                        </button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function filterAttempts() {
        const query = document.getElementById('attemptsSearchInput').value.toLowerCase().trim();
        if (!query) {
            renderAttemptsTable(allAttempts);
            return;
        }
        const filtered = allAttempts.filter(att => 
            (att.student_name && att.student_name.toLowerCase().includes(query)) ||
            (att.student_email && att.student_email.toLowerCase().includes(query)) ||
            (att.quiz_title && att.quiz_title.toLowerCase().includes(query)) ||
            (att.ip_address && att.ip_address.toLowerCase().includes(query)) ||
            (att.location && att.location.toLowerCase().includes(query))
        );
        renderAttemptsTable(filtered);
    }

    async function resetAttemptForUser(attemptId, studentName, quizTitle) {
        if (!confirm(`Are you sure you want to allow a RE-ATTEMPT for "${studentName}" on "${quizTitle}"?\n\nThis will delete attempt #${attemptId} from the database so the student can start fresh.`)) {
            return;
        }

        try {
            const res = await fetch(`/api/admin/attempts/${attemptId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
            const response = await res.json();
            if (res.ok && response.success) {
                showToast(`✅ Re-attempt granted for ${studentName}!`);
                loadAttempts();
            } else {
                showToast(`❌ ${response.message || 'Failed to reset attempt'}`);
            }
        } catch (e) {
            showToast('❌ Server error granting re-attempt.');
        }
    }

    function escapeQuotes(str) {
        return (str || '').replace(/'/g, "\\'");
    }
</script>
@endsection
