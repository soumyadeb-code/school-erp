<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($page_title ?? 'Site Under Maintenance'); ?> - School Business ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e3a5f 0%, #0d1b2a 100%);
            min-height: 100vh;
        }
        
        .maintenance-icon {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #60a5fa, #34d399);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="text-center max-w-2xl">
        <!-- Maintenance Icon -->
        <div class="maintenance-icon mb-8">
            <div class="w-32 h-32 mx-auto bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center shadow-2xl">
                <i class="fas fa-tools text-5xl text-white"></i>
            </div>
        </div>
        
        <!-- Floating Elements -->
        <div class="floating mb-8">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-4">
                Under <span class="gradient-text">Maintenance</span>
            </h1>
        </div>
        
        <!-- Description Card -->
        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 mb-8 border border-white/20">
            <h2 class="text-2xl font-semibold text-white mb-4">
                <i class="fas fa-school mr-2 text-blue-400"></i>
                <?php echo e($school_title ?? 'School Business ERP'); ?>

            </h2>
            <p class="text-gray-300 text-lg mb-4">
                <?php echo e($maintenance_message ?? "We're currently performing scheduled maintenance to improve our services."); ?>

            </p>
            <div class="flex flex-wrap justify-center gap-4 mt-6">
                <div class="bg-white/10 rounded-lg px-4 py-2 text-sm text-gray-300">
                    <i class="fas fa-clock mr-2 text-yellow-400"></i>
                    Estimated Time: Soon
                </div>
                <div class="bg-white/10 rounded-lg px-4 py-2 text-sm text-gray-300">
                    <i class="fas fa-server mr-2 text-blue-400"></i>
                    Database Maintenance
                </div>
            </div>
        </div>
        
        <!-- Contact Info -->
        <div class="text-gray-400 text-sm">
            <p class="mb-2">Need assistance? Contact our support team:</p>
            <div class="flex justify-center gap-4 flex-wrap">
                <?php if(!empty($email)): ?>
                <a href="mailto:<?php echo e($email); ?>" class="text-blue-400 hover:text-blue-300 transition">
                    <i class="fas fa-envelope mr-1"></i> <?php echo e($email); ?>

                </a>
                <?php endif; ?>
                <?php if(!empty($email) && !empty($phone)): ?>
                <span class="text-gray-600">|</span>
                <?php endif; ?>
                <?php if(!empty($phone)): ?>
                <span class="text-green-400">
                    <i class="fas fa-phone mr-1"></i> <?php echo e($phone); ?>

                </span>
                <?php endif; ?>
                <?php if(empty($email) && empty($phone)): ?>
                <span class="text-gray-500">
                    <i class="fas fa-envelope mr-1"></i> support@schoolerp.com
                    <span class="mx-2">|</span>
                    <i class="fas fa-phone mr-1"></i> +1 (555) 123-4567
                </span>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Back Soon Message -->
        <div class="mt-8 text-gray-500 text-sm">
            <i class="fas fa-arrow-left mr-2"></i>
            We'll be back online shortly. Thank you for your patience!
        </div>
    </div>
    
    <!-- Decorative Elements -->
    <div class="fixed top-10 left-10 w-20 h-20 bg-blue-500/20 rounded-full blur-xl"></div>
    <div class="fixed bottom-10 right-10 w-32 h-32 bg-purple-500/20 rounded-full blur-xl"></div>
    <div class="fixed top-1/2 right-20 w-16 h-16 bg-green-500/20 rounded-full blur-xl"></div>
</body>
</html>
<?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/errors/maintenance.blade.php ENDPATH**/ ?>