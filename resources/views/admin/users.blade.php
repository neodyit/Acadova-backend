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
</script>
@endsection
