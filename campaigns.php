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
                            <i class="fas fa-bullhorn me-2"></i>Reporte de Campañas
                        </h1>
                        <p class="text-muted mb-0">Análisis de rendimiento de campañas de cobranza</p>
                    </div>
                    <div>
                        <a href="<?= baseUrl('reports/export?type=campaigns&format=csv') ?>" class="btn btn-success">
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
                                <h4 class="card-title"><?= $campaignReport['total_campaigns'] ?></h4>
                                <p class="card-text">Total Campañas</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-bullhorn fa-2x"></i>
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
                                <h4 class="card-title"><?= $campaignReport['active_campaigns'] ?></h4>
                                <p class="card-text">Campañas Activas</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-play-circle fa-2x"></i>
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
                                <h4 class="card-title"><?= $campaignReport['completed_campaigns'] ?></h4>
                                <p class="card-text">Campañas Completadas</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check-circle fa-2x"></i>
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
                                <h4 class="card-title">$<?= number_format($campaignReport['total_revenue'], 0, ',', '.') ?></h4>
                                <p class="card-text">Ingresos Totales</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-dollar-sign fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de rendimiento de campañas -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-bar me-2"></i>Rendimiento de Campañas
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="campaignPerformanceChart" width="400" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de rendimiento de campañas -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Detalle de Campañas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Campaña</th>
                                        <th>Meta</th>
                                        <th>Recaudado</th>
                                        <th>Progreso</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($campaignReport['campaigns_performance'] as $campaign): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($campaign['name']) ?></td>
                                        <td>$<?= number_format($campaign['target'], 0, ',', '.') ?></td>
                                        <td>$<?= number_format($campaign['collected'], 0, ',', '.') ?></td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar <?= $campaign['percentage'] >= 70 ? 'bg-success' : ($campaign['percentage'] >= 40 ? 'bg-warning' : 'bg-danger') ?>" 
                                                     role="progressbar" 
                                                     style="width: <?= $campaign['percentage'] ?>%"
                                                     aria-valuenow="<?= $campaign['percentage'] ?>" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    <?= $campaign['percentage'] ?>%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($campaign['percentage'] >= 100): ?>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>Completada
                                                </span>
                                            <?php elseif ($campaign['percentage'] >= 70): ?>
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-play-circle me-1"></i>En Progreso
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>Requiere Atención
                                                </span>
                                            <?php endif; ?>
                                        </td>
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
        // Gráfico de rendimiento de campañas
        const ctx = document.getElementById('campaignPerformanceChart').getContext('2d');
        const campaignPerformanceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    <?php foreach ($campaignReport['campaigns_performance'] as $campaign): ?>
                    '<?= htmlspecialchars($campaign['name']) ?>',
                    <?php endforeach; ?>
                ],
                datasets: [{
                    label: 'Progreso (%)',
                    data: [
                        <?php foreach ($campaignReport['campaigns_performance'] as $campaign): ?>
                        <?= $campaign['percentage'] ?>,
                        <?php endforeach; ?>
                    ],
                    backgroundColor: [
                        <?php foreach ($campaignReport['campaigns_performance'] as $campaign): ?>
                        '<?= $campaign['percentage'] >= 70 ? '#28a745' : ($campaign['percentage'] >= 40 ? '#ffc107' : '#dc3545') ?>',
                        <?php endforeach; ?>
                    ],
                    borderColor: [
                        <?php foreach ($campaignReport['campaigns_performance'] as $campaign): ?>
                        '<?= $campaign['percentage'] >= 70 ? '#1e7e34' : ($campaign['percentage'] >= 40 ? '#e0a800' : '#bd2130') ?>',
                        <?php endforeach; ?>
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
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
    </script>
</body>
</html>
