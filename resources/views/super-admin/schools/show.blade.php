@extends('layouts.app')

@section('title', 'School Details')

@section('page-title', 'School Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('super-admin.schools.index') }}">Schools</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $school->name }}</h5>
                <div>
                    <a href="{{ route('super-admin.schools.edit', $school->id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="{{ route('super-admin.schools.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">School Code:</th>
                                <td><span class="badge bg-secondary">{{ $school->code }}</span></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $school->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>{{ $school->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td>{{ $school->address ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Joining Date:</th>
                                <td>{{ $school->joining_date->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th>Expiry Date:</th>
                                <td>{{ $school->expiry_date->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @if($school->isExpired())
                                        <span class="badge bg-danger">Expired</span>
                                    @elseif($school->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">School Admins</h6>
                    </div>
                    <div class="card-body">
                        @if($school->users->count() > 0)
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($school->users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td><span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted mb-0">No admins found.</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Quick Stats</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <h3 class="mb-0">{{ $school->students->count() }}</h3>
                                <small class="text-muted">Students</small>
                            </div>
                            <div class="col-6">
                                <h3 class="mb-0">{{ $school->receipts->count() }}</h3>
                                <small class="text-muted">Receipts</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
