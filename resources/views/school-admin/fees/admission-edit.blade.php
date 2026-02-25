@extends('layouts.app')

@section('title', 'Edit Admission Fee')

@section('page-title', 'Edit Admission Fee')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('school-admin.fees.admission') }}">Admission Fees</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Admission Fee</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('school-admin.fees.admission.update', $admissionFee->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Academic Year</label>
                        <input type="text" class="form-control" value="{{ $admissionFee->academicYear->year ?? 'N/A' }}" disabled>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Medium</label>
                        <input type="text" class="form-control" value="{{ $admissionFee->medium }}" disabled>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Amount (₹) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" class="form-control" name="amount" value="{{ $admissionFee->amount }}" min="0" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Admission Start Date</label>
                        <input type="date" class="form-control" name="admission_start_date" value="{{ $admissionFee->admission_start_date }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="active" {{ $admissionFee->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $admissionFee->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('school-admin.fees.admission') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Fee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
