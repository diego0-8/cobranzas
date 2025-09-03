<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> | <?= APP_NAME ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- CSS personalizado para login -->
    <link rel="stylesheet" href="<?= baseUrl('assets/css/auth.css') ?>">
</head>
<body class="login-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-container">
                    <!-- Logo y título -->
                    <div class="text-center mb-4">
                        <div class="logo">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h2 class="login-title"><?= APP_NAME ?></h2>
                        <p class="login-subtitle">Sistema de Gestión de Cobranzas</p>
                    </div>
                    
                    <!-- Formulario de login -->
                    <div class="login-form">
                        <?php if (isset($errors) && !empty($errors)): ?>
                            <div class="alert alert-danger" role="alert">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="<?= baseUrl('authenticate') ?>">
                            <div class="form-group mb-3">
                                <label for="username" class="form-label">
                                    <i class="fas fa-user me-2"></i>Nombre de Usuario
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="username" 
                                       name="username" 
                                       value="<?= isset($username) ? htmlspecialchars($username) : '' ?>"
                                       placeholder="Ingrese su nombre de usuario"
                                       required>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Contraseña
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Ingrese su contraseña"
                                           required>
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="remember" 
                                           name="remember">
                                    <label class="form-check-label" for="remember">
                                        Recordarme
                                    </label>
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-login">
                                    <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Footer -->
                    <div class="login-footer text-center mt-4">
                        <p class="text-muted">
                            <i class="fas fa-shield-alt me-1"></i>
                            Sistema seguro y confidencial
                        </p>
                        <p class="text-muted small">
                            Versión <?= APP_VERSION ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        // Auto focus en el primer campo
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('username').focus();
        });
    </script>
</body>
</html>
