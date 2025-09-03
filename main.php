<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' . APP_NAME : APP_NAME ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="<?= baseUrl('assets/css/dashboard.css') ?>">
    
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }
        .main-content {
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
    </style>
</head>
<body>
    <?php if (isAuthenticated()): ?>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= baseUrl() ?>">
                <i class="fas fa-chart-line me-2"></i><?= APP_NAME ?>
            </a>
            
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-1"></i><?= $user['name'] ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= baseUrl('profile') ?>">
                            <i class="fas fa-user-edit me-2"></i>Perfil
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= baseUrl('logout') ?>">
                            <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/') === strlen($_SERVER['REQUEST_URI']) - 1 ? 'active' : '' ?>" 
                               href="<?= baseUrl() ?>">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        
                        <?php if (isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/users') !== false ? 'active' : '' ?>" 
                               href="<?= baseUrl('users') ?>">
                                <i class="fas fa-users me-2"></i>Usuarios
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/campaigns') !== false ? 'active' : '' ?>" 
                               href="<?= baseUrl('campaigns') ?>">
                                <i class="fas fa-bullhorn me-2"></i>Campañas
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/debtors') !== false ? 'active' : '' ?>" 
                               href="<?= baseUrl('debtors') ?>">
                                <i class="fas fa-user-tie me-2"></i>Deudores
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/accounts') !== false ? 'active' : '' ?>" 
                               href="<?= baseUrl('accounts') ?>">
                                <i class="fas fa-file-invoice-dollar me-2"></i>Cuentas
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/interactions') !== false ? 'active' : '' ?>" 
                               href="<?= baseUrl('interactions') ?>">
                                <i class="fas fa-phone me-2"></i>Gestiones
                            </a>
                        </li>
                        
                        <?php if (isCoordinator() || isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/reports') !== false ? 'active' : '' ?>" 
                               href="<?= baseUrl('reports') ?>">
                                <i class="fas fa-chart-bar me-2"></i>Reportes
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/typifications') !== false ? 'active' : '' ?>" 
                               href="<?= baseUrl('typifications') ?>">
                                <i class="fas fa-tags me-2"></i>Tipificaciones
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="pt-3 pb-2 mb-3">
                    <?php
                    $sessionMessage = $this->getSessionMessage();
                    if ($sessionMessage['message']): ?>
                    <div class="alert alert-<?= $sessionMessage['type'] ?> alert-dismissible fade show" role="alert">
                        <?= $sessionMessage['message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>
                    
                    <?= $content ?? '' ?>
                </div>
            </main>
        </div>
    </div>
    <?php else: ?>
        <?= $content ?? '' ?>
    <?php endif; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        // Inicializar DataTables
        $(document).ready(function() {
            $('.datatable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                responsive: true
            });
        });
    </script>
</body>
</html>
