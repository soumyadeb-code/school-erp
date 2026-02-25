@extends('layouts.app')

@section('title', 'Edit School')

@section('page-title', 'Edit School')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('super-admin.schools.index') }}">Schools</a></li>
    <li class="breadcrumb-item"><a href="{{ route('super-admin.schools.show', $school->id) }}">{{ $school->name }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit School Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('super-admin.schools.update', $school->id) }}" novalidate>
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">School Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $school->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="code" class="form-label">School Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $school->code) }}" maxlength="10" required>
                            <div class="invalid-feedback" id="code_error" style="display: none;">School code already exists</div>
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
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $school->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="joining_date" class="form-label">Joining Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('joining_date') is-invalid @enderror" id="joining_date" name="joining_date" value="{{ old('joining_date', $school->joining_date->format('Y-m-d')) }}" required>
                            @error('joining_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="expiry_date" class="form-label">Expiry Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" id="expiry_date" name="expiry_date" value="{{ old('expiry_date', $school->expiry_date->format('Y-m-d')) }}" required>
                            @error('expiry_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="active" {{ old('status', $school->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $school->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('super-admin.schools.show', $school->id) }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Update School</button>
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
    const schoolId = {{ $school->id }};
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
            fetch('/super-admin/schools/check-code?code=' + encodeURIComponent(code) + '&exclude_id=' + schoolId)
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
