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
    <link rel="stylesheet" href="<?= baseUrl('assets/css/bootstrap-grid-custom.css') ?>">
    <link rel="stylesheet" href="<?= baseUrl('assets/css/typifications.css') ?>">
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
                                <div class="col-md-12 mb-3">
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
                                </div>
                                
                            <!-- Sistema de Tipificaciones Jerárquicas -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-sitemap me-2"></i>Sistema de Tipificaciones
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Nivel 1: Acción -->
                                        <div class="col-md-4 mb-3">
                                            <label for="action_category" class="form-label">Acción <span class="text-danger">*</span></label>
                                            <select class="form-select" id="action_category" name="action_category" required>
                                                <option value="">Selecciona una acción</option>
                                                <?php foreach ($action_categories as $category): ?>
                                                    <option value="<?= $category['id'] ?>" data-level="<?= $category['level'] ?>">
                                                        <?= htmlspecialchars($category['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                        
                                        <!-- Nivel 2: Contacto -->
                                        <div class="col-md-4 mb-3">
                                            <label for="contact_typification" class="form-label">Contacto <span class="text-danger">*</span></label>
                                            <select class="form-select" id="contact_typification" name="contact_typification" required disabled>
                                                <option value="">Primero selecciona una acción</option>
                                            </select>
                            </div>
                            
                                        <!-- Nivel 3: Perfil -->
                                        <div class="col-md-4 mb-3">
                                            <label for="profile_typification" class="form-label">Perfil <span class="text-danger">*</span></label>
                                            <select class="form-select" id="profile_typification" name="profile_typification" required disabled>
                                                <option value="">Primero selecciona un contacto</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Campos Dinámicos -->
                            <div class="card mb-4" id="dynamic-fields" style="display: none;">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-cogs me-2"></i>Información Adicional
                                    </h6>
                                </div>
                                <div class="card-body">
                            <div class="row">
                                        <!-- Teléfono -->
                                        <div class="col-md-6 mb-3" id="phone-field" style="display: none;">
                                            <label for="phone_number" class="form-label">Teléfono</label>
                                            <input type="tel" class="form-control" id="phone_number" name="phone_number" 
                                                   placeholder="Número de teléfono">
                            </div>
                            
                                        <!-- Fecha Agendada -->
                                        <div class="col-md-6 mb-3" id="date-field" style="display: none;">
                                            <label for="scheduled_date" class="form-label">Fecha Agendada</label>
                                            <input type="datetime-local" class="form-control" id="scheduled_date" name="scheduled_date">
                                    </div>
                                </div>
                                
                                    <!-- Canales Autorizados -->
                                    <div class="mb-3" id="channels-field" style="display: none;">
                                        <label class="form-label">Canales Autorizados</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h6 class="card-title">Seleccione los canales autorizados:</h6>
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input channel-checkbox" type="checkbox" value="telefono" id="channel_telefono">
                                                                    <label class="form-check-label" for="channel_telefono">
                                                                        Teléfono
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input channel-checkbox" type="checkbox" value="whatsapp" id="channel_whatsapp">
                                                                    <label class="form-check-label" for="channel_whatsapp">
                                                                        WhatsApp
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input channel-checkbox" type="checkbox" value="ivr" id="channel_ivr">
                                                                    <label class="form-check-label" for="channel_ivr">
                                                                        IVR
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input channel-checkbox" type="checkbox" value="sms" id="channel_sms">
                                                                    <label class="form-check-label" for="channel_sms">
                                                                        SMS
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input channel-checkbox" type="checkbox" value="correo" id="channel_correo">
                                                                    <label class="form-check-label" for="channel_correo">
                                                                        Correo
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input channel-checkbox" type="checkbox" value="direccion" id="channel_direccion">
                                                                    <label class="form-check-label" for="channel_direccion">
                                                                        Dirección Física
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input channel-checkbox" type="checkbox" value="bot" id="channel_bot">
                                                                    <label class="form-check-label" for="channel_bot">
                                                                        BOT
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h6 class="card-title">Información específica de canales:</h6>
                                                        
                                                        <!-- Teléfono Autorizado -->
                                                        <div class="mb-3" id="telefono-field" style="display: none;">
                                                            <label for="telefono_autorizado" class="form-label">Teléfono Autorizado:</label>
                                                            <input type="text" class="form-control" id="telefono_autorizado" name="telefono_autorizado" placeholder="Ingrese el número de teléfono autorizado">
                                                        </div>
                                                        
                                                        <!-- BOT Autorizado -->
                                                        <div class="mb-3" id="bot-field" style="display: none;">
                                                            <label for="bot_autorizado" class="form-label">BOT Autorizado:</label>
                                                            <input type="text" class="form-control" id="bot_autorizado" name="bot_autorizado" placeholder="Ingrese información del BOT autorizado">
                                                        </div>
                                                        
                                                        <!-- SMS Autorizado -->
                                                        <div class="mb-3" id="sms-field" style="display: none;">
                                                            <label for="sms_autorizado" class="form-label">SMS Autorizado:</label>
                                                            <input type="text" class="form-control" id="sms_autorizado" name="sms_autorizado" placeholder="Ingrese información del SMS autorizado">
                                                        </div>
                                                        
                                                        <!-- Correo Autorizado -->
                                                        <div class="mb-3" id="correo-field" style="display: none;">
                                                            <label for="correo_autorizado" class="form-label">Correo Autorizado:</label>
                                                            <input type="email" class="form-control" id="correo_autorizado" name="correo_autorizado" placeholder="Ingrese el correo autorizado">
                                                        </div>
                                                        
                                                        <!-- Dirección Autorizada -->
                                                        <div class="mb-3" id="direccion-field" style="display: none;">
                                                            <label for="direccion_autorizada" class="form-label">Dirección Autorizada:</label>
                                                            <textarea class="form-control" id="direccion_autorizada" name="direccion_autorizada" rows="2" placeholder="Ingrese la dirección física autorizada"></textarea>
                                                        </div>
                                                        
                                                        <!-- WhatsApp Autorizado -->
                                                        <div class="mb-3" id="whatsapp-field" style="display: none;">
                                                            <label for="whatsapp_autorizado" class="form-label">WhatsApp Autorizado:</label>
                                                            <input type="text" class="form-control" id="whatsapp_autorizado" name="whatsapp_autorizado" placeholder="Ingrese el número de WhatsApp autorizado">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            
                                    <!-- Marco de Obligación -->
                                    <div class="mb-3" id="obligation-field" style="display: none;">
                                        <label for="obligation_frame" class="form-label">Marco de Obligación</label>
                                        <textarea class="form-control" id="obligation_frame" name="obligation_frame" 
                                                  rows="3" placeholder="Detalles del marco de obligación..."></textarea>
                                    </div>
                                    
                                    <!-- Cuadro de Obligación -->
                                    <div class="mb-3" id="obligation-quadro-field" style="display: none;">
                                        <div class="card border-primary">
                                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">
                                                    <i class="fas fa-dollar-sign me-2"></i>Obligación
                                                </h6>
                                                <button type="button" class="btn btn-sm btn-light" id="add-obligation">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <div class="card-body">
                                                <div id="obligations-container">
                                                    <!-- Las obligaciones se agregarán dinámicamente aquí -->
                                                </div>
                                                
                                                <!-- Template para nueva obligación -->
                                                <div class="obligation-template" style="display: none;">
                                                    <div class="obligation-item border rounded p-3 mb-3">
                                                        <div class="row g-3">
                                                            <!-- Sección Cuenta -->
                                                            <div class="col-md-3 obligation-field obligation-field-account">
                                                                <label class="form-label small">Cuenta</label>
                                                                <select class="form-select form-select-sm" name="obligation_account[]" required>
                                                                    <option value="">Seleccione</option>
                                                                    <!-- Se llenará dinámicamente -->
                                                                </select>
                                                            </div>
                                                            
                                                            <!-- Sección Fecha Pago -->
                                                            <div class="col-md-3 obligation-field obligation-field-date">
                                                                <label class="form-label small">Fecha Pago</label>
                                                                <input type="date" class="form-control form-control-sm" name="obligation_payment_date[]" required>
                                                            </div>
                                                            
                                                            <!-- Sección Cuotas -->
                                                            <div class="col-md-2 obligation-field obligation-field-installments">
                                                                <label class="form-label small">Cuotas</label>
                                                                <input type="number" class="form-control form-control-sm" name="obligation_total_installments[]" min="1" required>
                                                            </div>
                                                            
                                                            <!-- Sección Valor Total -->
                                                            <div class="col-md-2 obligation-field obligation-field-total">
                                                                <label class="form-label small">Valor Total Acuerdo</label>
                                                                <div class="input-group input-group-sm">
                                                                    <span class="input-group-text">$</span>
                                                                    <input type="text" class="form-control obligation-total-value" name="obligation_total_value[]" placeholder="0" required>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Sección Número Cuota -->
                                                            <div class="col-md-1 obligation-field obligation-field-number">
                                                                <label class="form-label small">Número Cuota</label>
                                                                <input type="number" class="form-control form-control-sm" name="obligation_installment_number[]" min="1" required>
                                                            </div>
                                                            
                                                            <!-- Sección Valor Cuota -->
                                                            <div class="col-md-1 obligation-field obligation-field-value">
                                                                <label class="form-label small">Valor Cuota</label>
                                                                <div class="input-group input-group-sm">
                                                                    <span class="input-group-text">$</span>
                                                                    <input type="text" class="form-control obligation-installment-value" name="obligation_installment_value[]" placeholder="0" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-12 text-end">
                                                                <button type="button" class="btn btn-sm btn-outline-danger remove-obligation">
                                                                    <i class="fas fa-trash"></i> Eliminar
                                                                </button>
                                                            </div>
                                                        </div>
                                </div>
                            </div>
                            
                                                <!-- Botones de acción -->
                                                <div class="row mt-3">
                                                    <div class="col-12 text-end">
                                                        <button type="button" class="btn btn-success me-2" id="obligation-buzon">
                                                            <i class="fas fa-play"></i> Buzón
                                                        </button>
                                                        <button type="button" class="btn btn-secondary me-2" id="obligation-cancel">
                                                            <i class="fas fa-times"></i> Cancelar
                                                        </button>
                                                        <button type="button" class="btn btn-primary" id="obligation-save">
                                                            <i class="fas fa-save"></i> Guardar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                
                            <!-- Campo de Observaciones - Siempre visible -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-comment-alt me-2"></i>Observaciones de la Interacción
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Notas y Observaciones <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="notes" name="notes" rows="4"
                                        placeholder="Describe los detalles de la interacción, observaciones importantes, acuerdos alcanzados, próximos pasos, etc..." required></textarea>                                        
                                    </div>
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
            // Variables globales para almacenar datos
            let typificationsData = <?= json_encode($typifications_data) ?>;
            let authorizedChannels = <?= json_encode($authorized_channels) ?>;
            
            // Manejar cambio en Acción (Nivel 1)
            $('#action_category').change(function() {
                const actionId = $(this).val();
                const contactSelect = $('#contact_typification');
                const profileSelect = $('#profile_typification');
                
                // Limpiar opciones
                contactSelect.html('<option value="">Selecciona un contacto</option>').prop('disabled', true);
                profileSelect.html('<option value="">Primero selecciona un contacto</option>').prop('disabled', true);
                
                if (actionId) {
                    // Filtrar tipificaciones de nivel 2 para esta acción
                    const contactOptions = typificationsData.filter(t => 
                        t.category_id == actionId && t.level == 2
                    );
                    
                    contactOptions.forEach(option => {
                        contactSelect.append(`<option value="${option.id}">${option.name}</option>`);
                    });
                    
                    contactSelect.prop('disabled', false);
                }
                
                // Ocultar campos dinámicos
                $('#dynamic-fields').hide();
            });
            
            // Manejar cambio en Contacto (Nivel 2)
            $('#contact_typification').change(function() {
                const contactId = $(this).val();
                const actionId = $('#action_category').val();
                const profileSelect = $('#profile_typification');
                
                // Limpiar opciones
                profileSelect.html('<option value="">Selecciona un perfil</option>').prop('disabled', true);
                
                if (contactId && actionId) {
                    // Filtrar tipificaciones de nivel 3 para este contacto
                    const profileOptions = typificationsData.filter(t => 
                        t.parent_id == contactId && t.level == 3
                    );
                    
                    if (profileOptions.length > 0) {
                        profileOptions.forEach(option => {
                            profileSelect.append(`<option value="${option.id}" 
                                data-requires-phone="${option.requires_phone}" 
                                data-requires-date="${option.requires_date}" 
                                data-requires-channels="${option.requires_authorized_channels}" 
                                data-requires-obligation="${option.requires_obligation_frame}">${option.name}</option>`);
                        });
                    } else {
                        // Si no hay opciones de nivel 3, usar la tipificación de nivel 2
                        const contactTypification = typificationsData.find(t => t.id == contactId);
                        if (contactTypification) {
                            profileSelect.append(`<option value="${contactTypification.id}" 
                                data-requires-phone="${contactTypification.requires_phone}" 
                                data-requires-date="${contactTypification.requires_date}" 
                                data-requires-channels="${contactTypification.requires_authorized_channels}" 
                                data-requires-obligation="${contactTypification.requires_obligation_frame}">${contactTypification.name}</option>`);
                        }
                    }
                    
                    profileSelect.prop('disabled', false);
                }
            });
            
            // Manejar cambio en Perfil (Nivel 3)
            $('#profile_typification').change(function() {
                const selectedOption = $(this).find('option:selected');
                const requiresPhone = selectedOption.data('requires-phone') == 1;
                const requiresDate = selectedOption.data('requires-date') == 1;
                const requiresChannels = selectedOption.data('requires-channels') == 1;
                const requiresObligation = selectedOption.data('requires-obligation') == 1;
                
                // Mostrar/ocultar campos dinámicos
                if (requiresPhone || requiresDate || requiresChannels || requiresObligation) {
                    $('#dynamic-fields').show();
                    
                    // Teléfono
                    if (requiresPhone) {
                        $('#phone-field').show();
                        $('#phone_number').prop('required', true);
                    } else {
                        $('#phone-field').hide();
                        $('#phone_number').prop('required', false);
                    }
                    
                    // Fecha
                    if (requiresDate) {
                        $('#date-field').show();
                        $('#scheduled_date').prop('required', true);
                    } else {
                        $('#date-field').hide();
                        $('#scheduled_date').prop('required', false);
                    }
                    
                    // Canales autorizados
                    if (requiresChannels) {
                        $('#channels-field').show();
                    } else {
                        $('#channels-field').hide();
                    }
                    
                    // Marco de obligación
                    if (requiresObligation) {
                        $('#obligation-field').show();
                        $('#obligation-quadro-field').show();
                        $('#obligation_frame').prop('required', true);
                    } else {
                        $('#obligation-field').hide();
                        $('#obligation-quadro-field').hide();
                        $('#obligation_frame').prop('required', false);
                    }
                } else {
                    $('#dynamic-fields').hide();
                }
            });
            
            // Manejar canales autorizados con checkboxes
            $('.channel-checkbox').change(function() {
                const channelType = $(this).val();
                const isChecked = $(this).is(':checked');
                const fieldId = channelType + '-field';
                
                if (isChecked) {
                    $('#' + fieldId).show();
                } else {
                    $('#' + fieldId).hide();
                    // Limpiar el campo cuando se deselecciona
                    $('#' + fieldId + ' input, #' + fieldId + ' textarea').val('');
                }
            });
            
            // Funcionalidad del formulario de obligaciones
            let obligationCounter = 0;
            let accountsData = <?= json_encode($accounts) ?>;
            
            // Agregar nueva obligación
            $('#add-obligation').click(function() {
                const template = $('.obligation-template').html();
                const newObligation = $(template);
                
                // Llenar opciones de cuentas
                const accountSelect = newObligation.find('select[name="obligation_account[]"]');
                accountsData.forEach(account => {
                    accountSelect.append(`<option value="${account.id}">Cuenta #${account.id}</option>`);
                });
                
                // Asignar ID único
                obligationCounter++;
                newObligation.find('input, select').each(function() {
                    const name = $(this).attr('name');
                    if (name) {
                        $(this).attr('name', name.replace('[]', '[' + obligationCounter + ']'));
                    }
                });
                
                $('#obligations-container').append(newObligation);
            });
            
            // Eliminar obligación
            $(document).on('click', '.remove-obligation', function() {
                $(this).closest('.obligation-item').remove();
            });
            
            // Botones de acción de obligaciones
            $('#obligation-buzon').click(function() {
                alert('Funcionalidad de Buzón - Próximamente disponible');
            });
            
            $('#obligation-cancel').click(function() {
                $('#obligations-container').empty();
                obligationCounter = 0;
            });
            
            $('#obligation-save').click(function() {
                const obligations = [];
                $('.obligation-item').each(function() {
                    const obligation = {
                        account_id: $(this).find('select[name*="obligation_account"]').val(),
                        payment_date: $(this).find('input[name*="obligation_payment_date"]').val(),
                        total_installments: $(this).find('input[name*="obligation_total_installments"]').val(),
                        total_agreement_value: parseCurrency($(this).find('input[name*="obligation_total_value"]').val()),
                        installment_number: $(this).find('input[name*="obligation_installment_number"]').val(),
                        installment_value: parseCurrency($(this).find('input[name*="obligation_installment_value"]').val())
                    };
                    
                    // Validar que todos los campos estén llenos
                    if (Object.values(obligation).every(val => val && val.trim() !== '')) {
                        obligations.push(obligation);
                    }
                });
                
                if (obligations.length === 0) {
                    alert('Debe agregar al menos una obligación válida');
                    return;
                }
                
                // Aquí se enviarían las obligaciones al servidor
                console.log('Obligaciones a guardar:', obligations);
                alert(`Se guardarán ${obligations.length} obligación(es)`);
            });
            
            // Funciones para formateo de pesos colombianos
            function formatCurrency(value) {
                // Remover todo excepto números
                const numericValue = value.replace(/[^\d]/g, '');
                if (numericValue === '') return '';
                
                // Convertir a número y formatear con separadores de miles
                const number = parseInt(numericValue);
                return number.toLocaleString('es-CO');
            }
            
            function parseCurrency(value) {
                // Remover separadores de miles y devolver solo el número
                return value.replace(/[^\d]/g, '');
            }
            
            // Formateo automático de campos de moneda
            $(document).on('input', '.obligation-total-value', function() {
                const formatted = formatCurrency($(this).val());
                $(this).val(formatted);
            });
            
            $(document).on('input', '.obligation-installment-value', function() {
                const formatted = formatCurrency($(this).val());
                $(this).val(formatted);
            });
            
            // Validación del formulario
            $('form').submit(function(e) {
                const accountId = $('#account_id').val();
                const actionCategory = $('#action_category').val();
                const contactTypification = $('#contact_typification').val();
                const profileTypification = $('#profile_typification').val();
                const notes = $('#notes').val().trim();
                
                // Validación básica - campos obligatorios
                if (!accountId || !actionCategory || !contactTypification || !profileTypification || !notes) {
                    e.preventDefault();
                    alert('Por favor completa todos los campos obligatorios:\n' +
                          '• Cuenta\n' +
                          '• Acción\n' +
                          '• Contacto\n' +
                          '• Perfil\n' +
                          '• Observaciones');
                    return false;
                }
                
                // Validar que las observaciones tengan contenido mínimo
                if (notes.length < 10) {
                    e.preventDefault();
                    alert('Las observaciones deben tener al menos 10 caracteres para ser descriptivas.');
                    return false;
                }
                
                // Validar campos dinámicos requeridos
                const selectedProfile = $('#profile_typification option:selected');
                if (selectedProfile.data('requires-phone') == 1 && !$('#phone_number').val().trim()) {
                    e.preventDefault();
                    alert('El teléfono es requerido para esta tipificación.');
                    return false;
                }
                
                if (selectedProfile.data('requires-date') == 1 && !$('#scheduled_date').val()) {
                    e.preventDefault();
                    alert('La fecha agendada es requerida para esta tipificación.');
                    return false;
                }
                
                if (selectedProfile.data('requires-channels') == 1) {
                    const checkedChannels = $('.channel-checkbox:checked');
                    if (checkedChannels.length == 0) {
                        e.preventDefault();
                        alert('Debe seleccionar al menos un canal autorizado.');
                        return false;
                    }
                    
                    // Validar que los campos específicos estén llenos si el canal está seleccionado
                    checkedChannels.each(function() {
                        const channelType = $(this).val();
                        const fieldId = channelType + '-field';
                        const inputField = $('#' + fieldId + ' input, #' + fieldId + ' textarea');
                        
                        if (inputField.length > 0 && !inputField.val().trim()) {
                            e.preventDefault();
                            alert(`Debe completar la información del canal ${$(this).next('label').text().trim()}.`);
                            return false;
                        }
                    });
                }
                
                if (selectedProfile.data('requires-obligation') == 1 && !$('#obligation_frame').val().trim()) {
                    e.preventDefault();
                    alert('El marco de obligación es requerido para esta tipificación.');
                    return false;
                }
            });
        });
    </script>
</body>
</html>

