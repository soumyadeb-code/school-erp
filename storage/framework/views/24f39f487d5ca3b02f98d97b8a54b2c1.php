

<?php $__env->startSection('title', 'All Students'); ?>

<?php $__env->startSection('page-title', 'All Students'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('/dashboard')); ?>">Home</a></li>
    <li class="breadcrumb-item active">All Students</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <div class="row align-items-center mb-3">
            <div class="col-md-6">
                <h5 class="mb-0">Student List</h5>
            </div>
            <div class="col-md-6 text-end">
                <span class="badge bg-primary fs-6">
                    <i class="fas fa-users me-1"></i>
                    Total Records: <?php echo e($students->count()); ?>

                </span>
            </div>
        </div>
        
        <form method="GET" action="<?php echo e(route('students.index')); ?>" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search name, ID, father name..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Class</label>
                <select name="class_id" class="form-select">
                    <option value="">All Classes</option>
                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($class->id); ?>" <?php echo e(request('class_id') == $class->id ? 'selected' : ''); ?>><?php echo e($class->class_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select">
                    <option value="">All Gender</option>
                    <option value="male" <?php echo e(request('gender') == 'male' ? 'selected' : ''); ?>>Male</option>
                    <option value="female" <?php echo e(request('gender') == 'female' ? 'selected' : ''); ?>>Female</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Medium</label>
                <select name="medium" class="form-select">
                    <option value="">All Medium</option>
                    <option value="Bengali" <?php echo e(request('medium') == 'Bengali' ? 'selected' : ''); ?>>Bengali</option>
                    <option value="English" <?php echo e(request('medium') == 'English' ? 'selected' : ''); ?>>English</option>
                    <option value="Hindi" <?php echo e(request('medium') == 'Hindi' ? 'selected' : ''); ?>>Hindi</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                    <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Bus Route</label>
                <select name="bus" class="form-select">
                    <option value="">All Bus Routes</option>
                    <option value="yes" <?php echo e(request('bus') == 'yes' ? 'selected' : ''); ?>>With Bus</option>
                    <option value="no" <?php echo e(request('bus') == 'no' ? 'selected' : ''); ?>>Without Bus</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
            </div>
            <div class="col-md-1">
                <a href="<?php echo e(route('students.index')); ?>" class="btn btn-secondary w-100" title="Clear filters">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <?php if($students->count() > 0): ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="50">S.No.</th>
                            <th>Student ID</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Class</th>
                            <th>Roll</th>
                            <th>Gender</th>
                            <th>DOB</th>
                            <th>Medium</th>
                            <th>Bus Destination</th>
                            <th>Bus Fee</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><?php echo e($student->student_id); ?></td>
                            <td>
                                <?php if($student->photo): ?>
                                    <img src="<?php echo e(asset('storage/' . $student->photo)); ?>" width="40" height="40" class="rounded-circle">
                                <?php else: ?>
                                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                        <?php echo e(substr($student->name, 0, 1)); ?>

                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($student->name); ?></td>
                            <td><?php echo e($student->schoolClass ? $student->schoolClass->class_name : '-'); ?></td>
                            <td><?php echo e($student->roll ?? '-'); ?></td>
                            <td><?php echo e(ucfirst($student->gender)); ?></td>
                            <td><?php echo e($student->dob ? \Carbon\Carbon::parse($student->dob)->format('d-m-Y') : '-'); ?></td>
                            <td><?php echo e($student->medium); ?></td>
                            <td><?php echo e($student->busDestination ? $student->busDestination->destination : '-'); ?></td>
                            <td><?php echo e($student->busDestination ? '₹' . number_format($student->busDestination->price, 2) : '-'); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($student->status == 'active' ? 'success' : 'secondary'); ?>">
                                    <?php echo e(ucfirst($student->status)); ?>

                                </span>
                            </td>
                            <td>
                                <a href="<?php echo e(route('students.show', $student->id)); ?>" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('students.student-bill-history', $student->id)); ?>" class="btn btn-sm btn-primary" title="Bill History">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                                <a href="<?php echo e(route('students.edit', $student->id)); ?>" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-user-graduate fa-4x text-muted"></i>
                    </div>
                    <h5 class="text-muted">No Students Found</h5>
                    <p class="text-muted">There are no students in this school yet.</p>
                    <a href="<?php echo e(route('students.admission')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add First Student
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/students/index.blade.php ENDPATH**/ ?>