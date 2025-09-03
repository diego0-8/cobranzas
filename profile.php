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
    
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="<?= baseUrl('assets/css/dashboard.css') ?>">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= baseUrl('/') ?>">
                <i class="fas fa-chart-line me-2"></i><?= APP_NAME ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= baseUrl('/') ?>">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= baseUrl('users') ?>">
                            <i class="fas fa-users me-1"></i>Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= baseUrl('campaigns') ?>">
                            <i class="fas fa-bullhorn me-1"></i>Campañas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= baseUrl('reports') ?>">
                            <i class="fas fa-chart-bar me-1"></i>Reportes
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i><?= htmlspecialchars($user['user_name'] ?? 'Usuario') ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item active" href="<?= baseUrl('profile') ?>">
                                <i class="fas fa-user-edit me-2"></i>Mi Perfil
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= baseUrl('logout') ?>">
                                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container-fluid mt-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0">
                            <i class="fas fa-user-edit me-2"></i>Mi Perfil
                        </h1>
                        <p class="text-muted mb-0">Gestiona tu información personal</p>
                    </div>
                    <div>
                        <a href="<?= baseUrl('/') ?>" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-1"></i>Volver al Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de perfil -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user me-2"></i>Información Personal
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($errors) && !empty($errors)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <div>
                                        <?php if (count($errors) === 1): ?>
                                            <strong><?= $errors[0] ?></strong>
                                        <?php else: ?>
                                            <strong>Se encontraron los siguientes errores:</strong>
                                            <ul class="mb-0 mt-2">
                                                <?php foreach ($errors as $error): ?>
                                                    <li><?= $error ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?= baseUrl('update-profile') ?>">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="full_name" class="form-label">
                                        <i class="fas fa-user me-1"></i>Nombre Completo
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="full_name" 
                                           name="full_name" 
                                           value="<?= htmlspecialchars($user['full_name'] ?? '') ?>"
                                           required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label">
                                        <i class="fas fa-at me-1"></i>Nombre de Usuario
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="username" 
                                           name="username" 
                                           value="<?= htmlspecialchars($user['username'] ?? '') ?>"
                                           required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>Correo Electrónico
                                </label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                                       required>
                            </div>
                            
                            <hr class="my-4">
                            
                            <h6 class="mb-3">
                                <i class="fas fa-lock me-2"></i>Cambiar Contraseña
                                <small class="text-muted">(Opcional - Dejar en blanco para mantener la actual)</small>
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="current_password" class="form-label">Contraseña Actual</label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="current_password" 
                                           name="current_password">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="new_password" class="form-label">Nueva Contraseña</label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="new_password" 
                                           name="new_password">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="confirm_password" 
                                           name="confirm_password">
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Actualizar Perfil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Información adicional -->
            <div class="col-lg-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Información de Cuenta
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted">Rol</label>
                            <p class="mb-0">
                                <span class="badge bg-primary"><?= htmlspecialchars($user['user_role'] ?? 'Usuario') ?></span>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Estado</label>
                            <p class="mb-0">
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Activo
                                </span>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Última Actualización</label>
                            <p class="mb-0"><?= date('d/m/Y H:i') ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="card shadow mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-shield-alt me-2"></i>Seguridad
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Consejos de Seguridad:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Usa una contraseña segura</li>
                                <li>No compartas tus credenciales</li>
                                <li>Cierra sesión al terminar</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Validación de contraseñas
        document.getElementById('new_password').addEventListener('input', function() {
            const newPassword = this.value;
            const confirmPassword = document.getElementById('confirm_password');
            
            if (newPassword && confirmPassword.value) {
                if (newPassword === confirmPassword.value) {
                    confirmPassword.classList.remove('is-invalid');
                    confirmPassword.classList.add('is-valid');
                } else {
                    confirmPassword.classList.remove('is-valid');
                    confirmPassword.classList.add('is-invalid');
                }
            }
        });
        
        document.getElementById('confirm_password').addEventListener('input', function() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = this.value;
            
            if (newPassword && confirmPassword) {
                if (newPassword === confirmPassword) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                }
            }
        });
    </script>
</body>
</html>

