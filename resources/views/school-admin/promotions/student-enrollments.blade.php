@extends('layouts.app')

@section('title', 'Student Enrollments - ' . $student->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Academic History - {{ $student->name }}
                        <span class="badge bg-{{ $student->status == 'active' ? 'success' : ($student->status == 'tc_issued' ? 'danger' : 'secondary') }}">
                            {{ ucfirst($student->status) }}
                        </span>
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('school-admin.promotions.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Student Info -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <strong>Student ID:</strong> {{ $student->student_id }}
                        </div>
                        <div class="col-md-3">
                            <strong>Name:</strong> {{ $student->name }}
                        </div>
                        <div class="col-md-3">
                            <strong>Current Class:</strong> {{ $student->schoolClass ? $student->schoolClass->class_name : '-' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Medium:</strong> {{ ucfirst($student->medium) }}
                        </div>
                    </div>

                    <!-- Admission Record -->
                    <h4>Admission Record (First Admission - Never Changes)</h4>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Admission Date</th>
                                    <th>Academic Year</th>
                                    <th>Class</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($admission)
                                    <tr>
                                        <td>{{ Carbon\Carbon::parse($admission->admission_date)->format('d-m-Y') }}</td>
                                        <td>{{ $admission->academicYear ? $admission->academicYear->year : '-' }}</td>
                                        <td>{{ $admission->schoolClass ? $admission->schoolClass->class_name : '-' }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center">No admission record found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Enrollments -->
                    <h4>Yearly Enrollments</h4>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Academic Year</th>
                                    <th>Class</th>
                                    <th>Roll</th>
                                    <th>Section</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($enrollments as $enrollment)
                                    <tr>
                                        <td>{{ $enrollment->academicYear ? $enrollment->academicYear->year : '-' }}</td>
                                        <td>{{ $enrollment->schoolClass ? $enrollment->schoolClass->class_name : '-' }}</td>
                                        <td>{{ $enrollment->roll ?? '-' }}</td>
                                        <td>{{ $enrollment->section ?? '-' }}</td>
                                        <td>
                                            @if($enrollment->status == 'admitted')
                                                <span class="badge bg-primary">Admitted</span>
                                            @elseif($enrollment->status == 'enrolled')
                                                <span class="badge bg-info">Enrolled</span>
                                            @elseif($enrollment->status == 'promoted')
                                                <span class="badge bg-success">Promoted</span>
                                            @elseif($enrollment->status == 'completed')
                                                <span class="badge bg-warning">Completed</span>
                                            @elseif($enrollment->status == 'tc_issued')
                                                <span class="badge bg-danger">TC Issued</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $enrollment->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No enrollment records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Promotions -->
                    <h4>Promotion History</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>From Year</th>
                                    <th>To Year</th>
                                    <th>From Class</th>
                                    <th>To Class</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($promotions as $promotion)
                                    <tr>
                                        <td>{{ Carbon\Carbon::parse($promotion->promotion_date)->format('d-m-Y') }}</td>
                                        <td>{{ $promotion->fromAcademicYear ? $promotion->fromAcademicYear->year : '-' }}</td>
                                        <td>{{ $promotion->toAcademicYear ? $promotion->toAcademicYear->year : '-' }}</td>
                                        <td>{{ $promotion->fromClass ? $promotion->fromClass->class_name : '-' }}</td>
                                        <td>{{ $promotion->toClass ? $promotion->toClass->class_name : '-' }}</td>
                                        <td>{{ $promotion->remarks ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No promotion records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
