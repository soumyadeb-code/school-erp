@extends('layouts.app')

@section('title', 'Edit Bus Fee')

@section('page-title', 'Edit Bus Fee')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('school-admin.fees.bus') }}">Bus Fees</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Bus Fee</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('school-admin.fees.bus.update', $busFee->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Destination <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="destination" value="{{ $busFee->destination }}" required placeholder="e.g., Route 1 - City Center">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Price (₹) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" class="form-control" name="price" value="{{ $busFee->price }}" min="0" step="0.01" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" name="status" required>
                            <option value="active" {{ $busFee->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $busFee->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('school-admin.fees.bus') }}" class="btn btn-secondary">
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
