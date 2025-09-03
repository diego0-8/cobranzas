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
    <link rel="stylesheet" href="<?= baseUrl('assets/css/roles.css') ?>">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= baseUrl('/') ?>">
                <i class="fas fa-crown me-2"></i><?= APP_NAME ?> - Super Admin
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
                        <a class="nav-link active" href="<?= baseUrl('roles') ?>">
                            <i class="fas fa-user-tag me-1"></i>Roles
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= baseUrl('typifications') ?>">
                            <i class="fas fa-list me-1"></i>Tipificaciones
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-crown me-1"></i><?= htmlspecialchars($user['user_name'] ?? 'Super Admin') ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= baseUrl('profile') ?>">
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
                            <i class="fas fa-edit me-2 text-warning"></i>Editar Rol
                        </h1>
                        <p class="text-muted mb-0">Modificar información del rol</p>
                    </div>
                    <div>
                        <a href="<?= baseUrl('roles') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver a Roles
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user-tag me-2"></i>Información del Rol
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?= baseUrl('roles/update') ?>">
                            <input type="hidden" name="id" value="<?= $role['id'] ?>">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">
                                            <i class="fas fa-tag me-1"></i>Nombre del Rol *
                                        </label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="<?= htmlspecialchars($role['name']) ?>" required>
                                        <div class="form-text">Nombre único para identificar el rol</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">
                                            <i class="fas fa-info-circle me-1"></i>Descripción
                                        </label>
                                        <input type="text" class="form-control" id="description" name="description" 
                                               value="<?= htmlspecialchars($role['description'] ?? '') ?>">
                                        <div class="form-text">Descripción opcional del rol</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Nota:</strong> Los permisos específicos del rol se configurarán en una versión futura del sistema.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?= baseUrl('roles') ?>" class="btn btn-secondary">
                                            <i class="fas fa-times me-2"></i>Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Actualizar Rol
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

