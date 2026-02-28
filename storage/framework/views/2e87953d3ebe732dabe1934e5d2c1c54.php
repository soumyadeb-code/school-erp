

<?php $__env->startSection('title', 'Fee Collection'); ?>

<?php $__env->startSection('page-title', 'Student Monthly Fee Collection'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('/dashboard')); ?>">Home</a></li>
    <li class="breadcrumb-item active">Fee Collection</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Left Column - Search and Student Details -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-search me-2"></i>Search Student</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('students.fee-collection')); ?>">
                    <div class="mb-3">
                        <label class="form-label">Search by</label>
                        <select class="form-select" name="search_type">
                            <option value="id" <?php echo e(request('search_type', 'id') == 'id' ? 'selected' : ''); ?>>Student ID</option>
                            <option value="name" <?php echo e(request('search_type') == 'name' ? 'selected' : ''); ?>>Name</option>
                            <option value="phone" <?php echo e(request('search_type') == 'phone' ? 'selected' : ''); ?>>Phone Number</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" name="query" placeholder="Enter search term..." value="<?php echo e(request('query')); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                    <?php if(request('query')): ?>
                    <a href="<?php echo e(route('students.fee-collection')); ?>" class="btn btn-secondary w-100 mt-2">
                        <i class="fas fa-times me-2"></i>Clear Search
                    </a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        
        <?php if($selectedStudent): ?>
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Student Details</h5>
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> <?php echo e($selectedStudent->student_id); ?></p>
                <p><strong>Name:</strong> <?php echo e($selectedStudent->name); ?></p>
                <p><strong>Class:</strong> <?php echo e($selectedStudent->schoolClass->class_name ?? 'N/A'); ?></p>
                <p><strong>Medium:</strong> <?php echo e(ucfirst($selectedStudent->medium)); ?></p>
                <p><strong>Academic Year:</strong> <?php echo e($selectedStudent->academicYear->year ?? 'N/A'); ?></p>
                <hr>
                <div class="d-flex justify-content-between">
                    <div>
                        <small class="text-muted">Total Due</small>
                        <h4 class="text-danger">₹<?php echo e(number_format($totalDue, 2)); ?></h4>
                    </div>
                    <div>
                        <small class="text-muted">Advance</small>
                        <h4 class="text-success">₹<?php echo e(number_format($totalAdvance, 2)); ?></h4>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-money-bill me-2"></i>Fee Structure</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Monthly Tuition Fee</span>
                    <strong>₹<?php echo e(number_format($monthlyFee, 2)); ?></strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Bus Fee</span>
                    <strong>₹<?php echo e(number_format($busFee, 2)); ?></strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span>Total Monthly</span>
                    <strong>₹<?php echo e(number_format($monthlyFee + $busFee, 2)); ?></strong>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Right Column - Students Table or Payment Form -->
    <div class="col-md-8">
        <?php if($selectedStudent): ?>
        <!-- Payment History and Form when student is selected -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Payment History - <?php echo e($selectedStudent->name); ?></h5>
                <div>
                    <select class="form-select form-select-sm d-inline-block" style="width: auto;" id="yearFilter">
                        <?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($year->id); ?>" <?php echo e($year->id == $selectedYear ? 'selected' : ''); ?>>
                            <?php echo e($year->year); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Tuition Fee</th>
                                <th>Bus Fee</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Receipt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                            ?>
                            <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            $payment = $payments->firstWhere('month', $index + 4); // April = 4
                            ?>
                            <tr>
                                <td><?php echo e($month); ?></td>
                                <td>₹<?php echo e(number_format($monthlyFee, 2)); ?></td>
                                <td>₹<?php echo e(number_format($busFee, 2)); ?></td>
                                <td>₹<?php echo e(number_format($monthlyFee + $busFee, 2)); ?></td>
                                <td>
                                    <?php if($payment && $payment->status === 'paid'): ?>
                                        <span class="badge bg-success">Paid</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Due</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($payment && $payment->status === 'paid'): ?>
                                        <a href="<?php echo e(route('students.receipt', $payment->id)); ?>" class="btn btn-sm btn-success">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    <?php else: ?>
                                        <form method="POST" action="<?php echo e(route('students.collect-fee')); ?>" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="student_id" value="<?php echo e($selectedStudent->id); ?>">
                                            <input type="hidden" name="months[]" value="<?php echo e($index + 4); ?>">
                                            <input type="hidden" name="year" value="<?php echo e($selectedYear); ?>">
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="fas fa-plus"></i> Pay
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Quick Payment Form -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Quick Payment</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('students.collect-fee')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="student_id" value="<?php echo e($selectedStudent->id); ?>">
                    <input type="hidden" name="year" value="<?php echo e($selectedYear); ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Select Months</label>
                            <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $payment = $payments->firstWhere('month', $index + 4);
                                ?>
                                <?php if(!$payment || $payment->status !== 'paid'): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="months[]" value="<?php echo e($index + 4); ?>" id="month_<?php echo e($index); ?>">
                                    <label class="form-check-label" for="month_<?php echo e($index); ?>">
                                        <?php echo e($month); ?>

                                        <?php if($payment && $payment->status === 'due'): ?>
                                        <span class="text-danger">(Due)</span>
                                        <?php endif; ?>
                                    </label>
                                </div>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Billing Date</label>
                                <input type="date" class="form-control" name="billing_date" value="<?php echo e(date('Y-m-d')); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Payment Mode</label>
                                <select class="form-select" name="payment_mode">
                                    <option value="cash">Cash</option>
                                    <option value="online">Online</option>
                                    <option value="cheque">Cheque</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Discount (₹)</label>
                                <input type="number" class="form-control" name="discount" value="0" min="0">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Amount Paid</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="amount_type" value="total" checked>
                                    <label class="form-check-label">Total Amount</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="amount_type" value="custom">
                                    <label class="form-check-label">Custom Amount</label>
                                </div>
                                <input type="number" class="form-control mt-2" name="custom_amount" placeholder="Enter custom amount" disabled>
                            </div>
                            
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check me-2"></i>Pay Now
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php else: ?>
        <!-- Students Table - Show all students by default -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>All Students</h5>
            </div>
            <div class="card-body">
                <?php if($students->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Medium</th>
                                <th>Phone</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($student->student_id); ?></td>
                                <td><?php echo e($student->name); ?></td>
                                <td><?php echo e($student->schoolClass->class_name ?? 'N/A'); ?></td>
                                <td><?php echo e(ucfirst($student->medium)); ?></td>
                                <td><?php echo e($student->phone ?? '-'); ?></td>
                                <td>
                                    <a href="<?php echo e(route('students.fee-collection', ['student_id' => $student->id])); ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-money-bill"></i> Collect Fee
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if($students->hasPages()): ?>
                <div class="d-flex justify-content-center mt-4">
                    <?php echo e($students->links()); ?>

                </div>
                <?php endif; ?>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-users text-muted" style="font-size: 48px;"></i>
                    <h5 class="mt-3">No Students Found</h5>
                    <p class="text-muted">There are no active students in your school.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.querySelectorAll('input[name="amount_type"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const customInput = document.querySelector('input[name="custom_amount"]');
            if (this.value === 'custom') {
                customInput.disabled = false;
            } else {
                customInput.disabled = true;
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/students/fee-collection.blade.php ENDPATH**/ ?>