

<?php $__env->startSection('title', 'Generate Monthly Bill'); ?>

<?php $__env->startSection('page-title', 'Generate Monthly Bill'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('/dashboard')); ?>">Home</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('students.fee-collection')); ?>">Fee Collection</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('students.payment-history', $student->id)); ?>"><?php echo e($student->name); ?></a></li>
    <li class="breadcrumb-item active">Monthly Bill</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Payment Fees - <?php echo e($student->name); ?> (<?php echo e($student->student_id); ?>)</h5>
    </div>
    <div class="card-body">
        <form id="bill-form" method="POST" action="<?php echo e(route('students.monthly-bill.process', $student->id)); ?>">
            <?php echo csrf_field(); ?>
            
            <div class="row">
                <!-- Left Column - Student Details -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Receipt No:</label>
                        <input type="text" class="form-control" name="receipt_no" id="receipt_no" value="<?php echo e($receiptNo); ?>" required>
                    </div>
                    
                    <input type="hidden" name="pay_year" value="<?php echo e($academicYear ? $academicYear->year : date('Y')); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Student Name:</label>
                        <input type="text" class="form-control" readonly value="<?php echo e($student->name); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Class:</label>
                        <input type="text" class="form-control" readonly value="<?php echo e($student->schoolClass ? $student->schoolClass->class_name : '-'); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Section:</label>
                        <input type="text" class="form-control" readonly value="<?php echo e($student->section ?? 'None'); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Roll:</label>
                        <input type="text" class="form-control" readonly value="<?php echo e($student->roll ?? 'None'); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tuition Fees (Monthly):</label>
                        <input type="text" class="form-control" readonly id="tuitionFees" value="<?php echo e($tuitionFee); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Bus Fee (Monthly):</label>
                        <input type="text" class="form-control" readonly id="busFee" value="<?php echo e($busFee); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Sub-Total:</label>
                        <input type="text" class="form-control" id="subTotal" value="0.00" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Less Advance:</label>
                        <input type="text" class="form-control" readonly value="<?php echo e($advance); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Old Due:</label>
                        <input type="text" class="form-control" readonly id="oldDue" value="<?php echo e($oldDue); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">New Due:</label>
                        <input type="text" class="form-control" id="newDue" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">New Advance:</label>
                        <input type="text" class="form-control" id="newAdvance" readonly value="0.00">
                    </div>
                </div>
                
                <!-- Right Column - Payment Details -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Discount (₹):</label>
                        <input type="number" class="form-control" id="discount" value="0" min="0" step="0.01">
                    </div>
                    
                    <label class="form-label">Select Month:</label>
                    <div class="row ml-2" id="month-container">
                        <?php
                        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                        $unpaidShown = false;
                        $index = 0;
                        ?>
                        <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $monthName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            $isPaid = in_array($index + 1, $paidMonths);
                            ?>
                            <div class="form-check col-6 month-wrapper" data-index="<?php echo e($index); ?>" style="<?php echo e(($isPaid || !$unpaidShown) ? '' : 'display: none;'); ?>">
                                <label class="form-check-label">
                                    <?php if($isPaid): ?>
                                        <input type="checkbox" class="form-check-input" checked disabled>
                                        <?php echo e($monthName); ?>

                                    <?php elseif(!$unpaidShown): ?>
                                        <input type="checkbox" class="form-check-input month-chk" name="months[]" value="<?php echo e($index + 1); ?>">
                                        <?php echo e($monthName); ?>

                                        <?php $unpaidShown = true; ?>
                                    <?php else: ?>
                                        <input type="checkbox" class="form-check-input" disabled>
                                        <?php echo e($monthName); ?>

                                    <?php endif; ?>
                                </label>
                            </div>
                            <?php $index++; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <div class="mb-3 mt-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" id="paymentDate" name="billing_date" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Total Amount:</label>
                        <input type="text" class="form-control" id="totalAmount" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Amount Paid:</label><br>
                        <input type="radio" name="paymentOption" value="total" checked> Total
                        <input type="radio" name="paymentOption" value="custom" class="ms-3"> Custom
                        <input type="number" class="form-control mt-2" name="amount_paid" id="amountPaid" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Payment Mode</label>
                        <select name="payment_mode" required class="form-control">
                            <option value="">Select</option>
                            <option value="cash">Cash</option>
                            <option value="online">Online</option>
                        </select>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                        <a href="<?php echo e(route('students.payment-history', $student->id)); ?>" class="btn btn-secondary mt-3">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Get values from PHP
    var tuitionFee = parseFloat("<?php echo e($tuitionFee); ?>") || 0;
    var busFee = parseFloat("<?php echo e($busFee); ?>") || 0;
    var oldDue = parseFloat("<?php echo e($oldDue); ?>") || 0;
    var advance = parseFloat("<?php echo e($advance); ?>") || 0;
    
    // Get elements
    var subTotalEl = document.getElementById('subTotal');
    var totalAmountEl = document.getElementById('totalAmount');
    var amountPaidEl = document.getElementById('amountPaid');
    var newDueEl = document.getElementById('newDue');
    var newAdvanceEl = document.getElementById('newAdvance');
    var discountEl = document.getElementById('discount');
    var paymentDateEl = document.getElementById('paymentDate');
    var monthCheckboxes = document.querySelectorAll('.month-chk');
    var paymentOptions = document.getElementsByName('paymentOption');
    
    // Set today's date
    paymentDateEl.value = new Date().toISOString().split('T')[0];
    
    // Calculate function - calculates cumulative fee based on each selected month
    function calculate() {
        // Monthly fee per month (tuition + bus)
        var monthlyFee = tuitionFee + busFee;
        
        // Calculate cumulative sub-total: each selected month adds (tuitionFee + busFee)
        var cumulativeSubTotal = 0;
        
        monthCheckboxes.forEach(function(cb) {
            if (cb.checked) {
                // Each checked month adds the full monthly fee to cumulative total
                cumulativeSubTotal += monthlyFee;
            }
        });
        
        // Get discount
        var discount = parseFloat(discountEl.value) || 0;
        
        // Sub total is cumulative - adds up for each selected month
        var subTotalVal = cumulativeSubTotal;
        
        // Calculate total
        var total = subTotalVal + oldDue - advance - discount;
        
        // Update display
        subTotalEl.value = subTotalVal.toFixed(2);
        totalAmountEl.value = total.toFixed(2);
        
        // Get amount paid
        var paid = parseFloat(amountPaidEl.value) || 0;
        
        // Calculate new due and advance
        var remaining = total - paid;
        if (remaining > 0) {
            newDueEl.value = remaining.toFixed(2);
            newAdvanceEl.value = '0.00';
        } else {
            newDueEl.value = '0.00';
            newAdvanceEl.value = Math.abs(remaining).toFixed(2);
        }
    }
    
    // Month checkbox change - shows next month BLANK when current is checked
    monthCheckboxes.forEach(function(cb) {
        cb.addEventListener('change', function() {
            var idx = parseInt(this.closest('.month-wrapper').dataset.index);
            var wrappers = document.querySelectorAll('.month-wrapper');
            
            if (this.checked) {
                // When checked: Always show next month (keep it BLANK/unchecked)
                if (idx + 1 < wrappers.length) {
                    var nextWrapper = wrappers[idx + 1];
                    var nextCb = nextWrapper.querySelector('input');
                    
                    // Always show the next month wrapper
                    nextWrapper.style.display = 'block';
                    
                    // Enable the checkbox if it's disabled
                    if (nextCb && nextCb.disabled) {
                        nextCb.disabled = false;
                    }
                    
                    // Keep it blank/unchecked
                    if (nextCb) {
                        nextCb.checked = false;
                    }
                }
            } else {
                // When unchecked: Disable and hide subsequent months
                for (var i = idx + 1; i < wrappers.length; i++) {
                    var cb = wrappers[i].querySelector('input');
                    if (cb) {
                        cb.checked = false;
                        cb.disabled = true;
                        wrappers[i].style.display = 'none';
                    }
                }
            }
            
            // Recalculate - cumulative fee adds up for each selected month
            calculate();
        });
    });
    
    // Amount paid change
    amountPaidEl.addEventListener('input', calculate);
    
    // Discount change
    discountEl.addEventListener('input', calculate);
    
    // Payment option change
    for (var i = 0; i < paymentOptions.length; i++) {
        paymentOptions[i].addEventListener('change', function() {
            if (this.value === 'total') {
                amountPaidEl.value = totalAmountEl.value;
            } else {
                amountPaidEl.value = '';
                amountPaidEl.focus();
            }
            calculate();
        });
    }
    
    // Initial calculation
    calculate();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/students/monthly-bill.blade.php ENDPATH**/ ?>