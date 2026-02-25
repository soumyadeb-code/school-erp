@extends('layouts.app')

@section('title', 'Classes Management')

@section('page-title', 'Classes Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Classes</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Add Class</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('school-admin.classes.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Class Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="class_name" placeholder="e.g., Nursery, L.K.G, One" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Minimum Age (Years) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="minimum_age" min="1" max="20" placeholder="e.g., 3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Save Class
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Classes List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Class Name</th>
                                <th>Minimum Age</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classes as $class)
                            <tr>
                                <td><strong>{{ $class->class_name }}</strong></td>
                                <td>{{ $class->minimum_age }}+ years</td>
                                <td>
                                    @if($class->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $class->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('school-admin.classes.edit', $class->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('school-admin.classes.destroy', $class->id) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            
                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $class->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Class</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('school-admin.classes.update', $class->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Class Name</label>
                                                    <input type="text" class="form-control" name="class_name" value="{{ $class->class_name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Minimum Age</label>
                                                    <input type="number" class="form-control" name="minimum_age" value="{{ $class->minimum_age }}" min="1" max="20" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select class="form-select" name="status">
                                                        <option value="active" {{ $class->status === 'active' ? 'selected' : '' }}>Active</option>
                                                        <option value="inactive" {{ $class->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                                <td colspan="5" class="text-center text-muted py-4">
                                    No classes found. Add your first class.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
