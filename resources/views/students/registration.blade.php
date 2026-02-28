@extends('layouts.app')

@section('title', 'Registration & Promotion')

@section('page-title', 'Registration & Promotion')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Registration & Promotion</li>
@endsection

<style>
    :root {
        --primary-blue: #1C2C4C;
        --accent-green: #21C880;
        --soft-blue: #DCEEBFF;
        --light-bg: #F8FAFC;
    }
    
    .reg-promotion-header {
        background: linear-gradient(135deg, #1C2C4C 0%, #2D4A7C 100%);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        color: white;
    }
    
    .reg-promotion-header .icon-box {
        width: 56px;
        height: 56px;
        background: rgba(255,255,255,0.15);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }
    
    .reg-promotion-header h1 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 4px;
    }
    
    .reg-promotion-header p {
        opacity: 0.85;
        font-size: 15px;
        margin: 0;
    }
    
    .tab-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 2px solid transparent;
        background: transparent;
        color: #64748B;
    }
    
    .tab-pill.active {
        background: #1C2C4C;
        color: white;
        border-color: #1C2C4C;
    }
    
    .tab-pill:not(.active) {
        border-color: #E2E8F0;
        background: white;
    }
    
    .tab-pill:not(.active):hover {
        border-color: #1C2C4C;
        color: #1C2C4C;
    }
    
    .tab-pill .badge {
        background: rgba(255,255,255,0.2);
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 12px;
    }
    
    .tab-pill:not(.active) .badge {
        background: #F1F5F9;
        color: #64748B;
    }
    
    .student-table-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    
    .student-table-card table {
        margin-bottom: 0;
    }
    
    .student-table-card thead th {
        background: #F8FAFC;
        border-bottom: 2px solid #E2E8F0;
        padding: 16px 20px;
        font-weight: 600;
        color: #1C2C4C;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .student-table-card tbody td {
        padding: 16px 20px;
        vertical-align: middle;
        border-bottom: 1px solid #F1F5F9;
    }
    
    .student-table-card tbody tr:hover {
        background: #F8FAFC;
    }
    
    .student-id-link {
        color: #1C2C4C;
        font-weight: 600;
        text-decoration: none;
    }
    
    .student-id-link:hover {
        color: #21C880;
    }
    
    .student-name {
        font-weight: 600;
        color: #1E293B;
    }
    
    .next-class-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #DCEEBFF;
        color: #1C2C4C;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
    }
    
    .btn-register-promote {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #21C880;
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .btn-register-promote:hover {
        background: #1AAE6F;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(33, 200, 128, 0.3);
    }
    
    .btn-view {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        background: #F1F5F9;
        color: #64748B;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .btn-view:hover {
        background: #1C2C4C;
        color: white;
    }
    
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }
    
    .empty-state .empty-icon {
        width: 80px;
        height: 80px;
        background: #F1F5F9;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: #94A3B8;
    }
    
    .empty-state h5 {
        color: #1E293B;
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    .empty-state p {
        color: #64748B;
        margin: 0;
    }
</style>

@section('content')

<!-- Header Section -->
<div class="reg-promotion-header">
    <div class="d-flex align-items-start">
        <div class="icon-box">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
        <div>
            <h1>Registration & Promotion</h1>
            <p>Promote students to next academic year</p>
        </div>
    </div>
</div>

<!-- Tab Switcher -->
<div class="mb-4">
    <button class="tab-pill active" id="tab-unregistered" onclick="switchTab('unregistered')">
        <i class="fas fa-clipboard-list"></i>
        Unregistered ({{ $unregisteredStudents->count() }})
    </button>
    <button class="tab-pill" id="tab-registered" onclick="switchTab('registered')">
        <i class="fas fa-check-circle"></i>
        Registered ({{ $registeredStudents->count() }})
    </button>
</div>

<!-- Unregistered Students Table -->
<div id="unregistered-tab" class="student-table-card">
    @if($unregisteredStudents->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                    <line x1="9" y1="9" x2="9.01" y2="9"></line>
                    <line x1="15" y1="9" x2="15.01" y2="9"></line>
                </svg>
            </div>
            <h5>No Students Pending Registration</h5>
            <p>Students who have completed admission will appear here for registration.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Current Class</th>
                        <th>Next Class</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($unregisteredStudents as $student)
                    <tr>
                        <td>
                            <a href="#" class="student-id-link">{{ $student->student_id }}</a>
                        </td>
                        <td class="student-name">{{ $student->name }}</td>
                        <td>{{ $student->schoolClass ? $student->schoolClass->class_name : 'N/A' }}</td>
                        <td>
                            @if($student->nextClass)
                            <span class="next-class-pill">
                                <i class="fas fa-arrow-right"></i>
                                {{ $student->nextClass->class_name }}
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('students.registration-billing', $student->id) }}" class="btn-register-promote">
                                <i class="fas fa-user-plus"></i>
                                Register & Promote
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Registered Students Table (Hidden by default) -->
<div id="registered-tab" class="student-table-card" style="display: none;">
    @if($registeredStudents->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <h5>No Registered Students</h5>
            <p>Students who have completed registration will appear here.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Current Class</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registeredStudents as $student)
                    <tr>
                        <td>
                            <a href="#" class="student-id-link">{{ $student->student_id }}</a>
                        </td>
                        <td class="student-name">{{ $student->name }}</td>
                        <td>{{ $student->schoolClass ? $student->schoolClass->class_name : 'N/A' }}</td>
                        <td>
                            <span class="badge bg-success">Registered</span>
                        </td>
                        <td>
                            <a href="{{ route('students.show', $student->id) }}" class="btn-view" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<script>
function switchTab(tab) {
    // Update tab buttons
    document.querySelectorAll('.tab-pill').forEach(pill => pill.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    
    // Show/hide tables
    if (tab === 'unregistered') {
        document.getElementById('unregistered-tab').style.display = 'block';
        document.getElementById('registered-tab').style.display = 'none';
    } else {
        document.getElementById('unregistered-tab').style.display = 'none';
        document.getElementById('registered-tab').style.display = 'block';
    }
}
</script>
@endsection
