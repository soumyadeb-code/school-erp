<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - School Business ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
.login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
            min-height: 500px;
        }
        .login-image {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            padding: 40px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
        }
        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        .btn-login {
            background: #4f46e5;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            width: 100%;
        }
        .btn-login:hover {
            background: #4338ca;
        }
        .brand-logo {
            font-size: 48px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="login-card">
                    <div class="row g-0">
                        <div class="col-md-5 login-image d-none d-md-flex flex-column">
                            <i class="fas fa-graduation-cap brand-logo"></i>
                            <h2 class="text-center">School Business ERP</h2>
                            <p class="text-center opacity-75">Manage your school efficiently</p>
                        </div>
                        <div class="col-md-7 p-5">
                            <div class="text-center mb-4 d-md-none">
                                <i class="fas fa-graduation-cap text-primary" style="font-size: 48px;"></i>
                                <h4 class="mt-2">School Business ERP</h4>
                            </div>
                            
                            <h4 class="mb-4">Welcome Back</h4>
                            
                            @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            @endif
                            
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                                    </div>
                                    @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">Remember me</label>
                                </div>
                                
                                <button type="submit" class="btn btn-login">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login
                                </button>
                            </form>
                            
                            <div class="text-center mt-4">
                                <p class="text-muted small">
                                    Default Super Admin: <strong>superadmin@erp.com</strong> / <strong>password</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>
