

<?php $__env->startSection('title', 'Admission Fees Setup'); ?>

<?php $__env->startSection('page-title', 'Admission Fees Setup'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('/dashboard')); ?>">Home</a></li>
    <li class="breadcrumb-item"><a href="#">Fees Setup</a></li>
    <li class="breadcrumb-item active">Admission Fees</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Add Admission Fee</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('school-admin.fees.admission.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label">Academic Year <span class="text-danger">*</span></label>
                        <select class="form-select" name="academic_year_id" required>
                            <option value="">Select Year</option>
                            <?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($year->id); ?>" <?php echo e($year->is_active ? 'selected' : ''); ?>>
                                <?php echo e($year->year); ?> <?php echo e($year->is_active ? '(Active)' : ''); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Medium <span class="text-danger">*</span></label>
                        <select class="form-select" name="medium" required>
                            <option value="">Select Medium</option>
                            <option value="English">English</option>
                            <option value="Bengali">Bengali</option>
                            <option value="Hindi">Hindi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount (₹) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" class="form-control" name="amount" min="0" placeholder="0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Admission Start Date</label>
                        <input type="date" class="form-control" name="admission_start_date">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Save Fee
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Admission Fees List</h5>
                <div>
                    <select class="form-select form-select-sm d-inline-block" style="width: auto;" id="yearFilter">
                        <option value="">All Years</option>
                        <?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($year->id); ?>"><?php echo e($year->year); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Academic Year</th>
                                <th>Medium</th>
                                <th>Amount</th>
                                <th>Start Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $admissionFees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <span class="badge <?php echo e($fee->academicYear && $fee->academicYear->is_active ? 'bg-success' : 'bg-secondary'); ?>">
                                        <?php echo e($fee->academicYear->year ?? 'N/A'); ?>

                                    </span>
                                </td>
                                <td><?php echo e(ucfirst($fee->medium)); ?></td>
                                <td><strong>₹<?php echo e(number_format($fee->amount, 2)); ?></strong></td>
                                <td>
                                    <?php if($fee->admission_start_date): ?>
                                        <?php echo e(\Carbon\Carbon::parse($fee->admission_start_date)->format('d M Y')); ?>

                                    <?php else: ?>
                                        <span class="text-muted">Not Set</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($fee->status === 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('school-admin.fees.admission.edit', $fee->id)); ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="<?php echo e(route('school-admin.fees.admission.destroy', $fee->id)); ?>" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No admission fees configured. Add your first fee structure.
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/school-admin/fees/admission.blade.php ENDPATH**/ ?>