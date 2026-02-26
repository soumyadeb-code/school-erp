@extends('layouts.app')

@section('title', 'Payment History')

@section('page-title', 'Payment History')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('students.fee-collection') }}">Fee Collection</a></li>
    <li class="breadcrumb-item active">{{ $student->name }}</li>
@endsection

<!-- Navigation Tabs -->
<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('students.fee-price-list', $student->id) }}">
            <i class="fas fa-list me-1"></i> Fee Prices
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('students.payment-history', $student->id) }}">
            <i class="fas fa-history me-1"></i> Bill History
        </a>
    </li>
</ul>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Payment History - {{ $student->name }} ({{ $student->student_id }})</h5>
        <div>
            <a href="{{ route('students.monthly-bill', $student->id) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Generate Bill
            </a>
            <a href="{{ route('students.fee-collection') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="text-muted small">Tuition Fee</div>
                    <div class="fs-4 fw-bold">₹{{ number_format($tuitionFee, 2) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="text-muted small">Bus Fee</div>
                    <div class="fs-4 fw-bold">₹{{ number_format($busFee, 2) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="text-muted small">Old Due</div>
                    <div class="fs-4 fw-bold text-danger">₹{{ number_format($oldDue, 2) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="text-muted small">Advance</div>
                    <div class="fs-4 fw-bold text-success">₹{{ number_format($advance, 2) }}</div>
                </div>
            </div>
        </div>

        <h6>Monthly Payments</h6>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Tuition Fee</th>
                        <th>Bus Fee</th>
                        <th>Total</th>
                        <th>Discount</th>
                        <th>Paid Amount</th>
                        <th>Receipt No.</th>
                        <th>Payment Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ date('F', mktime(0, 0, 0, $payment->month, 1)) }}</td>
                            <td>₹{{ number_format($payment->tuition_fee, 2) }}</td>
                            <td>₹{{ number_format($payment->bus_fee, 2) }}</td>
                            <td>₹{{ number_format($payment->total_fee, 2) }}</td>
                            <td>₹{{ number_format($payment->discount, 2) }}</td>
                            <td>₹{{ number_format($payment->paid_amount, 2) }}</td>
                            <td>{{ $payment->receipt_no }}</td>
                            <td>{{ $payment->payment_date ? Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y') : '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $payment->status == 'paid' ? 'success' : 'warning' }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No payment records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
