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
            <div class="col-md-12">
                <h5 class="mb-2">Student List</h5>
            </div>
        </div>
        <div class="row g-2">
            <div class="col-md-3">
                <input type="text" id="searchFilter" class="form-control" placeholder="Search name or ID...">
            </div>
            <div class="col-md-2">
                <select class="form-select" id="classFilter">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" id="genderFilter">
                    <option value="">All Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" id="mediumFilter">
                    <option value="">All Medium</option>
                    <option value="Bengali">Bengali</option>
                    <option value="English">English</option>
                    <option value="Hindi">Hindi</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="pending">Pending</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-md-1">
                <button id="filterBtn" class="btn btn-primary w-100" title="Apply filters">
                    <i class="bi bi-funnel"></i> Filter
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="studentsTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Roll</th>
                        <th>Gender</th>
                        <th>DOB</th>
                        <th>Medium</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if ($('#studentsTable').length) {
        var table = $('#studentsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('students.index') }}',
                type: 'GET',
                data: function(d) {
                    // Custom search from our input field
                    d.search.value = $('#searchFilter').val();
                    // Custom filters
                    d.gender = $('#genderFilter').val();
                    d.class_id = $('#classFilter').val();
                    d.medium = $('#mediumFilter').val();
                    d.status = $('#statusFilter').val();
                }
            },
            columns: [
                { data: 'student_id', name: 'student_id' },
                { data: 'photo', name: 'photo', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'class_name', name: 'class_name' },
                { data: 'roll', name: 'roll' },
                { data: 'gender', name: 'gender' },
                { data: 'dob', name: 'dob' },
                { data: 'medium', name: 'medium' },
                { data: 'status', name: 'status' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search by name or ID...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                infoFiltered: "(filtered from _MAX_ total entries)",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                },
                processing: '<i class="fa fa-spinner fa-spin fa-fw"></i> Loading...'
            },
            columnDefs: [
                { orderable: false, targets: [1, 9] }
            ]
        });

        // Filter button click - triggers AJAX reload
        $('#filterBtn').on('click', function() {
            table.draw();
        });

        // Real-time search with debounce
        var searchTimeout;
        $('#searchFilter').on('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                table.draw();
            }, 300);
        });

        // Auto-trigger search on dropdown changes for real-time filtering
        $('#classFilter, #genderFilter, #mediumFilter, #statusFilter').on('change', function() {
            table.draw();
        });
    }
});
</script>
@endpush
