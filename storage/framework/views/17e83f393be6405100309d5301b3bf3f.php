<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="School Business ERP - Complete School Management System">
    <title><?php echo $__env->yieldContent('title', 'School Business ERP'); ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #6366f1;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
            --dark-color: #1f2937;
            --light-color: #f3f4f6;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #4f46e5 0%, #3730a3 100%);
            box-shadow: 4px 0 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 8px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 500;
        }
        
        .sidebar .nav-link i {
            width: 24px;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 600;
            padding: 16px 20px;
        }
        
        .btn-primary {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }
        
        .btn-primary:hover {
            background-color: #4338ca;
            border-color: #4338ca;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }
        
        .stat-card .icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background-color: #f9fafb;
            border-bottom: 2px solid #e5e7eb;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        
        .badge-soft-success {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-soft-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .badge-soft-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .badge-soft-info {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        
        .page-header {
            background: white;
            padding: 20px 0;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 24px;
        }
        
        .breadcrumb {
            margin-bottom: 0;
            font-size: 14px;
        }
        
        .breadcrumb-item a {
            color: #4f46e5;
            text-decoration: none;
        }
        
        .breadcrumb-item.active {
            color: #6b7280;
        }
        
        .main-content {
            padding: 24px;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                z-index: 1000;
                width: 100%;
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
        }
    </style>
    
    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body>
    <?php if(auth()->guard()->check()): ?>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            
            <!-- Main Content -->
            <div class="col-md-10 col-lg-10 main-content ms-auto">
                <!-- Top Header -->
                <div class="page-header">
                    <div class="container-fluid">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-0"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">

                                        <?php echo $__env->yieldContent('breadcrumb'); ?>
                                    </ol>
                                </nav>
                            </div>
                            <div class="col-md-6 text-end">
                                <div class="dropdown">
                                    <button class="btn btn-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                        <i class="fas fa-user-circle me-2"></i>
                                        <?php echo e(auth()->user()->name); ?>

                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><span class="dropdown-item-text">
                                            <small class="text-muted">Role: <?php echo e(ucfirst(str_replace('_', ' ', auth()->user()->role))); ?></small>
                                        </span></li>
                                        <?php if(auth()->user()->role === 'school_admin'): ?>
                                        <li><a class="dropdown-item" href="<?php echo e(route('school-admin.profile')); ?>">
                                            <i class="fas fa-user-cog me-2"></i>Profile
                                        </a></li>
                                        <?php endif; ?>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Flash Messages -->
                <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <?php if($errors->any()): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Please fix the following errors:
                    <ul class="mb-0 mt-2">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <!-- Page Content -->
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- Guest Layout -->
    <?php echo $__env->yieldContent('guest-content'); ?>
    <?php endif; ?>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/layouts/app.blade.php ENDPATH**/ ?>