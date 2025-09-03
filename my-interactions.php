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
                        <a class="nav-link active" href="<?= baseUrl('advisor/my-interactions') ?>">
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
                            <i class="fas fa-comments me-2 text-primary"></i>Mis Interacciones
                        </h1>
                        <p class="text-muted mb-0">Historial de todas tus interacciones con deudores</p>
                    </div>
                    <div>
                        <a href="<?= baseUrl('advisor/new-interaction') ?>" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Nueva Interacción
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de interacciones -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Lista de Interacciones
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($interactions)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No tienes interacciones registradas</h5>
                                <p class="text-muted">Comienza registrando tu primera interacción.</p>
                                <a href="<?= baseUrl('advisor/new-interaction') ?>" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Nueva Interacción
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover" id="interactionsTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Cuenta</th>
                                            <th>Deudor</th>
                                            <th>Tipificación</th>
                                            <th>Contactado</th>
                                            <th>Monto Promesa</th>
                                            <th>Fecha Promesa</th>
                                            <th>Notas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($interactions as $interaction): ?>
                                            <tr>
                                                <td><?= date('d/m/Y H:i', strtotime($interaction['created_at'])) ?></td>
                                                <td><?= htmlspecialchars($interaction['account_id']) ?></td>
                                                <td><?= htmlspecialchars($interaction['debtor_name'] ?? 'N/A') ?></td>
                                                <td><?= htmlspecialchars($interaction['typification_name'] ?? 'N/A') ?></td>
                                                <td>
                                                    <?php if ($interaction['contacted']): ?>
                                                        <span class="badge bg-success">Sí</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">No</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($interaction['promise_amount'] && $interaction['promise_amount'] > 0): ?>
                                                        $<?= number_format($interaction['promise_amount'], 2) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($interaction['promise_due_date']): ?>
                                                        <?= date('d/m/Y', strtotime($interaction['promise_due_date'])) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($interaction['notes']): ?>
                                                        <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                                              title="<?= htmlspecialchars($interaction['notes']) ?>">
                                                            <?= htmlspecialchars($interaction['notes']) ?>
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
            $('#interactionsTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                pageLength: 25,
                order: [[0, 'desc']]
            });
        });
    </script>
</body>
</html>

