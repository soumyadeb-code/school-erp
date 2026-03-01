

<?php $__env->startSection('title', 'Student Bill History'); ?>

<?php $__env->startSection('page-title', 'Student Bill History'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('/dashboard')); ?>">Home</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('students.list')); ?>">All Students</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('students.show', $student->id)); ?>"><?php echo e($student->name); ?></a></li>
    <li class="breadcrumb-item active">Bill History</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Left Side - Monthly Payment Table (Jan-Dec) -->
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>Monthly Payment History
                    <?php if($selectedYear): ?>
                        <span class="badge bg-primary ms-2"><?php echo e($selectedYear->year); ?></span>
                    <?php endif; ?>
                </h5>
            </div>
            <div class="card-body">
                <?php if($selectedYear): ?>
                <!-- Monthly Payment Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-sm text-center">
                        <thead class="table-light">
                            <tr>
                                <th>Month</th>
                                <th>Tuition Fee</th>
                                <th>Bus Fee</th>
                                <th>Sub Total</th>
                                <th>Status</th>
                                <th>Receipt No</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $months = [
                                    1 => 'January', 2 => 'February', 3 => 'March', 
                                    4 => 'April', 5 => 'May', 6 => 'June',
                                    7 => 'July', 8 => 'August', 9 => 'September',
                                    10 => 'October', 11 => 'November', 12 => 'December'
                                ];
                                $totalTuition = 0;
                                $totalBus = 0;
                                $totalSub = 0;
                            ?>
                            <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $monthNum => $monthName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $payment = isset($monthlyPayments[$monthNum]) ? $monthlyPayments[$monthNum] : null;
                                    $isPaid = $payment && $payment->status == 'paid';
                                    $isDue = !$isPaid && ($currentDue > 0 || (isset($monthlyPayments[$monthNum]) && $monthlyPayments[$monthNum]->status == 'due'));
                                ?>
                                <tr class="<?php echo e($isPaid ? 'table-success' : ($isDue ? 'table-warning' : '')); ?>">
                                    <td class="text-start">
                                        <strong><?php echo e($monthName); ?></strong>
                                        <?php if($payment && $payment->payment_date): ?>
                                            <br><small class="text-muted"><?php echo e(Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y')); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($isPaid): ?>
                                            ₹<?php echo e(number_format($payment->tuition_fee, 2)); ?>

                                            <?php $totalTuition += $payment->tuition_fee; ?>
                                        <?php else: ?>
                                            ₹<?php echo e(number_format($tuitionFee, 2)); ?>

                                            <?php $totalTuition += $tuitionFee; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($isPaid): ?>
                                            ₹<?php echo e(number_format($payment->bus_fee, 2)); ?>

                                            <?php $totalBus += $payment->bus_fee; ?>
                                        <?php else: ?>
                                            <?php if($busFee > 0): ?>
                                                ₹<?php echo e(number_format($busFee, 2)); ?>

                                                <?php $totalBus += $busFee; ?>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($isPaid): ?>
                                            ₹<?php echo e(number_format($payment->total_fee, 2)); ?>

                                            <?php $totalSub += $payment->total_fee; ?>
                                        <?php else: ?>
                                            <?php 
                                                $monthSub = $tuitionFee + ($busFee > 0 ? $busFee : 0);
                                                $totalSub += $monthSub;
                                            ?>
                                            ₹<?php echo e(number_format($monthSub, 2)); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($isPaid): ?>
                                            <span class="badge bg-success">Paid</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Due</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($payment && $payment->receipt_no): ?>
                                            <?php echo e($payment->receipt_no); ?>

                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($payment && $payment->receipt_id): ?>
                                            <a href="<?php echo e(route('students.receipt-view', $payment->receipt_id)); ?>" class="btn btn-sm btn-info" title="View Receipt">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo e(route('students.monthly-bill', $student->id)); ?>" class="btn btn-sm btn-primary" title="Pay Now">
                                                <i class="fas fa-plus"></i> Pay
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="2" class="text-end">Total:</th>
                                <th>₹<?php echo e(number_format($totalBus, 2)); ?></th>
                                <th>₹<?php echo e(number_format($totalSub, 2)); ?></th>
                                <th colspan="3"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>No academic year selected. Please select an academic year to view payment history.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Bill History Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>All Bills & Receipts</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Date</th>
                                <th>Receipt No</th>
                                <th>Type</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $receipts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $receipt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e(Carbon\Carbon::parse($receipt->billing_date)->format('d-m-Y')); ?></td>
                                <td><?php echo e($receipt->receipt_no); ?></td>
                                <td>
                                    <?php if($receipt->bill_type == 'admission'): ?>
                                        <span class="badge bg-primary">Admission</span>
                                    <?php elseif($receipt->bill_type == 'registration'): ?>
                                        <span class="badge bg-info">Registration</span>
                                    <?php elseif($receipt->bill_type == 'monthly'): ?>
                                        <span class="badge bg-success">Monthly</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?php echo e(ucfirst($receipt->bill_type)); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>₹<?php echo e(number_format($receipt->total_amount, 2)); ?></td>
                                <td>₹<?php echo e(number_format($receipt->paid_amount, 2)); ?></td>
                                <td>₹<?php echo e(number_format($receipt->due_amount, 2)); ?></td>
                                <td>
                                    <?php if($receipt->status == 'paid'): ?>
                                        <span class="badge bg-success">Paid</span>
                                    <?php elseif($receipt->status == 'due'): ?>
                                        <span class="badge bg-warning">Due</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?php echo e(ucfirst($receipt->status)); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('students.receipt-view', $receipt->id)); ?>" class="btn btn-sm btn-info" title="View Receipt">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted">No bills found for this student.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Student Details & Fee Summary -->
    <div class="col-lg-4">
        <!-- Student Details Card -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Student Details</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <?php if($student->photo): ?>
                        <img src="<?php echo e(asset('storage/' . $student->photo)); ?>" alt="<?php echo e($student->name); ?>" class="rounded-circle" width="80" height="80">
                    <?php else: ?>
                        <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width:80px;height:80px;font-size:32px;">
                            <?php echo e(substr($student->name, 0, 1)); ?>

                        </div>
                    <?php endif; ?>
                    <h5 class="mt-2"><?php echo e($student->name); ?></h5>
                    <p class="text-muted mb-1"><?php echo e($student->student_id); ?></p>
                    <span class="badge bg-<?php echo e($student->status == 'active' ? 'success' : 'secondary'); ?>">
                        <?php echo e(ucfirst($student->status)); ?>

                    </span>
                </div>
                
                <table class="table table-sm">
                    <tr>
                        <td><strong>Class:</strong></td>
                        <td><?php echo e($student->schoolClass ? $student->schoolClass->class_name : '-'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Medium:</strong></td>
                        <td><?php echo e(ucfirst($student->medium)); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Roll:</strong></td>
                        <td><?php echo e($student->roll ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Father's Name:</strong></td>
                        <td><?php echo e($student->father_name ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Phone:</strong></td>
                        <td><?php echo e($student->phone ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Bus:</strong></td>
                        <td>
                            <?php if($student->busDestination): ?>
                                <span class="badge bg-info"><?php echo e($student->busDestination->destination); ?></span>
                            <?php else: ?>
                                <span class="text-muted">Not Assigned</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Fee Summary Card -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-rupee-sign me-2"></i>Fee Summary</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td>Tuition Fee (Monthly):</td>
                        <td class="text-end">₹<?php echo e(number_format($tuitionFee, 2)); ?></td>
                    </tr>
                    <tr>
                        <td>Bus Fee (Monthly):</td>
                        <td class="text-end">₹<?php echo e(number_format($busFee, 2)); ?></td>
                    </tr>
                    <tr class="table-light">
                        <th>Total Monthly:</th>
                        <th class="text-end">₹<?php echo e(number_format($tuitionFee + $busFee, 2)); ?></th>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Payment Summary Card -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Payment Summary</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td>Admission Paid:</td>
                        <td class="text-end text-success">₹<?php echo e(number_format($totalAdmissionPaid, 2)); ?></td>
                    </tr>
                    <tr>
                        <td>Registration Paid:</td>
                        <td class="text-end text-success">₹<?php echo e(number_format($totalRegistrationPaid, 2)); ?></td>
                    </tr>
                    <tr>
                        <td>Monthly Paid:</td>
                        <td class="text-end text-success">₹<?php echo e(number_format($totalMonthlyPaid, 2)); ?></td>
                    </tr>
                    <tr class="table-light">
                        <th>Total Paid:</th>
                        <th class="text-end text-success">₹<?php echo e(number_format($totalAdmissionPaid + $totalRegistrationPaid + $totalMonthlyPaid, 2)); ?></th>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Due & Advance Card -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-exclamation-circle me-2"></i>Due & Advance</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td>Current Year Due:</td>
                        <td class="text-end text-danger">₹<?php echo e(number_format($currentDue, 2)); ?></td>
                    </tr>
                    <tr>
                        <td>Current Year Advance:</td>
                        <td class="text-end text-success">₹<?php echo e(number_format($currentAdvance, 2)); ?></td>
                    </tr>
                    <?php if($totalOldDue > 0): ?>
                    <tr>
                        <td>Old Due:</td>
                        <td class="text-end text-danger">₹<?php echo e(number_format($totalOldDue, 2)); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if($totalAdvance > 0): ?>
                    <tr>
                        <td>Total Advance:</td>
                        <td class="text-end text-success">₹<?php echo e(number_format($totalAdvance, 2)); ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card">
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('students.monthly-bill', $student->id)); ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-money-bill-wave me-2"></i>Pay Bill
                    </a>
                    <a href="<?php echo e(route('students.fee-price-list', $student->id)); ?>" class="btn btn-info">
                        <i class="fas fa-list me-2"></i>View Fee Price List
                    </a>
                    <a href="<?php echo e(route('students.show', $student->id)); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/students/student-bill-history.blade.php ENDPATH**/ ?>