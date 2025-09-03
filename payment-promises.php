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
                        <a class="nav-link active" href="<?= baseUrl('advisor/payment-promises') ?>">
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
                            <i class="fas fa-handshake me-2 text-info"></i>Promesas de Pago
                        </h1>
                        <p class="text-muted mb-0">Seguimiento de promesas de pago obtenidas</p>
                    </div>
                    <div>
                        <a href="<?= baseUrl('advisor/new-interaction') ?>" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Nueva Interacción
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Promesas
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= count($promises) ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-handshake fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Monto Total
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    $<?= number_format(array_sum(array_column($promises, 'promise_amount')), 2) ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Vencidas
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php
                                    $overdue = 0;
                                    foreach ($promises as $promise) {
                                        if ($promise['promise_due_date'] && strtotime($promise['promise_due_date']) < time()) {
                                            $overdue++;
                                        }
                                    }
                                    echo $overdue;
                                    ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Pendientes
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php
                                    $pending = 0;
                                    foreach ($promises as $promise) {
                                        if ($promise['promise_due_date'] && strtotime($promise['promise_due_date']) >= time()) {
                                            $pending++;
                                        }
                                    }
                                    echo $pending;
                                    ?>
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

        <!-- Tabla de promesas -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Lista de Promesas de Pago
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($promises)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-handshake fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No tienes promesas de pago registradas</h5>
                                <p class="text-muted">Las promesas de pago aparecerán aquí cuando registres interacciones con montos de promesa.</p>
                                <a href="<?= baseUrl('advisor/new-interaction') ?>" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Nueva Interacción
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover" id="promisesTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Fecha Interacción</th>
                                            <th>Cuenta</th>
                                            <th>Deudor</th>
                                            <th>Monto Promesa</th>
                                            <th>Fecha Promesa</th>
                                            <th>Estado</th>
                                            <th>Días Restantes</th>
                                            <th>Notas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($promises as $promise): ?>
                                            <?php
                                            $dueDate = $promise['promise_due_date'];
                                            $isOverdue = $dueDate && strtotime($dueDate) < time();
                                            $daysRemaining = $dueDate ? ceil((strtotime($dueDate) - time()) / (60 * 60 * 24)) : null;
                                            ?>
                                            <tr class="<?= $isOverdue ? 'table-danger' : '' ?>">
                                                <td><?= date('d/m/Y H:i', strtotime($promise['created_at'])) ?></td>
                                                <td><?= htmlspecialchars($promise['account_id']) ?></td>
                                                <td><?= htmlspecialchars($promise['debtor_name'] ?? 'N/A') ?></td>
                                                <td>
                                                    <strong>$<?= number_format($promise['promise_amount'], 2) ?></strong>
                                                </td>
                                                <td>
                                                    <?php if ($dueDate): ?>
                                                        <?= date('d/m/Y', strtotime($dueDate)) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($isOverdue): ?>
                                                        <span class="badge bg-danger">Vencida</span>
                                                    <?php elseif ($daysRemaining !== null && $daysRemaining <= 3): ?>
                                                        <span class="badge bg-warning">Por Vencer</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">Pendiente</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($daysRemaining !== null): ?>
                                                        <?php if ($isOverdue): ?>
                                                            <span class="text-danger">
                                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                                <?= abs($daysRemaining) ?> días vencida
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="text-info">
                                                                <i class="fas fa-clock me-1"></i>
                                                                <?= $daysRemaining ?> días
                                                            </span>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($promise['notes']): ?>
                                                        <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                                              title="<?= htmlspecialchars($promise['notes']) ?>">
                                                            <?= htmlspecialchars($promise['notes']) ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
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
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#promisesTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                pageLength: 25,
                order: [[4, 'asc']] // Ordenar por fecha de promesa
            });
        });
    </script>
</body>
</html>

