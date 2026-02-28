

<?php $__env->startSection('title', 'Admission Fees Not Set'); ?>

<?php $__env->startSection('page-title', 'Admission Fees Not Set'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('/dashboard')); ?>">Home</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('students.admission')); ?>">Admission</a></li>
    <li class="breadcrumb-item active">Fee Not Set</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-warning">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Admission Fees Not Set</h5>
            </div>
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-money-bill-wave text-warning" style="font-size: 4rem;"></i>
                </div>
                
                <h4 class="mb-3">Admission fees have not been set for <span class="text-primary"><?php echo e($medium); ?></span> medium</h4>
                
                <p class="text-muted mb-4">
                    Please set up the admission fees before proceeding with billing.
                    Go to <strong>Fees Setup → Admission Fees</strong> to configure the fees.
                </p>
                
                <div class="alert alert-info">
                    <strong>Navigation:</strong> goto. > Fees Setup > Admission Fees Set
                </div>
                
                
                <div class="alert alert-secondary text-start">
                    <strong>Debug Info:</strong><br>
                    Student Medium: <strong><?php echo e($medium ?? 'N/A'); ?></strong><br>
                    Academic Year: <?php echo e($academicYear->year ?? 'N/A'); ?> (ID: <?php echo e($academicYear->id ?? 'N/A'); ?>)
                </div>
                
                <div class="mt-4">
                    <a href="<?php echo e(route('school-admin.fees.admission')); ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-cog me-2"></i>Set Admission Fees
                    </a>
                    <a href="<?php echo e(route('students.admission')); ?>" class="btn btn-secondary btn-lg ms-2">
                        <i class="fas fa-arrow-left me-2"></i>Back to Admission
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/students/admission-fee-not-set.blade.php ENDPATH**/ ?>