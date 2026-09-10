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
            <div class="quiz-actions">
                <button class="btn btn-secondary" onclick="toggleCampaignStatus({{ $c->id }}, '{{ $c->status === 'active' ? 'inactive' : 'active' }}')">{{ $c->status === 'active' ? 'Hide Banner' : 'Activate' }}</button>
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
                        <option value="purple">Purple Gradient</option>
                        <option value="orange">Orange / Coral</option>
                        <option value="teal">Teal / Emerald</option>
                        <option value="blue">Ocean Blue</option>
                        <option value="pink">Pink / Rose</option>
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
    function openCreateCampaignModal() {
        document.getElementById('editingCampaignId').value = '';
        document.getElementById('createCampaignForm').reset();
        openModal('createCampaignModal');
    }

    async function handleSaveCampaign(e) {
        e.preventDefault();
        const payload = {
            title: document.getElementById('campaignTitle').value,
            badge: document.getElementById('campaignBadge').value,
            banner_color: document.getElementById('campaignColor').value,
            status: document.getElementById('campaignStatus').value,
            link_url: document.getElementById('campaignLink').value || null,
            description: document.getElementById('campaignDescription').value,
        };

        try {
            const res = await fetch('/api/campaigns', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: JSON.stringify(payload)
            });
            const json = await res.json();
            if (res.ok || json.success) {
                showToast('Campaign saved!');
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
