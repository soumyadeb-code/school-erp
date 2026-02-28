@extends('layouts.app')

@section('title', 'Registration Fee Not Set')

@section('page-title', 'Registration Fee Not Set')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('students.registration') }}">Registration</a></li>
    <li class="breadcrumb-item active">Fee Not Set</li>
@endsection

@section('content')

<div class="card">
    <div class="card-header bg-warning">
        <h5 class="mb-0">Registration Fee Not Set</h5>
    </div>
    <div class="card-body">
        <div class="alert alert-warning">
            <h4>Registration fee is not configured for {{ ucfirst($medium) }} medium!</h4>
            <p>Please set up the registration fee for the academic year {{ $academicYear->year }} and {{ ucfirst($medium) }} medium.</p>
        </div>
        
        <div class="card mb-3">
            <div class="card-body">
                <h6>Student Details:</h6>
                <table class="table table-bordered">
                    <tr>
                        <th>Name:</th>
                        <td>{{ $student->name }}</td>
                    </tr>
                    <tr>
                        <th>Student ID:</th>
                        <td>{{ $student->student_id }}</td>
                    </tr>
                    <tr>
                        <th>Class:</th>
                        <td>{{ $student->schoolClass->class_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Medium:</th>
                        <td>{{ ucfirst($student->medium) }}</td>
                    </tr>
                    <tr>
                        <th>Academic Year:</th>
                        <td>{{ $academicYear->year }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="text-center">
            <a href="{{ route('school-admin.fees.registration') }}" class="btn btn-primary">
                <i class="fas fa-cog"></i> Set Registration Fee
            </a>
            <a href="{{ route('students.registration') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Registration
            </a>
        </div>
    </div>
</div>
@endsection
