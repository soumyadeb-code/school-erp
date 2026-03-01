@extends('layouts.app')

@section('title', 'All Students')

@section('page-title', 'All Students')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">All Students</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center mb-3">
            <div class="col-md-6">
                <h5 class="mb-0">Student List</h5>
            </div>
            <div class="col-md-6 text-end">
                <span class="badge bg-primary fs-6">
                    <i class="fas fa-users me-1"></i>
                    Total Records: {{ $students->count() }}
                </span>
            </div>
        </div>
        
        <form method="GET" action="{{ route('students.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search name, ID, father name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Class</label>
                <select name="class_id" class="form-select">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select">
                    <option value="">All Gender</option>
                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Medium</label>
                <select name="medium" class="form-select">
                    <option value="">All Medium</option>
                    <option value="Bengali" {{ request('medium') == 'Bengali' ? 'selected' : '' }}>Bengali</option>
                    <option value="English" {{ request('medium') == 'English' ? 'selected' : '' }}>English</option>
                    <option value="Hindi" {{ request('medium') == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Bus Route</label>
                <select name="bus" class="form-select">
                    <option value="">All Bus Routes</option>
                    <option value="yes" {{ request('bus') == 'yes' ? 'selected' : '' }}>With Bus</option>
                    <option value="no" {{ request('bus') == 'no' ? 'selected' : '' }}>Without Bus</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
            </div>
            <div class="col-md-1">
                <a href="{{ route('students.index') }}" class="btn btn-secondary w-100" title="Clear filters">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            @if($students->count() > 0)
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="50">S.No.</th>
                            <th>Student ID</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Class</th>
                            <th>Roll</th>
                            <th>Gender</th>
                            <th>DOB</th>
                            <th>Medium</th>
                            <th>Bus Destination</th>
                            <th>Bus Fee</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $student->student_id }}</td>
                            <td>
                                @if($student->photo)
                                    <img src="{{ asset('storage/' . $student->photo) }}" width="40" height="40" class="rounded-circle">
                                @else
                                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                @endif
                            </td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->schoolClass ? $student->schoolClass->class_name : '-' }}</td>
                            <td>{{ $student->roll ?? '-' }}</td>
                            <td>{{ ucfirst($student->gender) }}</td>
                            <td>{{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('d-m-Y') : '-' }}</td>
                            <td>{{ $student->medium }}</td>
                            <td>{{ $student->busDestination ? $student->busDestination->destination : '-' }}</td>
                            <td>{{ $student->busDestination ? '₹' . number_format($student->busDestination->price, 2) : '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $student->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($student->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('students.student-bill-history', $student->id) }}" class="btn btn-sm btn-primary" title="Bill History">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-user-graduate fa-4x text-muted"></i>
                    </div>
                    <h5 class="text-muted">No Students Found</h5>
                    <p class="text-muted">There are no students in this school yet.</p>
                    <a href="{{ route('students.admission') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add First Student
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
