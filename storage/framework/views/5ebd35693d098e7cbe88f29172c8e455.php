

<?php $__env->startSection('title', 'Student Promotions'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Student Promotions</h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('school-admin.promotions.create')); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Promote Students
                        </a>
                        <a href="<?php echo e(route('school-admin.promotions.history')); ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-history"></i> Promotion History
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

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><?php echo e($eligibleStudents->count()); ?></h3>
                                    <p>Eligible for Promotion</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3><?php echo e($promotedCount); ?></h3>
                                    <p>Promoted Students</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-arrow-up"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3><?php echo e($currentYear ? $currentYear->year : 'N/A'); ?></h3>
                                    <p>Current Year</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-secondary">
                                <div class="inner">
                                    <h3><?php echo e($classes->count()); ?></h3>
                                    <p>Active Classes</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-door-open"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4>Students Eligible for Promotion</h4>
                    <?php if($eligibleStudents->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Current Class</th>
                                        <th>Next Class</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $eligibleStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($student->student_id); ?></td>
                                            <td><?php echo e($student->name); ?></td>
                                            <td><?php echo e($student->schoolClass ? $student->schoolClass->class_name : '-'); ?></td>
                                            <td>
                                                <?php if($student->nextClass()): ?>
                                                    <span class="badge bg-success"><?php echo e($student->nextClass()->class_name); ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">No next class</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo e(route('school-admin.promotions.student-enrollments', $student->id)); ?>" class="btn btn-sm btn-info" title="View Enrollments">
                                                    <i class="fas fa-history"></i>
                                                </a>
                                                <?php if($student->nextClass()): ?>
                                                    <form action="<?php echo e(route('school-admin.promotions.promote-single', $student->id)); ?>" method="POST" style="display:inline;">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="btn btn-sm btn-success" title="Promote" onclick="return confirm('Are you sure you want to promote this student?')">
                                                            <i class="fas fa-arrow-up"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                <button type="button" class="btn btn-sm btn-danger" title="Issue TC" data-toggle="modal" data-target="#tcModal<?php echo e($student->id); ?>">
                                                    <i class="fas fa-file-alt"></i>
                                                </button>
                                                
                                                <!-- TC Modal -->
                                                <div class="modal fade" id="tcModal<?php echo e($student->id); ?>" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Issue TC for <?php echo e($student->name); ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal">
                                                                    <span>&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="<?php echo e(route('school-admin.promotions.issue-tc', $student->id)); ?>" method="POST">
                                                                <?php echo csrf_field(); ?>
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label>TC Date</label>
                                                                        <input type="date" name="tc_date" class="form-control" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Reason</label>
                                                                        <input type="text" name="tc_reason" class="form-control" placeholder="Enter reason for TC" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-danger">Issue TC</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            No students eligible for promotion. Students must be registered and have enrollments to be eligible.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/school-admin/promotions/index.blade.php ENDPATH**/ ?>