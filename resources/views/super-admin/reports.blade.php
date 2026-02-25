@extends('layouts.app')

@section('title', 'Reports')

@section('page-title', 'Reports')

@section('breadcrumb')
    <li class="breadcrumb-item active">Reports</li>
@endsection

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">School-wise Reports</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>School Name</th>
                                <th>School Code</th>
                                <th>Total Students</th>
                                <th>Total Collections</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schools as $school)
                            <tr>
                                <td>{{ $school->name }}</td>
                                <td><span class="badge bg-secondary">{{ $school->code }}</span></td>
                                <td>{{ number_format($school->total_students) }}</td>
                                <td>₹{{ number_format($school->total_collection, 2) }}</td>
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
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No schools found.
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

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Summary</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <h3>{{ $schools->count() }}</h3>
                        <p class="text-muted mb-0">Total Schools</p>
                    </div>
                    <div class="col-md-4">
                        <h3>{{ $schools->sum('total_students') }}</h3>
                        <p class="text-muted mb-0">Total Students</p>
                    </div>
                    <div class="col-md-4">
                        <h3>₹{{ number_format($schools->sum('total_collection'), 2) }}</h3>
                        <p class="text-muted mb-0">Total Collections</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
