

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
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Class <span class="text-danger">*</span></label>
                        <select class="form-select" name="class_id" id="class_id" required>
                            <option value="">Select Class</option>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($class->id); ?>" data-min-age="<?php echo e($class->minimum_age); ?>">
                                <?php echo e($class->class_name); ?> (Min Age: <?php echo e($class->minimum_age); ?>+)
                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
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
function handleDelete(button) {
    // Get the form
    const form = button.closest('form');
    const modal = button.closest('.modal');
    
    // Hide the modal immediately
    const modalInstance = bootstrap.Modal.getInstance(modal);
    if (modalInstance) {
        modalInstance.hide();
    }
    
    // Remove modal backdrop
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    
    // Submit the form normally
    form.submit();
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/students/admission.blade.php ENDPATH**/ ?>