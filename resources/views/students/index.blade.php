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
                    Total Records: <span id="totalRecords">0</span>
                </span>
            </div>
        </div>
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" id="searchInput" class="form-control" placeholder="Search name, ID, phone, father name...">
            </div>
            <div class="col-md-2">
                <label class="form-label">Class</label>
                <select class="form-select" id="classFilter">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Gender</label>
                <select class="form-select" id="genderFilter">
                    <option value="">All Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Medium</label>
                <select class="form-select" id="mediumFilter">
                    <option value="">All Medium</option>
                    <option value="Bengali">Bengali</option>
                    <option value="English">English</option>
                    <option value="Hindi">Hindi</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="pending">Pending</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-md-1">
                <label class="form-label">&nbsp;</label>
                <button id="clearBtn" class="btn btn-secondary w-100" title="Clear filters">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="row g-2 mt-2">
            <div class="col-md-2">
                <label class="form-label">Show per page</label>
                <select class="form-select" id="perPage">
                    <option value="10">10 per page</option>
                    <option value="20">20 per page</option>
                    <option value="50">50 per page</option>
                    <option value="100">100 per page</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="studentsTable" class="table table-bordered table-striped">
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
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <!-- Empty state message -->
            <div id="emptyState" class="text-center py-5 d-none">
                <div class="mb-3">
                    <i class="fas fa-user-graduate fa-4x text-muted"></i>
                </div>
                <h5 class="text-muted">No Students Found</h5>
                <p class="text-muted">There are no students in this school yet.</p>
                <a href="{{ route('students.admission') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Add First Student
                </a>
            </div>
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
                    d.search = $('#searchInput').val();
                    // Custom filters
                    d.gender = $('#genderFilter').val();
                    d.class_id = $('#classFilter').val();
                    d.medium = $('#mediumFilter').val();
                    d.status = $('#statusFilter').val();
                }
            columns: [
                { 
                    data: null, 
                    name: 'sno', 
                    orderable: false, 
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
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
            lengthMenu: [[10, 20, 50, 100], [10, 20, 50, 100]],
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
                { orderable: false, targets: [0, 2, 10] }
            ],
            drawCallback: function(settings) {
                // Update total records badge
                var info = table.page.info();
                $('#totalRecords').text(info.recordsTotal);
                
                // Show/hide empty state
                if (info.recordsTotal === 0) {
                    $('#emptyState').removeClass('d-none');
                    $('#studentsTable').addClass('d-none');
                } else {
                    $('#emptyState').addClass('d-none');
                    $('#studentsTable').removeClass('d-none');
                }
            }
        });

        // Real-time search with debounce (300ms)
        var searchTimeout;
        $('#searchInput').on('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                table.draw();
            }, 300);
        });

        // Auto-trigger on dropdown changes for real-time filtering
        $('#classFilter, #genderFilter, #mediumFilter, #statusFilter').on('change', function() {
            table.draw();
        });

        // Per-page dropdown change
        $('#perPage').on('change', function() {
            var perPage = $(this).val();
            table.page.len(perPage).draw();
        });

        // Clear filters button
        $('#clearBtn').on('click', function() {
            $('#searchInput').val('');
            $('#classFilter').val('');
            $('#genderFilter').val('');
            $('#mediumFilter').val('');
            $('#statusFilter').val('');
            $('#perPage').val('10');
            table.page.len(10).draw();
        });
    }
});
</script>
@endpush
