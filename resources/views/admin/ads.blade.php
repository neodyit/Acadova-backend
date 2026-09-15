@extends('layouts.admin_layout')

@section('title', 'Ads Management - Acadova Admin')
@section('page_title', 'Google Mobile Ads Management')

@section('content')
<div style="margin-bottom: 28px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 800;">Google Mobile Ads Control Center</h1>
        <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Dynamically control advertisement visibility across student and faculty mobile apps in real-time.</p>
    </div>
    <button type="button" onclick="saveAdSettings()" class="btn btn-primary" style="padding: 12px 24px; font-size: 14px;">
        <i class="fa-solid fa-floppy-disk"></i> Save Configuration
    </button>
</div>

<!-- Global Feature Flag Card -->
<div style="background: white; border-radius: 18px; border: 2px solid #FCD34D; padding: 28px; margin-bottom: 32px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; align-items: center; gap: 14px;">
            <div style="width: 48px; height: 48px; background: #FEF3C7; color: #D97706; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                <i class="fa-solid fa-rectangle-ad"></i>
            </div>
            <div>
                <h2 style="font-size: 18px; font-weight: 800;">Global Ad Feature Flag</h2>
                <span id="adStatusBadge" style="font-size: 12.5px; color: #10B981; font-weight: 700;">● Active</span>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; background: var(--bg); padding: 24px; border-radius: 14px; border: 1px solid var(--border);">
        <div>
            <label style="font-weight: 800; font-size: 14px; display: block; margin-bottom: 8px;">Master Ads Switch</label>
            <div style="display: flex; align-items: center; gap: 12px; margin-top: 10px;">
                <input type="checkbox" id="adsEnabledToggle" style="width: 24px; height: 24px; cursor: pointer; accent-color: var(--primary);">
                <span style="font-size: 14.5px; font-weight: 700;">Enable Advertisements in App</span>
            </div>
            <p style="font-size: 12px; color: var(--text-muted); margin-top: 6px;">Master switch to turn off all banner, native, interstitial and rewarded ads across mobile devices.</p>
        </div>
        <div>
            <label style="font-weight: 800; font-size: 14px; display: block; margin-bottom: 8px;">Target Audience Filter</label>
            <select id="adsTargetAudience" class="form-control" style="font-size: 14px; font-weight: 600; padding: 12px 14px;">
                <option value="all">🌐 All Users (Show ads to everyone)</option>
                <option value="selected_users">🎯 Selected Users Only (Only users with Ads Enabled)</option>
                <option value="none">🚫 No Users (Turn off ads for everyone)</option>
            </select>
            <p style="font-size: 12px; color: var(--text-muted); margin-top: 6px;">Select target user group. In "Selected Users Only" mode, only users enabled in the directory below will see ads.</p>
        </div>
    </div>
</div>

<!-- Google Authentication Feature Flags Card -->
<div style="background: white; border-radius: 18px; border: 2px solid #6C5CE7; padding: 28px; margin-bottom: 32px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; align-items: center; gap: 14px;">
            <div style="width: 48px; height: 48px; background: #EEF2FF; color: #6C5CE7; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                <i class="fa-brands fa-google"></i>
            </div>
            <div>
                <h2 style="font-size: 18px; font-weight: 800;">Google Sign-In Feature Flags</h2>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">Control visibility of "Continue with Google" button on Android and Windows platforms separately.</p>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; background: var(--bg); padding: 24px; border-radius: 14px; border: 1px solid var(--border);">
        <div>
            <label style="font-weight: 800; font-size: 14px; display: block; margin-bottom: 8px;">🤖 Android App Google Login</label>
            <div style="display: flex; align-items: center; gap: 12px; margin-top: 10px;">
                <input type="checkbox" id="googleAuthAndroidToggle" style="width: 24px; height: 24px; cursor: pointer; accent-color: #6C5CE7;">
                <span style="font-size: 14.5px; font-weight: 700;">Show Google Sign-In on Android</span>
            </div>
            <p style="font-size: 12px; color: var(--text-muted); margin-top: 6px;">When enabled, Android users will see the "Continue with Google" button on login & signup screens.</p>
        </div>
        <div>
            <label style="font-weight: 800; font-size: 14px; display: block; margin-bottom: 8px;">💻 Windows App Google Login</label>
            <div style="display: flex; align-items: center; gap: 12px; margin-top: 10px;">
                <input type="checkbox" id="googleAuthWindowsToggle" style="width: 24px; height: 24px; cursor: pointer; accent-color: #6C5CE7;">
                <span style="font-size: 14.5px; font-weight: 700;">Show Google Sign-In on Windows</span>
            </div>
            <p style="font-size: 12px; color: var(--text-muted); margin-top: 6px;">When enabled, Windows desktop users will see the "Continue with Google" button on login & signup screens.</p>
        </div>
    </div>
</div>

<!-- App Version & Update Control Card -->
<div style="background: white; border-radius: 18px; border: 2px solid #10B981; padding: 28px; margin-bottom: 32px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; align-items: center; gap: 14px;">
            <div style="width: 48px; height: 48px; background: #D1FAE5; color: #10B981; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                <i class="fa-solid fa-cloud-arrow-up"></i>
            </div>
            <div>
                <h2 style="font-size: 18px; font-weight: 800;">App Update & Play Store Version Control</h2>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">When local app version is lower than the latest Play Store version, users will be prompted to update.</p>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; background: var(--bg); padding: 24px; border-radius: 14px; border: 1px solid var(--border);">
        <div>
            <label style="font-weight: 800; font-size: 13.5px; display: block; margin-bottom: 6px;">🚀 Latest Play Store Version</label>
            <input type="text" id="latestAppVersionInput" class="form-control" placeholder="1.0.0" style="font-weight: 700; font-size: 14px; padding: 10px 14px;">
            <p style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">e.g., 1.0.1 or 2.0.0. Current local app version is 1.0.0.</p>
        </div>
        <div>
            <label style="font-weight: 800; font-size: 13.5px; display: block; margin-bottom: 6px;">🔗 Play Store / Update URL</label>
            <input type="text" id="updateUrlInput" class="form-control" placeholder="https://play.google.com/store/apps/details?id=com.neodyit.acadova" style="font-weight: 600; font-size: 13.5px; padding: 10px 14px;">
            <p style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">URL user is directed to when tapping "Update Now".</p>
        </div>
        <div>
            <label style="font-weight: 800; font-size: 13.5px; display: block; margin-bottom: 6px;">⚠️ Mandatory / Force Update</label>
            <div style="display: flex; align-items: center; gap: 10px; margin-top: 8px;">
                <input type="checkbox" id="forceUpdateToggle" style="width: 22px; height: 22px; cursor: pointer; accent-color: #10B981;">
                <span style="font-size: 14px; font-weight: 700;">Force Immediate Update</span>
            </div>
            <p style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">If enabled, user cannot dismiss the update popup without updating.</p>
        </div>
    </div>
</div>

<!-- User Level Ads Targeting Directory Header & Controls -->
<div style="margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 style="font-size: 20px; font-weight: 800;">User-Level Ads Targeting Directory</h2>
        <p style="font-size: 13.5px; color: var(--text-muted); margin-top: 2px;">Toggle ad visibility per user. In "Selected Users Only" mode, only users marked 🟢 Ads Enabled will receive ads.</p>
    </div>
    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
        <button type="button" class="btn btn-secondary" style="padding: 10px 18px; font-size: 13px; font-weight: 700; border-radius: 10px;" onclick="bulkToggleAds(true)">
            <i class="fa-solid fa-square-check" style="color: #10B981; margin-right: 6px;"></i> Select All (Enable Ads)
        </button>
        <button type="button" class="btn btn-danger" style="padding: 10px 18px; font-size: 13px; font-weight: 700; border-radius: 10px;" onclick="bulkToggleAds(false)">
            <i class="fa-solid fa-square-xmark" style="margin-right: 6px;"></i> Deselect All (Disable Ads)
        </button>
    </div>
</div>

<!-- Filters & Search Toolbar -->
<div style="background: white; border-radius: 16px; border: 1px solid var(--border); padding: 18px 24px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
    <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap; flex: 1;">
        <!-- Role Filter Tabs -->
        <div style="display: flex; background: var(--bg); padding: 4px; border-radius: 10px; border: 1px solid var(--border);">
            <button type="button" class="role-filter-tab active" id="filterTabAll" onclick="setRoleFilter('all')" style="padding: 8px 16px; font-size: 13px; font-weight: 700; border: none; border-radius: 8px; cursor: pointer; background: white; color: var(--primary); box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                All Users (<span id="countAll">0</span>)
            </button>
            <button type="button" class="role-filter-tab" id="filterTabFaculty" onclick="setRoleFilter('faculty')" style="padding: 8px 16px; font-size: 13px; font-weight: 700; border: none; border-radius: 8px; cursor: pointer; background: transparent; color: var(--text-muted);">
                👨‍🏫 Faculty (<span id="countFaculty">0</span>)
            </button>
            <button type="button" class="role-filter-tab" id="filterTabStudent" onclick="setRoleFilter('student')" style="padding: 8px 16px; font-size: 13px; font-weight: 700; border: none; border-radius: 8px; cursor: pointer; background: transparent; color: var(--text-muted);">
                🎓 Students (<span id="countStudent">0</span>)
            </button>
        </div>

        <!-- Ads Status Filter -->
        <select id="statusFilter" class="form-control" onchange="renderUsersTable()" style="width: auto; min-width: 170px; font-size: 13px; font-weight: 600; padding: 9px 14px; border-radius: 10px;">
            <option value="all">All Ad Statuses</option>
            <option value="enabled">🟢 Ads Enabled Only</option>
            <option value="disabled">🔴 Ads Disabled Only</option>
        </select>
    </div>

    <!-- Search Input -->
    <div style="position: relative; width: 280px;">
        <input type="text" id="userSearchInput" class="form-control" placeholder="Search by name, email, ID..." oninput="renderUsersTable()" style="padding: 9px 14px 9px 38px; font-size: 13px; border-radius: 10px;">
        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 13px;"></i>
    </div>
</div>

<!-- Table Card -->
<div class="table-card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>User Details</th>
                    <th>Role</th>
                    <th>Roll / Faculty ID</th>
                    <th>Department</th>
                    <th>Quizzes Taken</th>
                    <th>Joined Date</th>
                    <th style="text-align: right;">Ads Status</th>
                </tr>
            </thead>
            <tbody id="adsUsersTableBody">
                <tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">Loading users directory...</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let rawUsersList = [];
    let currentRoleFilter = 'all';

    document.addEventListener('DOMContentLoaded', function() {
        loadAdSettings();
        loadUsersForAds();
    });

    async function loadAdSettings() {
        try {
            const res = await fetch('/api/admin/ad-settings');
            const json = await res.json();
            if (json.success && json.data) {
                const toggle = document.getElementById('adsEnabledToggle');
                const audience = document.getElementById('adsTargetAudience');
                const badge = document.getElementById('adStatusBadge');
                const googleAndroid = document.getElementById('googleAuthAndroidToggle');
                const googleWindows = document.getElementById('googleAuthWindowsToggle');

                const latestVer = document.getElementById('latestAppVersionInput');
                const updateUrl = document.getElementById('updateUrlInput');
                const forceUpdate = document.getElementById('forceUpdateToggle');

                if (toggle) toggle.checked = json.data.ads_enabled;
                if (audience) audience.value = json.data.ads_target_audience;
                if (googleAndroid) googleAndroid.checked = json.data.google_auth_android !== false;
                if (googleWindows) googleWindows.checked = json.data.google_auth_windows !== false;
                if (latestVer) latestVer.value = json.data.latest_app_version || '0.0.6';
                if (updateUrl) updateUrl.value = json.data.update_url || 'https://play.google.com/store/apps/details?id=com.neodyit.acadova';
                if (forceUpdate) forceUpdate.checked = json.data.force_update === true;

                if (badge) {
                    badge.innerText = json.data.ads_enabled ? '● Active' : '○ Disabled';
                    badge.style.color = json.data.ads_enabled ? '#10B981' : '#EF4444';
                }
            }
        } catch (e) {
            console.error('Failed to load settings', e);
        }
    }

    async function saveAdSettings() {
        const toggle = document.getElementById('adsEnabledToggle');
        const audience = document.getElementById('adsTargetAudience');
        const badge = document.getElementById('adStatusBadge');
        const googleAndroid = document.getElementById('googleAuthAndroidToggle');
        const googleWindows = document.getElementById('googleAuthWindowsToggle');
        const latestVer = document.getElementById('latestAppVersionInput');
        const updateUrl = document.getElementById('updateUrlInput');
        const forceUpdate = document.getElementById('forceUpdateToggle');

        const payload = {
            ads_enabled: toggle ? toggle.checked : true,
            ads_target_audience: audience ? audience.value : 'all',
            google_auth_android: googleAndroid ? googleAndroid.checked : true,
            google_auth_windows: googleWindows ? googleWindows.checked : true,
            latest_app_version: latestVer ? latestVer.value.trim() : '0.0.6',
            update_url: updateUrl ? updateUrl.value.trim() : 'https://play.google.com/store/apps/details?id=com.neodyit.acadova',
            force_update: forceUpdate ? forceUpdate.checked : false
        };

        try {
            const res = await fetch('/api/admin/ad-settings', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(payload)
            });
            const json = await res.json();
            if (json.success) {
                showToast('Feature flag configuration saved successfully!');
                if (badge) {
                    badge.innerText = payload.ads_enabled ? '● Active' : '○ Disabled';
                    badge.style.color = payload.ads_enabled ? '#10B981' : '#EF4444';
                }
            } else {
                showToast(json.message || 'Failed to save settings');
            }
        } catch (e) {
            showToast('Network error while saving settings');
        }
    }

    async function loadUsersForAds() {
        try {
            const res = await fetch('/api/admin/users');
            if (res.status === 401) {
                window.location.href = '/neodyit/login';
                return;
            }
            const json = await res.json();
            rawUsersList = json.data || [];
            
            // Update Role Counters
            const facultyCount = rawUsersList.filter(u => u.role === 'FACULTY').length;
            const studentCount = rawUsersList.filter(u => u.role === 'STUDENT').length;
            document.getElementById('countAll').innerText = rawUsersList.length;
            document.getElementById('countFaculty').innerText = facultyCount;
            document.getElementById('countStudent').innerText = studentCount;

            renderUsersTable();
        } catch (e) {
            console.error('Failed to load users for ads directory', e);
        }
    }

    function setRoleFilter(role) {
        currentRoleFilter = role;
        ['All', 'Faculty', 'Student'].forEach(tab => {
            const btn = document.getElementById('filterTab' + tab);
            if (btn) {
                const isActive = tab.toLowerCase() === role || (tab === 'All' && role === 'all');
                btn.style.background = isActive ? 'white' : 'transparent';
                btn.style.color = isActive ? 'var(--primary)' : 'var(--text-muted)';
                btn.style.boxShadow = isActive ? '0 2px 4px rgba(0,0,0,0.05)' : 'none';
            }
        });
        renderUsersTable();
    }

    function renderUsersTable() {
        const tbody = document.getElementById('adsUsersTableBody');
        const searchQuery = (document.getElementById('userSearchInput').value || '').toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;

        let filtered = rawUsersList.filter(u => {
            // Role Filter
            if (currentRoleFilter !== 'all') {
                if (u.role.toLowerCase() !== currentRoleFilter) return false;
            }
            // Status Filter
            if (statusFilter === 'enabled' && !u.show_ads) return false;
            if (statusFilter === 'disabled' && u.show_ads) return false;

            // Search Query
            if (searchQuery) {
                const name = (u.name || '').toLowerCase();
                const email = (u.email || '').toLowerCase();
                const idNum = (u.roll_number || u.faculty_id || '').toLowerCase();
                const dept = (u.department || '').toLowerCase();
                return name.includes(searchQuery) || email.includes(searchQuery) || idNum.includes(searchQuery) || dept.includes(searchQuery);
            }
            return true;
        });

        if (filtered.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">No matching users found in directory.</td></tr>`;
            return;
        }

        // Separate Faculty & Students for visual separator headings when showing "All Users"
        const facultyList = filtered.filter(u => u.role === 'FACULTY');
        const studentList = filtered.filter(u => u.role === 'STUDENT');
        const otherList = filtered.filter(u => u.role !== 'FACULTY' && u.role !== 'STUDENT');

        let html = '';

        if (currentRoleFilter === 'all') {
            if (facultyList.length > 0) {
                html += `
                    <tr style="background: #FFFBEB; border-top: 2px solid #FCD34D; border-bottom: 1px solid #FDE68A;">
                        <td colspan="7" style="padding: 12px 20px; font-weight: 800; color: #B45309; font-size: 13.5px;">
                            <i class="fa-solid fa-chalkboard-user" style="margin-right: 8px;"></i> FACULTY DIRECTORY (${facultyList.length} Members)
                        </td>
                    </tr>
                `;
                html += facultyList.map(u => renderUserRow(u)).join('');
            }

            if (studentList.length > 0) {
                html += `
                    <tr style="background: #EFF6FF; border-top: 2px solid #93C5FD; border-bottom: 1px solid #BFDBFE;">
                        <td colspan="7" style="padding: 12px 20px; font-weight: 800; color: #1D4ED8; font-size: 13.5px;">
                            <i class="fa-solid fa-user-graduate" style="margin-right: 8px;"></i> STUDENT DIRECTORY (${studentList.length} Students)
                        </td>
                    </tr>
                `;
                html += studentList.map(u => renderUserRow(u)).join('');
            }

            if (otherList.length > 0) {
                html += `
                    <tr style="background: #F3F4F6; border-top: 2px solid #D1D5DB;">
                        <td colspan="7" style="padding: 12px 20px; font-weight: 800; color: #374151; font-size: 13.5px;">
                            <i class="fa-solid fa-users-gear" style="margin-right: 8px;"></i> ADMINISTRATORS & OTHERS (${otherList.length})
                        </td>
                    </tr>
                `;
                html += otherList.map(u => renderUserRow(u)).join('');
            }
        } else {
            html = filtered.map(u => renderUserRow(u)).join('');
        }

        tbody.innerHTML = html;
    }

    function renderUserRow(u) {
        const isFaculty = u.role === 'FACULTY';
        const roleBadge = isFaculty
            ? `<span class="badge" style="background: #FEF3C7; color: #B45309; font-weight: 800; padding: 4px 10px; border-radius: 12px;"><i class="fa-solid fa-chalkboard-user"></i> FACULTY</span>`
            : `<span class="badge" style="background: #DBEAFE; color: #1E40AF; font-weight: 800; padding: 4px 10px; border-radius: 12px;"><i class="fa-solid fa-user-graduate"></i> STUDENT</span>`;

        return `
            <tr>
                <td>
                    <strong style="font-size: 14px;">${u.name}</strong><br>
                    <span style="font-size: 12px; color: var(--text-muted);">${u.email}</span>
                </td>
                <td>${roleBadge}</td>
                <td><strong>${u.roll_number || u.faculty_id || 'N/A'}</strong></td>
                <td>${u.department || 'Not Specified'}</td>
                <td><strong>${u.attempts_count}</strong> Quizzes</td>
                <td>${u.created_at}</td>
                <td style="text-align: right;">
                    <button type="button" class="btn ${u.show_ads ? 'btn-secondary' : 'btn-danger'}" style="padding: 7px 16px; font-size: 12.5px; font-weight: 700; border-radius: 20px;" onclick="toggleUserAds(${u.id}, ${!u.show_ads})">
                        ${u.show_ads ? '🟢 Ads Enabled' : '🔴 Ads Disabled'}
                    </button>
                </td>
            </tr>
        `;
    }

    async function toggleUserAds(userId, newShowAds) {
        try {
            const res = await fetch(`/api/admin/users/${userId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({ show_ads: newShowAds })
            });
            const json = await res.json();
            if (json.success) {
                showToast(newShowAds ? 'Ads enabled for user' : 'Ads disabled for user');
                const target = rawUsersList.find(u => u.id === userId);
                if (target) target.show_ads = newShowAds;
                renderUsersTable();
            } else {
                showToast(json.message || 'Failed to update user ads setting');
            }
        } catch (e) {
            showToast('Network error while updating user ads setting');
        }
    }

    async function bulkToggleAds(enableAds) {
        const actionText = enableAds ? 'Enable Ads' : 'Disable Ads';
        const roleScope = currentRoleFilter === 'all' ? 'All Users' : `${currentRoleFilter.toUpperCase()} Users`;

        if (!confirm(`Are you sure you want to ${actionText} for ${roleScope}?`)) {
            return;
        }

        try {
            const res = await fetch('/api/admin/users/bulk-ads', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({
                    show_ads: enableAds,
                    role: currentRoleFilter
                })
            });
            const json = await res.json();
            if (json.success) {
                showToast(json.message || `Bulk ${actionText} completed successfully!`);
                loadUsersForAds();
            } else {
                showToast(json.message || 'Failed to perform bulk update');
            }
        } catch (e) {
            showToast('Network error while performing bulk update');
        }
    }
</script>
@endsection

