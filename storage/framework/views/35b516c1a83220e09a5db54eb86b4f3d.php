

<?php $__env->startSection('title', 'Reports'); ?>

<?php $__env->startSection('page-title', 'Reports'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item active">Reports</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
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
                            <?php $__empty_1 = true; $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($school->name); ?></td>
                                <td><span class="badge bg-secondary"><?php echo e($school->code); ?></span></td>
                                <td><?php echo e(number_format($school->total_students)); ?></td>
                                <td>₹<?php echo e(number_format($school->total_collection, 2)); ?></td>
                                <td>
                                    <?php if($school->isExpired()): ?>
                                        <span class="badge bg-danger">Expired</span>
                                    <?php elseif($school->status === 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No schools found.
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

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Summary</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <h3><?php echo e($schools->count()); ?></h3>
                        <p class="text-muted mb-0">Total Schools</p>
                    </div>
                    <div class="col-md-4">
                        <h3><?php echo e($schools->sum('total_students')); ?></h3>
                        <p class="text-muted mb-0">Total Students</p>
                    </div>
                    <div class="col-md-4">
                        <h3>₹<?php echo e(number_format($schools->sum('total_collection'), 2)); ?></h3>
                        <p class="text-muted mb-0">Total Collections</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/super-admin/reports.blade.php ENDPATH**/ ?>