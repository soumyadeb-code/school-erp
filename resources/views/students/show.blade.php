@extends('layouts.app')

@section('title', 'Student Profile')

@section('page-title', 'Student Profile')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('students.list') }}">All Students</a></li>
    <li class="breadcrumb-item active">{{ $student->name }}</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Student Profile</h5>
        <div>
            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('students.list') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 text-center">
                @if($student->photo)
                    <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}" class="rounded-circle" width="150" height="150">
                @else
                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width:150px;height:150px;font-size:48px;">
                        {{ substr($student->name, 0, 1) }}
                    </div>
                @endif
                <h4 class="mt-3">{{ $student->name }}</h4>
                <p class="text-muted">{{ $student->student_id }}</p>
                <span class="badge bg-{{ $student->status == 'active' ? 'success' : 'secondary' }}">
                    {{ ucfirst($student->status) }}
                </span>
            </div>
            <div class="col-md-8">
                <h6 class="text-muted border-bottom pb-2">Basic Information</h6>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Class:</strong> {{ $student->schoolClass ? $student->schoolClass->class_name : '-' }}</p>
                        <p><strong>Next Class:</strong> {{ $student->schoolClass && $student->schoolClass->nextClass ? $student->schoolClass->nextClass->class_name : '-' }}</p>
                        <p><strong>Roll:</strong> {{ $student->roll ?? '-' }}</p>
                        <p><strong>Medium:</strong> {{ ucfirst($student->medium) }}</p>
                        <p><strong>Gender:</strong> {{ ucfirst($student->gender) }}</p>
                        <p><strong>Date of Birth:</strong> {{ $student->dob ? Carbon\Carbon::parse($student->dob)->format('d-m-Y') : '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Admission Date:</strong> {{ $student->admission_date ? Carbon\Carbon::parse($student->admission_date)->format('d-m-Y') : '-' }}</p>
                        <p><strong>Blood Group:</strong> {{ $student->blood_group ?? '-' }}</p>
                        <p><strong>Category:</strong> {{ $student->social_category ?? '-' }}</p>
                        <p><strong>Religion:</strong> {{ $student->religion ?? '-' }}</p>
                        <p><strong>Aadhaar:</strong> {{ $student->aadhaar ?? '-' }}</p>
                    </div>
                </div>

                <h6 class="text-muted border-bottom pb-2 mt-4">Parent Information</h6>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Father's Name:</strong> {{ $student->father_name ?? '-' }}</p>
                        <p><strong>Father's Education:</strong> {{ $student->father_education ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Mother's Name:</strong> {{ $student->mother_name ?? '-' }}</p>
                        <p><strong>Mother's Education:</strong> {{ $student->mother_education ?? '-' }}</p>
                    </div>
                </div>

                <h6 class="text-muted border-bottom pb-2 mt-4">Contact Information</h6>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Phone:</strong> {{ $student->phone ?? '-' }}</p>
                        <p><strong>WhatsApp:</strong> {{ $student->whatsapp ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Email:</strong> {{ $student->email ?? '-' }}</p>
                        <p><strong>Address:</strong> {{ $student->address ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
