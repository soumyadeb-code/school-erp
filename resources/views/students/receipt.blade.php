@extends('layouts.app')

@section('title', 'Payment Receipt')

@section('page-title', 'Payment Receipt')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('students.fee-collection') }}">Fee Collection</a></li>
    <li class="breadcrumb-item active">Receipt</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-success text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Payment Receipt</h5>
                    <button onclick="window.print()" class="btn btn-light btn-sm">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="receipt-container">
                    <!-- School Info -->
                    <div class="text-center mb-4">
                        <h4>{{ auth()->user()->school->school_name ?? 'School Name' }}</h4>
                        <p class="text-muted mb-0">{{ auth()->user()->school->address ?? '' }}</p>
                    </div>

                    <hr>

                    <!-- Receipt Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Receipt No:</strong> {{ $payment->receipt_no }}</p>
                            <p><strong>Student ID:</strong> {{ $payment->student->student_id }}</p>
                            <p><strong>Student Name:</strong> {{ $payment->student->name }}</p>
                            <p><strong>Class:</strong> {{ $payment->student->schoolClass->class_name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y') }}</p>
                            <p><strong>Academic Year:</strong> {{ $payment->academicYear->year ?? 'N/A' }}</p>
                            <p><strong>Month:</strong> {{ date('F', mktime(0, 0, 0, $payment->month, 1)) }}</p>
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
                                <td>Tuition Fee</td>
                                <td class="text-end">{{ number_format($payment->tuition_fee, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Bus Fee</td>
                                <td class="text-end">{{ number_format($payment->bus_fee, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Discount</td>
                                <td class="text-end">- {{ number_format($payment->discount, 2) }}</td>
                            </tr>
                            <tr class="bg-light">
                                <th>Total</th>
                                <th class="text-end">{{ number_format($payment->total_fee - $payment->discount, 2) }}</th>
                            </tr>
                            <tr>
                                <td>Amount Paid</td>
                                <td class="text-end text-success">{{ number_format($payment->paid_amount, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="text-center mt-4">
                        <p class="text-muted">Thank you for your payment!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
