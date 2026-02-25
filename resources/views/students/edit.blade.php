@extends('layouts.app')

@section('title', 'Edit Student')

@section('page-title', 'Edit Student')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('students.list') }}">All Students</a></li>
    <li class="breadcrumb-item active">Edit Student</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Edit Student Information</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('students.update', $student->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Student Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $student->name) }}" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Student ID</label>
                        <input type="text" class="form-control" value="{{ $student->student_id }}" disabled>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob', $student->dob) }}" required>
                        @error('dob')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="class_id" class="form-label">Class</label>
                        <select class="form-select" id="class_id" name="class_id" required>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
                                    {{ $class->class_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="roll" class="form-label">Roll Number</label>
                        <input type="number" class="form-control" id="roll" name="roll"roll', $student->roll) }}">
 value="{{ old('                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="medium" class="form-label">Medium</label>
                        <select class="form-select" id="medium" name="medium" required>
                            <option value="Bengali" {{ old('medium', $student->medium) == 'Bengali' ? 'selected' : '' }}>Bengali</option>
                            <option value="English" {{ old('medium', $student->medium) == 'English' ? 'selected' : '' }}>English</option>
                            <option value="Hindi" {{ old('medium', $student->medium) == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="section" class="form-label">Section</label>
                        <input type="text" class="form-control" id="section" name="section" value="{{ old('section', $student->section) }}">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="father_name" class="form-label">Father's Name</label>
                        <input type="text" class="form-control" id="father_name" name="father_name" value="{{ old('father_name', $student->father_name) }}">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="mother_name" class="form-label">Mother's Name</label>
                        <input type="text" class="form-control" id="mother_name" name="mother_name" value="{{ old('mother_name', $student->mother_name) }}">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $student->phone) }}">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="whatsapp" class="form-label">WhatsApp Number</label>
                        <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $student->whatsapp) }}">
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $student->address) }}</textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="blood_group" class="form-label">Blood Group</label>
                        <select class="form-select" id="blood_group" name="blood_group">
                            <option value="">Select</option>
                            <option value="A+" {{ old('blood_group', $student->blood_group) == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ old('blood_group', $student->blood_group) == 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ old('blood_group', $student->blood_group) == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ old('blood_group', $student->blood_group) == 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="O+" {{ old('blood_group', $student->blood_group) == 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ old('blood_group', $student->blood_group) == 'O-' ? 'selected' : '' }}>O-</option>
                            <option value="AB+" {{ old('blood_group', $student->blood_group) == 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ old('blood_group', $student->blood_group) == 'AB-' ? 'selected' : '' }}>AB-</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="social_category" class="form-label">Social Category</label>
                        <select class="form-select" id="social_category" name="social_category">
                            <option value="">Select</option>
                            <option value="General" {{ old('social_category', $student->social_category) == 'General' ? 'selected' : '' }}>General</option>
                            <option value="OBC" {{ old('social_category', $student->social_category) == 'OBC' ? 'selected' : '' }}>OBC</option>
                            <option value="SC" {{ old('social_category', $student->social_category) == 'SC' ? 'selected' : '' }}>SC</option>
                            <option value="ST" {{ old('social_category', $student->social_category) == 'ST' ? 'selected' : '' }}>ST</option>
                            <option value="Others" {{ old('social_category', $student->social_category) == 'Others' ? 'selected' : '' }}>Others</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $student->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('students.list') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Student</button>
            </div>
        </form>
    </div>
</div>
@endsection
