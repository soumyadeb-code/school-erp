

<?php $__env->startSection('title', 'Promotion History'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Promotion History</h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('school-admin.promotions.index')); ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Promotions
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('school-admin.promotions.history')); ?>" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Academic Year</label>
                                    <select name="academic_year" class="form-control">
                                        <option value="">All Years</option>
                                        <?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($year->id); ?>" <?php echo e($academicYearId == $year->id ? 'selected' : ''); ?>>
                                                <?php echo e($year->year); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Student ID</label>
                                    <input type="text" name="student_id" class="form-control" placeholder="Enter Student ID" value="<?php echo e($studentId); ?>">
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
                                <?php $__empty_1 = true; $__currentLoopData = $promotions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promotion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e(Carbon\Carbon::parse($promotion->promotion_date)->format('d-m-Y')); ?></td>
                                        <td><?php echo e($promotion->student ? $promotion->student->student_id : '-'); ?></td>
                                        <td><?php echo e($promotion->student ? $promotion->student->name : '-'); ?></td>
                                        <td><?php echo e($promotion->fromAcademicYear ? $promotion->fromAcademicYear->year : '-'); ?></td>
                                        <td><?php echo e($promotion->toAcademicYear ? $promotion->toAcademicYear->year : '-'); ?></td>
                                        <td><?php echo e($promotion->fromClass ? $promotion->fromClass->class_name : '-'); ?></td>
                                        <td><?php echo e($promotion->toClass ? $promotion->toClass->class_name : '-'); ?></td>
                                        <td><?php echo e($promotion->remarks ?? '-'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No promotion history found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php echo e($promotions->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/school-admin/promotions/history.blade.php ENDPATH**/ ?>