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
                <i class="fas fa-user me-2"></i><?= APP_NAME ?> - Asesor
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
                        <a class="nav-link" href="<?= baseUrl('advisor/my-accounts') ?>">
                            <i class="fas fa-file-invoice me-1"></i>Mis Cuentas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= baseUrl('advisor/my-interactions') ?>">
                            <i class="fas fa-comments me-1"></i>Interacciones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= baseUrl('advisor/payment-promises') ?>">
                            <i class="fas fa-handshake me-1"></i>Promesas de Pago
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= baseUrl('advisor/my-reports') ?>">
                            <i class="fas fa-chart-bar me-1"></i>Mis Reportes
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i><?= htmlspecialchars($user['user_name'] ?? 'Asesor') ?>
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
                            <i class="fas fa-user me-2 text-primary"></i>Dashboard Asesor
                        </h1>
                        <p class="text-muted mb-0">Gestión de tus cuentas asignadas y interacciones</p>
                    </div>
                    <div>
                        <span class="badge bg-primary fs-6">
                            <i class="fas fa-user me-1"></i>Asesor
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
                                    Mis Cuentas
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['my_accounts'] ?? 0) ?>
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
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Interacciones Hoy
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['today_interactions'] ?? 0) ?>
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
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Promesas Activas
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['active_promises'] ?? 0) ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-handshake fa-2x text-gray-300"></i>
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
                                    Mi Rendimiento
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['my_performance'] ?? 0, 1) ?>%
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

        <!-- Segunda fila de estadísticas -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Pagos Recibidos
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['payments_received'] ?? 0) ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Meta Mensual
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['monthly_goal'] ?? 0) ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-target fa-2x text-gray-300"></i>
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
                                <a href="<?= baseUrl('advisor/my-accounts') ?>" class="btn btn-primary btn-block w-100">
                                    <i class="fas fa-file-invoice me-2"></i>Mis Cuentas
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="<?= baseUrl('advisor/new-interaction') ?>" class="btn btn-success btn-block w-100">
                                    <i class="fas fa-comments me-2"></i>Nueva Interacción
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="<?= baseUrl('advisor/payment-promises') ?>" class="btn btn-info btn-block w-100">
                                    <i class="fas fa-handshake me-2"></i>Promesas de Pago
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="<?= baseUrl('advisor/my-reports') ?>" class="btn btn-secondary btn-block w-100">
                                    <i class="fas fa-chart-bar me-2"></i>Mis Reportes
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="<?= baseUrl('typifications') ?>" class="btn btn-warning btn-block w-100">
                                    <i class="fas fa-list me-2"></i>Tipificaciones
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
                            <i class="fas fa-chart-pie me-2"></i>Mi Rendimiento Semanal
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Día</th>
                                        <th>Interacciones</th>
                                        <th>Promesas</th>
                                        <th>Pagos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Lunes</td>
                                        <td>12</td>
                                        <td>3</td>
                                        <td>2</td>
                                    </tr>
                                    <tr>
                                        <td>Martes</td>
                                        <td>15</td>
                                        <td>4</td>
                                        <td>1</td>
                                    </tr>
                                    <tr>
                                        <td>Miércoles</td>
                                        <td>18</td>
                                        <td>5</td>
                                        <td>3</td>
                                    </tr>
                                    <tr>
                                        <td>Jueves</td>
                                        <td>14</td>
                                        <td>2</td>
                                        <td>2</td>
                                    </tr>
                                    <tr>
                                        <td>Viernes</td>
                                        <td>16</td>
                                        <td>4</td>
                                        <td>1</td>
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
                            <i class="fas fa-shield-alt me-2"></i>Permisos Asesor
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Gestionar mis cuentas asignadas</li>
                            <li><i class="fas fa-check text-success me-2"></i>Registrar interacciones</li>
                            <li><i class="fas fa-check text-success me-2"></i>Crear promesas de pago</li>
                            <li><i class="fas fa-check text-success me-2"></i>Usar tipificaciones</li>
                            <li><i class="fas fa-check text-success me-2"></i>Ver mis reportes</li>
                            <li><i class="fas fa-check text-success me-2"></i>Registrar pagos recibidos</li>
                            <li><i class="fas fa-check text-success me-2"></i>Ver mi rendimiento</li>
                            <li><i class="fas fa-check text-success me-2"></i>Gestionar mi perfil</li>
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

