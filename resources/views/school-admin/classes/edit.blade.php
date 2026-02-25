@extends('layouts.app')

@section('title', 'Edit Class')

@section('page-title', 'Edit Class')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('school-admin.classes.index') }}">Classes</a></li>
    <li class="breadcrumb-item active">Edit Class</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Class</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('school-admin.classes.update', $class->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Class Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="class_name" value="{{ $class->class_name }}" placeholder="e.g., Nursery, L.K.G, One" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Minimum Age (Years) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="minimum_age" value="{{ $class->minimum_age }}" min="1" max="20" placeholder="e.g., 3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="active" {{ $class->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $class->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Class
                        </button>
                        <a href="{{ route('school-admin.classes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
