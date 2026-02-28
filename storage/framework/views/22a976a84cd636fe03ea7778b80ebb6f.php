

<?php $__env->startSection('title', 'Bulk Student Promotion'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bulk Student Promotion</h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('school-admin.promotions.index')); ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>

                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle"></i> Promotion Details</h5>
                        <p>Current Academic Year: <strong><?php echo e($currentYear ? $currentYear->year : 'N/A'); ?></strong></p>
                        <p>Target Academic Year: <strong><?php echo e($nextYear ? $nextYear->year : 'N/A'); ?></strong></p>
                        <?php if(!$nextYear): ?>
                            <p class="text-danger">Please create the next academic year first before promoting students.</p>
                        <?php endif; ?>
                    </div>

                    <?php if($nextYear): ?>
                        <form action="<?php echo e(route('school-admin.promotions.store')); ?>" method="POST" id="promotionForm">
                            <?php echo csrf_field(); ?>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Target Academic Year</label>
                                        <select name="to_academic_year_id" class="form-control" required>
                                            <option value="<?php echo e($nextYear->id); ?>"><?php echo e($nextYear->year); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Target Class</label>
                                        <select name="to_class_id" class="form-control" required>
                                            <option value="">Select Class</option>
                                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($class->id); ?>"><?php echo e($class->class_name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Promotion Date</label>
                                        <input type="date" name="promotion_date" class="form-control" value="<?php echo e(date('Y-m-d')); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Remarks (Optional)</label>
                                        <input type="text" name="remarks" class="form-control" placeholder="Enter remarks">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" id="selectAll"> Select All Students
                                </label>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="studentsTable">
                                    <thead>
                                        <tr>
                                            <th width="50">
                                                <input type="checkbox" id="selectAllCheckbox">
                                            </th>
                                            <th>Student ID</th>
                                            <th>Name</th>
                                            <th>Current Class</th>
                                            <th>Next Class (Auto)</th>
                                            <th>Gender</th>
                                            <th>Medium</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="student_ids[]" value="<?php echo e($student->id); ?>" class="student-checkbox">
                                                </td>
                                                <td><?php echo e($student->student_id); ?></td>
                                                <td><?php echo e($student->name); ?></td>
                                                <td><?php echo e($student->schoolClass ? $student->schoolClass->class_name : '-'); ?></td>
                                                <td>
                                                    <?php if($student->nextClass): ?>
                                                        <span class="badge bg-success"><?php echo e($student->nextClass->class_name); ?></span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">No path</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo e(ucfirst($student->gender)); ?></td>
                                                <td><?php echo e(ucfirst($student->medium)); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="7" class="text-center">No students found for promotion.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to promote selected students?')">
                                    <i class="fas fa-arrow-up"></i> Promote Selected Students
                                </button>
                                <a href="<?php echo e(route('school-admin.promotions.index')); ?>" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            Please create the next academic year before promoting students.
                            <a href="<?php echo e(route('school-admin.academic-years.index')); ?>">Click here to create academic year.</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        // Select all checkbox
        $('#selectAllCheckbox').change(function() {
            $('.student-checkbox').prop('checked', $(this).prop('checked'));
        });

        // Also update when clicking select all label
        $('#selectAll').change(function() {
            $('.student-checkbox').prop('checked', $(this).prop('checked'));
            $('#selectAllCheckbox').prop('checked', $(this).prop('checked'));
        });

        // Update select all when individual checkboxes change
        $('.student-checkbox').change(function() {
            if (!$(this).prop('checked')) {
                $('#selectAllCheckbox').prop('checked', false);
                $('#selectAll').prop('checked', false);
            }
            
            // Check if all are checked
            var allChecked = $('.student-checkbox').length === $('.student-checkbox:checked').length;
            if (allChecked) {
                $('#selectAllCheckbox').prop('checked', true);
                $('#selectAll').prop('checked', true);
            }
        });

        // Form validation
        $('#promotionForm').submit(function(e) {
            if ($('.student-checkbox:checked').length === 0) {
                e.preventDefault();
                alert('Please select at least one student to promote.');
                return false;
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/school-admin/promotions/create.blade.php ENDPATH**/ ?>