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
    <link rel="stylesheet" href="<?= baseUrl('assets/css/coordinator.css') ?>">
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
                        <a class="nav-link" href="<?= baseUrl('/') ?>">
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
                        <a class="nav-link active" href="<?= baseUrl('coordinator/team-reports') ?>">
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
                            <i class="fas fa-chart-bar me-2 text-info"></i>Reportes del Equipo
                        </h1>
                        <p class="text-muted mb-0">Análisis y métricas de rendimiento de tu equipo</p>
                    </div>
                    <div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-download me-2"></i>Exportar Reportes
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?= baseUrl('coordinator/export-performance-report') ?>">
                                    <i class="fas fa-chart-line me-2"></i>Reporte de Rendimiento
                                </a></li>
                                <li><a class="dropdown-item" href="<?= baseUrl('coordinator/export-collections-report') ?>">
                                    <i class="fas fa-money-bill-wave me-2"></i>Reporte de Cobranzas
                                </a></li>
                                <li><a class="dropdown-item" href="<?= baseUrl('coordinator/export-interactions-report') ?>">
                                    <i class="fas fa-comments me-2"></i>Reporte de Interacciones
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros de período -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="reportPeriod" class="form-label">Período</label>
                                <select class="form-select" id="reportPeriod" onchange="updatePeriod()">
                                    <option value="today">Hoy</option>
                                    <option value="week">Esta Semana</option>
                                    <option value="month" selected>Este Mes</option>
                                    <option value="quarter">Este Trimestre</option>
                                    <option value="year">Este Año</option>
                                    <option value="custom">Personalizado</option>
                                </select>
                            </div>
                            <div class="col-md-3" id="customDateFrom" style="display: none;">
                                <label for="dateFrom" class="form-label">Desde</label>
                                <input type="date" class="form-control" id="dateFrom">
                            </div>
                            <div class="col-md-3" id="customDateTo" style="display: none;">
                                <label for="dateTo" class="form-label">Hasta</label>
                                <input type="date" class="form-control" id="dateTo">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div>
                                    <button type="button" class="btn btn-primary" onclick="generateReport()">
                                        <i class="fas fa-sync me-1"></i>Generar Reporte
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Métricas principales -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">S/ <?= number_format($metrics['total_collected'] ?? 0, 2) ?></h4>
                                <p class="card-text">Total Recaudado</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-money-bill-wave fa-2x"></i>
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
                                <h4 class="card-title"><?= $metrics['success_rate'] ?? 0 ?>%</h4>
                                <p class="card-text">Tasa de Éxito</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-percentage fa-2x"></i>
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
                                <h4 class="card-title"><?= $metrics['avg_interactions'] ?? 0 ?></h4>
                                <p class="card-text">Interacciones Promedio</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-comments fa-2x"></i>
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
                                <h4 class="card-title"><?= $metrics['pending_accounts'] ?? 0 ?></h4>
                                <p class="card-text">Cuentas Pendientes</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-pie me-2"></i>Rendimiento por Asesor
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="advisorPerformanceChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-line me-2"></i>Evolución de Cobranzas
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="collectionsTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de rendimiento por asesor -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-table me-2"></i>Rendimiento Detallado por Asesor
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Asesor</th>
                                        <th>Cuentas Asignadas</th>
                                        <th>Interacciones</th>
                                        <th>Monto Recaudado</th>
                                        <th>Tasa de Éxito</th>
                                        <th>Promesas de Pago</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($advisorPerformance as $advisor): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    <?= strtoupper(substr($advisor['name'] ?? 'A', 0, 1)) ?>
                                                </div>
                                                <span><?= htmlspecialchars($advisor['name'] ?? 'Sin nombre') ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary"><?= $advisor['assigned_accounts'] ?? 0 ?></span>
                                        </td>
                                        <td><?= $advisor['total_interactions'] ?? 0 ?></td>
                                        <td>
                                            <span class="fw-bold text-success">
                                                S/ <?= number_format($advisor['collected_amount'] ?? 0, 2) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            $successRate = $advisor['success_rate'] ?? 0;
                                            $badgeClass = $successRate >= 70 ? 'bg-success' : ($successRate >= 50 ? 'bg-warning' : 'bg-danger');
                                            ?>
                                            <span class="badge <?= $badgeClass ?>"><?= $successRate ?>%</span>
                                        </td>
                                        <td><?= $advisor['payment_promises'] ?? 0 ?></td>
                                        <td>
                                            <a href="<?= baseUrl('coordinator/advisor-detail?id=' . $advisor['id']) ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i>Ver Detalle
                                            </a>
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

        <!-- Análisis de tipificaciones -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-tags me-2"></i>Análisis de Tipificaciones
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <canvas id="typificationChart"></canvas>
                            </div>
                            <div class="col-md-4">
                                <h6>Top Tipificaciones</h6>
                                <?php foreach ($topTypifications as $typification): ?>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span><?= htmlspecialchars($typification['name'] ?? 'Sin nombre') ?></span>
                                    <span class="badge bg-primary"><?= $typification['count'] ?? 0 ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <script>
        // Datos de ejemplo para los gráficos
        const advisorPerformanceData = <?= json_encode($advisorPerformance ?? []) ?>;
        const collectionsTrendData = <?= json_encode($collectionsTrend ?? []) ?>;
        const typificationData = <?= json_encode($topTypifications ?? []) ?>;

        // Gráfico de rendimiento por asesor
        const advisorCtx = document.getElementById('advisorPerformanceChart').getContext('2d');
        new Chart(advisorCtx, {
            type: 'doughnut',
            data: {
                labels: advisorPerformanceData.map(advisor => advisor.name || 'Sin nombre'),
                datasets: [{
                    data: advisorPerformanceData.map(advisor => advisor.collected_amount || 0),
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
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Gráfico de tendencia de cobranzas
        const trendCtx = document.getElementById('collectionsTrendChart').getContext('2d');
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: collectionsTrendData.map(item => item.date || ''),
                datasets: [{
                    label: 'Monto Recaudado',
                    data: collectionsTrendData.map(item => item.amount || 0),
                    borderColor: '#36A2EB',
                    backgroundColor: 'rgba(54, 162, 235, 0.1)',
                    tension: 0.4
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

        // Gráfico de tipificaciones
        const typificationCtx = document.getElementById('typificationChart').getContext('2d');
        new Chart(typificationCtx, {
            type: 'bar',
            data: {
                labels: typificationData.map(item => item.name || 'Sin nombre'),
                datasets: [{
                    label: 'Cantidad',
                    data: typificationData.map(item => item.count || 0),
                    backgroundColor: '#4BC0C0'
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

        // Función para actualizar período
        function updatePeriod() {
            const period = document.getElementById('reportPeriod').value;
            const customFrom = document.getElementById('customDateFrom');
            const customTo = document.getElementById('customDateTo');
            
            if (period === 'custom') {
                customFrom.style.display = 'block';
                customTo.style.display = 'block';
            } else {
                customFrom.style.display = 'none';
                customTo.style.display = 'none';
            }
        }

        // Función para generar reporte
        function generateReport() {
            const period = document.getElementById('reportPeriod').value;
            let dateFrom = '';
            let dateTo = '';
            
            if (period === 'custom') {
                dateFrom = document.getElementById('dateFrom').value;
                dateTo = document.getElementById('dateTo').value;
            }
            
            // Aquí harías la petición para generar el reporte con los nuevos parámetros
            console.log('Generando reporte para período:', period, 'Desde:', dateFrom, 'Hasta:', dateTo);
            
            // Recargar la página con los nuevos parámetros
            const url = new URL(window.location);
            url.searchParams.set('period', period);
            if (dateFrom) url.searchParams.set('from', dateFrom);
            if (dateTo) url.searchParams.set('to', dateTo);
            
            window.location.href = url.toString();
        }
    </script>
</body>
</html>

