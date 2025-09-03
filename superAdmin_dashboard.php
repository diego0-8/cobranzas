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
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="<?= baseUrl('assets/css/dashboard.css') ?>">
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
                        <a class="nav-link active" href="<?= baseUrl('/') ?>">
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="configDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-cog me-1"></i>Configuración
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= baseUrl('roles') ?>">
                                <i class="fas fa-user-tag me-2"></i>Gestionar Roles
                            </a></li>
                            <li><a class="dropdown-item" href="<?= baseUrl('typifications') ?>">
                                <i class="fas fa-list me-2"></i>Tipificaciones
                            </a></li>
                            <li><a class="dropdown-item" href="<?= baseUrl('system-config') ?>">
                                <i class="fas fa-sliders-h me-2"></i>Configuración Sistema
                            </a></li>
                            <li><a class="dropdown-item" href="<?= baseUrl('audit-logs') ?>">
                                <i class="fas fa-history me-2"></i>Logs de Auditoría
                            </a></li>
                        </ul>
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
                            <i class="fas fa-crown me-2 text-warning"></i>Dashboard Super Administrador
                        </h1>
                        <p class="text-muted mb-0">Control total del sistema de cobranzas</p>
                    </div>
                    <div>
                        <span class="badge bg-warning text-dark fs-6">
                            <i class="fas fa-shield-alt me-1"></i>Super Administrador
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas principales -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Usuarios
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['total_users'] ?? 0) ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Usuarios Activos
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['active_users'] ?? 0) ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Roles
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['total_roles'] ?? 0) ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-tag fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Sistema Status
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <span class="text-success">Operativo</span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-server fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones rápidas -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <a href="<?= baseUrl('users') ?>" class="btn btn-primary btn-block w-100">
                                    <i class="fas fa-users me-2"></i>Gestionar Usuarios
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="<?= baseUrl('roles') ?>" class="btn btn-info btn-block w-100">
                                    <i class="fas fa-user-tag me-2"></i>Gestionar Roles
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="<?= baseUrl('campaigns') ?>" class="btn btn-success btn-block w-100">
                                    <i class="fas fa-bullhorn me-2"></i>Gestionar Campañas
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="<?= baseUrl('typifications') ?>" class="btn btn-warning btn-block w-100">
                                    <i class="fas fa-list me-2"></i>Tipificaciones
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="<?= baseUrl('reports') ?>" class="btn btn-secondary btn-block w-100">
                                    <i class="fas fa-chart-bar me-2"></i>Ver Reportes
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="<?= baseUrl('system-config') ?>" class="btn btn-dark btn-block w-100">
                                    <i class="fas fa-cog me-2"></i>Configuración
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del sistema -->
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Información del Sistema
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Versión:</strong></td>
                                <td><?= APP_VERSION ?></td>
                            </tr>
                            <tr>
                                <td><strong>Base de Datos:</strong></td>
                                <td>MySQL/MariaDB</td>
                            </tr>
                            <tr>
                                <td><strong>PHP Version:</strong></td>
                                <td><?= PHP_VERSION ?></td>
                            </tr>
                            <tr>
                                <td><strong>Servidor:</strong></td>
                                <td><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Desconocido' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Última Actualización:</strong></td>
                                <td><?= date('d/m/Y H:i:s') ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-shield-alt me-2"></i>Permisos Super Administrador
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Gestión completa de usuarios</li>
                            <li><i class="fas fa-check text-success me-2"></i>Gestión de roles y permisos</li>
                            <li><i class="fas fa-check text-success me-2"></i>Configuración del sistema</li>
                            <li><i class="fas fa-check text-success me-2"></i>Acceso a todos los reportes</li>
                            <li><i class="fas fa-check text-success me-2"></i>Gestión de tipificaciones</li>
                            <li><i class="fas fa-check text-success me-2"></i>Logs de auditoría</li>
                            <li><i class="fas fa-check text-success me-2"></i>Configuración de campañas</li>
                            <li><i class="fas fa-check text-success me-2"></i>Acceso total al sistema</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

