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
                            <i class="fas fa-plus me-2 text-success"></i>Nueva Interacción
                        </h1>
                        <p class="text-muted mb-0">Registra una nueva interacción con un deudor</p>
                    </div>
                    <div>
                        <a href="<?= baseUrl('advisor/my-interactions') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-edit me-2"></i>Datos de la Interacción
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?= baseUrl('advisor/new-interaction') ?>">
                            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="account_id" class="form-label">Cuenta <span class="text-danger">*</span></label>
                                    <select class="form-select" id="account_id" name="account_id" required>
                                        <option value="">Selecciona una cuenta</option>
                                        <?php foreach ($accounts as $account): ?>
                                            <option value="<?= $account['id'] ?>" 
                                                    <?= (isset($_GET['account_id']) && $_GET['account_id'] == $account['id']) ? 'selected' : '' ?>>
                                                ID: <?= $account['id'] ?> - <?= htmlspecialchars($account['debtor_name'] ?? 'N/A') ?> 
                                                ($<?= number_format($account['current_balance'], 2) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="typification_id" class="form-label">Tipificación <span class="text-danger">*</span></label>
                                    <select class="form-select" id="typification_id" name="typification_id" required>
                                        <option value="">Selecciona una tipificación</option>
                                        <?php foreach ($typifications as $typification): ?>
                                            <option value="<?= $typification['id'] ?>">
                                                <?= htmlspecialchars($typification['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notas de la Interacción <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="notes" name="notes" rows="4" 
                                          placeholder="Describe los detalles de la interacción..." required></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="contacted" name="contacted" value="1">
                                        <label class="form-check-label" for="contacted">
                                            <i class="fas fa-phone me-1"></i>Logré contactar al deudor
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="next_contact_at" class="form-label">Próximo Contacto</label>
                                    <input type="datetime-local" class="form-control" id="next_contact_at" name="next_contact_at">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="promise_amount" class="form-label">Monto de Promesa de Pago</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="promise_amount" name="promise_amount" 
                                               step="0.01" min="0" placeholder="0.00">
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="promise_due_date" class="form-label">Fecha de Promesa</label>
                                    <input type="date" class="form-control" id="promise_due_date" name="promise_due_date">
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="<?= baseUrl('advisor/my-interactions') ?>" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-times me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Guardar Interacción
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Información
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-lightbulb me-2"></i>Consejos para una buena interacción:</h6>
                            <ul class="mb-0">
                                <li>Sé claro y profesional</li>
                                <li>Escucha al deudor</li>
                                <li>Registra todos los detalles importantes</li>
                                <li>Si hay promesa de pago, anota el monto y fecha exacta</li>
                                <li>Programa el próximo contacto si es necesario</li>
                            </ul>
                        </div>
                        
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Importante:</h6>
                            <p class="mb-0">Todas las interacciones quedan registradas y son supervisadas por tu coordinador.</p>
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
        $(document).ready(function() {
            // Habilitar/deshabilitar campos de promesa según el checkbox
            $('#contacted').change(function() {
                if ($(this).is(':checked')) {
                    $('#promise_amount, #promise_due_date').prop('disabled', false);
                } else {
                    $('#promise_amount, #promise_due_date').prop('disabled', true).val('');
                }
            });
            
            // Validación del formulario
            $('form').submit(function(e) {
                var accountId = $('#account_id').val();
                var typificationId = $('#typification_id').val();
                var notes = $('#notes').val().trim();
                
                if (!accountId || !typificationId || !notes) {
                    e.preventDefault();
                    alert('Por favor completa todos los campos obligatorios.');
                    return false;
                }
            });
        });
    </script>
</body>
</html>

