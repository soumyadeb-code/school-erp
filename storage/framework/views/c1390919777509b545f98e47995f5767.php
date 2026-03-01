<?php $__env->startSection('title', 'Student Profile'); ?>

<?php $__env->startSection('page-title', 'Student Profile'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('/dashboard')); ?>">Home</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('students.list')); ?>">All Students</a></li>
    <li class="breadcrumb-item active"><?php echo e($student->name); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Student Profile</h5>
        <div>
            <a href="<?php echo e(route('students.edit', $student->id)); ?>" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="<?php echo e(route('students.list')); ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 text-center">
                <?php if($student->photo): ?>
                    <img src="<?php echo e(asset('storage/' . $student->photo)); ?>" alt="<?php echo e($student->name); ?>" class="rounded-circle" width="150" height="150">
                <?php else: ?>
                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width:150px;height:150px;font-size:48px;">
                        <?php echo e(substr($student->name, 0, 1)); ?>

                    </div>
                <?php endif; ?>
                <h4 class="mt-3"><?php echo e($student->name); ?></h4>
                <p class="text-muted"><?php echo e($student->student_id); ?></p>
                <span class="badge bg-<?php echo e($student->status == 'active' ? 'success' : 'secondary'); ?>">
                    <?php echo e(ucfirst($student->status)); ?>

                </span>
            </div>
            <div class="col-md-8">
                <h6 class="text-muted border-bottom pb-2">Basic Information</h6>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Class:</strong> <?php echo e($student->schoolClass ? $student->schoolClass->class_name : '-'); ?></p>
                        <p><strong>Next Class:</strong> <?php echo e($student->schoolClass && $student->schoolClass->nextClass ? $student->schoolClass->nextClass->class_name : '-'); ?></p>
                        <p><strong>Roll:</strong> <?php echo e($student->roll ?? '-'); ?></p>
                        <p><strong>Medium:</strong> <?php echo e(ucfirst($student->medium)); ?></p>
                        <p><strong>Gender:</strong> <?php echo e(ucfirst($student->gender)); ?></p>
                        <p><strong>Date of Birth:</strong> <?php echo e($student->dob ? Carbon\Carbon::parse($student->dob)->format('d-m-Y') : '-'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Admission Date:</strong> <?php echo e($student->admission_date ? Carbon\Carbon::parse($student->admission_date)->format('d-m-Y') : '-'); ?></p>
                        <p><strong>Blood Group:</strong> <?php echo e($student->blood_group ?? '-'); ?></p>
                        <p><strong>Category:</strong> <?php echo e($student->social_category ?? '-'); ?></p>
                        <p><strong>Religion:</strong> <?php echo e($student->religion ?? '-'); ?></p>
                        <p><strong>Aadhaar:</strong> <?php echo e($student->aadhaar ?? '-'); ?></p>
                    </div>
                </div>

                <h6 class="text-muted border-bottom pb-2 mt-4">Parent Information</h6>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Father's Name:</strong> <?php echo e($student->father_name ?? '-'); ?></p>
                        <p><strong>Father's Education:</strong> <?php echo e($student->father_education ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Mother's Name:</strong> <?php echo e($student->mother_name ?? '-'); ?></p>
                        <p><strong>Mother's Education:</strong> <?php echo e($student->mother_education ?? '-'); ?></p>
                    </div>
                </div>

                <h6 class="text-muted border-bottom pb-2 mt-4">Contact Information</h6>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Phone:</strong> <?php echo e($student->phone ?? '-'); ?></p>
                        <p><strong>WhatsApp:</strong> <?php echo e($student->whatsapp ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Email:</strong> <?php echo e($student->email ?? '-'); ?></p>
                        <p><strong>Address:</strong> <?php echo e($student->address ?? '-'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/students/show.blade.php ENDPATH**/ ?>