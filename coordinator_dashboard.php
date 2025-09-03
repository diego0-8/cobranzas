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
                <i class="fas fa-user-tie me-2"></i><?= APP_NAME ?> - Coordinador
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
                        <a class="nav-link" href="<?= baseUrl('coordinator/my-advisors') ?>">
                            <i class="fas fa-users me-1"></i>Mis Asesores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= baseUrl('coordinator/assigned-accounts') ?>">
                            <i class="fas fa-file-invoice me-1"></i>Cuentas Asignadas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= baseUrl('coordinator/team-interactions') ?>">
                            <i class="fas fa-comments me-1"></i>Interacciones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= baseUrl('coordinator/team-reports') ?>">
                            <i class="fas fa-chart-bar me-1"></i>Reportes
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-tie me-1"></i><?= htmlspecialchars($user['user_name'] ?? 'Coordinador') ?>
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
                            <i class="fas fa-user-tie me-2 text-success"></i>Dashboard Coordinador
                        </h1>
                        <p class="text-muted mb-0">Gestión de tu equipo de asesores y cuentas asignadas</p>
                    </div>
                    <div>
                        <span class="badge bg-success fs-6">
                            <i class="fas fa-user-tie me-1"></i>Coordinador
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
                                    Mis Asesores
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['my_advisors'] ?? 0) ?>
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
                                    Cuentas Asignadas
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['assigned_accounts'] ?? 0) ?>
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
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
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
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Promesas Pendientes
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['pending_promises'] ?? 0) ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segunda fila de estadísticas -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Pagos Completados
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['completed_payments'] ?? 0) ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                    Rendimiento del Equipo
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['team_performance'] ?? 0, 1) ?>%
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-gray-300"></i>
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
                                    Gestiones Hoy
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['today_interactions'] ?? 0) ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Cuentas Vencidas
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['overdue_accounts'] ?? 0) ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
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
                                <a href="<?= baseUrl('coordinator/my-advisors') ?>" class="btn btn-primary btn-block w-100">
                                    <i class="fas fa-users me-2"></i>Mis Asesores
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="<?= baseUrl('coordinator/assigned-accounts') ?>" class="btn btn-success btn-block w-100">
                                    <i class="fas fa-file-invoice me-2"></i>Cuentas Asignadas
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="<?= baseUrl('coordinator/team-interactions') ?>" class="btn btn-info btn-block w-100">
                                    <i class="fas fa-comments me-2"></i>Interacciones
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="<?= baseUrl('coordinator/team-reports') ?>" class="btn btn-secondary btn-block w-100">
                                    <i class="fas fa-chart-bar me-2"></i>Ver Reportes
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="<?= baseUrl('coordinator/assign-accounts') ?>" class="btn btn-warning btn-block w-100">
                                    <i class="fas fa-user-plus me-2"></i>Asignar Cuentas
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

        <!-- Información del equipo -->
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-users me-2"></i>Mi Equipo de Asesores
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Asesor</th>
                                        <th>Gestiones</th>
                                        <th>% Éxito</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Asesor 1</td>
                                        <td>45</td>
                                        <td><span class="badge bg-success">88%</span></td>
                                        <td><span class="badge bg-success">Activo</span></td>
                                    </tr>
                                    <tr>
                                        <td>Asesor 2</td>
                                        <td>38</td>
                                        <td><span class="badge bg-warning">75%</span></td>
                                        <td><span class="badge bg-success">Activo</span></td>
                                    </tr>
                                    <tr>
                                        <td>Asesor 3</td>
                                        <td>52</td>
                                        <td><span class="badge bg-success">92%</span></td>
                                        <td><span class="badge bg-success">Activo</span></td>
                                    </tr>
                                    <tr>
                                        <td>Asesor 4</td>
                                        <td>29</td>
                                        <td><span class="badge bg-danger">65%</span></td>
                                        <td><span class="badge bg-warning">En Capacitación</span></td>
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
                            <i class="fas fa-shield-alt me-2"></i>Permisos Coordinador
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Gestionar mi equipo de asesores</li>
                            <li><i class="fas fa-check text-success me-2"></i>Ver cuentas asignadas</li>
                            <li><i class="fas fa-check text-success me-2"></i>Supervisar interacciones</li>
                            <li><i class="fas fa-check text-success me-2"></i>Ver reportes de mi equipo</li>
                            <li><i class="fas fa-check text-success me-2"></i>Exportar datos del equipo</li>
                            <li><i class="fas fa-check text-success me-2"></i>Gestionar promesas de pago</li>
                            <li><i class="fas fa-check text-success me-2"></i>Ver estadísticas del equipo</li>
                            <li><i class="fas fa-check text-success me-2"></i>Asignar cuentas a asesores</li>
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
