@extends('layouts.app')

@section('title', 'Discount Rules')

@section('page-title', 'Discount Rules Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/fees') }}">Fees</a></li>
    <li class="breadcrumb-item active">Discount Rules</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-12">
                <h5 class="mb-0">Discount Rules Configuration</h5>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('school-admin.fees.discount.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Same Month Discount (₹)</label>
                        <input type="number" name="same_month_discount" class="form-control" 
                            value="{{ $discount ? $discount->same_month_discount : 0 }}" 
                            required min="0" step="0.01">
                        <small class="text-muted">Discount amount for paying within the same month</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Next Month Discount (₹)</label>
                        <input type="number" name="next_month_discount" class="form-control" 
                            value="{{ $discount ? $discount->next_month_discount : 0 }}" 
                            required min="0" step="0.01">
                        <small class="text-muted">Discount amount for paying in the next month</small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Valid Till Day</label>
                        <input type="number" name="valid_till_day" class="form-control" 
                            value="{{ $discount ? $discount->valid_till_day : 10 }}" 
                            required min="1" max="31">
                        <small class="text-muted">Day of month until which discount is valid</small>
                    </div>
                </div>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Discount Rules
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Current Configuration</h5>
    </div>
    <div class="card-body">
        @if($discount)
            <table class="table table-bordered">
                <tr>
                    <th>Same Month Discount</th>
                    <td>₹{{ number_format($discount->same_month_discount, 2) }}</td>
                </tr>
                <tr>
                    <th>Next Month Discount</th>
                    <td>₹{{ number_format($discount->next_month_discount, 2) }}</td>
                </tr>
                <tr>
                    <th>Valid Till Day</th>
                    <td>Day {{ $discount->valid_till_day }} of each month</td>
                </tr>
            </table>
        @else
            <p class="text-muted">No discount rules configured yet.</p>
        @endif
    </div>
</div>
@endsection
