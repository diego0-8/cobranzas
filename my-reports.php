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
    <link rel="stylesheet" href="<?= baseUrl('assets/css/advisor.css') ?>">
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
                        <a class="nav-link" href="<?= baseUrl('/') ?>">
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
                        <a class="nav-link active" href="<?= baseUrl('advisor/my-reports') ?>">
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
                            <i class="fas fa-chart-bar me-2 text-primary"></i>Mis Reportes
                        </h1>
                        <p class="text-muted mb-0">Análisis de tu rendimiento y estadísticas</p>
                    </div>
                    <div>
                        <a href="<?= baseUrl('advisor/export-my-reports') ?>" class="btn btn-success">
                            <i class="fas fa-download me-2"></i>Exportar Reporte
                        </a>
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
                                    Contactos Exitosos
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['contacted'] ?? 0) ?>
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
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Promesas Obtenidas
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['promises'] ?? 0) ?>
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
                                    Sin Respuesta
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($stats['no_answer'] ?? 0) ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-phone-slash fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos y análisis -->
        <div class="row mb-4">
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-pie me-2"></i>Distribución de Interacciones
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="interactionsChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-line me-2"></i>Interacciones por Día
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="dailyChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen de cuentas -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-file-invoice me-2"></i>Resumen de Mis Cuentas
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($accounts)): ?>
                            <div class="text-center py-3">
                                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No tienes cuentas asignadas</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>ID Cuenta</th>
                                            <th>Deudor</th>
                                            <th>Monto Original</th>
                                            <th>Saldo Actual</th>
                                            <th>Estado</th>
                                            <th>Última Interacción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($accounts as $account): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($account['id']) ?></td>
                                                <td><?= htmlspecialchars($account['debtor_name'] ?? 'N/A') ?></td>
                                                <td>$<?= number_format($account['original_amount'], 2) ?></td>
                                                <td>$<?= number_format($account['current_balance'], 2) ?></td>
                                                <td>
                                                    <?php
                                                    $statusClass = match($account['status']) {
                                                        'active' => 'success',
                                                        'paid' => 'primary',
                                                        'overdue' => 'danger',
                                                        default => 'secondary'
                                                    };
                                                    $statusText = match($account['status']) {
                                                        'active' => 'Activa',
                                                        'paid' => 'Pagada',
                                                        'overdue' => 'Vencida',
                                                        default => ucfirst($account['status'])
                                                    };
                                                    ?>
                                                    <span class="badge bg-<?= $statusClass ?>"><?= $statusText ?></span>
                                                </td>
                                                <td>
                                                    <?php if (isset($account['last_interaction'])): ?>
                                                        <?= date('d/m/Y', strtotime($account['last_interaction'])) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">Sin interacciones</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-trophy me-2"></i>Mi Rendimiento
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $totalInteractions = $stats['total_interactions'] ?? 0;
                        $contacted = $stats['contacted'] ?? 0;
                        $successRate = $totalInteractions > 0 ? round(($contacted / $totalInteractions) * 100, 1) : 0;
                        ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Tasa de Contacto</span>
                                <span><?= $successRate ?>%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width: <?= $successRate ?>%"></div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Promesas por Interacción</span>
                                <span><?= $totalInteractions > 0 ? round(($stats['promises'] ?? 0) / $totalInteractions, 2) : 0 ?></span>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <h6><i class="fas fa-lightbulb me-2"></i>Consejos para mejorar:</h6>
                            <ul class="mb-0">
                                <li>Intenta contactar en diferentes horarios</li>
                                <li>Sé persistente pero respetuoso</li>
                                <li>Ofrece opciones de pago flexibles</li>
                                <li>Registra todas las interacciones</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calendar me-2"></i>Actividad Reciente
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success">
                            <h6><i class="fas fa-check-circle me-2"></i>Interacciones Hoy</h6>
                            <p class="mb-0"><?= number_format($stats['today_interactions'] ?? 0) ?> interacciones registradas</p>
                        </div>
                        
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-clock me-2"></i>Próximas Acciones</h6>
                            <ul class="mb-0">
                                <li>Revisar promesas de pago vencidas</li>
                                <li>Contactar cuentas sin interacciones recientes</li>
                                <li>Actualizar seguimiento de promesas</li>
                            </ul>
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
        // Gráfico de distribución de interacciones
        const interactionsCtx = document.getElementById('interactionsChart').getContext('2d');
        new Chart(interactionsCtx, {
            type: 'doughnut',
            data: {
                labels: ['Contactados', 'Sin Respuesta', 'Promesas'],
                datasets: [{
                    data: [
                        <?= $stats['contacted'] ?? 0 ?>,
                        <?= $stats['no_answer'] ?? 0 ?>,
                        <?= $stats['promises'] ?? 0 ?>
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#dc3545',
                        '#17a2b8'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Gráfico de interacciones diarias (datos de ejemplo)
        const dailyCtx = document.getElementById('dailyChart').getContext('2d');
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                datasets: [{
                    label: 'Interacciones',
                    data: [12, 15, 8, 18, 14, 6, 4],
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>

