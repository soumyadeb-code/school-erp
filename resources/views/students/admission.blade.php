@extends('layouts.app')

@section('title', 'Student Admission')

@section('page-title', 'Student Admission')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Admission</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>New Admission</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('students.admission.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Student Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="dob" id="dob" required>
                        <div class="form-text text-info" id="age-display"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Class <span class="text-danger">*</span></label>
                        <select class="form-select" name="class_id" id="class_id" required>
                            <option value="">Select Date of Birth first</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}" data-min-age="{{ $class->minimum_age }}">
                                {{ $class->class_name }} (Min Age: {{ $class->minimum_age }}+)
                            </option>
                            @endforeach
                        </select>
                        <div class="form-text text-muted" id="class-hint">Enter Date of Birth to see eligible classes</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Medium <span class="text-danger">*</span></label>
                        <select class="form-select" name="medium" id="medium" required>
                            <option value="">Select Medium</option>
                            <option value="English">English</option>
                            <option value="Bengali">Bengali</option>
                            <option value="Hindi">Hindi</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Admission Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="admission_date" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">WhatsApp Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="whatsapp_number" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Father's Name</label>
                        <input type="text" class="form-control" name="father_name">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Mother's Name</label>
                        <input type="text" class="form-control" name="mother_name">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" class="form-control" name="phone">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                        <select class="form-select" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" name="address" rows="2"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-plus me-2"></i>Add Student
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Admission List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="studentsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>DOB</th>
                                <th>Medium</th>
                                <th>Admission Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingStudents as $student)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $student->student_id }}</span></td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->schoolClass->class_name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($student->dob)->format('d M Y') }}</td>
                                <td>{{ ucfirst($student->medium) }}</td>
                                <td>{{ \Carbon\Carbon::parse($student->admission_date)->format('d M Y') }}</td>
                                <td>
                                    @if($student->admission_status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($student->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($student->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->admission_status === 'pending')
                                    <a href="{{ route('students.billing', $student->id) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-file-invoice"></i> Generate Bill
                                    </a>
                                    <form method="POST" action="{{ route('students.destroy', $student->id) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this student? This action cannot be undone.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @else
                                    <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    No students found
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

@section('scripts')
<script>
// Store all classes data for filtering
const allClasses = @json($classes);

document.addEventListener('DOMContentLoaded', function() {
    const dobInput = document.getElementById('dob');
    const classSelect = document.getElementById('class_id');
    const ageDisplay = document.getElementById('age-display');
    const classHint = document.getElementById('class-hint');
    
    // Listen for DOB changes
    dobInput.addEventListener('change', function() {
        const dob = this.value;
        
        if (!dob) {
            // Reset class dropdown if DOB is cleared
            resetClassDropdown('Enter Date of Birth to see eligible classes');
            ageDisplay.textContent = '';
            return;
        }
        
        // Calculate age
        const birthDate = new Date(dob);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        
        // Display age
        ageDisplay.textContent = `Student Age: ${age} years`;
        
        // Fetch eligible classes via AJAX
        fetchEligibleClasses(dob);
    });
    
    function fetchEligibleClasses(dob) {
        const url = '{{ route("students.eligible-classes") }}?dob=' + dob;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateClassDropdown(data.classes, data.student_age);
                } else {
                    console.error('Error:', data.message);
                    resetClassDropdown('Error loading classes. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                resetClassDropdown('Error loading classes. Please try again.');
            });
    }
    
    function updateClassDropdown(classes, studentAge) {
        classSelect.innerHTML = '';
        
        if (classes.length === 0) {
            classSelect.innerHTML = '<option value="">No eligible classes found for age ' + studentAge + '</option>';
            classHint.textContent = 'No classes available for this age group';
            classHint.className = 'form-text text-danger';
            return;
        }
        
        // Add default option
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Select Class';
        classSelect.appendChild(defaultOption);
        
        // Add eligible classes
        classes.forEach(classItem => {
            const option = document.createElement('option');
            option.value = classItem.id;
            option.textContent = classItem.class_name + ' (Min Age: ' + classItem.minimum_age + '+)';
            option.dataset.minAge = classItem.minimum_age;
            classSelect.appendChild(option);
        });
        
        classHint.textContent = 'Showing ' + classes.length + ' eligible class(es) for age ' + studentAge;
        classHint.className = 'form-text text-success';
    }
    
    function resetClassDropdown(message) {
        classSelect.innerHTML = '<option value="">' + message + '</option>';
        classHint.textContent = message;
        classHint.className = 'form-text text-muted';
    }
    
    // Check for receipt_id in session and open in new tab
    @if(session('receipt_id'))
    const receiptUrl = '{{ route("students.receipt-view", session("receipt_id")) }}';
    const link = document.createElement('a');
    link.href = receiptUrl;
    link.target = '_blank';
    link.rel = 'noopener noreferrer';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    @endif
});
</script>
@endsection
