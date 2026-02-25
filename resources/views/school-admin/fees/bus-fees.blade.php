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
                <h5 class="mb-0">Bus Fees Configuration</h5>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFeeModal">
                    <i class="fas fa-plus"></i> Add Bus Fee
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
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
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Destination</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fees as $fee)
                        <tr>
                            <td>{{ $fee->destination }}</td>
                            <td>₹{{ number_format($fee->price, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $fee->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($fee->status) }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editFeeModal{{ $fee->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('school-admin.fees.bus.destroy', $fee->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        
                        <!-- Edit Modal -->
                        <div class="modal fade" id="editFeeModal{{ $fee->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Bus Fee</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('school-admin.fees.bus.update', $fee->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Destination</label>
                                                <input type="text" name="destination" class="form-control" value="{{ $fee->destination }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Price (₹)</label>
                                                <input type="number" name="price" class="form-control" value="{{ $fee->price }}" required min="0" step="0.01">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Status</label>
                                                <select name="status" class="form-select" required>
                                                    <option value="active" {{ $fee->status == 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ $fee->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No bus fees configured yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
@endsection
