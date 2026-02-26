@extends('layouts.app')

@section('title', 'School Profile')

@section('page-title', 'School Profile')

@section('breadcrumb')
    <li class="breadcrumb-item active">Profile</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <!-- School Information Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-school me-2"></i>School Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('school-admin.profile.update') }}" id="profileForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">School Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" 
                                value="{{ old('name', $school->name) }}" readonly style="background-color: #f8f9fa;">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="code" class="form-label">School Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                id="code" name="code" 
                                value="{{ old('code', $school->code) }}" maxlength="10" required>
                            <div class="invalid-feedback" id="code_error" style="display: none;"></div>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="2">{{ old('address', $school->address) }}</textarea>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $school->phone) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                id="email" name="email" 
                                value="{{ old('email', $school->email) }}" required>
                            <div class="invalid-feedback" id="email_error" style="display: none;"></div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Change Password Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-lock me-2"></i>Change Password</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('school-admin.profile.password') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                            id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                            id="password" name="password" minlength="8" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Minimum 8 characters</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" 
                            id="password_confirmation" name="password_confirmation" required>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-warning">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('code');
    const emailInput = document.getElementById('email');
    const codeError = document.getElementById('code_error');
    const emailError = document.getElementById('email_error');
    const form = document.getElementById('profileForm');
    const schoolId = {{ $school->id }};
    let codeTimeout;
    let emailTimeout;

    codeInput.addEventListener('input', function() {
        this.classList.remove('is-invalid');
        if (codeError) codeError.style.display = 'none';
    });

    emailInput.addEventListener('input', function() {
        this.classList.remove('is-invalid');
        if (emailError) emailError.style.display = 'none';
    });

    codeInput.addEventListener('blur', function() {
        if (this.value && this.value !== '{{ $school->code }}') checkCodeUnique(this.value);
    });

    emailInput.addEventListener('blur', function() {
        if (this.value && this.value !== '{{ $school->email }}') checkEmailUnique(this.value);
    });

    form.addEventListener('submit', function(e) {
        let isValid = true;
        if (codeInput.value !== '{{ $school->code }}' && codeInput.classList.contains('is-invalid')) isValid = false;
        if (emailInput.value !== '{{ $school->email }}' && emailInput.classList.contains('is-invalid')) isValid = false;
        if (!isValid) { e.preventDefault(); return false; }
    });

    function checkCodeUnique(code) {
        if (!code) return;
        if (codeTimeout) clearTimeout(codeTimeout);
        codeTimeout = setTimeout(function() {
            fetch('/school-admin/profile/check-code?code=' + encodeURIComponent(code) + '&exclude_id=' + schoolId)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        codeInput.classList.add('is-invalid');
                        if (codeError) { codeError.textContent = 'School code already exists'; codeError.style.display = 'block'; }
                    } else {
                        codeInput.classList.remove('is-invalid');
                        if (codeError) codeError.style.display = 'none';
                    }
                });
        }, 300);
    }

    function checkEmailUnique(email) {
        if (!email) return;
        if (emailTimeout) clearTimeout(emailTimeout);
        emailTimeout = setTimeout(function() {
            fetch('/school-admin/profile/check-email?email=' + encodeURIComponent(email) + '&exclude_id=' + schoolId)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        emailInput.classList.add('is-invalid');
                        if (emailError) { emailError.textContent = 'School email already exists'; emailError.style.display = 'block'; }
                    } else {
                        emailInput.classList.remove('is-invalid');
                        if (emailError) emailError.style.display = 'none';
                    }
                });
        }, 300);
    }
});
</script>
@endpush
@endsection
