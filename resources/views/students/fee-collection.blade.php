@extends('layouts.app')

@section('title', 'Fee Collection')

@section('page-title', 'Student Monthly Fee Collection')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Fee Collection</li>
@endsection

@section('content')
<div class="row">
    <!-- Left Column - Search and Student Details -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-search me-2"></i>Search Student</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('students.fee-collection') }}">
                    <div class="mb-3">
                        <label class="form-label">Search by</label>
                        <select class="form-select" name="search_type">
                            <option value="id" {{ request('search_type', 'id') == 'id' ? 'selected' : '' }}>Student ID</option>
                            <option value="name" {{ request('search_type') == 'name' ? 'selected' : '' }}>Name</option>
                            <option value="phone" {{ request('search_type') == 'phone' ? 'selected' : '' }}>Phone Number</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" name="query" placeholder="Enter search term..." value="{{ request('query') }}">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                    @if(request('query'))
                    <a href="{{ route('students.fee-collection') }}" class="btn btn-secondary w-100 mt-2">
                        <i class="fas fa-times me-2"></i>Clear Search
                    </a>
                    @endif
                </form>
            </div>
        </div>
        
        @if($selectedStudent)
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Student Details</h5>
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> {{ $selectedStudent->student_id }}</p>
                <p><strong>Name:</strong> {{ $selectedStudent->name }}</p>
                <p><strong>Class:</strong> {{ $selectedStudent->schoolClass->class_name ?? 'N/A' }}</p>
                <p><strong>Medium:</strong> {{ ucfirst($selectedStudent->medium) }}</p>
                <p><strong>Academic Year:</strong> {{ $selectedStudent->academicYear->year ?? 'N/A' }}</p>
                <hr>
                <div class="d-flex justify-content-between">
                    <div>
                        <small class="text-muted">Total Due</small>
                        <h4 class="text-danger">₹{{ number_format($totalDue, 2) }}</h4>
                    </div>
                    <div>
                        <small class="text-muted">Advance</small>
                        <h4 class="text-success">₹{{ number_format($totalAdvance, 2) }}</h4>
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
                    <strong>₹{{ number_format($monthlyFee, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Bus Fee</span>
                    <strong>₹{{ number_format($busFee, 2) }}</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span>Total Monthly</span>
                    <strong>₹{{ number_format($monthlyFee + $busFee, 2) }}</strong>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Right Column - Students Table or Payment Form -->
    <div class="col-md-8">
        @if($selectedStudent)
        <!-- Payment History and Form when student is selected -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Payment History - {{ $selectedStudent->name }}</h5>
                <div>
                    <select class="form-select form-select-sm d-inline-block" style="width: auto;" id="yearFilter">
                        @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $year->id == $selectedYear ? 'selected' : '' }}>
                            {{ $year->year }}
                        </option>
                        @endforeach
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
                            @php
                            $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                            @endphp
                            @foreach($months as $index => $month)
                            @php
                            $payment = $payments->firstWhere('month', $index + 4); // April = 4
                            @endphp
                            <tr>
                                <td>{{ $month }}</td>
                                <td>₹{{ number_format($monthlyFee, 2) }}</td>
                                <td>₹{{ number_format($busFee, 2) }}</td>
                                <td>₹{{ number_format($monthlyFee + $busFee, 2) }}</td>
                                <td>
                                    @if($payment && $payment->status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="badge bg-danger">Due</span>
                                    @endif
                                </td>
                                <td>
                                    @if($payment && $payment->status === 'paid')
                                        <a href="{{ route('students.receipt', $payment->id) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    @else
                                        <form method="POST" action="{{ route('students.collect-fee') }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="student_id" value="{{ $selectedStudent->id }}">
                                            <input type="hidden" name="months[]" value="{{ $index + 4 }}">
                                            <input type="hidden" name="year" value="{{ $selectedYear }}">
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="fas fa-plus"></i> Pay
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
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
                <form method="POST" action="{{ route('students.collect-fee') }}">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $selectedStudent->id }}">
                    <input type="hidden" name="year" value="{{ $selectedYear }}">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Select Months</label>
                            <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                @foreach($months as $index => $month)
                                @php
                                $payment = $payments->firstWhere('month', $index + 4);
                                @endphp
                                @if(!$payment || $payment->status !== 'paid')
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="months[]" value="{{ $index + 4 }}" id="month_{{ $index }}">
                                    <label class="form-check-label" for="month_{{ $index }}">
                                        {{ $month }}
                                        @if($payment && $payment->status === 'due')
                                        <span class="text-danger">(Due)</span>
                                        @endif
                                    </label>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Billing Date</label>
                                <input type="date" class="form-control" name="billing_date" value="{{ date('Y-m-d') }}">
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
        @else
        <!-- Students Table - Show all students by default -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>All Students</h5>
            </div>
            <div class="card-body">
                @if($students->count() > 0)
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
                            @foreach($students as $student)
                            <tr>
                                <td>{{ $student->student_id }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->schoolClass->class_name ?? 'N/A' }}</td>
                                <td>{{ ucfirst($student->medium) }}</td>
                                <td>{{ $student->phone ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('students.fee-collection', ['student_id' => $student->id]) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-money-bill"></i> Collect Fee
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($students->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $students->links() }}
                </div>
                @endif
                @else
                <div class="text-center py-5">
                    <i class="fas fa-users text-muted" style="font-size: 48px;"></i>
                    <h5 class="mt-3">No Students Found</h5>
                    <p class="text-muted">There are no active students in your school.</p>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
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
@endsection
