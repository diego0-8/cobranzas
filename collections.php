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
    <link rel="stylesheet" href="<?= baseUrl('assets/css/reports.css') ?>">
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
                        <a class="nav-link active" href="<?= baseUrl('reports') ?>">
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
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0">
                            <i class="fas fa-money-bill-wave me-2"></i>Reporte de Cobranzas
                        </h1>
                        <p class="text-muted mb-0">Análisis de recaudación y gestión de deudores</p>
                    </div>
                    <div>
                        <a href="<?= baseUrl('reports/export?type=collections&format=csv') ?>" class="btn btn-success">
                            <i class="fas fa-file-csv me-2"></i>Exportar CSV
                        </a>
                        <a href="<?= baseUrl('reports') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Volver a Reportes
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas principales -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title"><?= $collectionReport['total_debtors'] ?></h4>
                                <p class="card-text">Total Deudores</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title"><?= $collectionReport['active_accounts'] ?></h4>
                                <p class="card-text">Cuentas Activas</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-user-check fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">$<?= number_format($collectionReport['collected_amount'], 0, ',', '.') ?></h4>
                                <p class="card-text">Recaudado</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-dollar-sign fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title"><?= $collectionReport['collection_rate'] ?>%</h4>
                                <p class="card-text">Tasa de Cobranza</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-chart-line fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de cobranzas mensuales -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-line me-2"></i>Cobranzas Mensuales
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="monthlyCollectionsChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-pie me-2"></i>Estado de Cobranza
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="collectionStatusChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen financiero -->
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calculator me-2"></i>Resumen Financiero
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center p-3 bg-light rounded">
                                    <h3 class="text-success">$<?= number_format($collectionReport['collected_amount'], 0, ',', '.') ?></h3>
                                    <p class="mb-0">Total Recaudado</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 bg-light rounded">
                                    <h3 class="text-danger">$<?= number_format($collectionReport['pending_amount'], 0, ',', '.') ?></h3>
                                    <p class="mb-0">Pendiente por Cobrar</p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <h4 class="text-primary">$<?= number_format($collectionReport['collected_amount'] + $collectionReport['pending_amount'], 0, ',', '.') ?></h4>
                            <p class="mb-0">Total de la Cartera</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Detalle Mensual
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Mes</th>
                                        <th>Recaudado</th>
                                        <th>% del Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $totalCollected = array_sum(array_column($collectionReport['monthly_collections'], 'amount'));
                                    foreach ($collectionReport['monthly_collections'] as $month): 
                                        $percentage = ($month['amount'] / $totalCollected) * 100;
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($month['month']) ?></td>
                                        <td>$<?= number_format($month['amount'], 0, ',', '.') ?></td>
                                        <td><?= round($percentage, 1) ?>%</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Gráfico de cobranzas mensuales
        const monthlyCtx = document.getElementById('monthlyCollectionsChart').getContext('2d');
        const monthlyCollectionsChart = new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: [
                    <?php foreach ($collectionReport['monthly_collections'] as $month): ?>
                    '<?= htmlspecialchars($month['month']) ?>',
                    <?php endforeach; ?>
                ],
                datasets: [{
                    label: 'Cobranzas ($)',
                    data: [
                        <?php foreach ($collectionReport['monthly_collections'] as $month): ?>
                        <?= $month['amount'] ?>,
                        <?php endforeach; ?>
                    ],
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Gráfico de estado de cobranza
        const statusCtx = document.getElementById('collectionStatusChart').getContext('2d');
        const collectionStatusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Recaudado', 'Pendiente'],
                datasets: [{
                    data: [<?= $collectionReport['collected_amount'] ?>, <?= $collectionReport['pending_amount'] ?>],
                    backgroundColor: ['#28a745', '#ffc107'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
