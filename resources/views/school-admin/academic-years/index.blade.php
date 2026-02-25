@extends('layouts.app')

@section('title', 'Academic Years')

@section('page-title', 'Academic Years')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Academic Years</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-calendar me-2"></i>Add Academic Year</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('school-admin.academic-years.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Year <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="year" placeholder="e.g., 2027" min="2020" max="2100" required>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive">
                            <label class="form-check-label" for="isActive">Set as Active Year</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-plus me-2"></i>Add Year
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Academic Years List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($years as $year)
                            <tr>
                                <td><strong>{{ $year->year }}</strong></td>
                                <td>
                                    @if($year->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$year->is_active)
                                    <form method="POST" action="{{ route('school-admin.academic-years.activate', $year->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Set as Active">
                                            <i class="fas fa-check"></i> Activate
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('school-admin.academic-years.destroy', $year->id) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this academic year?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    No academic years found. Add your first year using the form.
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
