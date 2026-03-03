

<?php $__env->startSection('title', 'Student Admission'); ?>

<?php $__env->startSection('page-title', 'Student Admission'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('/dashboard')); ?>">Home</a></li>
    <li class="breadcrumb-item active">Admission</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>New Admission</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('students.admission.store')); ?>">
                    <?php echo csrf_field(); ?>
                    
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
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($class->id); ?>" data-min-age="<?php echo e($class->minimum_age); ?>">
                                <?php echo e($class->class_name); ?> (Min Age: <?php echo e($class->minimum_age); ?>+)
                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    
                    <div class="mb-3">
                        <label class="form-label">Transport / Convenience</label>
                        <div class="position-relative">
                            <input type="text" class="form-control" id="bus_search" placeholder="Search bus destination..." autocomplete="off">
                            <input type="hidden" name="bus_destination_id" id="bus_destination_id">
                            <button class="btn btn-outline-secondary position-absolute end-0 top-0 h-100 rounded-start-0" type="button" id="clear_bus" style="z-index: 5;">
                                <i class="fas fa-times"></i>
                            </button>
                            <ul class="list-group position-absolute w-100 shadow" id="bus_dropdown" style="z-index: 1000; max-height: 200px; overflow-y: auto; display: none;"></ul>
                        </div>
                        <div class="form-text" id="bus_fee_display"></div>
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
                            <?php $__empty_1 = true; $__currentLoopData = $pendingStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><span class="badge bg-secondary"><?php echo e($student->student_id); ?></span></td>
                                <td><?php echo e($student->name); ?></td>
                                <td><?php echo e($student->schoolClass->class_name ?? 'N/A'); ?></td>
                                <td><?php echo e(\Carbon\Carbon::parse($student->dob)->format('d M Y')); ?></td>
                                <td><?php echo e(ucfirst($student->medium)); ?></td>
                                <td><?php echo e(\Carbon\Carbon::parse($student->admission_date)->format('d M Y')); ?></td>
                                <td>
                                    <?php if($student->admission_status === 'pending'): ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php elseif($student->status === 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?php echo e(ucfirst($student->status)); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($student->admission_status === 'pending'): ?>
                                    <a href="<?php echo e(route('students.billing', $student->id)); ?>" class="btn btn-sm btn-success">
                                        <i class="fas fa-file-invoice"></i> Generate Bill
                                    </a>
                                    <form method="POST" action="<?php echo e(route('students.destroy', $student->id)); ?>" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this student? This action cannot be undone.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <?php else: ?>
                                    <a href="<?php echo e(route('students.show', $student->id)); ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    No students found
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
// Store all classes data for filtering
const allClasses = <?php echo json_encode($classes, 15, 512) ?>;

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
        const url = '<?php echo e(route("students.eligible-classes")); ?>?dob=' + dob;
        
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
    
    // Bus destination search
    const busSearchInput = document.getElementById('bus_search');
    const busDestinationIdInput = document.getElementById('bus_destination_id');
    const busFeeDisplay = document.getElementById('bus_fee_display');
    const clearBusBtn = document.getElementById('clear_bus');
    const busDropdown = document.getElementById('bus_dropdown');
    let busTimeout = null;
    
    if (busSearchInput && busDropdown) {
        busSearchInput.addEventListener('keyup', function() {
            const query = this.value.trim();
            if (busTimeout) clearTimeout(busTimeout);
            if (query === '') {
                hideBusDropdown();
                clearBusSelection();
                return;
            }
            busTimeout = setTimeout(function() {
                searchBusDestinations(query);
            }, 300);
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!busSearchInput.contains(e.target) && !busDropdown.contains(e.target)) {
                hideBusDropdown();
            }
        });
    }
    
    function searchBusDestinations(query) {
        const url = '<?php echo e(route("students.bus-destinations.search")); ?>?q=' + encodeURIComponent(query);
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.destinations.length > 0) {
                    showBusDropdown(data.destinations);
                } else {
                    hideBusDropdown();
                    busFeeDisplay.innerHTML = '<span class="text-danger">No destinations available</span>';
                    busDestinationIdInput.value = '';
                }
            })
            .catch(error => console.error('Error:', error));
    }
    
    function showBusDropdown(destinations) {
        busDropdown.innerHTML = '';
        destinations.forEach(dest => {
            const li = document.createElement('li');
            li.className = 'list-group-item list-group-item-action cursor-pointer';
            li.innerHTML = `<strong>${dest.destination}</strong> - <span class="text-success">₹${dest.price}</span>`;
            li.addEventListener('click', function() {
                selectBusDestination(dest);
            });
            busDropdown.appendChild(li);
        });
        busDropdown.style.display = 'block';
    }
    
    function selectBusDestination(dest) {
        busSearchInput.value = dest.destination;
        busDestinationIdInput.value = dest.id;
        busFeeDisplay.innerHTML = `<span class="text-success">Bus Fee: ₹${dest.price}</span>`;
        hideBusDropdown();
    }
    
    function hideBusDropdown() {
        if (busDropdown) busDropdown.style.display = 'none';
    }
    
    function clearBusSelection() {
        if (busSearchInput) busSearchInput.value = '';
        if (busDestinationIdInput) busDestinationIdInput.value = '';
        if (busFeeDisplay) busFeeDisplay.textContent = '';
    }
    
    if (clearBusBtn) {
        clearBusBtn.addEventListener('click', function() {
            clearBusSelection();
            hideBusDropdown();
        });
    }
    
    // Check for receipt_id in session and open in new tab
    <?php if(session('receipt_id')): ?>
    const receiptUrl = '<?php echo e(route("students.receipt-view", session("receipt_id"))); ?>';
    const link = document.createElement('a');
    link.href = receiptUrl;
    link.target = '_blank';
    link.rel = 'noopener noreferrer';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    <?php endif; ?>
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/students/admission.blade.php ENDPATH**/ ?>