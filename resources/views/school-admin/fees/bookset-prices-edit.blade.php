@extends('layouts.app')

@section('title', 'Edit Bookset Price')

@section('page-title', 'Edit Bookset Price')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/fees') }}">Fees</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/fees/bookset') }}">Bookset Prices</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Bookset Price</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('school-admin.fees.bookset.update', $booksetPrice->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">Academic Year</label>
                <select name="academic_year_id" class="form-select" required>
                    @foreach($years as $year)
                        <option value="{{ $year->id }}" {{ $booksetPrice->academic_year_id == $year->id ? 'selected' : '' }}>
                            {{ $year->year }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Class</label>
                <select name="class_id" class="form-select" required>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ $booksetPrice->class_id == $class->id ? 'selected' : '' }}>
                            {{ $class->class_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Medium</label>
                <select name="medium" class="form-select" required>
                    <option value="Bengali" {{ $booksetPrice->medium == 'Bengali' ? 'selected' : '' }}>Bengali</option>
                    <option value="English" {{ $booksetPrice->medium == 'English' ? 'selected' : '' }}>English</option>
                    <option value="Hindi" {{ $booksetPrice->medium == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Book Price (₹)</label>
                <input type="number" name="book_price" class="form-control" value="{{ $booksetPrice->book_price }}" required min="0" step="0.01">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Notebook Price (₹)</label>
                <input type="number" name="notebook_price" class="form-control" value="{{ $booksetPrice->notebook_price }}" required min="0" step="0.01">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Total Price (₹)</label>
                <input type="text" class="form-control" value="₹{{ number_format($booksetPrice->total_price, 2) }}" readonly>
            </div>
            
            <div class="text-end">
                <a href="{{ route('school-admin.fees.bookset') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
