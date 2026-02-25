@extends('layouts.app')

@section('title', 'Bus Fees')

@section('page-title', 'Bus Fees Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/fees') }}">Fees</a></li>
    <li class="breadcrumb-item active">Bus Fees</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Bus Fees Configuration <span class="badge bg-primary" id="totalRecords">0</span></h5>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group" role="group">
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="fas fa-file-import"></i> Import
                    </button>
                    <button class="btn btn-info btn-sm" onclick="exportBusFees()">
                        <i class="fas fa-file-export"></i> Export
                    </button>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addFeeModal">
                        <i class="fas fa-plus"></i> Add Bus Fee
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Search and Filter Section -->
        <div class="row mb-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by destination or price...">
                </div>
            </div>
            <div class="col-md-2">
                <select id="statusFilter" class="form-select">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <select id="perPage" class="form-select" onchange="loadBusFees(1)">
                    <option value="10">10 per page</option>
                    <option value="20">20 per page</option>
                    <option value="50">50 per page</option>
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-secondary" type="button" onclick="clearFilters()">
                    <i class="fas fa-times"></i> Clear
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="busFeesTable">
                <thead>
                    <tr>
                        <th width="50">S.No.</th>
                        <th>Destination</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody id="busFeesTableBody">
                    <!-- Data will be loaded via AJAX -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted" id="paginationInfo">
                Showing 0 to 0 of 0 entries
            </div>
            <div id="paginationLinks">
                <!-- Pagination will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addFeeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Bus Fee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('school-admin.fees.bus.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Destination</label>
                        <input type="text" name="destination" class="form-control" required placeholder="e.g., Route 1 - City Center">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price (₹)</label>
                        <input type="number" name="price" class="form-control" required min="0" step="0.01" placeholder="0.00">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Bus Fees</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('school-admin.fees.bus.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Excel File</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                        <small class="text-muted">Supported formats: .xlsx, .xls, .csv</small>
                    </div>
                    <div class="alert alert-info">
                        <strong>Excel Format:</strong> Columns should be: Destination, Price
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let searchTimeout = null;
let currentPage = 1;

// Initial load
document.addEventListener('DOMContentLoaded', function() {
    loadBusFees();
    
    // Real-time search on keyup
    document.getElementById('searchInput').addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            loadBusFees(1);
        }, 300); // 300ms delay for debounce
    });
    
    // Status filter change
    document.getElementById('statusFilter').addEventListener('change', function() {
        loadBusFees(1);
    });
});

function loadBusFees(page = 1) {
    currentPage = page;
    const search = document.getElementById('searchInput').value;
    const status = document.getElementById('statusFilter').value;
    const perPage = document.getElementById('perPage').value;
    
    const url = '{{ route("school-admin.fees.bus.search") }}?' + 
        'page=' + page + 
        '&search=' + encodeURIComponent(search) + 
        '&status=' + encodeURIComponent(status) +
        '&per_page=' + perPage;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            updateTable(data, page, perPage);
        })
        .catch(error => {
            console.error('Error loading bus fees:', error);
        });
}

function updateTable(data, page, perPage) {
    const tbody = document.getElementById('busFeesTableBody');
    const paginationInfo = document.getElementById('paginationInfo');
    const paginationLinks = document.getElementById('paginationLinks');
    const totalRecords = document.getElementById('totalRecords');
    
    // Update total records badge
    totalRecords.textContent = data.total;
    
    if (data.data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center">No bus fees found.</td></tr>';
        paginationInfo.textContent = 'Showing 0 to 0 of 0 entries';
        paginationLinks.innerHTML = '';
        return;
    }
    
    let html = '';
    const startSerial = (data.current_page - 1) * data.per_page + 1;
    
    data.data.forEach((fee, index) => {
        const statusClass = fee.status === 'active' ? 'success' : 'secondary';
        const editUrl = '{{ route("school-admin.fees.bus.edit", ":id") }}'.replace(':id', fee.id);
        const deleteUrl = '{{ route("school-admin.fees.bus.destroy", ":id") }}'.replace(':id', fee.id);
        
        html += `
            <tr>
                <td>${startSerial + index}</td>
                <td>${fee.destination}</td>
                <td>₹${parseFloat(fee.price).toFixed(2)}</td>
                <td><span class="badge bg-${statusClass}">${fee.status.charAt(0).toUpperCase() + fee.status.slice(1)}</span></td>
                <td>
                    <a href="${editUrl}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                    <form action="${deleteUrl}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
    
    // Update pagination info
    paginationInfo.textContent = `Showing ${data.from} to ${data.to} of ${data.total} entries`;
    
    // Update pagination links
    paginationLinks.innerHTML = buildPagination(data);
}

function buildPagination(data) {
    let html = '<nav><ul class="pagination mb-0">';
    
    // Previous page
    if (data.prev_page_url) {
        html += `<li class="page-item"><a class="page-link" href="#" onclick="loadBusFees(${data.current_page - 1}); return false;">Previous</a></li>`;
    } else {
        html += `<li class="page-item disabled"><span class="page-link">Previous</span></li>`;
    }
    
    // Page numbers
    for (let i = 1; i <= data.last_page; i++) {
        if (i === data.current_page) {
            html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
        } else {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="loadBusFees(${i}); return false;">${i}</a></li>`;
        }
    }
    
    // Next page
    if (data.next_page_url) {
        html += `<li class="page-item"><a class="page-link" href="#" onclick="loadBusFees(${data.current_page + 1}); return false;">Next</a></li>`;
    } else {
        html += `<li class="page-item disabled"><span class="page-link">Next</span></li>`;
    }
    
    html += '</ul></nav>';
    return html;
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('perPage').value = '10';
    loadBusFees(1);
}

function exportBusFees() {
    window.location.href = '{{ route("school-admin.fees.bus.export") }}';
}
</script>
@endsection
