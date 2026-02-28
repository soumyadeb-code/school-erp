@extends('layouts.app')

@section('title', 'Student Registration')

@section('page-title', 'Student Registration')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Registration</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Registration List</h5>
            </div>
            <div class="card-body">
                @if($registeredStudents->isEmpty())
                    <div class="text-center text-muted py-4">
                        <p>No students pending registration.</p>
                        <p>Students who have completed admission will appear here for registration.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
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
                                @foreach($registeredStudents as $student)
                                <tr>
                                    <td><span class="badge bg-secondary">{{ $student->student_id }}</span></td>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->schoolClass->class_name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($student->dob)->format('d M Y') }}</td>
                                    <td>{{ ucfirst($student->medium) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($student->admission_date)->format('d M Y') }}</td>
                                    <td>
                                        @if($student->registration_status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($student->registration_status === 'completed')
                                            <span class="badge bg-success">Registered</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($student->registration_status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($student->registration_status === 'pending')
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
