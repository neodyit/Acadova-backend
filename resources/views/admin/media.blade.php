@extends('layouts.admin_layout')

@section('title', 'Media Library - Acadova Admin')
@section('page_title', 'Media Library & Files')

@section('styles')
<style>
    .media-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; }
</style>
@endsection

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 800;">Media Storage</h1>
        <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Upload user avatars, banner graphics, and media assets.</p>
    </div>
</div>

<div style="background: white; border-radius: 18px; border: 1px solid var(--border); padding: 24px; margin-bottom: 32px;">
    <h2 style="font-size: 16px; font-weight: 800; margin-bottom: 16px;">Upload Media Asset</h2>
    <form id="uploadMediaForm" onsubmit="handleUploadMedia(event)" style="display: flex; gap: 16px; align-items: center; flex-wrap: wrap;">
        <input type="file" id="mediaFileInput" class="form-control" style="max-width: 320px;" required>
        <select id="mediaFolderSelect" class="form-control" style="max-width: 180px;">
            <option value="avatars">Avatars</option>
            <option value="banners">Banners</option>
            <option value="quizzes">Quiz Assets</option>
            <option value="documents">Documents</option>
        </select>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-upload"></i> Upload File</button>
    </form>
</div>

<div class="media-grid" id="mediaGrid">
    <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--text-muted);">Loading media files...</div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', loadMediaFiles);

    async function loadMediaFiles() {
        try {
            const res = await fetch('/api/media');
            const json = await res.json();
            const files = json.data || [];
            const grid = document.getElementById('mediaGrid');

            if (files.length === 0) {
                grid.innerHTML = `<div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--text-muted);">No uploaded media files found.</div>`;
                return;
            }

            grid.innerHTML = files.map(f => `
                <div class="table-card" style="padding: 16px;">
                    <div style="height: 140px; background: #F1F5F9; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 14px; overflow: hidden;">
                        ${isImage(f.file_name) 
                            ? `<img src="${f.url}" style="width: 100%; height: 100%; object-fit: cover;">`
                            : `<i class="fa-solid fa-file" style="font-size: 36px; color: var(--text-muted);"></i>`
                        }
                    </div>
                    <div style="font-size: 13px; font-weight: 700; word-break: break-all; margin-bottom: 4px;">${f.file_name}</div>
                    <div style="font-size: 11.5px; color: var(--text-muted); margin-bottom: 12px;">${(f.size_bytes / 1024).toFixed(1)} KB • ${f.last_modified}</div>
                    <button class="btn btn-secondary" style="font-size: 11px; padding: 6px 10px; width: 100%; justify-content: center;" onclick="copyToClipboard('${f.url}')">
                        <i class="fa-solid fa-copy"></i> Copy Direct URL
                    </button>
                </div>
            `).join('');
        } catch (e) {
            showToast('Failed to load media files');
        }
    }

    function isImage(filename) {
        return /\.(jpg|jpeg|png|gif|webp|svg)$/i.test(filename);
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text);
        showToast('Direct URL copied to clipboard!');
    }

    async function handleUploadMedia(e) {
        e.preventDefault();
        const fileInput = document.getElementById('mediaFileInput');
        const folder = document.getElementById('mediaFolderSelect').value;
        if (!fileInput.files || fileInput.files.length === 0) return;

        const formData = new FormData();
        formData.append('file', fileInput.files[0]);
        formData.append('folder', folder);

        try {
            const res = await fetch('/api/upload', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: formData,
            });
            const json = await res.json();
            if (json.success) {
                showToast('File uploaded successfully!');
                fileInput.value = '';
                loadMediaFiles();
            } else { showToast(json.message || 'Upload failed'); }
        } catch (e) { showToast('File upload error'); }
    }
</script>
@endsection
