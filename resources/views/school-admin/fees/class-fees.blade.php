@extends('layouts.app')

@section('title', 'Class Fees')

@section('page-title', 'Class Fees Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/fees') }}">Fees</a></li>
    <li class="breadcrumb-item active">Class Fees</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Class Fees Configuration</h5>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFeeModal">
                    <i class="fas fa-plus"></i> Add Class Fee
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Academic Year</th>
                        <th>Class</th>
                        <th>Medium</th>
                        <th>Tuition Fee</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fees as $fee)
                        <tr>
                            <td>{{ $fee->academicYear ? $fee->academicYear->year : '-' }}</td>
                            <td>{{ $fee->schoolClass ? $fee->schoolClass->class_name : '-' }}</td>
                            <td>{{ $fee->medium }}</td>
                            <td>₹{{ number_format($fee->tuition_fee, 2) }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editFeeModal{{ $fee->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('school-admin.fees.class.destroy', $fee->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        
                        <!-- Edit Modal -->
                        <div class="modal fade" id="editFeeModal{{ $fee->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Class Fee</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('school-admin.fees.class.update', $fee->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Academic Year</label>
                                                <select name="academic_year_id" class="form-select" required>
                                                    @foreach($years as $year)
                                                        <option value="{{ $year->id }}" {{ $fee->academic_year_id == $year->id ? 'selected' : '' }}>
                                                            {{ $year->year }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Class</label>
                                                <select name="class_id" class="form-select" required>
                                                    @foreach($classes as $class)
                                                        <option value="{{ $class->id }}" {{ $fee->class_id == $class->id ? 'selected' : '' }}>
                                                            {{ $class->class_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Medium</label>
                                                <select name="medium" class="form-select" required>
                                                    <option value="Bengali" {{ $fee->medium == 'Bengali' ? 'selected' : '' }}>Bengali</option>
                                                    <option value="English" {{ $fee->medium == 'English' ? 'selected' : '' }}>English</option>
                                                    <option value="Hindi" {{ $fee->medium == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Tuition Fee (₹)</label>
                                                <input type="number" name="tuition_fee" class="form-control" value="{{ $fee->tuition_fee }}" required min="0" step="0.01">
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
                            <td colspan="5" class="text-center">No class fees configured yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addFeeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Class Fee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('school-admin.fees.class.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Academic Year</label>
                        <select name="academic_year_id" class="form-select" required>
                            <option value="">Select Year</option>
                            @foreach($years as $year)
                                <option value="{{ $year->id }}">{{ $year->year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Class</label>
                        <select name="class_id" class="form-select" required>
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Medium</label>
                        <select name="medium" class="form-select" required>
                            <option value="">Select Medium</option>
                            <option value="Bengali">Bengali</option>
                            <option value="English">English</option>
                            <option value="Hindi">Hindi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tuition Fee (₹)</label>
                        <input type="number" name="tuition_fee" class="form-control" required min="0" step="0.01" placeholder="0.00">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
