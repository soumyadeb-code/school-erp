

<?php $__env->startSection('title', 'Schools Management'); ?>

<?php $__env->startSection('page-title', 'Schools Management'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item active">Schools</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Schools</h5>
        <a href="<?php echo e(route('super-admin.schools.create')); ?>" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i> Add School
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>School Name</th>
                        <th>Trust Name</th>
                        <th>School Code</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Joining Date</th>
                        <th>Expiry Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($school->name); ?></td>
                        <td><?php echo e($school->trust_name ?? 'N/A'); ?></td>
                        <td><span class="badge bg-secondary"><?php echo e($school->code); ?></span></td>
                        <td><?php echo e($school->email); ?></td>
                        <td><?php echo e($school->phone); ?></td>
                        <td><?php echo e($school->joining_date->format('d M Y')); ?></td>
                        <td><?php echo e($school->expiry_date->format('d M Y')); ?></td>
                        <td>
                            <?php if($school->isExpired()): ?>
                                <span class="badge bg-danger">Expired</span>
                            <?php elseif($school->status === 'active'): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo e(route('super-admin.schools.show', $school->id)); ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?php echo e(route('super-admin.schools.edit', $school->id)); ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            No schools found. <a href="<?php echo e(route('super-admin.schools.create')); ?>">Create your first school</a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php echo e($schools->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/super-admin/schools/index.blade.php ENDPATH**/ ?>