@extends('layouts.app')

@section('title', 'Generate Monthly Bill')

@section('page-title', 'Generate Monthly Bill')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('students.fee-collection') }}">Fee Collection</a></li>
    <li class="breadcrumb-item"><a href="{{ route('students.payment-history', $student->id) }}">{{ $student->name }}</a></li>
    <li class="breadcrumb-item active">Monthly Bill</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Payment Fees - {{ $student->name }} ({{ $student->student_id }})</h5>
    </div>
    <div class="card-body">
        <form id="bill-form" method="POST" action="{{ route('students.monthly-bill.process', $student->id) }}">
            @csrf
            
            <div class="row">
                <!-- Left Column - Student Details -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Receipt No:</label>
                        <input type="text" class="form-control" name="receipt_no" id="receipt_no" value="{{ $receiptNo }}" required>
                    </div>
                    
                    <input type="hidden" name="pay_year" value="{{ $academicYear ? $academicYear->year : date('Y') }}">
                    
                    <div class="mb-3">
                        <label class="form-label">Student Name:</label>
                        <input type="text" class="form-control" readonly value="{{ $student->name }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Class:</label>
                        <input type="text" class="form-control" readonly value="{{ $student->schoolClass ? $student->schoolClass->class_name : '-' }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Section:</label>
                        <input type="text" class="form-control" readonly value="{{ $student->section ?? 'None' }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Roll:</label>
                        <input type="text" class="form-control" readonly value="{{ $student->roll ?? 'None' }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tuition Fees (Monthly):</label>
                        <input type="text" class="form-control" readonly id="tuitionFees" value="{{ $tuitionFee }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Bus Fee (Monthly):</label>
                        <input type="text" class="form-control" readonly id="busFee" value="{{ $busFee }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Sub-Total:</label>
                        <input type="text" class="form-control" id="subTotal" value="0.00" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Less Advance:</label>
                        <input type="text" class="form-control" readonly value="{{ $advance }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Old Due:</label>
                        <input type="text" class="form-control" readonly id="oldDue" value="{{ $oldDue }}">
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
                        <div class="input-group">
                            <input type="number" class="form-control" id="discount" value="0" min="0" step="0.01" readonly>
                            <button type="button" class="btn btn-info" id="applyCustomDiscountBtn" title="Apply Custom Discount">
                                <i class="fas fa-tag"></i> Custom
                            </button>
                        </div>
                        <small class="text-muted" id="autoDiscountInfo"></small>
                    </div>
                    
                    <label class="form-label">Select Month:</label>
                    <div class="row ml-2" id="month-container">
                        @php
                        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                        $index = 0;
                        @endphp
                        @foreach($months as $monthName)
                            @php
                            $isPaid = in_array($index + 1, $paidMonths);
                            @endphp
                            <div class="form-check col-6 month-wrapper" data-index="{{ $index }}" data-paid="{{ $isPaid ? '1' : '0' }}">
                                <label class="form-check-label">
                                    @if($isPaid)
                                        <input type="checkbox" class="form-check-input" checked disabled>
                                        {{ $monthName }} (Paid)
                                    @else
                                        <input type="checkbox" class="form-check-input month-chk" name="months[]" value="{{ $index + 1 }}">
                                        {{ $monthName }}
                                    @endif
                                </label>
                            </div>
                            @php $index++; @endphp
                        @endforeach
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
                        <a href="{{ route('students.payment-history', $student->id) }}" class="btn btn-secondary mt-3">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Get values from PHP
    var tuitionFee = parseFloat("{{ $tuitionFee }}") || 0;
    var busFee = parseFloat("{{ $busFee }}") || 0;
    var oldDue = parseFloat("{{ $oldDue }}") || 0;
    var advance = parseFloat("{{ $advance }}") || 0;
    
    // Get discount rule values from PHP
    var sameMonthDiscount = parseFloat("{{ $discountRule ? $discountRule->same_month_discount : 0 }}") || 0;
    var nextMonthDiscount = parseFloat("{{ $discountRule ? $discountRule->next_month_discount : 0 }}") || 0;
    var validTillDay = parseInt("{{ $discountRule ? $discountRule->valid_till_day : 10 }}") || 10;
    
    // Get current user role from PHP
    var userRole = "{{ auth()->user()->role ?? '' }}";
    var isSchoolAdmin = (userRole === 'school_admin');
    
    // Get current date info
    var currentDate = new Date();
    var currentMonth = currentDate.getMonth() + 1;
    var currentYear = currentDate.getFullYear();
    
    // Custom discount variable
    var customDiscount = 0;
    
    // Calculate auto discount based on billing date vs first selected month
    function calculateAutoDiscount() {
        var billingDate = new Date(paymentDateEl.value);
        var billingMonth = billingDate.getMonth() + 1;
        var billingYear = billingDate.getFullYear();
        var billingDay = billingDate.getDate();
        
        // If no discount rule set, return 0
        if (sameMonthDiscount === 0 && nextMonthDiscount === 0) {
            return 0;
        }
        
        // Get first selected month (smallest month value)
        var firstSelectedMonth = null;
        monthCheckboxes.forEach(function(cb) {
            if (cb.checked && !firstSelectedMonth) {
                firstSelectedMonth = parseInt(cb.value);
            }
        });
        
        // If no month selected, no discount
        if (!firstSelectedMonth) {
            return 0;
        }
        
        var totalDiscount = 0;
        
        // Calculate months difference between billing month and selected month
        var monthDiff;
        if (billingYear === currentYear) {
            monthDiff = billingMonth - firstSelectedMonth;
        } else if (billingYear > currentYear) {
            // Next year case (e.g., Dec 2024 to Jan 2025)
            monthDiff = (12 - firstSelectedMonth + 1) + billingMonth;
        } else {
            monthDiff = 0;
        }
        
        // Apply discount based on month difference
        if (monthDiff === 0) {
            // Same month payment - full same month discount
            totalDiscount = sameMonthDiscount;
        } else if (monthDiff === 1) {
            // Next month payment - check if within valid day
            if (billingDay <= validTillDay) {
                totalDiscount = nextMonthDiscount;
            } else {
                totalDiscount = 0;
            }
        } else if (monthDiff > 1) {
            // Multiple months late - same month discount + (months-1) * next month discount
            totalDiscount = sameMonthDiscount + ((monthDiff - 1) * nextMonthDiscount);
        }
        
        return totalDiscount;
    }
    
    // Update auto discount info text
    function updateAutoDiscountInfo() {
        var infoEl = document.getElementById('autoDiscountInfo');
        var autoDiscount = calculateAutoDiscount();
        
        if (autoDiscount > 0) {
            infoEl.textContent = 'Auto Discount: ₹' + autoDiscount.toFixed(2);
        } else {
            infoEl.textContent = '';
        }
    }
    
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
    var monthWrappers = document.querySelectorAll('.month-wrapper');
    var customDiscountBtn = document.getElementById('applyCustomDiscountBtn');
    
    // Set today's date
    paymentDateEl.value = new Date().toISOString().split('T')[0];
    
    // Show/hide custom discount button based on role
    if (!isSchoolAdmin) {
        customDiscountBtn.style.display = 'none';
    }
    
    // Custom discount button click handler
    customDiscountBtn.addEventListener('click', function() {
        var customAmount = prompt('Enter custom discount amount (₹):', '0');
        if (customAmount !== null) {
            customDiscount = parseFloat(customAmount) || 0;
            if (customDiscount < 0) customDiscount = 0;
            calculate();
        }
    });
    
    // Initialize months visibility - hide all unpaid months initially
    function initializeMonths() {
        var firstUnpaidFound = false;
        
        monthWrappers.forEach(function(wrapper) {
            var isPaid = wrapper.dataset.paid === '1';
            
            if (isPaid) {
                // Paid months - show as disabled
                wrapper.style.display = 'block';
            } else {
                // Unpaid months - hide all initially
                if (!firstUnpaidFound) {
                    wrapper.style.display = 'block';
                    firstUnpaidFound = true;
                } else {
                    wrapper.style.display = 'none';
                }
            }
        });
    }
    
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
        
        // Get auto discount from discount rule
        var autoDiscount = calculateAutoDiscount();
        
        // Total discount = auto discount + custom discount
        var totalDiscount = autoDiscount + customDiscount;
        
        // Sub total is cumulative - adds up for each selected month
        var subTotalVal = cumulativeSubTotal;
        
        // Calculate total
        var total = subTotalVal + oldDue - advance - totalDiscount;
        
        // Update display
        subTotalEl.value = subTotalVal.toFixed(2);
        discountEl.value = totalDiscount.toFixed(2);
        totalAmountEl.value = total.toFixed(2);
        
        // Update auto discount info
        updateAutoDiscountInfo();
        
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
    
    // Month checkbox change - shows next month when current is checked
    monthCheckboxes.forEach(function(cb) {
        cb.addEventListener('change', function() {
            var idx = parseInt(this.closest('.month-wrapper').dataset.index);
            var wrappers = document.querySelectorAll('.month-wrapper');
            
            if (this.checked) {
                // When checked: Always show next month (keep it BLANK/unchecked)
                if (idx + 1 < wrappers.length) {
                    var nextWrapper = wrappers[idx + 1];
                    var nextCb = nextWrapper.querySelector('input');
                    
                    // Check if next month is already paid
                    var isNextPaid = nextWrapper.dataset.paid === '1';
                    
                    if (isNextPaid) {
                        // If next month is paid, skip to find first unpaid month after this
                        for (var i = idx + 1; i < wrappers.length; i++) {
                            var futureWrapper = wrappers[i];
                            if (futureWrapper.dataset.paid === '0') {
                                futureWrapper.style.display = 'block';
                                var futureCb = futureWrapper.querySelector('input');
                                if (futureCb) {
                                    futureCb.disabled = false;
                                    futureCb.checked = false;
                                }
                                break;
                            }
                        }
                    } else {
                        // Next month is unpaid - show it
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
    
    // Payment date change - recalculate auto discount
    paymentDateEl.addEventListener('change', calculate);
    
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
    
    // Initialize months visibility on page load
    initializeMonths();
    
    // Initial calculation
    calculate();
});
</script>
@endsection
