

<?php $__env->startSection('title', 'Edit Student'); ?>

<?php $__env->startSection('page-title', 'Edit Student'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('/dashboard')); ?>">Home</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('students.list')); ?>">All Students</a></li>
    <li class="breadcrumb-item active">Edit Student</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h5>Edit Student Information</h5>
    </div>
    <div class="card-body">
        <form action="<?php echo e(route('students.update', $student->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Student Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo e(old('name', $student->name)); ?>" required>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Student ID</label>
                        <input type="text" class="form-control" value="<?php echo e($student->student_id); ?>" disabled>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob" value="<?php echo e(old('dob', $student->dob)); ?>" required>
                        <?php $__errorArgs = ['dob'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="male" <?php echo e(old('gender', $student->gender) == 'male' ? 'selected' : ''); ?>>Male</option>
                            <option value="female" <?php echo e(old('gender', $student->gender) == 'female' ? 'selected' : ''); ?>>Female</option>
                            <option value="other" <?php echo e(old('gender', $student->gender) == 'other' ? 'selected' : ''); ?>>Other</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="class_id" class="form-label">Class</label>
                        <select class="form-select" id="class_id" name="class_id" required>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>" <?php echo e(old('class_id', $student->class_id) == $class->id ? 'selected' : ''); ?>>
                                    <?php echo e($class->class_name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="roll" class="form-label">Roll Number</label>
                        <input type="number" class="form-control" id="roll" name="roll" value="<?php echo e(old('roll', $student->roll)); ?>">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="medium" class="form-label">Medium</label>
                        <select class="form-select" id="medium" name="medium" required>
                            <option value="Bengali" <?php echo e(old('medium', $student->medium) == 'Bengali' ? 'selected' : ''); ?>>Bengali</option>
                            <option value="English" <?php echo e(old('medium', $student->medium) == 'English' ? 'selected' : ''); ?>>English</option>
                            <option value="Hindi" <?php echo e(old('medium', $student->medium) == 'Hindi' ? 'selected' : ''); ?>>Hindi</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="section" class="form-label">Section</label>
                        <input type="text" class="form-control" id="section" name="section" value="<?php echo e(old('section', $student->section)); ?>">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="father_name" class="form-label">Father's Name</label>
                        <input type="text" class="form-control" id="father_name" name="father_name" value="<?php echo e(old('father_name', $student->father_name)); ?>">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="mother_name" class="form-label">Mother's Name</label>
                        <input type="text" class="form-control" id="mother_name" name="mother_name" value="<?php echo e(old('mother_name', $student->mother_name)); ?>">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="mother_education" class="form-label">Mother's Education</label>
                        <input type="text" class="form-control" id="mother_education" name="mother_education" value="<?php echo e(old('mother_education', $student->mother_education)); ?>">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="father_education" class="form-label">Father's Education</label>
                        <input type="text" class="form-control" id="father_education" name="father_education" value="<?php echo e(old('father_education', $student->father_education)); ?>">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="yearly_income" class="form-label">Yearly Income</label>
                        <input type="number" class="form-control" id="yearly_income" name="yearly_income" value="<?php echo e(old('yearly_income', $student->yearly_income)); ?>">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="religion" class="form-label">Religion</label>
                        <select class="form-select" id="religion" name="religion">
                            <option value="">Select</option>
                            <option value="Hindu" <?php echo e(old('religion', $student->religion) == 'Hindu' ? 'selected' : ''); ?>>Hindu</option>
                            <option value="Muslim" <?php echo e(old('religion', $student->religion) == 'Muslim' ? 'selected' : ''); ?>>Muslim</option>
                            <option value="Christian" <?php echo e(old('religion', $student->religion) == 'Christian' ? 'selected' : ''); ?>>Christian</option>
                            <option value="Sikh" <?php echo e(old('religion', $student->religion) == 'Sikh' ? 'selected' : ''); ?>>Sikh</option>
                            <option value="Buddhist" <?php echo e(old('religion', $student->religion) == 'Buddhist' ? 'selected' : ''); ?>>Buddhist</option>
                            <option value="Jain" <?php echo e(old('religion', $student->religion) == 'Jain' ? 'selected' : ''); ?>>Jain</option>
                            <option value="Other" <?php echo e(old('religion', $student->religion) == 'Other' ? 'selected' : ''); ?>>Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo e(old('phone', $student->phone)); ?>">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="whatsapp" class="form-label">WhatsApp Number</label>
                        <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="<?php echo e(old('whatsapp', $student->whatsapp)); ?>">
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" rows="3"><?php echo e(old('address', $student->address)); ?></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="blood_group" class="form-label">Blood Group</label>
                        <select class="form-select" id="blood_group" name="blood_group">
                            <option value="">Select</option>
                            <option value="A+" <?php echo e(old('blood_group', $student->blood_group) == 'A+' ? 'selected' : ''); ?>>A+</option>
                            <option value="A-" <?php echo e(old('blood_group', $student->blood_group) == 'A-' ? 'selected' : ''); ?>>A-</option>
                            <option value="B+" <?php echo e(old('blood_group', $student->blood_group) == 'B+' ? 'selected' : ''); ?>>B+</option>
                            <option value="B-" <?php echo e(old('blood_group', $student->blood_group) == 'B-' ? 'selected' : ''); ?>>B-</option>
                            <option value="O+" <?php echo e(old('blood_group', $student->blood_group) == 'O+' ? 'selected' : ''); ?>>O+</option>
                            <option value="O-" <?php echo e(old('blood_group', $student->blood_group) == 'O-' ? 'selected' : ''); ?>>O-</option>
                            <option value="AB+" <?php echo e(old('blood_group', $student->blood_group) == 'AB+' ? 'selected' : ''); ?>>AB+</option>
                            <option value="AB-" <?php echo e(old('blood_group', $student->blood_group) == 'AB-' ? 'selected' : ''); ?>>AB-</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="social_category" class="form-label">Social Category</label>
                        <select class="form-select" id="social_category" name="social_category">
                            <option value="">Select</option>
                            <option value="General" <?php echo e(old('social_category', $student->social_category) == 'General' ? 'selected' : ''); ?>>General</option>
                            <option value="OBC" <?php echo e(old('social_category', $student->social_category) == 'OBC' ? 'selected' : ''); ?>>OBC</option>
                            <option value="SC" <?php echo e(old('social_category', $student->social_category) == 'SC' ? 'selected' : ''); ?>>SC</option>
                            <option value="ST" <?php echo e(old('social_category', $student->social_category) == 'ST' ? 'selected' : ''); ?>>ST</option>
                            <option value="Others" <?php echo e(old('social_category', $student->social_category) == 'Others' ? 'selected' : ''); ?>>Others</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="active" <?php echo e(old('status', $student->status) == 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="inactive" <?php echo e(old('status', $student->status) == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                </select>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="<?php echo e(route('students.list')); ?>" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Student</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/students/edit.blade.php ENDPATH**/ ?>