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
                <i class="fas fa-user-shield me-2"></i><?= APP_NAME ?> - Admin
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
                    <li class="nav-item">
                        <a class="nav-link" href="<?= baseUrl('debtors') ?>">
                            <i class="fas fa-user-friends me-1"></i>Deudores
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-shield me-1"></i><?= htmlspecialchars($user['user_name'] ?? 'Admin') ?>
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
                            <i class="fas fa-user-shield me-2 text-info"></i>Dashboard Administrador
                        </h1>
                        <p class="text-muted mb-0">Gestión operativa del sistema de cobranzas</p>
                    </div>
                    <div>
                        <span class="badge bg-info fs-6">
                            <i class="fas fa-user-shield me-1"></i>Administrador
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
                                    Total Campañas
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['total_campaigns'] ?? 0) ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-bullhorn fa-2x text-gray-300"></i>
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
                                    Campañas Activas
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['active_campaigns'] ?? 0) ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-play-circle fa-2x text-gray-300"></i>
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
                                    Total Coordinadores
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['total_coordinators'] ?? 0) ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-tie fa-2x text-gray-300"></i>
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
                                    Total Asesores
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['total_advisors'] ?? 0) ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segunda fila de estadísticas -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Total Deudores
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['total_debtors'] ?? 0) ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-friends fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                    Total Cuentas
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['total_accounts'] ?? 0) ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Interacciones
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['total_interactions'] ?? 0) ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comments fa-2x text-gray-300"></i>
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
                                    Tasa de Recuperación
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['recovery_rate'] ?? 0, 1) ?>%
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-gray-300"></i>
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
                                <a href="<?= baseUrl('campaigns') ?>" class="btn btn-success btn-block w-100">
                                    <i class="fas fa-bullhorn me-2"></i>Gestionar Campañas
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="<?= baseUrl('debtors') ?>" class="btn btn-info btn-block w-100">
                                    <i class="fas fa-user-friends me-2"></i>Gestionar Deudores
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="<?= baseUrl('reports') ?>" class="btn btn-secondary btn-block w-100">
                                    <i class="fas fa-chart-bar me-2"></i>Ver Reportes
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="<?= baseUrl('reports/export?type=collections&format=csv') ?>" class="btn btn-warning btn-block w-100">
                                    <i class="fas fa-download me-2"></i>Exportar Datos
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="<?= baseUrl('profile') ?>" class="btn btn-dark btn-block w-100">
                                    <i class="fas fa-user-edit me-2"></i>Mi Perfil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de rendimiento -->
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-pie me-2"></i>Rendimiento por Coordinador
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Coordinador</th>
                                        <th>Asesores</th>
                                        <th>Gestiones</th>
                                        <th>% Éxito</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Coordinador 1</td>
                                        <td>5</td>
                                        <td>150</td>
                                        <td><span class="badge bg-success">85%</span></td>
                                    </tr>
                                    <tr>
                                        <td>Coordinador 2</td>
                                        <td>4</td>
                                        <td>120</td>
                                        <td><span class="badge bg-warning">72%</span></td>
                                    </tr>
                                    <tr>
                                        <td>Coordinador 3</td>
                                        <td>6</td>
                                        <td>180</td>
                                        <td><span class="badge bg-success">90%</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-shield-alt me-2"></i>Permisos Administrador
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Ver todas las campañas</li>
                            <li><i class="fas fa-check text-success me-2"></i>Ver resultados de coordinadores</li>
                            <li><i class="fas fa-check text-success me-2"></i>Exportar reportes</li>
                            <li><i class="fas fa-check text-success me-2"></i>Gestionar usuarios (limitado)</li>
                            <li><i class="fas fa-check text-success me-2"></i>Gestionar deudores</li>
                            <li><i class="fas fa-check text-success me-2"></i>Gestionar campañas</li>
                            <li><i class="fas fa-check text-success me-2"></i>Ver estadísticas globales</li>
                            <li><i class="fas fa-check text-success me-2"></i>Acceso a reportes completos</li>
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

