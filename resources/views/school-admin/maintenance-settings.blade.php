@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">
                                <i class="fas fa-tools me-2"></i>Maintenance Settings
                            </h3>
                            <p class="mb-0 opacity-75">Configure your school's maintenance page</p>
                        </div>
                        <a href="{{ route('school-admin.dashboard') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <!-- Settings Form -->
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-cog text-primary me-2"></i>Page Configuration
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('school-admin.maintenance.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Page Title</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-heading"></i></span>
                                    <input type="text" name="page_title" class="form-control form-control-lg" 
                                        value="{{ old('page_title', $schoolSettings->page_title ?? 'Site Under Maintenance') }}" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">School Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-school"></i></span>
                                    <input type="text" name="school_title" class="form-control form-control-lg" 
                                        value="{{ old('school_title', $schoolSettings->school_title ?? auth()->user()->school->school_name ?? 'My School') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Maintenance Message</label>
                            <textarea name="maintenance_message" rows="4" class="form-control" required>{{ old('maintenance_message', $schoolSettings->maintenance_message ?? "We're currently performing scheduled maintenance to improve our services.") }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Contact Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" 
                                        value="{{ old('email', $schoolSettings->email ?? 'support@schoolerp.com') }}">
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Contact Phone</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text" name="phone" class="form-control" 
                                        value="{{ old('phone', $schoolSettings->phone ?? '+1 (555) 123-4567') }}">
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Status Card -->
        <div class="col-lg-4">
            <!-- Current Status -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-power-off text-warning me-2"></i>System Status
                    </h5>
                </div>
                <div class="card-body text-center py-4">
                    <div class="display-1 mb-3">
                        @if($schoolSettings && $schoolSettings->is_active)
                            <i class="fas fa-tools text-danger animate-pulse"></i>
                        @else
                            <i class="fas fa-check-circle text-success"></i>
                        @endif
                    </div>
                    <h4 class="mb-3">
                        @if($schoolSettings && $schoolSettings->is_active)
                            <span class="badge bg-danger fs-5">MAINTENANCE MODE ON</span>
                        @else
                            <span class="badge bg-success fs-5">SYSTEM OPERATIONAL</span>
                        @endif
                    </h4>
                    <p class="text-muted">
                        @if($schoolSettings && $schoolSettings->is_active)
                            Users are seeing the maintenance page
                        @else
                            All users can access your school portal normally
                        @endif
                    </p>
                </div>
                <div class="card-footer bg-white border-0">
                    @if($schoolSettings && $schoolSettings->is_active)
                        <form action="{{ route('school-admin.maintenance.disable') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 btn-lg">
                                <i class="fas fa-play me-2"></i>Disable Maintenance
                            </button>
                        </form>
                    @else
                        <form action="{{ route('school-admin.maintenance.enable') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100 btn-lg">
                                <i class="fas fa-pause me-2"></i>Enable Maintenance
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Info Card -->
            <div class="card shadow-lg border-0 bg-info bg-gradient text-white">
                <div class="card-body">
                    <h5><i class="fas fa-info-circle me-2"></i>How it works</h5>
                    <ul class="mb-0 small">
                        <li class="mb-2">Enable maintenance mode to show the custom page to your school's users only</li>
                        <li class="mb-2">Other schools will not be affected</li>
                        <li>Parents and students of your school will see this page</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-eye text-purple me-2"></i>Live Preview
                        </h5>
                        <a href="{{ url('/maintenance-preview') }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i>Open in New Tab
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="ratio ratio-16x9">
                        <iframe src="{{ url('/maintenance-preview') }}" class="border-0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}
.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: .5; }
}
</style>
@endsection
