<?php
/**
 * Controlador de Gestión de Usuarios
 * CRM de Cobranzas - CRUD de usuarios
 */

require_once 'controllers/Controller.php';
require_once 'models/UserModel.php';

class UserController extends Controller {
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new UserModel();
    }
    
    /**
     * Listar usuarios
     */
    public function index() {
        $this->requireAuth();
        
        // Verificar permisos (solo Super Admin y Admin pueden ver usuarios)
        if (!isAdmin()) {
            $this->render('errors/403');
            return;
        }
        
        $title = 'Gestión de Usuarios';
        $user = $this->user;
        
        // Obtener usuarios
        $users = $this->userModel->getUsersWithRoles();
        
        // Obtener roles para el formulario
        $roles = $this->getRoles();
        
        $this->render('users/index', compact('title', 'user', 'users', 'roles'));
    }
    
    /**
     * Crear nuevo usuario
     */
    public function create() {
        $this->requireAuth();
        
        if (!isAdmin()) {
            $this->render('errors/403');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('users');
        }
        
        // Obtener datos del formulario
        $roleId = $this->getPost('role_id');
        $username = $this->getPost('username');
        $fullName = $this->getPost('full_name');
        $email = $this->getPost('email');
        $password = $this->getPost('password');
        $isActive = $this->getPost('is_active') === 'on';
        
        // Validaciones
        $errors = [];
        
        if (empty($roleId)) {
            $errors[] = 'El rol es obligatorio';
        }
        
        if (empty($username)) {
            $errors[] = 'El nombre de usuario es obligatorio';
        } elseif ($this->userModel->usernameExists($username)) {
            $errors[] = 'El nombre de usuario ya está en uso';
        }
        
        if (empty($fullName)) {
            $errors[] = 'El nombre completo es obligatorio';
        }
        
        if (empty($email)) {
            $errors[] = 'El email es obligatorio';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El formato del email no es válido';
        } elseif ($this->userModel->emailExists($email)) {
            $errors[] = 'El email ya está en uso';
        }
        
        if (empty($password)) {
            $errors[] = 'La contraseña es obligatoria';
        } elseif (strlen($password) < 6) {
            $errors[] = 'La contraseña debe tener al menos 6 caracteres';
        }
        
        // Si hay errores, redirigir con mensaje
        if (!empty($errors)) {
            $this->redirectWithMessage('users', implode(', ', $errors), 'error');
            return;
        }
        
        // Crear usuario
        $data = [
            'role_id' => $roleId,
            'username' => $username,
            'full_name' => $fullName,
            'email' => $email,
            'password' => $password,
            'is_active' => $isActive
        ];
        
        if ($this->userModel->createUser($data)) {
            $this->redirectWithMessage('users', 'Usuario creado exitosamente');
        } else {
            $this->redirectWithMessage('users', 'Error al crear el usuario', 'error');
        }
    }
    
    /**
     * Actualizar usuario
     */
    public function update() {
        $this->requireAuth();
        
        if (!isAdmin()) {
            $this->render('errors/403');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('users');
        }
        
        $userId = $this->getPost('user_id');
        $roleId = $this->getPost('role_id');
        $username = $this->getPost('username');
        $fullName = $this->getPost('full_name');
        $email = $this->getPost('email');
        $isActive = $this->getPost('is_active') === 'on';
        
        // Validaciones
        $errors = [];
        
        if (empty($userId)) {
            $errors[] = 'ID de usuario requerido';
        }
        
        if (empty($roleId)) {
            $errors[] = 'El rol es obligatorio';
        }
        
        if (empty($username)) {
            $errors[] = 'El nombre de usuario es obligatorio';
        } elseif ($this->userModel->usernameExists($username, $userId)) {
            $errors[] = 'El nombre de usuario ya está en uso';
        }
        
        if (empty($fullName)) {
            $errors[] = 'El nombre completo es obligatorio';
        }
        
        if (empty($email)) {
            $errors[] = 'El email es obligatorio';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El formato del email no es válido';
        } elseif ($this->userModel->emailExists($email, $userId)) {
            $errors[] = 'El email ya está en uso';
        }
        
        // Si hay errores, redirigir con mensaje
        if (!empty($errors)) {
            $this->redirectWithMessage('users', implode(', ', $errors), 'error');
            return;
        }
        
        // Actualizar usuario
        $data = [
            'role_id' => $roleId,
            'username' => $username,
            'full_name' => $fullName,
            'email' => $email,
            'is_active' => $isActive
        ];
        
        if ($this->userModel->updateUser($userId, $data)) {
            $this->redirectWithMessage('users', 'Usuario actualizado exitosamente');
        } else {
            $this->redirectWithMessage('users', 'Error al actualizar el usuario', 'error');
        }
    }
    
    /**
     * Eliminar usuario
     */
    public function delete() {
        $this->requireAuth();
        
        if (!isAdmin()) {
            $this->render('errors/403');
            return;
        }
        
        $userId = $this->getPost('user_id');
        
        if (empty($userId)) {
            $this->redirectWithMessage('users', 'ID de usuario requerido', 'error');
            return;
        }
        
        // No permitir eliminar el propio usuario
        if ($userId == $this->user['id']) {
            $this->redirectWithMessage('users', 'No puedes eliminar tu propio usuario', 'error');
            return;
        }
        
        if ($this->userModel->deleteUser($userId)) {
            $this->redirectWithMessage('users', 'Usuario eliminado exitosamente');
        } else {
            $this->redirectWithMessage('users', 'Error al eliminar el usuario', 'error');
        }
    }
    
    /**
     * Habilitar/Deshabilitar usuario
     */
    public function toggle() {
        $this->requireAuth();
        
        if (!isAdmin()) {
            $this->render('errors/403');
            return;
        }
        
        $userId = $this->getPost('user_id');
        
        if (empty($userId)) {
            $this->redirectWithMessage('users', 'ID de usuario requerido', 'error');
            return;
        }
        
        // No permitir deshabilitar el propio usuario
        if ($userId == $this->user['id']) {
            $this->redirectWithMessage('users', 'No puedes deshabilitar tu propio usuario', 'error');
            return;
        }
        
        if ($this->userModel->toggleUserStatus($userId)) {
            $this->redirectWithMessage('users', 'Estado del usuario actualizado exitosamente');
        } else {
            $this->redirectWithMessage('users', 'Error al actualizar el estado del usuario', 'error');
        }
    }
    
    /**
     * Obtener roles
     */
    private function getRoles() {
        try {
            return $this->db->fetchAll("SELECT * FROM roles ORDER BY name");
        } catch (Exception $e) {
            return [];
        }
    }
}
