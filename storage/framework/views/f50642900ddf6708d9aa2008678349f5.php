

<?php $__env->startSection('title', 'Edit Admission Fee'); ?>

<?php $__env->startSection('page-title', 'Edit Admission Fee'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('/dashboard')); ?>">Home</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('school-admin.fees.admission')); ?>">Admission Fees</a></li>
    <li class="breadcrumb-item active">Edit</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Admission Fee</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('school-admin.fees.admission.update', $admissionFee->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Academic Year</label>
                        <input type="text" class="form-control" value="<?php echo e($admissionFee->academicYear->year ?? 'N/A'); ?>" disabled>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Medium</label>
                        <input type="text" class="form-control" value="<?php echo e($admissionFee->medium); ?>" disabled>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Amount (₹) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" class="form-control" name="amount" value="<?php echo e($admissionFee->amount); ?>" min="0" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Admission Start Date</label>
                        <input type="date" class="form-control" name="admission_start_date" value="<?php echo e($admissionFee->admission_start_date); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="active" <?php echo e($admissionFee->status === 'active' ? 'selected' : ''); ?>>Active</option>
                            <option value="inactive" <?php echo e($admissionFee->status === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo e(route('school-admin.fees.admission')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Fee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/school-admin/fees/admission-edit.blade.php ENDPATH**/ ?>