@extends('layouts.app')

@section('title', 'Admission Fees Setup')

@section('page-title', 'Admission Fees Setup')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="#">Fees Setup</a></li>
    <li class="breadcrumb-item active">Admission Fees</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Add Admission Fee</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('school-admin.fees.admission.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Academic Year <span class="text-danger">*</span></label>
                        <select class="form-select" name="academic_year_id" required>
                            <option value="">Select Year</option>
                            @foreach($academicYears as $year)
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
                            <option value="english">English</option>
                            <option value="bengali">Bengali</option>
                            <option value="hindi">Hindi</option>
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
                        <label class="form-label">Admission Start Date</label>
                        <input type="date" class="form-control" name="admission_start_date">
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
                <h5 class="mb-0">Admission Fees List</h5>
                <div>
                    <select class="form-select form-select-sm d-inline-block" style="width: auto;" id="yearFilter">
                        <option value="">All Years</option>
                        @foreach($academicYears as $year)
                        <option value="{{ $year->id }}">{{ $year->year }}</option>
                        @endforeach
                    </select>
                </div>
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admissionFees as $fee)
                            <tr>
                                <td>
                                    <span class="badge {{ $fee->academicYear && $fee->academicYear->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $fee->academicYear->year ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>{{ ucfirst($fee->medium) }}</td>
                                <td><strong>₹{{ number_format($fee->amount, 2) }}</strong></td>
                                <td>
                                    @if($fee->admission_start_date)
                                        {{ \Carbon\Carbon::parse($fee->admission_start_date)->format('d M Y') }}
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
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $fee->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" action="{{ route('school-admin.fees.admission.destroy', $fee->id) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            
                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $fee->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Admission Fee</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('school-admin.fees.admission.update', $fee->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Amount (₹)</label>
                                                    <input type="number" class="form-control" name="amount" value="{{ $fee->amount }}" min="0" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Admission Start Date</label>
                                                    <input type="date" class="form-control" name="admission_start_date" value="{{ $fee->admission_start_date }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select class="form-select" name="status">
                                                        <option value="active" {{ $fee->status === 'active' ? 'selected' : '' }}>Active</option>
                                                        <option value="inactive" {{ $fee->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No admission fees configured. Add your first fee structure.
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
