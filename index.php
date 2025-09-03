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
    <link rel="stylesheet" href="<?= baseUrl('assets/css/dashboard.css') ?>">
    <link rel="stylesheet" href="<?= baseUrl('assets/css/users.css') ?>">
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
                        <a class="nav-link active" href="<?= baseUrl('users') ?>">
                            <i class="fas fa-users me-1"></i>Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= baseUrl('campaigns') ?>">
                            <i class="fas fa-bullhorn me-1"></i>Campañas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= baseUrl('reports') ?>">
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
                            <i class="fas fa-users me-2"></i>Gestión de Usuarios
                        </h1>
                        <p class="text-muted mb-0">Administra los usuarios del sistema</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                            <i class="fas fa-plus me-2"></i>Nuevo Usuario
                        </button>
                        <a href="<?= baseUrl('/') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Volver al Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de usuarios -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Lista de Usuarios
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="usersTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Nombre Completo</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th>Estado</th>
                                        <th>Fecha Creación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $userItem): ?>
                                    <tr>
                                        <td><?= $userItem['id'] ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($userItem['username'] ?? 'Sin usuario') ?></strong>
                                        </td>
                                        <td><?= htmlspecialchars($userItem['full_name'] ?? 'Sin nombre') ?></td>
                                        <td><?= htmlspecialchars($userItem['email'] ?? 'Sin email') ?></td>
                                        <td>
                                            <span class="badge bg-info"><?= htmlspecialchars($userItem['role_name'] ?? 'Sin rol') ?></span>
                                        </td>
                                        <td>
                                            <?php if ($userItem['is_active']): ?>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>Activo
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times-circle me-1"></i>Inactivo
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $userItem['created_at'] ? date('d/m/Y', strtotime($userItem['created_at'])) : 'Sin fecha' ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        onclick="editUser(<?= htmlspecialchars(json_encode($userItem)) ?>)"
                                                        title="Editar usuario">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <?php if ($userItem['id'] != $user['id']): ?>
                                                <button type="button" class="btn btn-sm <?= $userItem['is_active'] ? 'btn-outline-warning' : 'btn-outline-success' ?>" 
                                                        onclick="toggleUser(<?= $userItem['id'] ?>, '<?= htmlspecialchars($userItem['username']) ?>', <?= $userItem['is_active'] ? 'false' : 'true' ?>)"
                                                        title="<?= $userItem['is_active'] ? 'Deshabilitar usuario' : 'Habilitar usuario' ?>">
                                                    <i class="fas <?= $userItem['is_active'] ? 'fa-user-slash' : 'fa-user-check' ?>"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="deleteUser(<?= $userItem['id'] ?>, '<?= htmlspecialchars($userItem['username']) ?>')"
                                                        title="Eliminar usuario">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <?php endif; ?>
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

    <!-- Modal Crear Usuario -->
    <div class="modal fade" id="createUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus me-2"></i>Nuevo Usuario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?= baseUrl('users/create') ?>">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="create_role_id" class="form-label">Rol</label>
                                <select class="form-select" id="create_role_id" name="role_id" required>
                                    <option value="">Seleccionar rol</option>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?= $role['id'] ?>"><?= htmlspecialchars($role['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="create_username" class="form-label">Nombre de Usuario</label>
                                <input type="text" class="form-control" id="create_username" name="username" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="create_full_name" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="create_full_name" name="full_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="create_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="create_email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="create_password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="create_password" name="password" required>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="create_is_active" name="is_active" checked>
                            <label class="form-check-label" for="create_is_active">
                                Usuario activo
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Crear Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Usuario -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-edit me-2"></i>Editar Usuario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?= baseUrl('users/update') ?>">
                    <input type="hidden" id="edit_user_id" name="user_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_role_id" class="form-label">Rol</label>
                                <select class="form-select" id="edit_role_id" name="role_id" required>
                                    <option value="">Seleccionar rol</option>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?= $role['id'] ?>"><?= htmlspecialchars($role['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_username" class="form-label">Nombre de Usuario</label>
                                <input type="text" class="form-control" id="edit_username" name="username" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_full_name" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="edit_full_name" name="full_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active">
                            <label class="form-check-label" for="edit_is_active">
                                Usuario activo
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar Usuario -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?= baseUrl('users/delete') ?>">
                    <input type="hidden" id="delete_user_id" name="user_id">
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar el usuario <strong id="delete_username"></strong>?</p>
                        <p class="text-danger"><small>Esta acción no se puede deshacer.</small></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Eliminar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Habilitar/Deshabilitar Usuario -->
    <div class="modal fade" id="toggleUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-cog me-2"></i>Confirmar Cambio de Estado
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?= baseUrl('users/toggle') ?>">
                    <input type="hidden" id="toggle_user_id" name="user_id">
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas <span id="toggle_action"></span> el usuario <strong id="toggle_username"></strong>?</p>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <span id="toggle_message"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn" id="toggle_confirm_btn">
                            <i class="fas fa-check me-2"></i><span id="toggle_confirm_text"></span>
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
            $('#usersTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                pageLength: 25,
                order: [[0, 'desc']]
            });
        });

        // Función para editar usuario
        function editUser(user) {
            document.getElementById('edit_user_id').value = user.id;
            document.getElementById('edit_role_id').value = user.role_id;
            document.getElementById('edit_username').value = user.username;
            document.getElementById('edit_full_name').value = user.full_name;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('edit_is_active').checked = user.is_active == 1;
            
            new bootstrap.Modal(document.getElementById('editUserModal')).show();
        }

        // Función para eliminar usuario
        function deleteUser(userId, username) {
            document.getElementById('delete_user_id').value = userId;
            document.getElementById('delete_username').textContent = username;
            
            new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
        }

        // Función para habilitar/deshabilitar usuario
        function toggleUser(userId, username, willBeActive) {
            document.getElementById('toggle_user_id').value = userId;
            document.getElementById('toggle_username').textContent = username;
            
            const actionText = willBeActive ? 'habilitar' : 'deshabilitar';
            const message = willBeActive ? 
                'El usuario podrá acceder al sistema y realizar todas sus funciones.' : 
                'El usuario no podrá acceder al sistema hasta que sea habilitado nuevamente.';
            const confirmText = willBeActive ? 'Habilitar Usuario' : 'Deshabilitar Usuario';
            const confirmClass = willBeActive ? 'btn-success' : 'btn-warning';
            
            document.getElementById('toggle_action').textContent = actionText;
            document.getElementById('toggle_message').textContent = message;
            document.getElementById('toggle_confirm_text').textContent = confirmText;
            
            const confirmBtn = document.getElementById('toggle_confirm_btn');
            confirmBtn.className = 'btn ' + confirmClass;
            
            new bootstrap.Modal(document.getElementById('toggleUserModal')).show();
        }
    </script>
</body>
</html>
