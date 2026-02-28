

<?php $__env->startSection('title', 'School Admin Dashboard'); ?>

<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item active">Dashboard</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Stats Row -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Total Students</p>
                    <h3 class="mb-0"><?php echo e(number_format($totalStudents)); ?></h3>
                </div>
                <div class="icon bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Active Students</p>
                    <h3 class="mb-0 text-success"><?php echo e(number_format($activeStudents)); ?></h3>
                </div>
                <div class="icon bg-success bg-opacity-10 text-success">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Due Students</p>
                    <h3 class="mb-0 text-danger"><?php echo e(number_format($dueStudents)); ?></h3>
                </div>
                <div class="icon bg-danger bg-opacity-10 text-danger">
                    <i class="fas fa-user-clock"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Admission Completed</p>
                    <h3 class="mb-0 text-info"><?php echo e(number_format($admissionStudents)); ?></h3>
                </div>
                <div class="icon bg-info bg-opacity-10 text-info">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">This Month Collection</p>
                    <h3 class="mb-0">₹<?php echo e(number_format($monthlyCollection, 2)); ?></h3>
                </div>
                <div class="icon bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-rupee-sign"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-2">
                        <a href="<?php echo e(route('students.admission')); ?>" class="btn btn-outline-primary w-100 p-3">
                            <i class="fas fa-user-plus fa-2x mb-2"></i>
                            <br>New Admission
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo e(route('students.fee-collection')); ?>" class="btn btn-outline-success w-100 p-3">
                            <i class="fas fa-hand-holding-usd fa-2x mb-2"></i>
                            <br>Fee Collection
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo e(route('school-admin.fees.admission')); ?>" class="btn btn-outline-warning w-100 p-3">
                            <i class="fas fa-cogs fa-2x mb-2"></i>
                            <br>Fee Setup
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo e(route('school-admin.classes.index')); ?>" class="btn btn-outline-secondary w-100 p-3">
                            <i class="fas fa-door-open fa-2x mb-2"></i>
                            <br>Classes
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="#" class="btn btn-outline-dark w-100 p-3">
                            <i class="fas fa-chart-bar fa-2x mb-2"></i>
                            <br>Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Students -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Admissions</h5>
        <a href="<?php echo e(route('students.admission')); ?>" class="btn btn-sm btn-primary">View Admissions</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Medium</th>
                        <th>Admission Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><span class="badge bg-secondary"><?php echo e($student->student_id); ?></span></td>
                        <td><?php echo e($student->name); ?></td>
                        <td><?php echo e($student->schoolClass->class_name ?? 'N/A'); ?></td>
                        <td><?php echo e(ucfirst($student->medium)); ?></td>
                        <td><?php echo e($student->admission_date->format('d M Y')); ?></td>
                        <td>
                            <?php if($student->status === 'active'): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?php echo e(ucfirst($student->status)); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            No students found. <a href="<?php echo e(route('students.admission')); ?>">Add your first student</a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/school-admin/dashboard.blade.php ENDPATH**/ ?>