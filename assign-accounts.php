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
                            <i class="fas fa-user-plus me-2 text-info"></i>Asignar Cuentas
                        </h1>
                        <p class="text-muted mb-0">Asigna cuentas disponibles a tus asesores</p>
                    </div>
                    <div>
                        <a href="<?= baseUrl('coordinator/assigned-accounts') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver a Cuentas
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de asignación -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-tasks me-2"></i>Asignación de Cuentas
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?= baseUrl('coordinator/assign-accounts') ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="advisor_id" class="form-label">Seleccionar Asesor</label>
                                        <select class="form-select" id="advisor_id" name="advisor_id" required>
                                            <option value="">Seleccionar asesor</option>
                                            <?php foreach ($advisors as $advisor): ?>
                                            <option value="<?= $advisor['id'] ?>">
                                                <?= htmlspecialchars($advisor['full_name'] ?? 'Sin nombre') ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Cuentas Disponibles</label>
                                        <div class="form-text">Selecciona las cuentas que deseas asignar</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabla de cuentas disponibles -->
                            <div class="table-responsive">
                                <table id="accountsTable" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="selectAll" class="form-check-input">
                                            </th>
                                            <th>ID</th>
                                            <th>Número de Cuenta</th>
                                            <th>Deudor</th>
                                            <th>Monto</th>
                                            <th>Fecha Vencimiento</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($availableAccounts as $account): ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="account_ids[]" value="<?= $account['id'] ?>" class="form-check-input account-checkbox">
                                            </td>
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
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <span class="text-muted">
                                                <span id="selectedCount">0</span> cuentas seleccionadas
                                            </span>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-primary" id="assignBtn" disabled>
                                                <i class="fas fa-user-plus me-2"></i>Asignar Cuentas Seleccionadas
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
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
            $('#accountsTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                pageLength: 25,
                order: [[1, 'desc']],
                columnDefs: [
                    { orderable: false, targets: 0 }
                ]
            });
        });

        // Seleccionar/deseleccionar todos
        $('#selectAll').change(function() {
            $('.account-checkbox').prop('checked', this.checked);
            updateSelectedCount();
        });

        // Actualizar contador de seleccionados
        $('.account-checkbox').change(function() {
            updateSelectedCount();
        });

        function updateSelectedCount() {
            const selectedCount = $('.account-checkbox:checked').length;
            $('#selectedCount').text(selectedCount);
            $('#assignBtn').prop('disabled', selectedCount === 0);
            
            // Actualizar checkbox "Seleccionar todos"
            const totalCheckboxes = $('.account-checkbox').length;
            $('#selectAll').prop('checked', selectedCount === totalCheckboxes);
            $('#selectAll').prop('indeterminate', selectedCount > 0 && selectedCount < totalCheckboxes);
        }

        // Validar formulario antes de enviar
        $('form').submit(function(e) {
            const selectedCount = $('.account-checkbox:checked').length;
            const advisorId = $('#advisor_id').val();
            
            if (selectedCount === 0) {
                e.preventDefault();
                alert('Por favor selecciona al menos una cuenta para asignar.');
                return false;
            }
            
            if (!advisorId) {
                e.preventDefault();
                alert('Por favor selecciona un asesor.');
                return false;
            }
            
            return confirm(`¿Estás seguro de asignar ${selectedCount} cuenta(s) al asesor seleccionado?`);
        });
    </script>
</body>
</html>

