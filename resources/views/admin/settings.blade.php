@extends('admin.layout')

@section('content')
<div class="admin-header" style="font-size:2rem;font-weight:700;margin-bottom:2rem;display:flex;align-items:center;gap:0.7rem;"><i class="fas fa-cogs"></i> System Settings</div>
<div style="max-width:700px;margin:0 auto;background:rgba(24,31,27,0.95);border-radius:18px;box-shadow:0 4px 32px rgba(34,139,34,0.13);padding:2.5rem 2.2rem 2rem 2.2rem;backdrop-filter:blur(2px);">
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" id="settings-form">
        @csrf
        <div class="mb-4">
            <label class="input-label" for="site_name">Site/Application Name</label>
            <input type="text" class="text-input" id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? '') }}" required>
        </div>
        <div class="mb-4">
            <label class="input-label" for="logo">Logo</label>
            <input type="file" class="text-input" id="logo" name="logo" accept="image/*">
        </div>
        <div class="mb-4">
            <label class="input-label" for="contact_email">Contact Email</label>
            <input type="email" class="text-input" id="contact_email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}">
        </div>
        <div class="mb-4">
            <label class="input-label" for="contact_phone">Contact Phone</label>
            <input type="text" class="text-input" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}">
        </div>
        <div class="mb-4">
            <label class="input-label" for="maintenance_mode">Maintenance Mode</label>
            <select class="text-input" id="maintenance_mode" name="maintenance_mode">
                <option value="0" {{ (old('maintenance_mode', $settings['maintenance_mode'] ?? 0) == 0) ? 'selected' : '' }}>Off</option>
                <option value="1" {{ (old('maintenance_mode', $settings['maintenance_mode'] ?? 0) == 1) ? 'selected' : '' }}>On</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="input-label" for="default_user_role">Default User Role</label>
            <select class="text-input" id="default_user_role" name="default_user_role">
                <option value="user" {{ (old('default_user_role', $settings['default_user_role'] ?? 'user') == 'user') ? 'selected' : '' }}>User</option>
                <option value="admin" {{ (old('default_user_role', $settings['default_user_role'] ?? 'user') == 'admin') ? 'selected' : '' }}>Admin</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="input-label" for="about">About/Description</label>
            <textarea class="text-input" id="about" name="about" rows="3">{{ old('about', $settings['about'] ?? '') }}</textarea>
        </div>
        <div class="mb-4">
            <label class="input-label" for="custom_settings">Custom App Settings</label>
            <textarea class="text-input" id="custom_settings" name="custom_settings" rows="2">{{ old('custom_settings', $settings['custom_settings'] ?? '') }}</textarea>
        </div>
        <button type="submit" class="primary-button" style="width:100%;font-size:1.15rem;padding:0.8rem 0;margin-top:0.7rem;">Save Settings</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: @json(session('success')),
        confirmButtonColor: '#228B22',
        background: '#181f1b',
        color: '#f3fdf7',
    });
</script>
@endif
@if($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        html: `{!! implode('<br>', $errors->all()) !!}`,
        confirmButtonColor: '#228B22',
        background: '#181f1b',
        color: '#f3fdf7',
    });
</script>
@endif
<style>
.input-label {font-weight:600;color:#6ee7b7;margin-bottom:0.3rem;display:block;}
.text-input {width:100%;padding:0.7rem 1rem;border-radius:10px;border:none;background:#232d25;color:#f3fdf7;font-size:1.08rem;margin-bottom:0.2rem;box-shadow:0 1px 4px rgba(34,139,34,0.07);}
.text-input:focus {outline:2px solid #6ee7b7;background:#1e2b22;}
.primary-button {background:linear-gradient(90deg,#228B22 80%,#6ee7b7 100%);color:#fff;border:none;border-radius:12px;font-weight:700;transition:background 0.18s,box-shadow 0.16s;box-shadow:0 2px 8px rgba(34,139,34,0.13);cursor:pointer;}
.primary-button:hover {background:linear-gradient(90deg,#6ee7b7 80%,#228B22 100%);}
.mb-4 {margin-bottom:1.3rem;}
</style>
@endsection 