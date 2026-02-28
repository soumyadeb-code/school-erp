

<?php $__env->startSection('title', 'Student Registration'); ?>

<?php $__env->startSection('page-title', 'Student Registration'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('/dashboard')); ?>">Home</a></li>
    <li class="breadcrumb-item active">Registration</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Registration List</h5>
            </div>
            <div class="card-body">
                <?php if($registeredStudents->isEmpty()): ?>
                    <div class="text-center text-muted py-4">
                        <p>No students pending registration.</p>
                        <p>Students who have completed admission will appear here for registration.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
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
                                <?php $__currentLoopData = $registeredStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><span class="badge bg-secondary"><?php echo e($student->student_id); ?></span></td>
                                    <td><?php echo e($student->name); ?></td>
                                    <td><?php echo e($student->schoolClass->class_name ?? 'N/A'); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($student->dob)->format('d M Y')); ?></td>
                                    <td><?php echo e(ucfirst($student->medium)); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($student->admission_date)->format('d M Y')); ?></td>
                                    <td>
                                        <?php if($student->registration_status === 'pending'): ?>
                                            <span class="badge bg-warning">Pending</span>
                                        <?php elseif($student->registration_status === 'completed'): ?>
                                            <span class="badge bg-success">Registered</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?php echo e(ucfirst($student->registration_status)); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($student->registration_status === 'pending'): ?>
                                        <a href="<?php echo e(route('students.billing', $student->id)); ?>" class="btn btn-sm btn-success">
                                            <i class="fas fa-file-invoice"></i> Generate Bill
                                        </a>
                                        <?php else: ?>
                                        <a href="<?php echo e(route('students.show', $student->id)); ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/students/registration.blade.php ENDPATH**/ ?>