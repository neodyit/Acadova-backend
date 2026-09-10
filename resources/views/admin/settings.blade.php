@extends('layouts.admin_layout')

@section('title', 'System Settings - Acadova Admin')
@section('page_title', 'System & Database Settings')

@section('content')
<div style="margin-bottom: 28px;">
    <h1 style="font-size: 24px; font-weight: 800;">System Configuration</h1>
    <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Production server details, API endpoints, and database connection status.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
    <div style="background: white; border-radius: 18px; border: 1px solid var(--border); padding: 28px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
            <div style="width: 42px; height: 42px; background: #EEF2FF; color: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                <i class="fa-solid fa-server"></i>
            </div>
            <div>
                <h2 style="font-size: 16px; font-weight: 800;">Server Information</h2>
                <p style="font-size: 12px; color: var(--text-muted);">Hostinger LiteSpeed Production</p>
            </div>
        </div>
        <div style="display: flex; flex-direction: column; gap: 12px; font-size: 13.5px;">
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #F1F5F9; padding-bottom: 8px;">
                <span style="color: var(--text-muted);">Environment</span>
                <strong>Production</strong>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #F1F5F9; padding-bottom: 8px;">
                <span style="color: var(--text-muted);">PHP Version</span>
                <strong>{{ phpversion() }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #F1F5F9; padding-bottom: 8px;">
                <span style="color: var(--text-muted);">Laravel Framework</span>
                <strong>v{{ app()->version() }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: var(--text-muted);">Database Driver</span>
                <strong>MySQL / MariaDB</strong>
            </div>
        </div>
    </div>

    <div style="background: white; border-radius: 18px; border: 1px solid var(--border); padding: 28px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
            <div style="width: 42px; height: 42px; background: #E6FFFA; color: var(--secondary); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
                <h2 style="font-size: 16px; font-weight: 800;">API Security & Endpoints</h2>
                <p style="font-size: 12px; color: var(--text-muted);">RESTful JSON Endpoints</p>
            </div>
        </div>
        <div style="display: flex; flex-direction: column; gap: 12px; font-size: 13.5px;">
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #F1F5F9; padding-bottom: 8px;">
                <span style="color: var(--text-muted);">Base API URL</span>
                <strong style="color: var(--primary);">https://acadova.neodyit.com/api</strong>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #F1F5F9; padding-bottom: 8px;">
                <span style="color: var(--text-muted);">Web Authentication</span>
                <strong>Session Auth (`auth` guard)</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: var(--text-muted);">Mobile API Guard</span>
                <strong>Laravel Sanctum Bearer Token</strong>
            </div>
        </div>
    </div>
</div>
@endsection
