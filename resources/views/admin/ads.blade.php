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

<!-- User Level Ads Targeting Directory -->
<div style="margin-bottom: 20px;">
    <h2 style="font-size: 18px; font-weight: 800;">User-Level Ads Targeting Directory</h2>
    <p style="font-size: 13.5px; color: var(--text-muted); margin-top: 2px;">Toggle ad visibility per user. When "Selected Users Only" mode is active, only users marked 🟢 Ads Enabled will receive ads.</p>
</div>

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
                    <th>Ads Status</th>
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
                if (toggle) toggle.checked = json.data.ads_enabled;
                if (audience) audience.value = json.data.ads_target_audience;
                if (badge) {
                    badge.innerText = json.data.ads_enabled ? '● Active' : '○ Disabled';
                    badge.style.color = json.data.ads_enabled ? '#10B981' : '#EF4444';
                }
            }
        } catch (e) {
            console.error('Failed to load ad settings', e);
        }
    }

    async function saveAdSettings() {
        const toggle = document.getElementById('adsEnabledToggle');
        const audience = document.getElementById('adsTargetAudience');
        const badge = document.getElementById('adStatusBadge');

        const payload = {
            ads_enabled: toggle ? toggle.checked : true,
            ads_target_audience: audience ? audience.value : 'all'
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
                showToast('AdMob configuration saved successfully!');
                if (badge) {
                    badge.innerText = payload.ads_enabled ? '● Active' : '○ Disabled';
                    badge.style.color = payload.ads_enabled ? '#10B981' : '#EF4444';
                }
            } else {
                showToast(json.message || 'Failed to save ad settings');
            }
        } catch (e) {
            showToast('Network error while saving ad settings');
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
            const list = json.data || [];
            const tbody = document.getElementById('adsUsersTableBody');
            
            if (list.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 24px;">No users found.</td></tr>`;
                return;
            }

            tbody.innerHTML = list.map(u => `
                <tr>
                    <td>
                        <strong>${u.name}</strong><br>
                        <span style="font-size: 11.5px; color: var(--text-muted);">${u.email}</span>
                    </td>
                    <td><span class="badge ${u.role === 'STUDENT' ? 'badge-active' : 'badge-upcoming'}">${u.role}</span></td>
                    <td><strong>${u.roll_number || u.faculty_id || 'N/A'}</strong></td>
                    <td>${u.department || 'Not Specified'}</td>
                    <td><strong>${u.attempts_count}</strong> Quizzes</td>
                    <td>${u.created_at}</td>
                    <td>
                        <button type="button" class="btn ${u.show_ads ? 'btn-secondary' : 'btn-danger'}" style="padding: 6px 14px; font-size: 12px; border-radius: 20px;" onclick="toggleUserAds(${u.id}, ${!u.show_ads})">
                            ${u.show_ads ? '🟢 Ads Enabled' : '🔴 Ads Disabled'}
                        </button>
                    </td>
                </tr>
            `).join('');
        } catch (e) {
            console.error('Failed to load users for ads directory', e);
        }
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
                loadUsersForAds();
            } else {
                showToast(json.message || 'Failed to update user ads setting');
            }
        } catch (e) {
            showToast('Network error while updating user ads setting');
        }
    }
</script>
@endsection
