

<?php $__env->startSection('title', 'Classes Management'); ?>

<?php $__env->startSection('page-title', 'Classes Management'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('/dashboard')); ?>">Home</a></li>
    <li class="breadcrumb-item active">Classes</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Add Class</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('school-admin.classes.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label">Class Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="class_name" placeholder="e.g., Nursery, L.K.G, One" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Minimum Age (Years) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="minimum_age" min="1" max="20" placeholder="e.g., 3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Save Class
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Classes List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Class Name</th>
                                <th>Minimum Age</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><strong><?php echo e($class->class_name); ?></strong></td>
                                <td><?php echo e($class->minimum_age); ?>+ years</td>
                                <td>
                                    <?php if($class->status === 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($class->created_at->format('d M Y')); ?></td>
                                <td>
                                    <a href="<?php echo e(route('school-admin.classes.edit', $class->id)); ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="<?php echo e(route('school-admin.classes.destroy', $class->id)); ?>" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            
                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal<?php echo e($class->id); ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Class</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="<?php echo e(route('school-admin.classes.update', $class->id)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PUT'); ?>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Class Name</label>
                                                    <input type="text" class="form-control" name="class_name" value="<?php echo e($class->class_name); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Minimum Age</label>
                                                    <input type="number" class="form-control" name="minimum_age" value="<?php echo e($class->minimum_age); ?>" min="1" max="20" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select class="form-select" name="status">
                                                        <option value="active" <?php echo e($class->status === 'active' ? 'selected' : ''); ?>>Active</option>
                                                        <option value="inactive" <?php echo e($class->status === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No classes found. Add your first class.
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/school-admin/classes/index.blade.php ENDPATH**/ ?>