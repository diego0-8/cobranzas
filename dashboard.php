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
    
    <!-- CSS personalizado para dashboard -->
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
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i><?= htmlspecialchars($user['user_name'] ?? 'Usuario') ?>
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
        <!-- Header del Dashboard -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </h1>
                        <p class="text-muted mb-0">
                            Bienvenido, <?= htmlspecialchars($user['user_name'] ?? 'Usuario') ?> 
                            <span class="badge bg-primary ms-2"><?= htmlspecialchars($user['user_role'] ?? 'Usuario') ?></span>
                        </p>
                    </div>
                    <div>
                        <span class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            <?= date('d/m/Y H:i') ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="row mb-4">
            <?php if (($user['user_role_id'] ?? 0) == 1): // Super Administrador ?>
                <!-- Estadísticas para Super Administrador -->
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
                                        Campañas Activas
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= number_format($stats['active_campaigns'] ?? 0) ?>
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
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Total Deudores
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= number_format($stats['total_debtors'] ?? 0) ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-times fa-2x text-gray-300"></i>
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
                                        Total Cuentas
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= number_format($stats['total_accounts'] ?? 0) ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-credit-card fa-2x text-gray-300"></i>
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
                                        Total Gestiones
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= number_format($stats['total_interactions'] ?? 0) ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-phone fa-2x text-gray-300"></i>
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
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
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
            <?php endif; ?>
        </div>

        <!-- Gráficos -->
        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Gestiones por Día (Últimos 7 días)</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="interactionsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Pagos por Mes</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="paymentsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones rápidas -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Acciones Rápidas</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="<?= baseUrl('users') ?>" class="btn btn-primary btn-block w-100">
                                    <i class="fas fa-users me-2"></i>Gestionar Usuarios
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?= baseUrl('campaigns') ?>" class="btn btn-success btn-block w-100">
                                    <i class="fas fa-bullhorn me-2"></i>Gestionar Campañas
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?= baseUrl('reports') ?>" class="btn btn-info btn-block w-100">
                                    <i class="fas fa-chart-bar me-2"></i>Ver Reportes
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?= baseUrl('profile') ?>" class="btn btn-warning btn-block w-100">
                                    <i class="fas fa-user-edit me-2"></i>Mi Perfil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Gráfico de interacciones
        const interactionsCtx = document.getElementById('interactionsChart').getContext('2d');
        const interactionsChart = new Chart(interactionsCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_column($chartData['interactions'], 'date')) ?>,
                datasets: [{
                    label: 'Gestiones',
                    data: <?= json_encode(array_column($chartData['interactions'], 'count')) ?>,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Gráfico de pagos
        const paymentsCtx = document.getElementById('paymentsChart').getContext('2d');
        const paymentsChart = new Chart(paymentsCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode(array_column($chartData['payments'], 'month')) ?>,
                datasets: [{
                    data: <?= json_encode(array_column($chartData['payments'], 'count')) ?>,
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
</body>
</html>