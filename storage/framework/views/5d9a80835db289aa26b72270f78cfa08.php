

<?php $__env->startSection('title', 'Student Fee Price List'); ?>

<?php $__env->startSection('page-title', 'Fee Price List'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('/dashboard')); ?>">Home</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('students.fee-collection')); ?>">Fee Collection</a></li>
    <li class="breadcrumb-item active">Fee Price List</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Navigation Tabs -->
<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link active" href="<?php echo e(route('students.fee-price-list', $student->id)); ?>">
            <i class="fas fa-list me-1"></i> Fee Prices
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('students.payment-history', $student->id)); ?>">
            <i class="fas fa-history me-1"></i> Bill History
        </a>
    </li>
</ul>

<div class="card">
    <div class="card-header">
        <h5>Fee Price List - <?php echo e($student->name); ?> (<?php echo e($student->student_id); ?>)</h5>
    </div>
    <div class="card-body">
        <!-- Student Info -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="text-muted">Student Details</h6>
                <table class="table table-sm">
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
                        <td><?php echo e($student->schoolClass ? $student->schoolClass->class_name : '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Medium:</th>
                        <td><?php echo e(ucfirst($student->medium)); ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted">Current Balance</h6>
                <table class="table table-sm">
                    <tr>
                        <th>Old Due:</th>
                        <td class="text-danger">₹<?php echo e(number_format($oldDue, 2)); ?></td>
                    </tr>
                    <tr>
                        <th>Advance:</th>
                        <td class="text-success">₹<?php echo e(number_format($advance, 2)); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Fee Price List -->
        <h6 class="mb-3">Available Fee Types - Click "Generate Bill" to create payment</h6>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Sl No</th>
                        <th>Fee Type</th>
                        <th>Amount (₹)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><strong>Admission Fee</strong></td>
                        <td>₹<?php echo e(number_format($admissionFeeAmount, 2)); ?></td>
                        <td>
                            <a href="<?php echo e(route('students.billing', $student->id)); ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-file-invoice me-1"></i> Generate Bill
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><strong>Registration Fee</strong></td>
                        <td>₹<?php echo e(number_format($registrationFeeAmount, 2)); ?></td>
                        <td>
                            <a href="<?php echo e(route('students.billing', $student->id)); ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-file-invoice me-1"></i> Generate Bill
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td><strong>Class Fee (Monthly)</strong></td>
                        <td>₹<?php echo e(number_format($classFeeAmount, 2)); ?></td>
                        <td>
                            <a href="<?php echo e(route('students.monthly-bill', $student->id)); ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-file-invoice me-1"></i> Generate Bill
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td><strong>Bus Fee (Monthly)</strong></td>
                        <td>₹<?php echo e(number_format($busFeeAmount, 2)); ?></td>
                        <td>
                            <a href="<?php echo e(route('students.monthly-bill', $student->id)); ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-file-invoice me-1"></i> Generate Bill
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td><strong>Bookset Price</strong></td>
                        <td>₹<?php echo e(number_format($booksetPriceAmount, 2)); ?></td>
                        <td>
                            <a href="<?php echo e(route('students.monthly-bill', $student->id)); ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-file-invoice me-1"></i> Generate Bill
                            </a>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="table-success">
                        <td colspan="2"><strong>Total (One Time Fees)</strong></td>
                        <td><strong>₹<?php echo e(number_format($admissionFeeAmount + $registrationFeeAmount + $booksetPriceAmount, 2)); ?></strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-3">
            <a href="<?php echo e(route('students.fee-collection')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Fee Collection
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/students/fee-price-list.blade.php ENDPATH**/ ?>