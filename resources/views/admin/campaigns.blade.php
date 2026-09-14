@extends('layouts.admin_layout')

@section('title', 'Campaigns & Notices - Acadova Admin')
@section('page_title', 'Campaigns & Notices')

@section('styles')
<style>
    .quiz-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 24px; }
    .quiz-card { background: white; border-radius: 18px; padding: 24px; border: 1px solid var(--border); display: flex; flex-direction: column; box-shadow: 0 2px 10px rgba(0,0,0,0.03); }
    .quiz-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px; }
    .quiz-title { font-size: 17px; font-weight: 800; color: var(--dark); margin-bottom: 6px; }
    .quiz-sub { font-size: 13px; color: var(--text-muted); margin-bottom: 18px; font-weight: 500; }
    .quiz-meta { display: flex; flex-direction: column; gap: 6px; font-size: 12px; color: var(--text-muted); margin-bottom: 20px; padding: 12px; background: var(--bg); border-radius: 12px; }
    .quiz-actions { margin-top: auto; display: flex; gap: 10px; }
    .quiz-actions .btn { flex: 1; justify-content: center; padding: 10px 14px; font-size: 13px; }
</style>
@endsection

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 800;">Campaigns & Student Notices</h1>
        <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Publish promotional banners, announcement cards, and contest notices for students.</p>
    </div>
    <button class="btn btn-primary" onclick="openCreateCampaignModal()"><i class="fa-solid fa-bullhorn"></i> New Campaign</button>
</div>

<div class="quiz-grid">
    @forelse($campaigns as $c)
        <div class="quiz-card">
            <div class="quiz-header">
                <span class="badge" style="padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 11px; {{ $c->status === 'active' ? 'background: #DEF7EC; color: #03543F;' : 'background: #EDF2F7; color: #4A5568;' }}">{{ strtoupper($c->status) }}</span>
                <span style="font-size: 12px; font-weight: 700; color: var(--primary);">{{ $c->badge ?: 'Notice' }}</span>
            </div>
            <div class="quiz-title">{{ $c->title }}</div>
            <div class="quiz-sub">{{ $c->description }}</div>
            @if($c->ends_at)
                <div class="quiz-meta">
                    <span><i class="fa-regular fa-clock" style="color: #E17055;"></i> Auto-Expires: <strong>{{ \Carbon\Carbon::parse($c->ends_at)->format('d-m-Y H:i') }}</strong></span>
                </div>
            @endif
            <div class="quiz-actions">
                <button class="btn btn-secondary" onclick="editCampaign({{ json_encode($c) }})"><i class="fa-solid fa-pen"></i> Edit</button>
                <button class="btn btn-secondary" onclick="toggleCampaignStatus({{ $c->id }}, '{{ $c->status === 'active' ? 'inactive' : 'active' }}')">{{ $c->status === 'active' ? 'Hide' : 'Activate' }}</button>
                <button class="btn btn-danger" onclick="deleteCampaign({{ $c->id }})"><i class="fa-solid fa-trash"></i></button>
            </div>
        </div>
    @empty
        <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--text-muted);">No campaigns found. Click "New Campaign" to create one.</div>
    @endforelse
</div>

<!-- Modal: Create / Edit Campaign -->
<div class="modal-overlay" id="createCampaignModal">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title" id="campaignModalTitleText">Create Campaign / Notice</div>
            <button class="close-btn" onclick="closeModal('createCampaignModal')">&times;</button>
        </div>
        <form id="createCampaignForm" onsubmit="handleSaveCampaign(event)">
            <input type="hidden" id="editingCampaignId">
            <div class="form-group">
                <label>Campaign Title</label>
                <input type="text" id="campaignTitle" class="form-control" placeholder="e.g. 🏆 Annual Tech Quiz League 2026" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Badge / Category</label>
                    <input type="text" id="campaignBadge" class="form-control" placeholder="e.g. Featured Event" required>
                </div>
                <div class="form-group">
                    <label>Banner Theme Color</label>
                    <select id="campaignColor" class="form-control">
                        <option value="amber">Warm Amber / Terracotta (Brand Default)</option>
                        <option value="orange">Rust Coral / Terracotta Orange</option>
                        <option value="teal">Forest Emerald</option>
                        <option value="blue">Deep Ocean Blue</option>
                        <option value="brown">Deep Warm Chocolate</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Status</label>
                    <select id="campaignStatus" class="form-control">
                        <option value="active">Active (Visible on Dashboard)</option>
                        <option value="inactive">Inactive (Hidden)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Action Link URL (Optional)</label>
                    <input type="url" id="campaignLink" class="form-control" placeholder="https://example.com/register">
                </div>
            </div>

            <div class="form-group">
                <label>Banner Image / Poster Header (Optional)</label>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 6px;">
                    <input type="file" id="campaignImageFile" class="form-control" accept="image/*" onchange="uploadCampaignBannerFile(this)">
                    <input type="hidden" id="campaignImageUrl">
                </div>
                <div id="campaignImagePreview" style="display: none; margin-top: 8px; position: relative; max-width: 200px;">
                    <img id="campaignPreviewImg" src="" style="width: 100%; border-radius: 8px; border: 1px solid var(--border);" alt="Preview">
                    <button type="button" onclick="clearCampaignBannerImage()" style="position: absolute; top: -6px; right: -6px; background: #FF7675; color: white; border: none; border-radius: 50%; width: 22px; height: 22px; cursor: pointer; font-size: 12px; line-height: 22px; text-align: center;">&times;</button>
                </div>
                <small style="color: var(--text-muted); font-size: 11px;">Upload a poster/banner image (JPEG, PNG, WebP) to display on top of the campaign card.</small>
            </div>

            <div class="form-group">
                <label style="color: #E17055; font-weight: 700;"><i class="fa-regular fa-clock"></i> Expiration End Date & Time (Optional)</label>
                <input type="datetime-local" id="campaignEndsAt" class="form-control">
                <small style="color: var(--text-muted); font-size: 11px;">Notice will auto-hide from dashboard after this time</small>
            </div>

            <div class="form-group">
                <label>Description / Notice Body</label>
                <textarea id="campaignDescription" class="form-control" rows="3" placeholder="Write detailed notice..." required></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('createCampaignModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Campaign</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function formatLocalDatetimeInput(dateVal) {
        if (!dateVal) return '';
        let str = String(dateVal);
        if (str.includes(' ') && !str.includes('T')) {
            str = str.replace(' ', 'T');
        }
        const d = new Date(str);
        if (isNaN(d.getTime())) return '';
        const pad = n => String(n).padStart(2, '0');
        return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
    }

    function clearCampaignBannerImage() {
        document.getElementById('campaignImageFile').value = '';
        document.getElementById('campaignImageUrl').value = '';
        document.getElementById('campaignImagePreview').style.display = 'none';
        document.getElementById('campaignPreviewImg').src = '';
    }

    async function uploadCampaignBannerFile(input) {
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];
        const formData = new FormData();
        formData.append('file', file);
        formData.append('folder', 'campaigns');

        try {
            showToast('Uploading banner image...');
            const res = await fetch('/api/upload', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: formData
            });
            const json = await res.json();
            if (res.ok && json.success && json.data && json.data.url) {
                document.getElementById('campaignImageUrl').value = json.data.url;
                document.getElementById('campaignPreviewImg').src = json.data.url;
                document.getElementById('campaignImagePreview').style.display = 'block';
                showToast('Image uploaded successfully!');
            } else {
                showToast(json.message || 'Failed to upload banner image');
            }
        } catch (err) {
            showToast('Error uploading banner image');
        }
    }

    function openCreateCampaignModal() {
        document.getElementById('editingCampaignId').value = '';
        document.getElementById('createCampaignForm').reset();
        clearCampaignBannerImage();
        document.getElementById('campaignEndsAt').value = '';
        document.getElementById('campaignModalTitleText').innerText = 'Create Campaign / Notice';
        openModal('createCampaignModal');
    }

    function editCampaign(c) {
        document.getElementById('editingCampaignId').value = c.id;
        document.getElementById('campaignTitle').value = c.title || '';
        document.getElementById('campaignBadge').value = c.badge || '';
        document.getElementById('campaignColor').value = c.banner_color || 'amber';
        document.getElementById('campaignStatus').value = c.status || 'active';
        
        if (c.image_url) {
            document.getElementById('campaignImageUrl').value = c.image_url;
            document.getElementById('campaignPreviewImg').src = c.image_url.startsWith('http') ? c.image_url : `/api/media/file/${c.image_url}`;
            document.getElementById('campaignImagePreview').style.display = 'block';
        } else {
            clearCampaignBannerImage();
        }

        document.getElementById('campaignLink').value = c.link_url || '';
        document.getElementById('campaignEndsAt').value = formatLocalDatetimeInput(c.ends_at);
        document.getElementById('campaignDescription').value = c.description || '';
        document.getElementById('campaignModalTitleText').innerText = 'Edit Campaign Details';
        openModal('createCampaignModal');
    }

    async function handleSaveCampaign(e) {
        e.preventDefault();
        const campaignId = document.getElementById('editingCampaignId').value;
        const endsAtVal = document.getElementById('campaignEndsAt').value;

        const payload = {
            title: document.getElementById('campaignTitle').value,
            badge: document.getElementById('campaignBadge').value,
            banner_color: document.getElementById('campaignColor').value,
            status: document.getElementById('campaignStatus').value,
            image_url: document.getElementById('campaignImageUrl').value || null,
            link_url: document.getElementById('campaignLink').value || null,
            description: document.getElementById('campaignDescription').value,
            ends_at: endsAtVal ? endsAtVal : null,
        };

        const url = campaignId ? `/api/campaigns/${campaignId}` : '/api/campaigns';
        const method = campaignId ? 'PUT' : 'POST';

        try {
            const res = await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: JSON.stringify(payload)
            });
            const json = await res.json();
            if (res.ok || json.success) {
                showToast(campaignId ? 'Campaign updated!' : 'Campaign saved!');
                closeModal('createCampaignModal');
                location.reload();
            } else { showToast(json.message || 'Failed to save campaign'); }
        } catch (e) { showToast('Error saving campaign'); }
    }

    async function toggleCampaignStatus(id, newStatus) {
        try {
            const res = await fetch(`/api/campaigns/${id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: JSON.stringify({ status: newStatus })
            });
            if (res.ok) { showToast('Status updated!'); location.reload(); }
        } catch (e) { showToast('Error updating status'); }
    }

    async function deleteCampaign(id) {
        if (!confirm('Delete this campaign notice?')) return;
        try {
            const res = await fetch(`/api/campaigns/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF_TOKEN } });
            if (res.ok) { showToast('Campaign deleted'); location.reload(); }
        } catch (e) { showToast('Error deleting campaign'); }
    }
</script>
@endsection
