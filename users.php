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
                            <i class="fas fa-users me-2"></i>Reporte de Usuarios
                        </h1>
                        <p class="text-muted mb-0">Estadísticas y análisis de usuarios del sistema</p>
                    </div>
                    <div>
                        <a href="<?= baseUrl('reports/export?type=users&format=csv') ?>" class="btn btn-success">
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
                                <h4 class="card-title"><?= $userReport['total_users'] ?></h4>
                                <p class="card-text">Total Usuarios</p>
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
                                <h4 class="card-title"><?= $userReport['active_users'] ?></h4>
                                <p class="card-text">Usuarios Activos</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-user-check fa-2x"></i>
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
                                <h4 class="card-title"><?= $userReport['inactive_users'] ?></h4>
                                <p class="card-text">Usuarios Inactivos</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-user-slash fa-2x"></i>
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
                                <h4 class="card-title"><?= round(($userReport['active_users'] / $userReport['total_users']) * 100, 1) ?>%</h4>
                                <p class="card-text">Tasa de Actividad</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-chart-pie fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos y tablas -->
        <div class="row">
            <!-- Gráfico de usuarios por rol -->
            <div class="col-md-6 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-pie me-2"></i>Usuarios por Rol
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="usersByRoleChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Tabla de usuarios por rol -->
            <div class="col-md-6 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Distribución por Rol
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Rol</th>
                                        <th>Cantidad</th>
                                        <th>Porcentaje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($userReport['users_by_role'] as $role): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($role['role']) ?></td>
                                        <td><?= $role['count'] ?></td>
                                        <td><?= round(($role['count'] / $userReport['total_users']) * 100, 1) ?>%</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Usuarios recientes -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-clock me-2"></i>Usuarios Recientes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Rol</th>
                                        <th>Fecha de Registro</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($userReport['recent_users'] as $recentUser): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($recentUser['name']) ?></td>
                                        <td>
                                            <span class="badge bg-info"><?= htmlspecialchars($recentUser['role']) ?></span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($recentUser['created_at'])) ?></td>
                                        <td>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Activo
                                            </span>
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
        // Gráfico de usuarios por rol
        const ctx = document.getElementById('usersByRoleChart').getContext('2d');
        const usersByRoleChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [
                    <?php foreach ($userReport['users_by_role'] as $role): ?>
                    '<?= htmlspecialchars($role['role']) ?>',
                    <?php endforeach; ?>
                ],
                datasets: [{
                    data: [
                        <?php foreach ($userReport['users_by_role'] as $role): ?>
                        <?= $role['count'] ?>,
                        <?php endforeach; ?>
                    ],
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0'
                    ],
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
