@extends('layouts.app')

@section('title', 'Student Admission')

@section('page-title', 'Student Admission')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Admission</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>New Admission</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('students.admission.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Student Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="dob" id="dob" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Class <span class="text-danger">*</span></label>
                        <select class="form-select" name="class_id" id="class_id" required>
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}" data-min-age="{{ $class->minimum_age }}">
                                {{ $class->class_name }} (Min Age: {{ $class->minimum_age }}+)
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Medium <span class="text-danger">*</span></label>
                        <select class="form-select" name="medium" id="medium" required>
                            <option value="">Select Medium</option>
                            <option value="English">English</option>
                            <option value="Bengali">Bengali</option>
                            <option value="Hindi">Hindi</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Admission Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="admission_date" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">WhatsApp Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="whatsapp_number" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Father's Name</label>
                        <input type="text" class="form-control" name="father_name">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Mother's Name</label>
                        <input type="text" class="form-control" name="mother_name">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" class="form-control" name="phone">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                        <select class="form-select" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" name="address" rows="2"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-plus me-2"></i>Add Student
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Admission List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="studentsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>DOB</th>
                                <th>Medium</th>
                                <th>Admission Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingStudents as $student)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $student->student_id }}</span></td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->schoolClass->class_name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($student->dob)->format('d M Y') }}</td>
                                <td>{{ ucfirst($student->medium) }}</td>
                                <td>{{ \Carbon\Carbon::parse($student->admission_date)->format('d M Y') }}</td>
                                <td>
                                    @if($student->admission_status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($student->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($student->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->admission_status === 'pending')
                                    <a href="{{ route('students.billing', $student->id) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-file-invoice"></i> Generate Bill
                                    </a>
                                    @else
                                    <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    No students found
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
