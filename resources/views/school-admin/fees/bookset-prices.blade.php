@extends('layouts.app')

@section('title', 'Bookset Prices')

@section('page-title', 'Bookset Prices Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/fees') }}">Fees</a></li>
    <li class="breadcrumb-item active">Bookset Prices</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Bookset Prices Configuration</h5>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPriceModal">
                    <i class="fas fa-plus"></i> Add Bookset Price
                </button>
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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Academic Year</th>
                        <th>Class</th>
                        <th>Book Price</th>
                        <th>Notebook Price</th>
                        <th>Total Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prices as $price)
                        <tr>
                            <td>{{ $price->academicYear ? $price->academicYear->year : '-' }}</td>
                            <td>{{ $price->schoolClass ? $price->schoolClass->class_name : '-' }}</td>
                            <td>₹{{ number_format($price->book_price, 2) }}</td>
                            <td>₹{{ number_format($price->notebook_price, 2) }}</td>
                            <td>₹{{ number_format($price->total_price, 2) }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editPriceModal{{ $price->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('school-admin.fees.bookset.destroy', $price->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        
                        <!-- Edit Modal -->
                        <div class="modal fade" id="editPriceModal{{ $price->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Bookset Price</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('school-admin.fees.bookset.update', $price->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Academic Year</label>
                                                <select name="academic_year_id" class="form-select" required>
                                                    @foreach($years as $year)
                                                        <option value="{{ $year->id }}" {{ $price->academic_year_id == $year->id ? 'selected' : '' }}>
                                                            {{ $year->year }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Class</label>
                                                <select name="class_id" class="form-select" required>
                                                    @foreach($classes as $class)
                                                        <option value="{{ $class->id }}" {{ $price->class_id == $class->id ? 'selected' : '' }}>
                                                            {{ $class->class_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Book Price (₹)</label>
                                                <input type="number" name="book_price" class="form-control" value="{{ $price->book_price }}" required min="0" step="0.01">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Notebook Price (₹)</label>
                                                <input type="number" name="notebook_price" class="form-control" value="{{ $price->notebook_price }}" required min="0" step="0.01">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No bookset prices configured yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addPriceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Bookset Price</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('school-admin.fees.bookset.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Academic Year</label>
                        <select name="academic_year_id" class="form-select" required>
                            <option value="">Select Year</option>
                            @foreach($years as $year)
                                <option value="{{ $year->id }}">{{ $year->year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Class</label>
                        <select name="class_id" class="form-select" required>
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Book Price (₹)</label>
                        <input type="number" name="book_price" class="form-control" required min="0" step="0.01" placeholder="0.00">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notebook Price (₹)</label>
                        <input type="number" name="notebook_price" class="form-control" required min="0" step="0.01" placeholder="0.00">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
