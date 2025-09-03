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
                        <a class="nav-link" href="<?= baseUrl('coordinator/my-advisors') ?>">
                            <i class="fas fa-users me-1"></i>Mis Asesores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= baseUrl('coordinator/assigned-accounts') ?>">
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
                            <i class="fas fa-file-invoice me-2 text-info"></i>Cuentas Asignadas
                        </h1>
                        <p class="text-muted mb-0">Gestiona las cuentas asignadas a tu equipo</p>
                    </div>
                    <div>
                        <a href="<?= baseUrl('coordinator/assign-accounts') ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Asignar Cuentas
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="filterAdvisor" class="form-label">Asesor</label>
                                <select class="form-select" id="filterAdvisor">
                                    <option value="">Todos los asesores</option>
                                    <!-- Opciones se llenarán dinámicamente -->
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filterStatus" class="form-label">Estado</label>
                                <select class="form-select" id="filterStatus">
                                    <option value="">Todos los estados</option>
                                    <option value="active">Activo</option>
                                    <option value="paid">Pagado</option>
                                    <option value="overdue">Vencido</option>
                                    <option value="cancelled">Cancelado</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filterDate" class="form-label">Fecha de Vencimiento</label>
                                <input type="date" class="form-control" id="filterDate">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div>
                                    <button type="button" class="btn btn-outline-primary" onclick="applyFilters()">
                                        <i class="fas fa-filter me-1"></i>Filtrar
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                                        <i class="fas fa-times me-1"></i>Limpiar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de cuentas -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Lista de Cuentas Asignadas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="accountsTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Número de Cuenta</th>
                                        <th>Deudor</th>
                                        <th>Monto</th>
                                        <th>Fecha Vencimiento</th>
                                        <th>Asignado a</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($accounts as $account): ?>
                                    <tr>
                                        <td><?= $account['id'] ?></td>
                                        <td>
                                            <span class="fw-bold"><?= htmlspecialchars($account['account_number'] ?? 'Sin número') ?></span>
                                        </td>
                                        <td><?= htmlspecialchars($account['debtor_name'] ?? 'Sin deudor') ?></td>
                                        <td>
                                            <span class="fw-bold text-success">
                                                S/ <?= number_format($account['current_amount'] ?? 0, 2) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php 
                                            $dueDate = $account['due_date'] ?? null;
                                            if ($dueDate) {
                                                $isOverdue = strtotime($dueDate) < time();
                                                $class = $isOverdue ? 'text-danger' : 'text-muted';
                                                echo "<span class='{$class}'>" . date('d/m/Y', strtotime($dueDate)) . "</span>";
                                            } else {
                                                echo "<span class='text-muted'>Sin fecha</span>";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    <?= strtoupper(substr($account['assigned_to_name'] ?? 'A', 0, 1)) ?>
                                                </div>
                                                <span><?= htmlspecialchars($account['assigned_to_name'] ?? 'Sin asignar') ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $status = $account['status'] ?? 'active';
                                            $statusClasses = [
                                                'active' => 'bg-primary',
                                                'paid' => 'bg-success',
                                                'overdue' => 'bg-danger',
                                                'cancelled' => 'bg-secondary'
                                            ];
                                            $statusLabels = [
                                                'active' => 'Activo',
                                                'paid' => 'Pagado',
                                                'overdue' => 'Vencido',
                                                'cancelled' => 'Cancelado'
                                            ];
                                            $class = $statusClasses[$status] ?? 'bg-secondary';
                                            $label = $statusLabels[$status] ?? ucfirst($status);
                                            ?>
                                            <span class="badge <?= $class ?>"><?= $label ?></span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?= baseUrl('coordinator/account-detail?id=' . $account['id']) ?>" 
                                                   class="btn btn-sm btn-outline-primary" title="Ver Detalle">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= baseUrl('coordinator/account-interactions?id=' . $account['id']) ?>" 
                                                   class="btn btn-sm btn-outline-info" title="Interacciones">
                                                    <i class="fas fa-comments"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-warning" 
                                                        onclick="reassignAccount(<?= $account['id'] ?>, '<?= htmlspecialchars($account['account_number']) ?>')"
                                                        title="Reasignar">
                                                    <i class="fas fa-user-edit"></i>
                                                </button>
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

    <!-- Modal de reasignación -->
    <div class="modal fade" id="reassignModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-edit me-2"></i>Reasignar Cuenta
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?= baseUrl('coordinator/reassign-account') ?>">
                    <input type="hidden" id="reassign_account_id" name="account_id">
                    <div class="modal-body">
                        <p>Reasignar cuenta <strong id="reassign_account_number"></strong> a:</p>
                        <div class="mb-3">
                            <label for="new_advisor" class="form-label">Nuevo Asesor</label>
                            <select class="form-select" id="new_advisor" name="advisor_id" required>
                                <option value="">Seleccionar asesor</option>
                                <!-- Opciones se llenarán dinámicamente -->
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Reasignar
                        </button>
                    </div>
                </form>
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
            $('#accountsTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                pageLength: 25,
                order: [[0, 'desc']]
            });
        });
        
        // Función para reasignar cuenta
        function reassignAccount(accountId, accountNumber) {
            document.getElementById('reassign_account_id').value = accountId;
            document.getElementById('reassign_account_number').textContent = accountNumber;
            new bootstrap.Modal(document.getElementById('reassignModal')).show();
        }
        
        // Funciones de filtrado
        function applyFilters() {
            // Implementar lógica de filtrado
            console.log('Aplicando filtros...');
        }
        
        function clearFilters() {
            document.getElementById('filterAdvisor').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterDate').value = '';
            // Recargar tabla sin filtros
        }
    </script>
</body>
</html>

