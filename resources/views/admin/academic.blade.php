@extends('layouts.admin_layout')

@section('title', 'Academic Structure Management - Acadova Admin')
@section('page_title', 'Academic Structure Management')

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 style="font-size: 24px; font-weight: 800; color: var(--dark); margin-bottom: 4px;">Academic Entities Hierarchy</h2>
        <p style="color: var(--text-muted); font-size: 14px;">Manage Universities, Colleges, Departments, Courses, Branches, Subjects, Sections & Subsections with full CRUD operations.</p>
    </div>
    <button class="btn btn-primary" onclick="openCreateModal()">
        <i class="fa-solid fa-plus"></i> Add New Record
    </button>
</div>

<!-- Tab Navigation for Academic Entities -->
<div style="display: flex; gap: 8px; border-bottom: 2px solid var(--border); margin-bottom: 24px; overflow-x: auto; padding-bottom: 4px;">
    <button class="tab-btn active" data-entity="universities" onclick="switchEntity('universities', this)"><i class="fa-solid fa-building-columns"></i> Universities</button>
    <button class="tab-btn" data-entity="colleges" onclick="switchEntity('colleges', this)"><i class="fa-solid fa-school"></i> Colleges</button>
    <button class="tab-btn" data-entity="departments" onclick="switchEntity('departments', this)"><i class="fa-solid fa-diagram-project"></i> Departments</button>
    <button class="tab-btn" data-entity="courses" onclick="switchEntity('courses', this)"><i class="fa-solid fa-graduation-cap"></i> Courses</button>
    <button class="tab-btn" data-entity="branches" onclick="switchEntity('branches', this)"><i class="fa-solid fa-code-branch"></i> Branches</button>
    <button class="tab-btn" data-entity="subjects" onclick="switchEntity('subjects', this)"><i class="fa-solid fa-book-open"></i> Subjects</button>
    <button class="tab-btn" data-entity="sections" onclick="switchEntity('sections', this)"><i class="fa-solid fa-users-rectangle"></i> Sections</button>
    <button class="tab-btn" data-entity="subsections" onclick="switchEntity('subsections', this)"><i class="fa-solid fa-user-group"></i> Subsections</button>
</div>

<!-- Main Data Table Container -->
<div class="table-card">
    <div class="table-responsive">
        <table id="academicTable">
            <thead>
                <tr id="tableHeader">
                    <!-- Dynamically injected columns -->
                </tr>
            </thead>
            <tbody id="tableBody">
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                        <i class="fa-solid fa-spinner fa-spin fa-2x"></i><br><br>Loading data...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Form for Create / Edit -->
<div class="modal-overlay" id="crudModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Add New Entity</h3>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>
        <form id="crudForm" onsubmit="handleFormSubmit(event)">
            <input type="hidden" id="entityId" value="">
            <div id="dynamicFormFields">
                <!-- Dynamically generated fields -->
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" id="saveBtn">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Toast notification element -->
<div id="toast" class="toast"></div>

<style>
    .tab-btn {
        padding: 10px 18px;
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        font-size: 14px;
        font-weight: 700;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .tab-btn.active, .tab-btn:hover {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }
    .status-pill {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
    }
    .status-pill.active { background: #E6FFFA; color: #047857; }
    .status-pill.inactive { background: #FFF5F5; color: #E53E3E; }
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-right: 4px;
    }
    .action-edit { background: #EEF2FF; color: var(--primary); }
    .action-edit:hover { background: var(--primary); color: white; }
    .action-delete { background: #FFF5F5; color: var(--danger); }
    .action-delete:hover { background: var(--danger); color: white; }
</style>

<script>
    let currentEntity = 'universities';
    let entityData = [];
    let lookupOptions = {
        universities: [],
        colleges: [],
        departments: [],
        courses: [],
        branches: [],
        sections: []
    };

    document.addEventListener('DOMContentLoaded', () => {
        loadLookups();
        switchEntity('universities');
    });

    async function loadLookups() {
        try {
            const [uRes, cRes, dRes, crRes, bRes, sRes] = await Promise.all([
                fetch('/api/academic/universities').then(r => r.json()),
                fetch('/api/academic/colleges').then(r => r.json()),
                fetch('/api/academic/departments').then(r => r.json()),
                fetch('/api/academic/courses').then(r => r.json()),
                fetch('/api/academic/branches').then(r => r.json()),
                fetch('/api/academic/sections').then(r => r.json())
            ]);
            if (uRes.success) lookupOptions.universities = uRes.data;
            if (cRes.success) lookupOptions.colleges = cRes.data;
            if (dRes.success) lookupOptions.departments = dRes.data;
            if (crRes.success) lookupOptions.courses = crRes.data;
            if (bRes.success) lookupOptions.branches = bRes.data;
            if (sRes.success) lookupOptions.sections = sRes.data;
        } catch (e) {
            console.error('Lookup load error', e);
        }
    }

    async function switchEntity(entity, btnElement = null) {
        currentEntity = entity;
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        
        if (btnElement) {
            btnElement.classList.add('active');
        } else {
            const targetBtn = document.querySelector(`.tab-btn[data-entity="${entity}"]`);
            if (targetBtn) targetBtn.classList.add('active');
        }

        renderTableHeader();
        fetchEntityData();
    }

    function renderTableHeader() {
        const header = document.getElementById('tableHeader');
        let cols = [];

        switch (currentEntity) {
            case 'universities':
                cols = ['ID', 'Name', 'Code', 'State', 'City', 'Status', 'Colleges Count', 'Actions'];
                break;
            case 'colleges':
                cols = ['ID', 'College Name', 'University', 'Code', 'City', 'Status', 'Actions'];
                break;
            case 'departments':
                cols = ['ID', 'Department Name', 'College', 'Code', 'Status', 'Actions'];
                break;
            case 'courses':
                cols = ['ID', 'Course Name', 'Department', 'Code', 'Duration (Years)', 'Status', 'Actions'];
                break;
            case 'branches':
                cols = ['ID', 'Branch / Spec', 'Course', 'Code', 'Status', 'Actions'];
                break;
            case 'subjects':
                cols = ['ID', 'Subject Name', 'Branch', 'Code', 'Semester', 'Status', 'Actions'];
                break;
            case 'sections':
                cols = ['ID', 'Section Name', 'Branch', 'Academic Year', 'Status', 'Subsections', 'Actions'];
                break;
            case 'subsections':
                cols = ['ID', 'Subsection / Batch', 'Parent Section', 'Status', 'Actions'];
                break;
        }

        header.innerHTML = cols.map(c => `<th>${c}</th>`).join('');
    }

    async function fetchEntityData() {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; padding:40px; color:var(--text-muted);"><i class="fa-solid fa-spinner fa-spin fa-2x"></i><br><br>Loading ${currentEntity}...</td></tr>`;

        try {
            const res = await fetch(`/api/academic/${currentEntity}`);
            const json = await res.json();
            if (json.success) {
                entityData = json.data;
                renderTableRows();
            } else {
                tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; color:var(--danger); padding:20px;">Failed to load data.</td></tr>`;
            }
        } catch (err) {
            tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; color:var(--danger); padding:20px;">Error fetching ${currentEntity}.</td></tr>`;
        }
    }

    function renderTableRows() {
        const tbody = document.getElementById('tableBody');
        if (entityData.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; padding:40px; color:var(--text-muted);">No records found for ${currentEntity}. Click "Add New Record" to create one.</td></tr>`;
            return;
        }

        tbody.innerHTML = entityData.map(item => {
            const statusClass = item.status === 'active' ? 'active' : 'inactive';
            const statusBadge = `<span class="status-pill ${statusClass}">${item.status || 'active'}</span>`;
            const actions = `
                <button class="action-btn action-edit" onclick="openEditModal(${item.id})"><i class="fa-solid fa-pen"></i></button>
                <button class="action-btn action-delete" onclick="deleteRecord(${item.id})"><i class="fa-solid fa-trash"></i></button>
            `;

            switch (currentEntity) {
                case 'universities':
                    return `<tr><td>#${item.id}</td><td><strong>${item.name}</strong></td><td>${item.code || '-'}</td><td>${item.state || '-'}</td><td>${item.city || '-'}</td><td>${statusBadge}</td><td>${item.colleges_count || 0}</td><td>${actions}</td></tr>`;
                case 'colleges':
                    return `<tr><td>#${item.id}</td><td><strong>${item.name}</strong></td><td>${item.university?.name || '-'}</td><td>${item.code || '-'}</td><td>${item.city || '-'}</td><td>${statusBadge}</td><td>${actions}</td></tr>`;
                case 'departments':
                    return `<tr><td>#${item.id}</td><td><strong>${item.name}</strong></td><td>${item.college?.name || '-'}</td><td>${item.code || '-'}</td><td>${statusBadge}</td><td>${actions}</td></tr>`;
                case 'courses':
                    return `<tr><td>#${item.id}</td><td><strong>${item.name}</strong></td><td>${item.department?.name || '-'}</td><td>${item.code || '-'}</td><td>${item.duration_years} Years</td><td>${statusBadge}</td><td>${actions}</td></tr>`;
                case 'branches':
                    return `<tr><td>#${item.id}</td><td><strong>${item.name}</strong></td><td>${item.course?.name || '-'}</td><td>${item.code || '-'}</td><td>${statusBadge}</td><td>${actions}</td></tr>`;
                case 'subjects':
                    return `<tr><td>#${item.id}</td><td><strong>${item.name}</strong></td><td>${item.branch?.name || '-'}</td><td>${item.code || '-'}</td><td>Sem ${item.semester || '-'}</td><td>${statusBadge}</td><td>${actions}</td></tr>`;
                case 'sections':
                    return `<tr><td>#${item.id}</td><td><strong>${item.name}</strong></td><td>${item.branch?.name || '-'}</td><td>${item.academic_year || '-'}</td><td>${statusBadge}</td><td>${item.subsections_count || 0}</td><td>${actions}</td></tr>`;
                case 'subsections':
                    return `<tr><td>#${item.id}</td><td><strong>${item.name}</strong></td><td>${item.section?.name || '-'}</td><td>${statusBadge}</td><td>${actions}</td></tr>`;
            }
        }).join('');
    }

    function openCreateModal() {
        document.getElementById('modalTitle').innerText = `Add New ${singularize(currentEntity)}`;
        document.getElementById('entityId').value = '';
        generateFormFields();
        document.getElementById('crudModal').classList.add('active');
    }

    function openEditModal(id) {
        const item = entityData.find(x => x.id === id);
        if (!item) return;

        document.getElementById('modalTitle').innerText = `Edit ${singularize(currentEntity)} #${id}`;
        document.getElementById('entityId').value = id;
        generateFormFields(item);
        document.getElementById('crudModal').classList.add('active');
    }

    function closeModal() {
        document.getElementById('crudModal').classList.remove('active');
    }

    function generateFormFields(data = {}) {
        const container = document.getElementById('dynamicFormFields');
        let fields = '';

        const statusSelect = `
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="active" ${data.status === 'active' || !data.status ? 'selected' : ''}>Active</option>
                    <option value="inactive" ${data.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                </select>
            </div>
        `;

        switch (currentEntity) {
            case 'universities':
                fields = `
                    <div class="form-group"><label>University Name *</label><input type="text" name="name" class="form-control" value="${data.name || ''}" required placeholder="e.g. Harvard University"></div>
                    <div class="form-row">
                        <div class="form-group"><label>University Code</label><input type="text" name="code" class="form-control" value="${data.code || ''}" placeholder="e.g. HARV01"></div>
                        <div class="form-group"><label>State</label><input type="text" name="state" class="form-control" value="${data.state || ''}" placeholder="e.g. Massachusetts"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label>City</label><input type="text" name="city" class="form-control" value="${data.city || ''}" placeholder="e.g. Cambridge"></div>
                        ${statusSelect}
                    </div>
                `;
                break;
            case 'colleges':
                fields = `
                    <div class="form-group"><label>College Name *</label><input type="text" name="name" class="form-control" value="${data.name || ''}" required placeholder="e.g. Faculty of Arts and Sciences"></div>
                    <div class="form-group">
                        <label>Belongs to University *</label>
                        <select name="university_id" class="form-control" required>
                            <option value="">-- Select University --</option>
                            ${lookupOptions.universities.map(u => `<option value="${u.id}" ${data.university_id == u.id ? 'selected' : ''}>${u.name}</option>`).join('')}
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label>College Code</label><input type="text" name="code" class="form-control" value="${data.code || ''}" placeholder="e.g. FAS01"></div>
                        <div class="form-group"><label>City</label><input type="text" name="city" class="form-control" value="${data.city || ''}"></div>
                    </div>
                    ${statusSelect}
                `;
                break;
            case 'departments':
                fields = `
                    <div class="form-group"><label>Department Name *</label><input type="text" name="name" class="form-control" value="${data.name || ''}" required placeholder="e.g. Department of Computer Science"></div>
                    <div class="form-group">
                        <label>College (Optional)</label>
                        <select name="college_id" class="form-control">
                            <option value="">-- Select College --</option>
                            ${lookupOptions.colleges.map(c => `<option value="${c.id}" ${data.college_id == c.id ? 'selected' : ''}>${c.name}</option>`).join('')}
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label>Dept Code</label><input type="text" name="code" class="form-control" value="${data.code || ''}" placeholder="e.g. CS"></div>
                        ${statusSelect}
                    </div>
                `;
                break;
            case 'courses':
                fields = `
                    <div class="form-group"><label>Course Name (Degree) *</label><input type="text" name="name" class="form-control" value="${data.name || ''}" required placeholder="e.g. Bachelor of Technology (B.Tech)"></div>
                    <div class="form-group">
                        <label>Department (Optional)</label>
                        <select name="department_id" class="form-control">
                            <option value="">-- Select Department --</option>
                            ${lookupOptions.departments.map(d => `<option value="${d.id}" ${data.department_id == d.id ? 'selected' : ''}>${d.name}</option>`).join('')}
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label>Course Code</label><input type="text" name="code" class="form-control" value="${data.code || ''}" placeholder="e.g. BTECH"></div>
                        <div class="form-group"><label>Duration (Years)</label><input type="number" name="duration_years" class="form-control" value="${data.duration_years || 4}"></div>
                    </div>
                    ${statusSelect}
                `;
                break;
            case 'branches':
                fields = `
                    <div class="form-group"><label>Branch / Specialization Name *</label><input type="text" name="name" class="form-control" value="${data.name || ''}" required placeholder="e.g. Computer Science & Engineering"></div>
                    <div class="form-group">
                        <label>Course *</label>
                        <select name="course_id" class="form-control" required>
                            <option value="">-- Select Course --</option>
                            ${lookupOptions.courses.map(cr => `<option value="${cr.id}" ${data.course_id == cr.id ? 'selected' : ''}>${cr.name}</option>`).join('')}
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label>Branch Code</label><input type="text" name="code" class="form-control" value="${data.code || ''}" placeholder="e.g. CSE"></div>
                        ${statusSelect}
                    </div>
                `;
                break;
            case 'subjects':
                fields = `
                    <div class="form-group"><label>Subject Title *</label><input type="text" name="name" class="form-control" value="${data.name || ''}" required placeholder="e.g. Data Structures & Algorithms"></div>
                    <div class="form-group">
                        <label>Branch (Optional)</label>
                        <select name="branch_id" class="form-control">
                            <option value="">-- Select Branch --</option>
                            ${lookupOptions.branches.map(b => `<option value="${b.id}" ${data.branch_id == b.id ? 'selected' : ''}>${b.name}</option>`).join('')}
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label>Subject Code</label><input type="text" name="code" class="form-control" value="${data.code || ''}" placeholder="e.g. CS101"></div>
                        <div class="form-group"><label>Semester</label><input type="number" name="semester" class="form-control" value="${data.semester || ''}" placeholder="e.g. 3"></div>
                    </div>
                    ${statusSelect}
                `;
                break;
            case 'sections':
                fields = `
                    <div class="form-group"><label>Section Name *</label><input type="text" name="name" class="form-control" value="${data.name || ''}" required placeholder="e.g. Section A"></div>
                    <div class="form-group">
                        <label>Branch (Optional)</label>
                        <select name="branch_id" class="form-control">
                            <option value="">-- Select Branch --</option>
                            ${lookupOptions.branches.map(b => `<option value="${b.id}" ${data.branch_id == b.id ? 'selected' : ''}>${b.name}</option>`).join('')}
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label>Academic Year</label><input type="text" name="academic_year" class="form-control" value="${data.academic_year || ''}" placeholder="e.g. 2026-2027"></div>
                        ${statusSelect}
                    </div>
                `;
                break;
            case 'subsections':
                fields = `
                    <div class="form-group"><label>Subsection / Batch Name *</label><input type="text" name="name" class="form-control" value="${data.name || ''}" required placeholder="e.g. Batch A1"></div>
                    <div class="form-group">
                        <label>Parent Section *</label>
                        <select name="section_id" class="form-control" required>
                            <option value="">-- Select Section --</option>
                            ${lookupOptions.sections.map(s => `<option value="${s.id}" ${data.section_id == s.id ? 'selected' : ''}>${s.name}</option>`).join('')}
                        </select>
                    </div>
                    ${statusSelect}
                `;
                break;
        }

        container.innerHTML = fields;
    }

    async function handleFormSubmit(e) {
        e.preventDefault();
        const form = document.getElementById('crudForm');
        const formData = new FormData(form);
        const json = {};
        formData.forEach((val, key) => { if(val !== '') json[key] = val; });

        const id = document.getElementById('entityId').value;
        const isEdit = !!id;
        const url = isEdit ? `/api/academic/${currentEntity}/${id}` : `/api/academic/${currentEntity}`;
        const method = isEdit ? 'PUT' : 'POST';

        const saveBtn = document.getElementById('saveBtn');
        saveBtn.disabled = true;
        saveBtn.innerText = 'Saving...';

        try {
            const res = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify(json)
            });

            const response = await res.json();
            if (res.ok && response.success) {
                const entityName = singularize(currentEntity);
                showToast(`✅ ${entityName} ${isEdit ? 'updated' : 'created'} successfully!`, false);
                closeModal();
                await loadLookups();
                fetchEntityData();
            } else {
                let errorMsg = response.message || 'Failed to save record';
                if (response.errors && typeof response.errors === 'object') {
                    const firstKey = Object.keys(response.errors)[0];
                    if (firstKey && response.errors[firstKey]?.length) {
                        errorMsg = response.errors[firstKey][0];
                    }
                }
                showToast(`❌ Error: ${errorMsg}`, true);
            }
        } catch (err) {
            console.error('Submit error:', err);
            showToast('❌ Server network error while saving. Please try again.', true);
        } finally {
            saveBtn.disabled = false;
            saveBtn.innerText = 'Save Changes';
        }
    }

    async function deleteRecord(id) {
        const entityName = singularize(currentEntity);
        if (!confirm(`Are you sure you want to delete ${entityName} #${id}?`)) return;

        try {
            const res = await fetch(`/api/academic/${currentEntity}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
            const response = await res.json();
            if (res.ok && response.success) {
                showToast(`✅ ${entityName} deleted successfully!`, false);
                await loadLookups();
                fetchEntityData();
            } else {
                showToast(`❌ ${response.message || 'Failed to delete record'}`, true);
            }
        } catch (e) {
            showToast('❌ Server error deleting record', true);
        }
    }

    function singularize(str) {
        if (str === 'universities') return 'University';
        if (str === 'colleges') return 'College';
        if (str === 'branches') return 'Branch';
        if (str.endsWith('s')) return str.slice(0, -1).toUpperCase();
        return str;
    }

    function showToast(msg, isError = false) {
        const toast = document.getElementById('toast');
        if (!toast) return;
        toast.innerText = msg;
        toast.style.background = isError ? '#FF7675' : '#2D3436';
        toast.style.border = isError ? '1px solid #E53E3E' : '1px solid #4A5568';
        toast.style.display = 'flex';
        setTimeout(() => { toast.style.display = 'none'; }, 4000);
    }
</script>
@endsection
