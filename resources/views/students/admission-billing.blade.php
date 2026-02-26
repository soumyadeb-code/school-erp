@extends('layouts.app')

@section('title', 'Admission Billing')

@section('page-title', 'Admission Billing')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('students.admission') }}">Admission</a></li>
    <li class="breadcrumb-item active">Billing</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Admission Billing - {{ $student->name }} ({{ $student->student_id }})</h5>
    </div>
    <div class="card-body">
        <form id="bill-form" action="{{ route('students.billing.process', $student->id) }}" method="POST">
            @csrf
            
            <div class="row">
                <!-- LEFT SIDE -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Receipt No:</label>
                        <input type="text" class="form-control" name="receipt_no" value="{{ $receiptNo }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Student Name:</label>
                        <input type="text" class="form-control" value="{{ $student->name }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Class:</label>
                        <input type="text" class="form-control" value="{{ $student->schoolClass ? $student->schoolClass->class_name : '-' }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Less Advance:</label>
                        <input type="text" class="form-control" id="lessAdvance" value="{{ $advance }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Old Due:</label>
                        <input type="text" class="form-control" id="oldDueVal" value="{{ $oldDue }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Due:</label>
                        <input type="text" class="form-control" id="newDue" name="new_due" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Due Paid:</label>
                        <input type="text" class="form-control" id="duePaid" name="due_paid" readonly>
                    </div>
                </div>

                <!-- RIGHT SIDE -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Discount (₹):</label>
                        <input type="number" class="form-control" id="discount" name="discount" value="0" min="0" step="0.01">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fee Type:</label>
                        <input type="text" class="form-control" value="Admission" readonly>
                        <input type="hidden" id="feeValue" value="{{ $admissionFee ? $admissionFee->amount : 0 }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date:</label>
                        <input type="date" class="form-control" name="billing_date" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Advance:</label>
                        <input type="text" class="form-control" id="advance" name="advance" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Total Amount:</label>
                        <input type="text" class="form-control" id="totalAmount" name="total_amount" readonly>
                    </div>

                    <!-- Amount Paid - Radio Options -->
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
                        <a href="{{ route('students.admission') }}" class="btn btn-secondary mt-3">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Get elements
    var feeValue = parseFloat(document.getElementById('feeValue').value) || 0;
    var oldDueVal = parseFloat(document.getElementById('oldDueVal').value) || 0;
    var lessAdvance = parseFloat(document.getElementById('lessAdvance').value) || 0;
    var discountInput = document.getElementById('discount');
    var totalAmountInput = document.getElementById('totalAmount');
    var amountPaidInput = document.getElementById('amountPaid');
    var newDueInput = document.getElementById('newDue');
    var duePaidInput = document.getElementById('duePaid');
    var advanceInput = document.getElementById('advance');
    var paymentOptions = document.getElementsByName('paymentOption');
    
    // Calculate total
    function calculateTotal() {
        var discount = parseFloat(discountInput.value) || 0;
        var total = oldDueVal + feeValue - discount - lessAdvance;
        if (total < 0) total = 0;
        totalAmountInput.value = total.toFixed(2);
        return total;
    }
    
    // Calculate due/advance
    function calculateDueAdvance() {
        var total = parseFloat(totalAmountInput.value) || 0;
        var amountPaid = parseFloat(amountPaidInput.value) || 0;
        
        var duePaid = 0;
        var newDue = 0;
        var advance = 0;
        var oldDue = oldDueVal;
        
        if (amountPaid > 0) {
            if (amountPaid > total) {
                advance = amountPaid - total;
                newDue = 0;
            } else {
                newDue = total - amountPaid;
            }
            
            if (amountPaid >= oldDueVal) {
                duePaid = oldDueVal;
                oldDue = 0;
            } else {
                duePaid = amountPaid;
                oldDue = oldDueVal - duePaid;
            }
        }
        
        duePaidInput.value = duePaid.toFixed(2);
        document.getElementById('oldDueVal').value = oldDue.toFixed(2);
        newDueInput.value = newDue.toFixed(2);
        advanceInput.value = advance.toFixed(2);
    }
    
    // Initialize
    var initialTotal = calculateTotal();
    amountPaidInput.value = initialTotal.toFixed(2);
    calculateDueAdvance();
    
    // Discount change
    discountInput.addEventListener('input', function() {
        calculateTotal();
        calculateDueAdvance();
    });
    
    // Amount paid change
    amountPaidInput.addEventListener('input', function() {
        calculateDueAdvance();
    });
    
    // Payment option change
    for (var i = 0; i < paymentOptions.length; i++) {
        paymentOptions[i].addEventListener('change', function() {
            if (this.value === 'total') {
                amountPaidInput.value = totalAmountInput.value;
            } else {
                amountPaidInput.value = '';
                amountPaidInput.focus();
            }
            calculateDueAdvance();
        });
    }
    
    // Form validation
    document.getElementById('bill-form').addEventListener('submit', function(e) {
        var amountPaid = parseFloat(amountPaidInput.value) || 0;
        if (amountPaid <= 0) {
            e.preventDefault();
            alert('Please enter a valid Amount Paid.');
            amountPaidInput.focus();
            return false;
        }
    });
});
</script>
@endsection
