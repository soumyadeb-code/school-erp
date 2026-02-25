@extends('layouts.app')

@section('title', 'Edit Class Fee')

@section('page-title', 'Edit Class Fee')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('school-admin.fees.class') }}">Class Fees</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Class Fee</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('school-admin.fees.class.update', $classFee->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Academic Year <span class="text-danger">*</span></label>
                        <select class="form-select" name="academic_year_id" required>
                            <option value="">Select Year</option>
                            @foreach($years as $year)
                            <option value="{{ $year->id }}" {{ $classFee->academic_year_id == $year->id ? 'selected' : '' }}>
                                {{ $year->year }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Class <span class="text-danger">*</span></label>
                        <select class="form-select" name="class_id" required>
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $classFee->class_id == $class->id ? 'selected' : '' }}>
                                {{ $class->class_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Medium <span class="text-danger">*</span></label>
                        <select class="form-select" name="medium" required>
                            <option value="Bengali" {{ $classFee->medium == 'Bengali' ? 'selected' : '' }}>Bengali</option>
                            <option value="English" {{ $classFee->medium == 'English' ? 'selected' : '' }}>English</option>
                            <option value="Hindi" {{ $classFee->medium == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tuition Fee (₹) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" class="form-control" name="tuition_fee" value="{{ $classFee->tuition_fee }}" min="0" step="0.01" required>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('school-admin.fees.class') }}" class="btn btn-secondary">
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
