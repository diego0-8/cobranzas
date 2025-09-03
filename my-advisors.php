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
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
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
                        <a class="nav-link active" href="<?= baseUrl('coordinator/my-advisors') ?>">
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
                            <i class="fas fa-users me-2 text-success"></i>Mis Asesores
                        </h1>
                        <p class="text-muted mb-0">Gestiona tu equipo de asesores</p>
                    </div>
                    <div>
                        <a href="<?= baseUrl('coordinator/assign-accounts') ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Asignar Cuentas
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Asesores
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= count($advisors) ?>
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
                                    Asesores Activos
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= count(array_filter($advisors, function($advisor) { return $advisor['is_active'] == 1; })) ?>
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
                                    Gestiones Hoy
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    0
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
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Rendimiento Promedio
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    85%
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

        <!-- Tabla de asesores -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Lista de Asesores
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="advisorsTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>Estado</th>
                                        <th>Última Actividad</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($advisors as $advisor): ?>
                                    <tr>
                                        <td><?= $advisor['id'] ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    <?= strtoupper(substr($advisor['full_name'] ?? 'A', 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <div class="fw-bold"><?= htmlspecialchars($advisor['full_name'] ?? 'Sin nombre') ?></div>
                                                    <small class="text-muted"><?= htmlspecialchars($advisor['username'] ?? 'Sin usuario') ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($advisor['email'] ?? 'Sin email') ?></td>
                                        <td><?= htmlspecialchars($advisor['phone'] ?? 'Sin teléfono') ?></td>
                                        <td>
                                            <?php if ($advisor['is_active'] == 1): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= date('d/m/Y H:i', strtotime($advisor['last_login'] ?? $advisor['created_at'])) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?= baseUrl('coordinator/advisor-detail?id=' . $advisor['id']) ?>" 
                                                   class="btn btn-sm btn-outline-primary" title="Ver Detalle">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= baseUrl('coordinator/advisor-performance?id=' . $advisor['id']) ?>" 
                                                   class="btn btn-sm btn-outline-info" title="Rendimiento">
                                                    <i class="fas fa-chart-line"></i>
                                                </a>
                                            </div>
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
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        // Inicializar DataTable
        $(document).ready(function() {
            $('#advisorsTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                pageLength: 25,
                order: [[0, 'asc']]
            });
        });
    </script>
</body>
</html>

