

<?php $__env->startSection('title', 'School Details'); ?>

<?php $__env->startSection('page-title', 'School Details'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('super-admin.schools.index')); ?>">Schools</a></li>
    <li class="breadcrumb-item active">Details</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?php echo e($school->name); ?></h5>
                <div>
                    <a href="<?php echo e(route('super-admin.schools.edit', $school->id)); ?>" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="<?php echo e(route('super-admin.schools.index')); ?>" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Trust Name:</th>
                                <td><?php echo e($school->trust_name ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <th width="40%">School Code:</th>
                                <td><span class="badge bg-secondary"><?php echo e($school->code); ?></span></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td><?php echo e($school->email); ?></td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td><?php echo e($school->phone ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td><?php echo e($school->address ?? 'N/A'); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Joining Date:</th>
                                <td><?php echo e($school->joining_date->format('d M Y')); ?></td>
                            </tr>
                            <tr>
                                <th>Expiry Date:</th>
                                <td><?php echo e($school->expiry_date->format('d M Y')); ?></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
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
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">School Admins</h6>
                    </div>
                    <div class="card-body">
                        <?php if($school->users->count() > 0): ?>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $school->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($user->name); ?></td>
                                        <td><?php echo e($user->email); ?></td>
                                        <td><span class="badge bg-info"><?php echo e(ucfirst(str_replace('_', ' ', $user->role))); ?></span></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-muted mb-0">No admins found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Quick Stats</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <h3 class="mb-0"><?php echo e($school->students->count()); ?></h3>
                                <small class="text-muted">Students</small>
                            </div>
                            <div class="col-6">
                                <h3 class="mb-0"><?php echo e($school->receipts->count()); ?></h3>
                                <small class="text-muted">Receipts</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/super-admin/schools/show.blade.php ENDPATH**/ ?>