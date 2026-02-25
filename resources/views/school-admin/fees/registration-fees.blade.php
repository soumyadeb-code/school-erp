@extends('layouts.app')

@section('title', 'Registration Fees Setup')

@section('page-title', 'Registration Fees Setup')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="#">Fees Setup</a></li>
    <li class="breadcrumb-item active">Registration Fees</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Add Registration Fee</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('school-admin.fees.registration.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Academic Year <span class="text-danger">*</span></label>
                        <select class="form-select" name="academic_year_id" required>
                            <option value="">Select Year</option>
                            @foreach($years as $year)
                            <option value="{{ $year->id }}" {{ $year->is_active ? 'selected' : '' }}>
                                {{ $year->year }} {{ $year->is_active ? '(Active)' : '' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Medium <span class="text-danger">*</span></label>
                        <select class="form-select" name="medium" required>
                            <option value="">Select Medium</option>
                            <option value="English">English</option>
                            <option value="Bengali">Bengali</option>
                            <option value="Hindi">Hindi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount (₹) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" class="form-control" name="amount" min="0" placeholder="0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Registration Start Date</label>
                        <input type="date" class="form-control" name="registration_start_date">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Save Fee
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Registration Fees List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Academic Year</th>
                                <th>Medium</th>
                                <th>Amount</th>
                                <th>Start Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fees as $fee)
                            <tr>
                                <td>
                                    <span class="badge {{ $fee->academicYear && $fee->academicYear->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $fee->academicYear->year ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>{{ ucfirst($fee->medium) }}</td>
                                <td><strong>₹{{ number_format($fee->amount, 2) }}</strong></td>
                                <td>
                                    @if($fee->registration_start_date)
                                        {{ \Carbon\Carbon::parse($fee->registration_start_date)->format('d M Y') }}
                                    @else
                                        <span class="text-muted">Not Set</span>
                                    @endif
                                </td>
                                <td>
                                    @if($fee->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No registration fees configured. Add your first fee structure.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
