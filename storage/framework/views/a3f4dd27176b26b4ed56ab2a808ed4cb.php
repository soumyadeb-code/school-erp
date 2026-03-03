

<?php $__env->startSection('title', 'Payment History'); ?>

<?php $__env->startSection('page-title', 'Payment History'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('/dashboard')); ?>">Home</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('students.fee-collection')); ?>">Fee Collection</a></li>
    <li class="breadcrumb-item active"><?php echo e($student->name); ?></li>
<?php $__env->stopSection(); ?>

<!-- Navigation Tabs -->
<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('students.fee-price-list', $student->id)); ?>">
            <i class="fas fa-list me-1"></i> Fee Prices
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="<?php echo e(route('students.payment-history', $student->id)); ?>">
            <i class="fas fa-history me-1"></i> Bill History
        </a>
    </li>
</ul>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Payment History - <?php echo e($student->name); ?> (<?php echo e($student->student_id); ?>)</h5>
        <div>
            <a href="<?php echo e(route('students.fee-collection')); ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="text-muted small">Tuition Fee</div>
                    <div class="fs-4 fw-bold">₹<?php echo e(number_format($tuitionFee, 2)); ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="text-muted small">
                        <?php if($student->busDestination): ?>
                            Bus Fee (<?php echo e($student->busDestination->destination); ?>)
                        <?php else: ?>
                            Bus Fee
                        <?php endif; ?>
                    </div>
                    <div class="fs-4 fw-bold">₹<?php echo e(number_format($busFee, 2)); ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="text-muted small">Old Due</div>
                    <div class="fs-4 fw-bold text-danger">₹<?php echo e(number_format($oldDue, 2)); ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="text-muted small">Advance</div>
                    <div class="fs-4 fw-bold text-success">₹<?php echo e(number_format($advance, 2)); ?></div>
                </div>
            </div>
        </div>

        <h6>Monthly Payments</h6>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Tuition Fee</th>
                        <th>Bus Fee</th>
                        <th>Total</th>
                        <th>Discount</th>
                        <th>Paid Amount</th>
                        <th>Receipt No.</th>
                        <th>Payment Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e(date('F', mktime(0, 0, 0, $payment->month, 1))); ?></td>
                            <td>₹<?php echo e(number_format($payment->tuition_fee, 2)); ?></td>
                            <td>₹<?php echo e(number_format($payment->bus_fee, 2)); ?></td>
                            <td>₹<?php echo e(number_format($payment->total_fee, 2)); ?></td>
                            <td>₹<?php echo e(number_format($payment->discount, 2)); ?></td>
                            <td>₹<?php echo e(number_format($payment->paid_amount, 2)); ?></td>
                            <td><?php echo e($payment->receipt_no); ?></td>
                            <td><?php echo e($payment->payment_date ? Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y') : '-'); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($payment->status == 'paid' ? 'success' : 'warning'); ?>">
                                    <?php echo e(ucfirst($payment->status)); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted">No payment records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/students/payment-history.blade.php ENDPATH**/ ?>