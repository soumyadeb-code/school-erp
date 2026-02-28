

<?php $__env->startSection('title', 'Registration Fee Not Set'); ?>

<?php $__env->startSection('page-title', 'Registration Fee Not Set'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('/dashboard')); ?>">Home</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('students.registration')); ?>">Registration</a></li>
    <li class="breadcrumb-item active">Fee Not Set</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header bg-warning">
        <h5 class="mb-0">Registration Fee Not Set</h5>
    </div>
    <div class="card-body">
        <div class="alert alert-warning">
            <h4>Registration fee is not configured for <?php echo e(ucfirst($medium)); ?> medium!</h4>
            <p>Please set up the registration fee for the academic year <?php echo e($academicYear->year); ?> and <?php echo e(ucfirst($medium)); ?> medium.</p>
        </div>
        
        <div class="card mb-3">
            <div class="card-body">
                <h6>Student Details:</h6>
                <table class="table table-bordered">
                    <tr>
                        <th>Name:</th>
                        <td><?php echo e($student->name); ?></td>
                    </tr>
                    <tr>
                        <th>Student ID:</th>
                        <td><?php echo e($student->student_id); ?></td>
                    </tr>
                    <tr>
                        <th>Class:</th>
                        <td><?php echo e($student->schoolClass->class_name ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Medium:</th>
                        <td><?php echo e(ucfirst($student->medium)); ?></td>
                    </tr>
                    <tr>
                        <th>Academic Year:</th>
                        <td><?php echo e($academicYear->year); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="text-center">
            <a href="<?php echo e(route('school-admin.fees.registration')); ?>" class="btn btn-primary">
                <i class="fas fa-cog"></i> Set Registration Fee
            </a>
            <a href="<?php echo e(route('students.registration')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Registration
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/students/registration-fee-not-set.blade.php ENDPATH**/ ?>