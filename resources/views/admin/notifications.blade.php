@extends('layouts.admin_layout')

@section('title', 'Send Notifications - Acadova Admin')
@section('page_title', 'Send Notifications')

@section('styles')
<style>
    .notif-card {
        background: white;
        border-radius: 18px;
        padding: 28px;
        border: 1px solid var(--border);
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }
</style>
@endsection

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 800;">Push & In-App Notification System</h1>
        <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Send instant alerts, announcements, and push messages to all or targeted app users.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: minmax(0, 2fr) minmax(0, 1fr); gap: 24px;">
    <div class="notif-card">
        <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-paper-plane" style="color: var(--primary);"></i> Dispatch New Notification
        </h3>

        <form id="adminSendNotifForm" onsubmit="handleSendAdminNotification(event)">
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: var(--dark); margin-bottom: 8px;">Target Audience</label>
                <select id="notifTargetAudience" class="form-control" onchange="toggleNotificationTargetUserSelect()" style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid var(--border); font-size: 14px; font-weight: 600;">
                    <option value="all">📢 All Registered Users (Broadcast)</option>
                    <option value="students">🎓 All Students</option>
                    <option value="faculty">👨‍🏫 All Faculty Members</option>
                    <option value="user">👤 Specific User</option>
                </select>
            </div>

            <div class="form-group" id="notifSpecificUserGroup" style="display: none; margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: var(--dark); margin-bottom: 8px;">Select User</label>
                <select id="notifTargetUserId" class="form-control" style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid var(--border); font-size: 14px; font-weight: 600;">
                    <option value="">Loading users...</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: var(--dark); margin-bottom: 8px;">Notification Title</label>
                    <input type="text" id="notifTitle" class="form-control" placeholder="e.g. New Quiz Published!" required style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid var(--border); font-size: 14px;">
                </div>
                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: var(--dark); margin-bottom: 8px;">Category / Type</label>
                    <select id="notifCategoryType" class="form-control" style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid var(--border); font-size: 14px; font-weight: 600;">
                        <option value="general">General Announcement</option>
                        <option value="quiz">Quiz Update</option>
                        <option value="batch">Batch / Class Notice</option>
                        <option value="alert">Important Alert</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: var(--dark); margin-bottom: 8px;">Message Content</label>
                <textarea id="notifBody" class="form-control" rows="4" placeholder="Enter your detailed notification message here..." required style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid var(--border); font-size: 14px; resize: vertical;"></textarea>
            </div>

            <button type="submit" id="btnSendNotifSubmit" class="btn btn-primary" style="padding: 14px 28px; width: 100%; justify-content: center; font-size: 15px;">
                <i class="fa-solid fa-paper-plane"></i> Send Push & In-App Notification
            </button>
        </form>
    </div>

    <div>
        <div class="notif-card" style="margin-bottom: 20px;">
            <h4 style="font-size: 16px; font-weight: 800; margin-bottom: 14px; color: var(--dark);">📊 Notification Statistics</h4>
            <div style="display: flex; flex-direction: column; gap: 14px;">
                <div style="padding: 14px; background: var(--bg); border-radius: 12px; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 13px; color: var(--text-muted); font-weight: 600;">Active Push FCM Tokens</span>
                    <span id="statFcmTokenCount" style="font-size: 18px; font-weight: 800; color: var(--primary);">--</span>
                </div>
                <div style="padding: 14px; background: var(--bg); border-radius: 12px; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 13px; color: var(--text-muted); font-weight: 600;">Total Active Users</span>
                    <span id="statTotalUserCount" style="font-size: 18px; font-weight: 800; color: #00B894;">--</span>
                </div>
            </div>
        </div>

        <div class="notif-card">
            <h4 style="font-size: 15px; font-weight: 800; margin-bottom: 10px; color: var(--dark);"><i class="fa-solid fa-circle-info" style="color: #0984E3;"></i> Delivery Note</h4>
            <p style="font-size: 13px; color: var(--text-muted); line-height: 1.6;">
                Notifications are simultaneously delivered via <strong>Firebase Cloud Messaging (FCM)</strong> push alerts to active devices and saved in the user's <strong>In-App Notification Inbox</strong>.
            </p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        loadNotificationSectionData();
    });

    async function loadNotificationSectionData() {
        try {
            const res = await fetch('/api/admin/users', {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + (localStorage.getItem('acadova_admin_token') || '')
                }
            });
            if (res.ok) {
                const data = await res.json();
                const users = data.data || data.users || [];
                populateNotificationUserDropdown(users);
                updateNotificationStats(users);
            }
        } catch (err) {
            console.error('Failed to fetch users for notification panel', err);
        }
    }

    function populateNotificationUserDropdown(users) {
        const select = document.getElementById('notifTargetUserId');
        if (!select) return;
        select.innerHTML = '<option value="">-- Choose User --</option>';
        users.forEach(u => {
            const opt = document.createElement('option');
            opt.value = u.id;
            opt.textContent = `${u.name} (${u.email}) [${u.role || 'user'}]`;
            select.appendChild(opt);
        });
    }

    function updateNotificationStats(users) {
        const tokenCountEl = document.getElementById('statFcmTokenCount');
        const userCountEl = document.getElementById('statTotalUserCount');
        if (userCountEl) userCountEl.innerText = users.length;
        if (tokenCountEl) {
            const fcmCount = users.filter(u => u.fcm_token && u.fcm_token.trim() !== '').length;
            tokenCountEl.innerText = fcmCount;
        }
    }

    function toggleNotificationTargetUserSelect() {
        const audience = document.getElementById('notifTargetAudience').value;
        const userGrp = document.getElementById('notifSpecificUserGroup');
        if (userGrp) {
            userGrp.style.display = (audience === 'user') ? 'block' : 'none';
        }
    }

    async function handleSendAdminNotification(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSendNotifSubmit');
        const audience = document.getElementById('notifTargetAudience').value;
        const userId = document.getElementById('notifTargetUserId').value;
        const title = document.getElementById('notifTitle').value.trim();
        const type = document.getElementById('notifCategoryType').value;
        const body = document.getElementById('notifBody').value.trim();

        if (audience === 'user' && !userId) {
            alert('Please select a specific user from the list.');
            return;
        }

        if (!title || !body) {
            alert('Title and Message body are required.');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Dispatching Notification...';

        try {
            const res = await fetch('/api/admin/notifications/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Authorization': 'Bearer ' + (localStorage.getItem('acadova_admin_token') || '')
                },
                body: JSON.stringify({
                    target_audience: audience,
                    user_id: userId ? parseInt(userId) : null,
                    title: title,
                    type: type,
                    body: body
                })
            });

            const json = await res.json();
            if (res.ok && json.status === 'success') {
                alert(json.message || 'Notification sent successfully!');
                document.getElementById('notifTitle').value = '';
                document.getElementById('notifBody').value = '';
            } else {
                alert(json.message || 'Failed to send notification.');
            }
        } catch (err) {
            console.error('Send Notification error:', err);
            alert('Network error while dispatching notification.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Send Push & In-App Notification';
        }
    }
</script>
@endsection
