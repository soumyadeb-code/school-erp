@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

@section('page-title', 'Super Admin Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Total Schools</p>
                    <h3 class="mb-0">{{ number_format($totalSchools) }}</h3>
                </div>
                <div class="icon bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-school"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Active Schools</p>
                    <h3 class="mb-0 text-success">{{ number_format($activeSchools) }}</h3>
                </div>
                <div class="icon bg-success bg-opacity-10 text-success">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Expired Schools</p>
                    <h3 class="mb-0 text-danger">{{ number_format($expiredSchools) }}</h3>
                </div>
                <div class="icon bg-danger bg-opacity-10 text-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Total Collections</p>
                    <h3 class="mb-0">₹{{ number_format($totalCollections, 2) }}</h3>
                </div>
                <div class="icon bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-rupee-sign"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Schools</h5>
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
                        <th>School Code</th>
                        <th>Email</th>
                        <th>Joining Date</th>
                        <th>Expiry Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentSchools as $school)
                    <tr>
                        <td>{{ $school->name }}</td>
                        <td><span class="badge bg-secondary">{{ $school->code }}</span></td>
                        <td>{{ $school->email }}</td>
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
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No schools found. <a href="{{ route('super-admin.schools.create') }}">Create your first school</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
