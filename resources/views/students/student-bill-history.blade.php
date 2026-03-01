@extends('layouts.app')

@section('title', 'Student Bill History')

@section('page-title', 'Student Bill History')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('students.list') }}">All Students</a></li>
    <li class="breadcrumb-item"><a href="{{ route('students.show', $student->id) }}">{{ $student->name }}</a></li>
    <li class="breadcrumb-item active">Bill History</li>
@endsection

@section('content')
<div class="row">
    <!-- Left Side - Monthly Payment Table (Jan-Dec) -->
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>Monthly Payment History
                    @if($selectedYear)
                        <span class="badge bg-primary ms-2">{{ $selectedYear->year }}</span>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                @if($selectedYear)
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
                            @php
                                $months = [
                                    1 => 'January', 2 => 'February', 3 => 'March', 
                                    4 => 'April', 5 => 'May', 6 => 'June',
                                    7 => 'July', 8 => 'August', 9 => 'September',
                                    10 => 'October', 11 => 'November', 12 => 'December'
                                ];
                                $totalTuition = 0;
                                $totalBus = 0;
                                $totalSub = 0;
                            @endphp
                            @foreach($months as $monthNum => $monthName)
                                @php
                                    $payment = isset($monthlyPayments[$monthNum]) ? $monthlyPayments[$monthNum] : null;
                                    $isPaid = $payment && $payment->status == 'paid';
                                    $isDue = !$isPaid && ($currentDue > 0 || (isset($monthlyPayments[$monthNum]) && $monthlyPayments[$monthNum]->status == 'due'));
                                @endphp
                                <tr class="{{ $isPaid ? 'table-success' : ($isDue ? 'table-warning' : '') }}">
                                    <td class="text-start">
                                        <strong>{{ $monthName }}</strong>
                                        @if($payment && $payment->payment_date)
                                            <br><small class="text-muted">{{ Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y') }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($isPaid)
                                            ₹{{ number_format($payment->tuition_fee, 2) }}
                                            @php $totalTuition += $payment->tuition_fee; @endphp
                                        @else
                                            ₹{{ number_format($tuitionFee, 2) }}
                                            @php $totalTuition += $tuitionFee; @endphp
                                        @endif
                                    </td>
                                    <td>
                                        @if($isPaid)
                                            ₹{{ number_format($payment->bus_fee, 2) }}
                                            @php $totalBus += $payment->bus_fee; @endphp
                                        @else
                                            @if($busFee > 0)
                                                ₹{{ number_format($busFee, 2) }}
                                                @php $totalBus += $busFee; @endphp
                                            @else
                                                -
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($isPaid)
                                            ₹{{ number_format($payment->total_fee, 2) }}
                                            @php $totalSub += $payment->total_fee; @endphp
                                        @else
                                            @php 
                                                $monthSub = $tuitionFee + ($busFee > 0 ? $busFee : 0);
                                                $totalSub += $monthSub;
                                            @endphp
                                            ₹{{ number_format($monthSub, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($isPaid)
                                            <span class="badge bg-success">Paid</span>
                                        @else
                                            <span class="badge bg-danger">Due</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment && $payment->receipt_no)
                                            {{ $payment->receipt_no }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment && $payment->receipt_id)
                                            <a href="{{ route('students.receipt-view', $payment->receipt_id) }}" class="btn btn-sm btn-info" title="View Receipt">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('students.monthly-bill', $student->id) }}" class="btn btn-sm btn-primary" title="Pay Now">
                                                <i class="fas fa-plus"></i> Pay
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="2" class="text-end">Total:</th>
                                <th>₹{{ number_format($totalBus, 2) }}</th>
                                <th>₹{{ number_format($totalSub, 2) }}</th>
                                <th colspan="3"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>No academic year selected. Please select an academic year to view payment history.
                    </div>
                @endif
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
                            @forelse($receipts as $index => $receipt)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ Carbon\Carbon::parse($receipt->billing_date)->format('d-m-Y') }}</td>
                                <td>{{ $receipt->receipt_no }}</td>
                                <td>
                                    @if($receipt->bill_type == 'admission')
                                        <span class="badge bg-primary">Admission</span>
                                    @elseif($receipt->bill_type == 'registration')
                                        <span class="badge bg-info">Registration</span>
                                    @elseif($receipt->bill_type == 'monthly')
                                        <span class="badge bg-success">Monthly</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($receipt->bill_type) }}</span>
                                    @endif
                                </td>
                                <td>₹{{ number_format($receipt->total_amount, 2) }}</td>
                                <td>₹{{ number_format($receipt->paid_amount, 2) }}</td>
                                <td>₹{{ number_format($receipt->due_amount, 2) }}</td>
                                <td>
                                    @if($receipt->status == 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($receipt->status == 'due')
                                        <span class="badge bg-warning">Due</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($receipt->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('students.receipt-view', $receipt->id) }}" class="btn btn-sm btn-info" title="View Receipt">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No bills found for this student.</td>
                            </tr>
                            @endforelse
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
                    @if($student->photo)
                        <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}" class="rounded-circle" width="80" height="80">
                    @else
                        <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width:80px;height:80px;font-size:32px;">
                            {{ substr($student->name, 0, 1) }}
                        </div>
                    @endif
                    <h5 class="mt-2">{{ $student->name }}</h5>
                    <p class="text-muted mb-1">{{ $student->student_id }}</p>
                    <span class="badge bg-{{ $student->status == 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($student->status) }}
                    </span>
                </div>
                
                <table class="table table-sm">
                    <tr>
                        <td><strong>Class:</strong></td>
                        <td>{{ $student->schoolClass ? $student->schoolClass->class_name : '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Medium:</strong></td>
                        <td>{{ ucfirst($student->medium) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Roll:</strong></td>
                        <td>{{ $student->roll ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Father's Name:</strong></td>
                        <td>{{ $student->father_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Phone:</strong></td>
                        <td>{{ $student->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Bus:</strong></td>
                        <td>
                            @if($student->busDestination)
                                <span class="badge bg-info">{{ $student->busDestination->destination }}</span>
                            @else
                                <span class="text-muted">Not Assigned</span>
                            @endif
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
                        <td class="text-end">₹{{ number_format($tuitionFee, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Bus Fee (Monthly):</td>
                        <td class="text-end">₹{{ number_format($busFee, 2) }}</td>
                    </tr>
                    <tr class="table-light">
                        <th>Total Monthly:</th>
                        <th class="text-end">₹{{ number_format($tuitionFee + $busFee, 2) }}</th>
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
                        <td class="text-end text-success">₹{{ number_format($totalAdmissionPaid, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Registration Paid:</td>
                        <td class="text-end text-success">₹{{ number_format($totalRegistrationPaid, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Monthly Paid:</td>
                        <td class="text-end text-success">₹{{ number_format($totalMonthlyPaid, 2) }}</td>
                    </tr>
                    <tr class="table-light">
                        <th>Total Paid:</th>
                        <th class="text-end text-success">₹{{ number_format($totalAdmissionPaid + $totalRegistrationPaid + $totalMonthlyPaid, 2) }}</th>
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
                        <td class="text-end text-danger">₹{{ number_format($currentDue, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Current Year Advance:</td>
                        <td class="text-end text-success">₹{{ number_format($currentAdvance, 2) }}</td>
                    </tr>
                    @if($totalOldDue > 0)
                    <tr>
                        <td>Old Due:</td>
                        <td class="text-end text-danger">₹{{ number_format($totalOldDue, 2) }}</td>
                    </tr>
                    @endif
                    @if($totalAdvance > 0)
                    <tr>
                        <td>Total Advance:</td>
                        <td class="text-end text-success">₹{{ number_format($totalAdvance, 2) }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card">
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('students.monthly-bill', $student->id) }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-money-bill-wave me-2"></i>Pay Bill
                    </a>
                    <a href="{{ route('students.fee-price-list', $student->id) }}" class="btn btn-info">
                        <i class="fas fa-list me-2"></i>View Fee Price List
                    </a>
                    <a href="{{ route('students.show', $student->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
