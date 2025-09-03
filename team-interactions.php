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
                        <a class="nav-link active" href="<?= baseUrl('coordinator/team-interactions') ?>">
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
                            <i class="fas fa-comments me-2 text-info"></i>Interacciones del Equipo
                        </h1>
                        <p class="text-muted mb-0">Monitorea las interacciones de tu equipo de asesores</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary" onclick="exportInteractions()">
                            <i class="fas fa-download me-2"></i>Exportar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title"><?= $stats['total_interactions'] ?? 0 ?></h4>
                                <p class="card-text">Total Interacciones</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-comments fa-2x"></i>
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
                                <h4 class="card-title"><?= $stats['successful_interactions'] ?? 0 ?></h4>
                                <p class="card-text">Exitosas</p>
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
                                <h4 class="card-title"><?= $stats['pending_interactions'] ?? 0 ?></h4>
                                <p class="card-text">Pendientes</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-clock fa-2x"></i>
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
                                <h4 class="card-title"><?= $stats['avg_response_time'] ?? 0 ?>h</h4>
                                <p class="card-text">Tiempo Promedio</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-stopwatch fa-2x"></i>
                            </div>
                        </div>
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
                                <label for="filterType" class="form-label">Tipo</label>
                                <select class="form-select" id="filterType">
                                    <option value="">Todos los tipos</option>
                                    <option value="call">Llamada</option>
                                    <option value="email">Email</option>
                                    <option value="sms">SMS</option>
                                    <option value="visit">Visita</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filterDateFrom" class="form-label">Desde</label>
                                <input type="date" class="form-control" id="filterDateFrom">
                            </div>
                            <div class="col-md-3">
                                <label for="filterDateTo" class="form-label">Hasta</label>
                                <input type="date" class="form-control" id="filterDateTo">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
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
                        <div class="table-responsive">
                            <table id="interactionsTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Fecha</th>
                                        <th>Asesor</th>
                                        <th>Deudor</th>
                                        <th>Tipo</th>
                                        <th>Resultado</th>
                                        <th>Duración</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($interactions as $interaction): ?>
                                    <tr>
                                        <td><?= $interaction['id'] ?></td>
                                        <td>
                                            <span class="fw-bold"><?= date('d/m/Y', strtotime($interaction['created_at'] ?? 'now')) ?></span>
                                            <br>
                                            <small class="text-muted"><?= date('H:i', strtotime($interaction['created_at'] ?? 'now')) ?></small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    <?= strtoupper(substr($interaction['advisor_name'] ?? 'A', 0, 1)) ?>
                                                </div>
                                                <span><?= htmlspecialchars($interaction['advisor_name'] ?? 'Sin asesor') ?></span>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($interaction['debtor_name'] ?? 'Sin deudor') ?></td>
                                        <td>
                                            <?php
                                            $type = $interaction['type'] ?? 'call';
                                            $typeIcons = [
                                                'call' => 'fas fa-phone',
                                                'email' => 'fas fa-envelope',
                                                'sms' => 'fas fa-sms',
                                                'visit' => 'fas fa-home'
                                            ];
                                            $typeLabels = [
                                                'call' => 'Llamada',
                                                'email' => 'Email',
                                                'sms' => 'SMS',
                                                'visit' => 'Visita'
                                            ];
                                            $icon = $typeIcons[$type] ?? 'fas fa-phone';
                                            $label = $typeLabels[$type] ?? ucfirst($type);
                                            ?>
                                            <span class="badge bg-light text-dark">
                                                <i class="<?= $icon ?> me-1"></i><?= $label ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            $result = $interaction['result'] ?? 'pending';
                                            $resultClasses = [
                                                'successful' => 'bg-success',
                                                'pending' => 'bg-warning',
                                                'failed' => 'bg-danger'
                                            ];
                                            $resultLabels = [
                                                'successful' => 'Exitosa',
                                                'pending' => 'Pendiente',
                                                'failed' => 'Fallida'
                                            ];
                                            $class = $resultClasses[$result] ?? 'bg-secondary';
                                            $label = $resultLabels[$result] ?? ucfirst($result);
                                            ?>
                                            <span class="badge <?= $class ?>"><?= $label ?></span>
                                        </td>
                                        <td>
                                            <?php
                                            $duration = $interaction['duration'] ?? 0;
                                            if ($duration > 0) {
                                                $minutes = floor($duration / 60);
                                                $seconds = $duration % 60;
                                                echo "{$minutes}:{$seconds}";
                                            } else {
                                                echo "<span class='text-muted'>-</span>";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?= baseUrl('coordinator/interaction-detail?id=' . $interaction['id']) ?>" 
                                                   class="btn btn-sm btn-outline-primary" title="Ver Detalle">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-info" 
                                                        onclick="viewNotes(<?= $interaction['id'] ?>)"
                                                        title="Ver Notas">
                                                    <i class="fas fa-sticky-note"></i>
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

    <!-- Modal de notas -->
    <div class="modal fade" id="notesModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-sticky-note me-2"></i>Notas de la Interacción
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="notesContent">
                        <!-- Contenido se cargará dinámicamente -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
            $('#interactionsTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                pageLength: 25,
                order: [[1, 'desc']]
            });
        });
        
        // Función para ver notas
        function viewNotes(interactionId) {
            // Cargar notas dinámicamente
            document.getElementById('notesContent').innerHTML = '<p>Cargando notas...</p>';
            new bootstrap.Modal(document.getElementById('notesModal')).show();
            
            // Aquí harías una petición AJAX para cargar las notas
            // Por ahora mostramos contenido de ejemplo
            setTimeout(() => {
                document.getElementById('notesContent').innerHTML = `
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Notas de la interacción #${interactionId}
                    </div>
                    <p>Esta funcionalidad se implementará para mostrar las notas reales de la interacción.</p>
                `;
            }, 500);
        }
        
        // Función para exportar interacciones
        function exportInteractions() {
            window.location.href = '<?= baseUrl('coordinator/export-interactions') ?>';
        }
        
        // Funciones de filtrado
        function applyFilters() {
            // Implementar lógica de filtrado
            console.log('Aplicando filtros...');
        }
        
        function clearFilters() {
            document.getElementById('filterAdvisor').value = '';
            document.getElementById('filterType').value = '';
            document.getElementById('filterDateFrom').value = '';
            document.getElementById('filterDateTo').value = '';
            // Recargar tabla sin filtros
        }
    </script>
</body>
</html>

