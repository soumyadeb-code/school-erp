@extends('layouts.app')

@section('title', 'Schools Management')

@section('page-title', 'Schools Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Schools</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Schools</h5>
        <a href="{{ route('super-admin.schools.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i> Add School
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>School Name</th>
                        <th>Trust Name</th>
                        <th>School Code</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Joining Date</th>
                        <th>Expiry Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schools as $school)
                    <tr>
                        <td>{{ $school->name }}</td>
                        <td>{{ $school->trust_name ?? 'N/A' }}</td>
                        <td><span class="badge bg-secondary">{{ $school->code }}</span></td>
                        <td>{{ $school->email }}</td>
                        <td>{{ $school->phone }}</td>
                        <td>{{ $school->joining_date->format('d M Y') }}</td>
                        <td>{{ $school->expiry_date->format('d M Y') }}</td>
                        <td>
                            @if($school->isExpired())
                                <span class="badge bg-danger">Expired</span>
                            @elseif($school->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('super-admin.schools.show', $school->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('super-admin.schools.edit', $school->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            No schools found. <a href="{{ route('super-admin.schools.create') }}">Create your first school</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $schools->links() }}
    </div>
</div>
@endsection
