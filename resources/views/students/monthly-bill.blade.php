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
                        <label class="form-label">Conveyance Charge (Monthly):</label>
                        <input type="text" class="form-control" readonly id="conveyanceCharge" value="{{ $busFee }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Sub-Total:</label>
                        <input type="text" class="form-control" id="subTotal" readonly>
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
                        <input type="number" class="form-control" id="discount" value="0" min="0" step="0.01">
                    </div>
                    
                    <label class="form-label">Select Month:</label>
                    <div class="row ml-2" id="month-container">
                        @php
                        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                        $unpaidShown = false;
                        $index = 0;
                        @endphp
                        @foreach($months as $monthName)
                            @php
                            $isPaid = in_array($index + 1, $paidMonths);
                            @endphp
                            <div class="form-check col-6 month-wrapper" data-index="{{ $index }}" style="{{ ($isPaid || !$unpaidShown) ? '' : 'display: none;' }}">
                                <label class="form-check-label">
                                    @if($isPaid)
                                        <input type="checkbox" class="form-check-input" checked disabled>
                                        {{ $monthName }}
                                    @elseif(!$unpaidShown)
                                        <input type="checkbox" class="form-check-input month-chk" name="months[]" value="{{ $index + 1 }}">
                                        {{ $monthName }}
                                        @php $unpaidShown = true; @endphp
                                    @else
                                        <input type="checkbox" class="form-check-input" disabled>
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
    
    // Calculate function
    function calculate() {
        // Count selected months
        var monthsSelected = 0;
        monthCheckboxes.forEach(function(cb) {
            if (cb.checked) monthsSelected++;
        });
        
        // Get discount
        var discount = parseFloat(discountEl.value) || 0;
        
        // Calculate sub total
        var subTotalVal = (tuitionFee + busFee) * monthsSelected;
        
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
    
    // Month checkbox change
    monthCheckboxes.forEach(function(cb) {
        cb.addEventListener('change', function() {
            var idx = parseInt(this.closest('.month-wrapper').dataset.index);
            var wrappers = document.querySelectorAll('.month-wrapper');
            
            if (this.checked) {
                // Enable next month
                if (idx + 1 < wrappers.length) {
                    var nextCb = wrappers[idx + 1].querySelector('input');
                    if (nextCb && nextCb.disabled) {
                        nextCb.disabled = false;
                        wrappers[idx + 1].style.display = 'block';
                    }
                }
            } else {
                // Disable subsequent months
                for (var i = idx + 1; i < wrappers.length; i++) {
                    var cb = wrappers[i].querySelector('input');
                    if (cb) {
                        cb.checked = false;
                        cb.disabled = true;
                        wrappers[i].style.display = 'none';
                    }
                }
            }
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
@endsection
