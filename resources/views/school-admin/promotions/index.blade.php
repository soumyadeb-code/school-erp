@extends('layouts.app')

@section('title', 'Student Promotions')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Student Promotions</h3>
                    <div class="card-tools">
                        <a href="{{ route('school-admin.promotions.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Promote Students
                        </a>
                        <a href="{{ route('school-admin.promotions.history') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-history"></i> Promotion History
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

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $eligibleStudents->count() }}</h3>
                                    <p>Eligible for Promotion</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $promotedCount }}</h3>
                                    <p>Promoted Students</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-arrow-up"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $currentYear ? $currentYear->year : 'N/A' }}</h3>
                                    <p>Current Year</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-secondary">
                                <div class="inner">
                                    <h3>{{ $classes->count() }}</h3>
                                    <p>Active Classes</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-door-open"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4>Students Eligible for Promotion</h4>
                    @if($eligibleStudents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Current Class</th>
                                        <th>Next Class</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($eligibleStudents as $student)
                                        <tr>
                                            <td>{{ $student->student_id }}</td>
                                            <td>{{ $student->name }}</td>
                                            <td>{{ $student->schoolClass ? $student->schoolClass->class_name : '-' }}</td>
                                            <td>
                                                @if($student->nextClass())
                                                    <span class="badge bg-success">{{ $student->nextClass()->class_name }}</span>
                                                @else
                                                    <span class="badge bg-danger">No next class</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('school-admin.promotions.student-enrollments', $student->id) }}" class="btn btn-sm btn-info" title="View Enrollments">
                                                    <i class="fas fa-history"></i>
                                                </a>
                                                @if($student->nextClass())
                                                    <form action="{{ route('school-admin.promotions.promote-single', $student->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" title="Promote" onclick="return confirm('Are you sure you want to promote this student?')">
                                                            <i class="fas fa-arrow-up"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-danger" title="Issue TC" data-toggle="modal" data-target="#tcModal{{ $student->id }}">
                                                    <i class="fas fa-file-alt"></i>
                                                </button>
                                                
                                                <!-- TC Modal -->
                                                <div class="modal fade" id="tcModal{{ $student->id }}" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Issue TC for {{ $student->name }}</h5>
                                                                <button type="button" class="close" data-dismiss="modal">
                                                                    <span>&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="{{ route('school-admin.promotions.issue-tc', $student->id) }}" method="POST">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label>TC Date</label>
                                                                        <input type="date" name="tc_date" class="form-control" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Reason</label>
                                                                        <input type="text" name="tc_reason" class="form-control" placeholder="Enter reason for TC" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-danger">Issue TC</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            No students eligible for promotion. Students must be registered and have enrollments to be eligible.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
