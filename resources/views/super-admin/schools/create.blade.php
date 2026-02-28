@extends('layouts.app')

@section('title', 'Create School')

@section('page-title', 'Create School')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('super-admin.schools.index') }}">Schools</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">School Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('super-admin.schools.store') }}" novalidate>
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">School Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="trust_name" class="form-label">Trust Name</label>
                            <input type="text" class="form-control" id="trust_name" name="trust_name" value="{{ old('trust_name') }}">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label">School Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" maxlength="10" required>
                            <div class="invalid-feedback" id="code_error" style="display: none;">School code already exists</div>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="2">{{ old('address') }}</textarea>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="joining_date" class="form-label">Joining Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('joining_date') is-invalid @enderror" id="joining_date" name="joining_date" value="{{ old('joining_date') }}" required>
                            @error('joining_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="expiry_date" class="form-label">Expiry Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}" required>
                            @error('expiry_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr>
                    <h5 class="mb-3">School Admin Credentials</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="admin_name" class="form-label">Admin Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('admin_name') is-invalid @enderror" id="admin_name" name="admin_name" value="{{ old('admin_name') }}" required>
                            @error('admin_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="admin_email" class="form-label">Admin Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('admin_email') is-invalid @enderror" id="admin_email" name="admin_email" value="{{ old('admin_email') }}" required>
                            @error('admin_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="admin_password" class="form-label">Admin Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('admin_password') is-invalid @enderror" id="admin_password" name="admin_password" required minlength="8">
                        @error('admin_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('super-admin.schools.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create School</button>
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
    const codeError = document.getElementById('code_error');
    const form = document.querySelector('form');
    let codeTimeout;

    // Clear error when user types
    codeInput.addEventListener('input', function() {
        this.classList.remove('is-invalid');
        if (codeError) {
            codeError.style.display = 'none';
        }
    });

    // Check uniqueness on blur
    codeInput.addEventListener('blur', function() {
        checkCodeUnique(this.value);
    });

    // Also check on form submit
    form.addEventListener('submit', function(e) {
        if (codeInput.value && codeInput.classList.contains('is-invalid')) {
            e.preventDefault();
            return false;
        }
    });

    function checkCodeUnique(code) {
        if (!code) return;

        // Clear previous timeout
        if (codeTimeout) clearTimeout(codeTimeout);

        codeTimeout = setTimeout(function() {
            fetch('/super-admin/schools/check-code?code=' + encodeURIComponent(code))
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        codeInput.classList.add('is-invalid');
                        if (codeError) {
                            codeError.textContent = 'School code already exists';
                            codeError.style.display = 'block';
                        }
                    } else {
                        codeInput.classList.remove('is-invalid');
                        if (codeError) {
                            codeError.style.display = 'none';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error checking code:', error);
                });
        }, 300);
    }
});
</script>
@endpush
@endsection
