

<?php $__env->startSection('title', 'Admission Receipt'); ?>

<?php $__env->startSection('page-title', 'Admission Receipt'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('/dashboard')); ?>">Home</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('students.admission')); ?>">Admission</a></li>
    <li class="breadcrumb-item active">Receipt</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-success text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Admission Receipt</h5>
                    <button onclick="window.print()" class="btn btn-light btn-sm">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="receipt-container">
                    <!-- School Info -->
                    <div class="text-center mb-4">
                        <h4><?php echo e(auth()->user()->school->school_name ?? 'School Name'); ?></h4>
                        <p class="text-muted mb-0"><?php echo e(auth()->user()->school->address ?? ''); ?></p>
                    </div>

                    <hr>

                    <!-- Receipt Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Receipt No:</strong> <?php echo e($receipt->receipt_no); ?></p>
                            <p><strong>Student ID:</strong> <?php echo e($receipt->student->student_id); ?></p>
                            <p><strong>Student Name:</strong> <?php echo e($receipt->student->name); ?></p>
                            <p><strong>Class:</strong> <?php echo e($receipt->student->schoolClass->class_name ?? 'N/A'); ?></p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p><strong>Date:</strong> <?php echo e(\Carbon\Carbon::parse($receipt->billing_date)->format('d-m-Y')); ?></p>
                            <p><strong>Academic Year:</strong> <?php echo e($receipt->student->academic_year ?? 'N/A'); ?></p>
                            <p><strong>Bill Type:</strong> Admission Fee</p>
                        </div>
                    </div>

                    <!-- Fee Details -->
                    <table class="table table-bordered">
                        <thead>
                            <tr class="bg-light">
                                <th>Description</th>
                                <th class="text-end">Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Admission Fee</td>
                                <td class="text-end"><?php echo e(number_format($receipt->total_amount, 2)); ?></td>
                            </tr>
                            <tr>
                                <td>Discount</td>
                                <td class="text-end">- <?php echo e(number_format($receipt->discount, 2)); ?></td>
                            </tr>
                            <tr>
                                <td>Old Due Paid</td>
                                <td class="text-end"><?php echo e(number_format($receipt->old_due_paid, 2)); ?></td>
                            </tr>
                            <tr class="bg-light">
                                <th>Total</th>
                                <th class="text-end"><?php echo e(number_format($receipt->total_amount + $receipt->old_due_paid - $receipt->discount, 2)); ?></th>
                            </tr>
                            <tr>
                                <td>Amount Paid</td>
                                <td class="text-end text-success"><?php echo e(number_format($receipt->paid_amount, 2)); ?></td>
                            </tr>
                            <?php if($receipt->due_amount > 0): ?>
                            <tr>
                                <td>Due Amount</td>
                                <td class="text-end text-warning"><?php echo e(number_format($receipt->due_amount, 2)); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if($receipt->advance_amount > 0): ?>
                            <tr>
                                <td>Advance Amount</td>
                                <td class="text-end text-info"><?php echo e(number_format($receipt->advance_amount, 2)); ?></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <p><strong>Payment Mode:</strong> <?php echo e(ucfirst($receipt->payment_mode)); ?></p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p><strong>Status:</strong>
                                <?php if($receipt->status === 'paid'): ?>
                                    <span class="badge bg-success">Paid</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Due</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-muted">Thank you for your payment!</p>
                        <p class="text-muted">Admission completed successfully.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function printReceipt() {
    window.print();
}

// Auto print on page load (optional)
document.addEventListener('DOMContentLoaded', function() {
    // Uncomment the line below if you want auto-print on page load
    // setTimeout(function() { window.print(); }, 1000);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/students/admission-receipt.blade.php ENDPATH**/ ?>