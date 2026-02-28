@extends('layouts.app')

@section('title', 'Admission Receipt')

@section('page-title', 'Admission Receipt')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('students.admission') }}">Admission</a></li>
    <li class="breadcrumb-item active">Receipt</li>
@endsection

@section('content')
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
                    <div class="text-center mb-4">
                        <h4>{{ auth()->user()->school->school_name ?? 'School Name' }}</h4>
                        <p class="text-muted mb-0">{{ auth()->user()->school->address ?? '' }}</p>
                    </div>

                    <hr>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Receipt No:</strong> {{ $receipt->receipt_no }}</p>
                            <p><strong>Student ID:</strong> {{ $receipt->student->student_id }}</p>
                            <p><strong>Student Name:</strong> {{ $receipt->student->name }}</p>
                            <p><strong>Class:</strong> {{ $receipt->student->schoolClass->class_name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($receipt->billing_date)->format('d-m-Y') }}</p>
                            <p><strong>Academic Year:</strong> {{ $receipt->student->academic_year ?? 'N/A' }}</p>
                            <p><strong>Bill Type:</strong> Admission Fee</p>
                        </div>
                    </div>

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
                                <td class="text-end">{{ number_format($receipt->total_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Discount</td>
                                <td class="text-end">- {{ number_format($receipt->discount, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Old Due Paid</td>
                                <td class="text-end">{{ number_format($receipt->old_due_paid, 2) }}</td>
                            </tr>
                            @if(($receipt->less_advance ?? 0) > 0)
                            <tr>
                                <td>Less Advance</td>
                                <td class="text-end">- {{ number_format($receipt->less_advance, 2) }}</td>
                            </tr>
                            @endif
                            <tr class="bg-light">
                                <th>Total</th>
                                <th class="text-end">{{ number_format($receipt->total_amount + $receipt->old_due_paid - $receipt->discount - ($receipt->less_advance ?? 0), 2) }}</th>
                            </tr>
                            <tr>
                                <td>Amount Paid</td>
                                <td class="text-end text-success">{{ number_format($receipt->paid_amount, 2) }}</td>
                            </tr>
                            @if($receipt->due_amount > 0)
                            <tr>
                                <td>Due Amount</td>
                                <td class="text-end text-warning">{{ number_format($receipt->due_amount, 2) }}</td>
                            </tr>
                            @endif
                            @if($receipt->advance_amount > 0)
                            <tr>
                                <td>Advance Amount</td>
                                <td class="text-end text-info">{{ number_format($receipt->advance_amount, 2) }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <p><strong>Payment Mode:</strong> {{ ucfirst($receipt->payment_mode) }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p><strong>Status:</strong>
                                @if($receipt->status === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @else
                                    <span class="badge bg-warning">Due</span>
                                @endif
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
@endsection

@section('scripts')
<script>
function printReceipt() {
    window.print();
}

document.addEventListener('DOMContentLoaded', function() {
});
</script>
@endsection
