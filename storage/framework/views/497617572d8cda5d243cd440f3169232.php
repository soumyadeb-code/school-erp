<div class="sidebar col-md-2 col-lg-2 d-none d-md-block p-0">
    <div class="p-3 text-center border-bottom border-white border-opacity-25">
        <h5 class="text-white mb-0">
            <i class="fas fa-graduation-cap me-2"></i>
            School ERP
        </h5>
        <small class="text-white-50">
            <?php if(auth()->user()->role === 'super_admin'): ?>
                Super Admin Panel
            <?php else: ?>
                <?php echo e(auth()->user()->school->school_name ?? 'School Admin'); ?>

            <?php endif; ?>
        </small>
    </div>
    
    <ul class="nav flex-column mt-3">
        <?php if(auth()->user()->role === 'super_admin'): ?>
            <!-- Super Admin Menu -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('super-admin.dashboard')); ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('super-admin.schools.index')); ?>">
                    <i class="fas fa-school"></i>
                    Schools
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('super-admin.reports')); ?>">
                    <i class="fas fa-chart-bar"></i>
                    Reports
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('super-admin.maintenance.index')); ?>">
                    <i class="fas fa-tools"></i>
                    Maintenance
                </a>
            </li>
        <?php else: ?>
            <!-- School Admin Menu -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('school-admin.dashboard')); ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>
            
            <!-- School Setup -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#schoolSetup">
                    <i class="fas fa-cogs"></i>
                    School Setup
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <div class="collapse" id="schoolSetup">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('school-admin.classes.index')); ?>">
                                <i class="fas fa-door-open"></i>
                                Classes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('school-admin.academic-years.index')); ?>">
                                <i class="fas fa-calendar"></i>
                                Academic Years
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <!-- Fees Setup -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#feesSetup">
                    <i class="fas fa-money-bill-wave"></i>
                    Fees Setup
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <div class="collapse" id="feesSetup">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('school-admin.fees.admission')); ?>">
                                <i class="fas fa-user-plus"></i>
                                Admission Fees
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('school-admin.fees.registration')); ?>">
                                <i class="fas fa-user-edit"></i>
                                Registration Fees
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('school-admin.fees.class')); ?>">
                                <i class="fas fa-book"></i>
                                Class Monthly Fees
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('school-admin.fees.bus')); ?>">
                                <i class="fas fa-bus"></i>
                                Bus Fees
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('school-admin.fees.bookset')); ?>">
                                <i class="fas fa-book-open"></i>
                                Bookset Prices
                            </a>
                        </li>
<li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('school-admin.fees.discount')); ?>">
                                <i class="fas fa-percent"></i>
                                Discount Rules
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('school-admin.bill-layouts.designer')); ?>">
                                <i class="fas fa-file-pdf"></i>
                                Bill Layout Designer
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <!-- Students -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#studentsMenu">
                    <i class="fas fa-users"></i>
                    Students
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <div class="collapse" id="studentsMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('students.admission')); ?>">
                                <i class="fas fa-user-plus"></i>
                                Admission
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('students.registration')); ?>">
                                <i class="fas fa-user-check"></i>
                                Registration
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('students.list')); ?>">
                                <i class="fas fa-list"></i>
                                All Students
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <!-- Fee Collection -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('students.fee-collection')); ?>">
                    <i class="fas fa-hand-holding-usd"></i>
                    Fee Collection
                </a>
            </li>
            
            <!-- Bill History -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('students.bill-history')); ?>">
                    <i class="fas fa-history"></i>
                    Bill History
                </a>
            </li>
            
            <!-- Promotions -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('school-admin.promotions.index')); ?>">
                    <i class="fas fa-arrow-up"></i>
                    Promotions
                </a>
            </li>
            
            <!-- Maintenance -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('school-admin.maintenance.index')); ?>">
                    <i class="fas fa-tools"></i>
                    Maintenance
                </a>
            </li>
        <?php endif; ?>
    </ul>
</div>
<?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>