@extends('layouts.app')

@section('title', 'Promotion History')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Promotion History</h3>
                    <div class="card-tools">
                        <a href="{{ route('school-admin.promotions.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Promotions
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('school-admin.promotions.history') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Academic Year</label>
                                    <select name="academic_year" class="form-control">
                                        <option value="">All Years</option>
                                        @foreach($academicYears as $year)
                                            <option value="{{ $year->id }}" {{ $academicYearId == $year->id ? 'selected' : '' }}>
                                                {{ $year->year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Student ID</label>
                                    <input type="text" name="student_id" class="form-control" placeholder="Enter Student ID" value="{{ $studentId }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>From Year</th>
                                    <th>To Year</th>
                                    <th>From Class</th>
                                    <th>To Class</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($promotions as $promotion)
                                    <tr>
                                        <td>{{ Carbon\Carbon::parse($promotion->promotion_date)->format('d-m-Y') }}</td>
                                        <td>{{ $promotion->student ? $promotion->student->student_id : '-' }}</td>
                                        <td>{{ $promotion->student ? $promotion->student->name : '-' }}</td>
                                        <td>{{ $promotion->fromAcademicYear ? $promotion->fromAcademicYear->year : '-' }}</td>
                                        <td>{{ $promotion->toAcademicYear ? $promotion->toAcademicYear->year : '-' }}</td>
                                        <td>{{ $promotion->fromClass ? $promotion->fromClass->class_name : '-' }}</td>
                                        <td>{{ $promotion->toClass ? $promotion->toClass->class_name : '-' }}</td>
                                        <td>{{ $promotion->remarks ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No promotion history found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $promotions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
