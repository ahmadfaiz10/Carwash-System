@extends('layouts.staff')

@section('title', 'Edit Profile - AutoShineX')
@section('page_title', 'Edit Profile')
@section('page_subtitle', 'Update your staff account information')

@section('content')

@php
    $photoUrl = $user->Image ? asset('storage/staff/'.$user->Image) : null;
@endphp

<style>
    .edit-profile-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        align-items: flex-start;
    }

    .edit-profile-card {
        flex: 1 1 320px;
        max-width: 560px;
        border-radius: 18px;
        padding: 20px 20px 18px;
        background: radial-gradient(circle at top left, #e0f2fe, #f9fafb);
        border: 1px solid #bfdbfe;
        box-shadow: 0 10px 28px rgba(15,23,42,0.12);
    }

    .edit-profile-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 14px;
    }

    .edit-profile-avatar {
        width: 70px;
        height: 70px;
        border-radius: 999px;
        overflow: hidden;
        border: 3px solid rgba(37,99,235,0.8);
        background: radial-gradient(circle, #38bdf8, #0ea5e9, #1d4ed8);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #e0f2fe;
    }

    .edit-profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .edit-profile-avatar-initial {
        font-size: 30px;
        font-weight: 800;
    }

    .edit-profile-title {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
    }

    .edit-profile-sub {
        font-size: 12px;
        color: #6b7280;
        margin-top: 2px;
    }

    .form-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px 16px;
        margin-top: 12px;
    }

    @media (max-width: 650px) {
        .form-grid-2 {
            grid-template-columns: 1fr;
        }
    }

    .field-full {
        grid-column: 1 / -1;
    }

    .form-label {
        font-size: 12px;
        font-weight: 600;
        color: #4b5563;
        margin-bottom: 3px;
        display: block;
    }

    .form-input, .form-textarea {
        width: 100%;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 8px 10px;
        font-size: 13px;
        background: #ffffff;
        color: #0f172a;
        box-sizing: border-box;
    }

    .form-textarea { resize: vertical; min-height: 70px; }

    .error-text { font-size: 11px; color: #b91c1c; margin-top: 2px; }

    .edit-profile-footer {
        margin-top: 14px;
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .btn-save {
        border: none;
        border-radius: 999px;
        padding: 8px 16px;
        cursor: pointer;
        background: linear-gradient(135deg,#2563eb,#1d4ed8);
        color: #e0f2fe;
        font-size: 13px;
        font-weight: 600;
        box-shadow: 0 6px 16px rgba(37,99,235,0.45);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-save i { font-size: 14px; }

    .btn-cancel-link {
        font-size: 12px;
        text-decoration: none;
        color: #6b7280;
    }
</style>

<div class="edit-profile-wrap">
    <form action="{{ route('staff.profile.update') }}"
          method="POST"
          enctype="multipart/form-data"
          class="edit-profile-card">

        @csrf

        {{-- Header --}}
        <div class="edit-profile-header">
            <div class="edit-profile-avatar">
                @if($photoUrl)
                    <img src="{{ $photoUrl }}" id="previewImage" alt="Profile Photo">
                @else
                    <div class="edit-profile-avatar-initial" id="previewInitial">
                        {{ strtoupper(substr($user->FullName ?? 'S', 0, 1)) }}
                    </div>
                @endif
            </div>

            <div>
                <div class="edit-profile-title">{{ $user->FullName }}</div>
                <div class="edit-profile-sub">Update your personal information.</div>

                {{-- Upload field --}}
                <label style="font-size:11px; margin-top:6px; cursor:pointer; display:inline-block;">
                    <input type="file" name="Image" accept="image/*"
                           style="display:none;"
                           onchange="previewPhoto(event)">
                    <span style="color:#2563eb; font-weight:600; cursor:pointer;">
                        Change Profile Photo
                    </span>
                </label>

                @error('Image')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Form fields --}}
        <div class="form-grid-2">

            <div class="field-full">
                <label class="form-label">Full Name</label>
                <input type="text" name="FullName" class="form-input"
                       value="{{ old('FullName', $user->FullName) }}" required>
                @error('FullName') <div class="error-text">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="form-label">Phone Number</label>
                <input type="text" name="PhoneNumber" class="form-input"
                       value="{{ old('PhoneNumber', $user->PhoneNumber) }}" required>
                @error('PhoneNumber') <div class="error-text">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="form-label">Email</label>
                <input type="email" name="Email" class="form-input"
                       value="{{ old('Email', $user->Email) }}" required>
                @error('Email') <div class="error-text">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="form-label">Username</label>
                <input type="text" name="UserName" class="form-input"
                       value="{{ old('UserName', $user->UserName) }}" required>
                @error('UserName') <div class="error-text">{{ $message }}</div> @enderror
            </div>

            <div class="field-full">
                <label class="form-label">Address</label>
                <textarea name="Address" class="form-textarea">{{ old('Address', $user->Address) }}</textarea>
                @error('Address') <div class="error-text">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Footer --}}
        <div class="edit-profile-footer">
            <button type="submit" class="btn-save">
                <i class="bi bi-check2-circle"></i> Save Changes
            </button>

            <a href="{{ route('staff.profile') }}" class="btn-cancel-link">
                Cancel & Go Back
            </a>
        </div>
    </form>
</div>

<script>
    function previewPhoto(event) {
        const img = document.getElementById('previewImage');
        const initial = document.getElementById('previewInitial');

        if (event.target.files.length > 0) {
            const src = URL.createObjectURL(event.target.files[0]);
            if (img) {
                img.src = src;
            } else {
                initial.style.display = 'none';
            }
        }
    }
</script>

@endsection
