

<?php $__env->startSection('title', 'School Profile'); ?>

<?php $__env->startSection('page-title', 'School Profile'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item active">Profile</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <!-- School Information Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-school me-2"></i>School Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('school-admin.profile.update')); ?>" id="profileForm">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">School Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="name" name="name" 
                                value="<?php echo e(old('name', $school->name)); ?>" readonly style="background-color: #f8f9fa;">
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label for="trust_name" class="form-label">Trust Name</label>
                            <input type="text" class="form-control" 
                                id="trust_name" name="trust_name" 
                                value="<?php echo e(old('trust_name', $school->trust_name)); ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label">School Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="code" name="code" 
                                value="<?php echo e(old('code', $school->code)); ?>" maxlength="10" required>
                            <div class="invalid-feedback" id="code_error" style="display: none;"></div>
                            <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="2"><?php echo e(old('address', $school->address)); ?></textarea>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo e(old('phone', $school->phone)); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="email" name="email" 
                                value="<?php echo e(old('email', $school->email)); ?>" required>
                            <div class="invalid-feedback" id="email_error" style="display: none;"></div>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Change Password Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-lock me-2"></i>Change Password</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('school-admin.profile.password')); ?>">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            id="current_password" name="current_password" required>
                        <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            id="password" name="password" minlength="8" required>
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-muted">Minimum 8 characters</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" 
                            id="password_confirmation" name="password_confirmation" required>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-warning">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('code');
    const emailInput = document.getElementById('email');
    const codeError = document.getElementById('code_error');
    const emailError = document.getElementById('email_error');
    const form = document.getElementById('profileForm');
    const schoolId = <?php echo e($school->id); ?>;
    let codeTimeout;
    let emailTimeout;

    codeInput.addEventListener('input', function() {
        this.classList.remove('is-invalid');
        if (codeError) codeError.style.display = 'none';
    });

    emailInput.addEventListener('input', function() {
        this.classList.remove('is-invalid');
        if (emailError) emailError.style.display = 'none';
    });

    codeInput.addEventListener('blur', function() {
        if (this.value && this.value !== '<?php echo e($school->code); ?>') checkCodeUnique(this.value);
    });

    emailInput.addEventListener('blur', function() {
        if (this.value && this.value !== '<?php echo e($school->email); ?>') checkEmailUnique(this.value);
    });

    form.addEventListener('submit', function(e) {
        let isValid = true;
        if (codeInput.value !== '<?php echo e($school->code); ?>' && codeInput.classList.contains('is-invalid')) isValid = false;
        if (emailInput.value !== '<?php echo e($school->email); ?>' && emailInput.classList.contains('is-invalid')) isValid = false;
        if (!isValid) { e.preventDefault(); return false; }
    });

    function checkCodeUnique(code) {
        if (!code) return;
        if (codeTimeout) clearTimeout(codeTimeout);
        codeTimeout = setTimeout(function() {
            fetch('/school-admin/profile/check-code?code=' + encodeURIComponent(code) + '&exclude_id=' + schoolId)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        codeInput.classList.add('is-invalid');
                        if (codeError) { codeError.textContent = 'School code already exists'; codeError.style.display = 'block'; }
                    } else {
                        codeInput.classList.remove('is-invalid');
                        if (codeError) codeError.style.display = 'none';
                    }
                });
        }, 300);
    }

    function checkEmailUnique(email) {
        if (!email) return;
        if (emailTimeout) clearTimeout(emailTimeout);
        emailTimeout = setTimeout(function() {
            fetch('/school-admin/profile/check-email?email=' + encodeURIComponent(email) + '&exclude_id=' + schoolId)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        emailInput.classList.add('is-invalid');
                        if (emailError) { emailError.textContent = 'School email already exists'; emailError.style.display = 'block'; }
                    } else {
                        emailInput.classList.remove('is-invalid');
                        if (emailError) emailError.style.display = 'none';
                    }
                });
        }, 300);
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/school-admin/profile.blade.php ENDPATH**/ ?>