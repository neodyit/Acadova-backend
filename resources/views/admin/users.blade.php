@extends('layouts.admin_layout')

@section('title', 'Users Directory - Acadova Admin')
@section('page_title', 'Users Directory')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 800;">Users Management</h1>
        <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Manage student accounts, faculty members, roles, and profiles.</p>
    </div>
    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
        <input type="text" id="userSearchInput" onkeyup="searchUsers()" class="form-control" placeholder="Search by name or email..." style="width: 260px;">
        <button class="btn btn-primary" onclick="openCreateUserModal()"><i class="fa-solid fa-user-plus"></i> Add New User</button>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Name / Email</th>
                    <th>Role</th>
                    <th>ID / Roll No.</th>
                    <th>Department</th>
                    <th>Attempts</th>
                    <th>Joined</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody id="usersTableBody">
                @forelse($users as $u)
                    <tr class="user-row">
                        <td>
                            <strong>{{ $u->name }}</strong><br>
                            <span style="font-size: 11.5px; color: var(--text-muted);">{{ $u->email }}</span>
                        </td>
                        <td>
                            <span class="badge" style="padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 11px; {{ strtolower($u->role) === 'student' ? 'background: #DEF7EC; color: #03543F;' : (strtolower($u->role) === 'admin' ? 'background: #FEE2E2; color: #991B1B;' : 'background: #FEF08A; color: #713F12;') }}">
                                {{ strtoupper($u->role) }}
                            </span>
                        </td>
                        <td><strong>{{ $u->roll_number ?: ($u->faculty_id ?: 'N/A') }}</strong></td>
                        <td>{{ $u->department ?: 'Not Specified' }}</td>
                        <td><strong>{{ $u->attempts_count }}</strong> Quizzes</td>
                        <td>{{ $u->created_at ? $u->created_at->format('M d, Y') : 'N/A' }}</td>
                        <td style="text-align: right;">
                            @if(strtolower($u->role) === 'faculty')
                                <button class="btn btn-primary" style="padding: 6px 10px; font-size: 12px; background: #6366F1; border-color: #6366F1;" onclick="openAllocationModal({{ $u->id }}, '{{ addslashes($u->name) }}')"><i class="fa-solid fa-book-bookmark"></i> Allocations</button>
                            @endif
                            <button class="btn btn-secondary" style="padding: 6px 10px; font-size: 12px;" onclick="editUser({{ json_encode($u) }})"><i class="fa-solid fa-pen"></i> Edit</button>
                            <button class="btn btn-danger" style="padding: 6px 10px; font-size: 12px;" onclick="deleteUser({{ $u->id }})"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">No users found in directory.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Faculty Subject & Section Allocations -->
<div class="modal-overlay" id="facultyAllocationModal">
    <div class="modal-container" style="max-width: 680px;">
        <div class="modal-header">
            <div>
                <div class="modal-title" id="allocationModalTitle">Faculty Allocations</div>
                <div style="font-size: 12.5px; color: var(--text-muted); margin-top: 2px;" id="allocationModalSubtitle">Assign subjects, sections & semesters to this faculty</div>
            </div>
            <button class="close-btn" onclick="closeModal('facultyAllocationModal')">&times;</button>
        </div>
        <div class="modal-body" style="padding: 20px;">
            <form id="addAllocationForm" onsubmit="handleAddAllocation(event)" style="background: var(--bg-surface-secondary, #f8fafc); padding: 16px; border-radius: 8px; border: 1px solid var(--border-color, #e2e8f0); margin-bottom: 20px;">
                <input type="hidden" id="allocFacultyId">
                <div style="font-weight: 700; font-size: 14px; margin-bottom: 12px; color: var(--text-main);">Add New Subject & Section Allocation</div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Select Semester</label>
                        <select id="allocSemesterSelect" class="form-control" required>
                            <option value="Semester 1">Semester 1</option>
                            <option value="Semester 2">Semester 2</option>
                            <option value="Semester 3">Semester 3</option>
                            <option value="Semester 4">Semester 4</option>
                            <option value="Semester 5">Semester 5</option>
                            <option value="Semester 6">Semester 6</option>
                            <option value="Semester 7">Semester 7</option>
                            <option value="Semester 8">Semester 8</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Predefined Subject</label>
                        <select id="allocSubjectSelect" class="form-control" onchange="toggleCustomSubjectInput()">
                            <option value="">-- Loading Subjects --</option>
                        </select>
                        <input type="text" id="allocSubjectNameCustom" class="form-control" placeholder="Or enter custom subject..." style="display: none; margin-top: 8px;">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Predefined Section / Batch</label>
                        <select id="allocSectionSelect" class="form-control" onchange="toggleCustomSectionInput()">
                            <option value="">-- Loading Sections --</option>
                        </select>
                        <input type="text" id="allocSectionNameCustom" class="form-control" placeholder="Or enter custom section..." style="display: none; margin-top: 8px;">
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 10px;">
                    <button type="submit" class="btn btn-primary" style="font-size: 13px;"><i class="fa-solid fa-plus"></i> Assign Allocation</button>
                </div>
            </form>

            <div style="font-weight: 700; font-size: 14px; margin-bottom: 10px;">Current Assigned Subject & Sections</div>
            <div class="table-responsive" style="max-height: 250px; overflow-y: auto; border: 1px solid var(--border-color, #e2e8f0); border-radius: 6px;">
                <table style="width: 100%; font-size: 13px;">
                    <thead>
                        <tr style="background: var(--bg-surface-secondary, #f1f5f9);">
                            <th style="padding: 8px 12px;">Semester</th>
                            <th style="padding: 8px 12px;">Subject Name</th>
                            <th style="padding: 8px 12px;">Section Name</th>
                            <th style="padding: 8px 12px; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="allocationsTableBody">
                        <tr><td colspan="4" style="text-align: center; color: var(--text-muted); padding: 16px;">Loading allocations...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Create / Edit User -->
<div class="modal-overlay" id="createUserModal">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title" id="userModalTitleText">Add New User</div>
            <button class="close-btn" onclick="closeModal('createUserModal')">&times;</button>
        </div>
        <form id="createUserForm" onsubmit="handleSaveUser(event)">
            <input type="hidden" id="editingUserId">
            <div class="form-row">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" id="userName" class="form-control" placeholder="e.g. Aman Sharma" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" id="userEmail" class="form-control" placeholder="e.g. aman@acadova.com" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Role</label>
                    <select id="userRole" class="form-control" onchange="toggleUserRoleFields()">
                        <option value="student">Student</option>
                        <option value="faculty">Faculty / Instructor</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>
                <div class="form-group">
                    <label id="userRollLabel">Roll Number</label>
                    <input type="text" id="userRollOrFaculty" class="form-control" placeholder="e.g. CS2026001">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Department / Branch</label>
                    <input type="text" id="userDept" class="form-control" placeholder="e.g. Computer Science">
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" id="userPhone" class="form-control" placeholder="e.g. +91 9876543210">
                </div>
            </div>

            <div class="form-group">
                <label id="userPasswordLabel">Password</label>
                <input type="password" id="userPassword" class="form-control" placeholder="Minimum 6 characters">
                <small id="userPasswordHelp" style="color: var(--text-muted); font-size: 11.5px; display: none;">Leave empty to keep existing password.</small>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('createUserModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Save User Details</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openCreateUserModal() {
        document.getElementById('editingUserId').value = '';
        document.getElementById('createUserForm').reset();
        document.getElementById('userModalTitleText').innerText = 'Add New User';
        document.getElementById('userPassword').required = true;
        document.getElementById('userPasswordHelp').style.display = 'none';
        toggleUserRoleFields();
        openModal('createUserModal');
    }

    function editUser(user) {
        document.getElementById('editingUserId').value = user.id;
        document.getElementById('userName').value = user.name || '';
        document.getElementById('userEmail').value = user.email || '';
        document.getElementById('userRole').value = (user.role || 'student').toLowerCase();
        document.getElementById('userRollOrFaculty').value = user.roll_number || user.faculty_id || '';
        document.getElementById('userDept').value = user.department || '';
        document.getElementById('userPhone').value = user.phone || '';
        document.getElementById('userPassword').value = '';
        document.getElementById('userPassword').required = false;
        document.getElementById('userPasswordHelp').style.display = 'block';
        document.getElementById('userModalTitleText').innerText = 'Edit User Details';
        toggleUserRoleFields();
        openModal('createUserModal');
    }

    function toggleUserRoleFields() {
        const role = document.getElementById('userRole').value;
        const label = document.getElementById('userRollLabel');
        label.innerText = (role === 'faculty') ? 'Faculty ID' : (role === 'admin' ? 'Admin ID' : 'Roll Number');
    }

    async function handleSaveUser(e) {
        e.preventDefault();
        const userId = document.getElementById('editingUserId').value;
        const role = document.getElementById('userRole').value;
        const rollVal = document.getElementById('userRollOrFaculty').value;

        const payload = {
            name: document.getElementById('userName').value,
            email: document.getElementById('userEmail').value,
            role: role,
            department: document.getElementById('userDept').value,
            phone: document.getElementById('userPhone').value,
            roll_number: role === 'student' ? rollVal : null,
            faculty_id: role === 'faculty' ? rollVal : null,
        };

        const pwd = document.getElementById('userPassword').value;
        if (pwd) payload.password = pwd;

        const url = userId ? `/api/admin/users/${userId}` : '/api/admin/users';
        const method = userId ? 'PUT' : 'POST';

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
            if (res.ok && json.success) {
                showToast(userId ? 'User updated successfully!' : 'User created successfully!');
                closeModal('createUserModal');
                location.reload();
            } else {
                showToast(json.message || 'Failed to save user');
            }
        } catch (err) {
            showToast('Error saving user');
        }
    }

    async function deleteUser(id) {
        if (!confirm('Are you sure you want to delete this user? All their quiz attempt records will also be removed.')) return;
        try {
            const res = await fetch(`/api/admin/users/${id}`, { 
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN }
            });
            const json = await res.json();
            if (res.ok && json.success) {
                showToast('User account deleted');
                location.reload();
            } else {
                showToast(json.message || 'Failed to delete user');
            }
        } catch (e) {
            showToast('Error deleting user');
        }
    }

    function searchUsers() {
        const query = document.getElementById('userSearchInput').value.toLowerCase();
        document.querySelectorAll('.user-row').forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    }

    let globalPredefinedSubjects = [];
    let globalPredefinedSections = [];

    async function loadPredefinedAcademicData() {
        try {
            const [subRes, secRes] = await Promise.all([
                fetch('/api/academic/subjects'),
                fetch('/api/academic/sections')
            ]);
            
            const subJson = await subRes.json();
            const secJson = await secRes.json();

            if (subJson.success) {
                globalPredefinedSubjects = subJson.data || [];
            }
            if (secJson.success) {
                globalPredefinedSections = secJson.data || [];
            }
            populateAllocationDropdowns();
        } catch (e) {
            console.error('Error fetching academic data:', e);
        }
    }

    function populateAllocationDropdowns() {
        const subSelect = document.getElementById('allocSubjectSelect');
        const secSelect = document.getElementById('allocSectionSelect');

        if (globalPredefinedSubjects.length > 0) {
            subSelect.innerHTML = `
                <option value="">-- Select Predefined Subject --</option>
                ${globalPredefinedSubjects.map(s => `<option value="${s.id}" data-name="${s.name}">${s.name} (${s.code || 'N/A'})</option>`).join('')}
                <option value="CUSTOM">+ Enter Custom Subject Name</option>
            `;
        } else {
            subSelect.innerHTML = `<option value="CUSTOM">+ Enter Custom Subject Name</option>`;
        }

        if (globalPredefinedSections.length > 0) {
            secSelect.innerHTML = `
                <option value="">-- Select Predefined Section --</option>
                ${globalPredefinedSections.map(s => `<option value="${s.id}" data-name="${s.name}">${s.name} (${s.code || 'N/A'})</option>`).join('')}
                <option value="CUSTOM">+ Enter Custom Section Name</option>
            `;
        } else {
            secSelect.innerHTML = `<option value="CUSTOM">+ Enter Custom Section Name</option>`;
        }

        toggleCustomSubjectInput();
        toggleCustomSectionInput();
    }

    function toggleCustomSubjectInput() {
        const select = document.getElementById('allocSubjectSelect');
        const customInput = document.getElementById('allocSubjectNameCustom');
        if (select.value === 'CUSTOM') {
            customInput.style.display = 'block';
            customInput.required = true;
        } else {
            customInput.style.display = 'none';
            customInput.required = false;
        }
    }

    function toggleCustomSectionInput() {
        const select = document.getElementById('allocSectionSelect');
        const customInput = document.getElementById('allocSectionNameCustom');
        if (select.value === 'CUSTOM') {
            customInput.style.display = 'block';
            customInput.required = true;
        } else {
            customInput.style.display = 'none';
            customInput.required = false;
        }
    }

    async function openAllocationModal(facultyId, facultyName) {
        document.getElementById('allocFacultyId').value = facultyId;
        document.getElementById('allocationModalTitle').innerText = `Allocations: ${facultyName}`;
        document.getElementById('allocSemesterSelect').value = 'Semester 1';
        openModal('facultyAllocationModal');
        await loadPredefinedAcademicData();
        await loadFacultyAllocations(facultyId);
    }

    async function loadFacultyAllocations(facultyId) {
        const tbody = document.getElementById('allocationsTableBody');
        tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: var(--text-muted); padding: 16px;">Loading allocations...</td></tr>';
        
        try {
            const res = await fetch(`/api/admin/faculty/${facultyId}/allocations`);
            const json = await res.json();
            if (res.ok && json.success) {
                if (json.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: var(--text-muted); padding: 16px;">No subject/section allocations assigned yet.</td></tr>';
                    return;
                }
                tbody.innerHTML = json.data.map(item => `
                    <tr>
                        <td style="padding: 8px 12px;"><span class="badge" style="background: #E0E7FF; color: #3730A3; font-size: 11px; padding: 4px 8px; border-radius: 12px; font-weight: 700;">${item.semester || 'Semester 1'}</span></td>
                        <td style="padding: 8px 12px; font-weight: 600;">${item.subject_name || 'N/A'}</td>
                        <td style="padding: 8px 12px;">${item.section_name || 'All Sections'}</td>
                        <td style="padding: 8px 12px; text-align: right;">
                            <button class="btn btn-danger" style="padding: 4px 8px; font-size: 11px;" onclick="deleteFacultyAllocation(${item.id}, ${facultyId})"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: var(--text-muted); padding: 16px;">Failed to load allocations.</td></tr>';
            }
        } catch (e) {
            tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: var(--text-muted); padding: 16px;">Error loading allocations.</td></tr>';
        }
    }

    async function handleAddAllocation(e) {
        e.preventDefault();
        const facultyId = document.getElementById('allocFacultyId').value;
        const semester = document.getElementById('allocSemesterSelect').value;
        const subSelect = document.getElementById('allocSubjectSelect');
        const secSelect = document.getElementById('allocSectionSelect');

        let subjectId = null;
        let subjectName = '';
        if (subSelect.value === 'CUSTOM') {
            subjectName = document.getElementById('allocSubjectNameCustom').value.trim();
        } else if (subSelect.value) {
            subjectId = subSelect.value;
            const opt = subSelect.options[subSelect.selectedIndex];
            subjectName = opt ? opt.getAttribute('data-name') : '';
        }

        let sectionId = null;
        let sectionName = '';
        if (secSelect.value === 'CUSTOM') {
            sectionName = document.getElementById('allocSectionNameCustom').value.trim();
        } else if (secSelect.value) {
            sectionId = secSelect.value;
            const opt = secSelect.options[secSelect.selectedIndex];
            sectionName = opt ? opt.getAttribute('data-name') : '';
        }

        if (!subjectName && !subjectId) {
            showToast('Please select or enter a subject name');
            return;
        }

        try {
            const res = await fetch('/api/admin/faculty/allocations', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({
                    faculty_id: facultyId,
                    semester: semester,
                    subject_id: subjectId,
                    subject_name: subjectName,
                    section_id: sectionId,
                    section_name: sectionName || 'All Sections'
                })
            });
            const json = await res.json();
            if (res.ok && json.success) {
                showToast('Subject & Section allocation assigned!');
                document.getElementById('allocSubjectNameCustom').value = '';
                document.getElementById('allocSectionNameCustom').value = '';
                subSelect.value = '';
                secSelect.value = '';
                toggleCustomSubjectInput();
                toggleCustomSectionInput();
                loadFacultyAllocations(facultyId);
            } else {
                showToast(json.message || 'Failed to add allocation');
            }
        } catch (err) {
            showToast('Error adding allocation');
        }
    }

    async function deleteFacultyAllocation(id, facultyId) {
        if (!confirm('Remove this subject and section allocation?')) return;
        try {
            const res = await fetch(`/api/admin/faculty/allocations/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN }
            });
            const json = await res.json();
            if (res.ok && json.success) {
                showToast('Allocation removed');
                loadFacultyAllocations(facultyId);
            } else {
                showToast(json.message || 'Failed to remove allocation');
            }
        } catch (e) {
            showToast('Error removing allocation');
        }
    }
</script>
@endsection
