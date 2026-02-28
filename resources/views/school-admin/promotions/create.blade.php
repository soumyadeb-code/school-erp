@extends('layouts.app')

@section('title', 'Bulk Student Promotion')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bulk Student Promotion</h3>
                    <div class="card-tools">
                        <a href="{{ route('school-admin.promotions.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle"></i> Promotion Details</h5>
                        <p>Current Academic Year: <strong>{{ $currentYear ? $currentYear->year : 'N/A' }}</strong></p>
                        <p>Target Academic Year: <strong>{{ $nextYear ? $nextYear->year : 'N/A' }}</strong></p>
                        @if(!$nextYear)
                            <p class="text-danger">Please create the next academic year first before promoting students.</p>
                        @endif
                    </div>

                    @if($nextYear)
                        <form action="{{ route('school-admin.promotions.store') }}" method="POST" id="promotionForm">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Target Academic Year</label>
                                        <select name="to_academic_year_id" class="form-control" required>
                                            <option value="{{ $nextYear->id }}">{{ $nextYear->year }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Target Class</label>
                                        <select name="to_class_id" class="form-control" required>
                                            <option value="">Select Class</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Promotion Date</label>
                                        <input type="date" name="promotion_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Remarks (Optional)</label>
                                        <input type="text" name="remarks" class="form-control" placeholder="Enter remarks">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" id="selectAll"> Select All Students
                                </label>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="studentsTable">
                                    <thead>
                                        <tr>
                                            <th width="50">
                                                <input type="checkbox" id="selectAllCheckbox">
                                            </th>
                                            <th>Student ID</th>
                                            <th>Name</th>
                                            <th>Current Class</th>
                                            <th>Next Class (Auto)</th>
                                            <th>Gender</th>
                                            <th>Medium</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($students as $student)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="student-checkbox">
                                                </td>
                                                <td>{{ $student->student_id }}</td>
                                                <td>{{ $student->name }}</td>
                                                <td>{{ $student->schoolClass ? $student->schoolClass->class_name : '-' }}</td>
                                                <td>
                                                    @if($student->nextClass)
                                                        <span class="badge bg-success">{{ $student->nextClass->class_name }}</span>
                                                    @else
                                                        <span class="badge bg-danger">No path</span>
                                                    @endif
                                                </td>
                                                <td>{{ ucfirst($student->gender) }}</td>
                                                <td>{{ ucfirst($student->medium) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No students found for promotion.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to promote selected students?')">
                                    <i class="fas fa-arrow-up"></i> Promote Selected Students
                                </button>
                                <a href="{{ route('school-admin.promotions.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            Please create the next academic year before promoting students.
                            <a href="{{ route('school-admin.academic-years.index') }}">Click here to create academic year.</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Select all checkbox
        $('#selectAllCheckbox').change(function() {
            $('.student-checkbox').prop('checked', $(this).prop('checked'));
        });

        // Also update when clicking select all label
        $('#selectAll').change(function() {
            $('.student-checkbox').prop('checked', $(this).prop('checked'));
            $('#selectAllCheckbox').prop('checked', $(this).prop('checked'));
        });

        // Update select all when individual checkboxes change
        $('.student-checkbox').change(function() {
            if (!$(this).prop('checked')) {
                $('#selectAllCheckbox').prop('checked', false);
                $('#selectAll').prop('checked', false);
            }
            
            // Check if all are checked
            var allChecked = $('.student-checkbox').length === $('.student-checkbox:checked').length;
            if (allChecked) {
                $('#selectAllCheckbox').prop('checked', true);
                $('#selectAll').prop('checked', true);
            }
        });

        // Form validation
        $('#promotionForm').submit(function(e) {
            if ($('.student-checkbox:checked').length === 0) {
                e.preventDefault();
                alert('Please select at least one student to promote.');
                return false;
            }
        });
    });
</script>
@endpush
@endsection
