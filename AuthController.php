<?php
/**
 * Controlador de Autenticación
 * CRM de Cobranzas - Login, logout y gestión de sesiones
 */

require_once 'controllers/Controller.php';
require_once 'models/UserModel.php';

class AuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new UserModel();
    }
    
    /**
     * Mostrar formulario de login
     */
    public function login() {
        // Si ya está autenticado, redirigir al dashboard
        if (isAuthenticated()) {
            redirect('/');
        }
        
        // Obtener mensajes de error de la sesión
        $errors = [];
        $username = '';
        
        if (isset($_SESSION['login_error'])) {
            $errors[] = $_SESSION['login_error'];
            unset($_SESSION['login_error']);
        }
        
        if (isset($_SESSION['login_username'])) {
            $username = $_SESSION['login_username'];
            unset($_SESSION['login_username']);
        }
        
        $title = 'Iniciar Sesión';
        $this->render('auth/login', compact('title', 'errors', 'username'));
    }
    
    /**
     * Procesar autenticación
     */
    public function authenticate() {
        // Validar método POST
        if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('login');
        }
        
        // Obtener datos del formulario
        $username = $this->getPost('username');
        $password = $this->getPost('password');
        $remember = $this->getPost('remember') === 'on';
        
        // Validaciones básicas
        $errors = [];
        
        if (empty($username)) {
            $errors[] = 'El nombre de usuario es obligatorio';
        }
        
        if (empty($password)) {
            $errors[] = 'La contraseña es obligatoria';
        }
        
        // Si hay errores, mostrar formulario con errores
        if (!empty($errors)) {
            $title = 'Iniciar Sesión';
            $this->render('auth/login', compact('title', 'errors', 'username'));
            return;
        }
        
        // Verificar si el usuario existe
        $userExists = $this->userModel->findByUsername($username);
        
        if (!$userExists) {
            // Usuario no encontrado - usar mensaje de sesión
            $_SESSION['login_error'] = 'Usuario no encontrado';
            $_SESSION['login_username'] = $username;
            redirect('login');
            return;
        }
        
        // Intentar autenticar
        $user = $this->userModel->authenticate($username, $password);
        
        if ($user) {
            // Crear sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role_name'];
            $_SESSION['user_role_id'] = $user['role_id'];
            
            // Configurar tiempo de sesión
            if ($remember) {
                ini_set('session.gc_maxlifetime', 86400); // 24 horas
                session_set_cookie_params(86400);
            }
            
            // Generar token CSRF
            generateCSRFToken();
            
            // Redirigir según el rol
            $this->redirectWithMessage('/', '¡Bienvenido ' . $user['full_name'] . '!');
        } else {
            // Contraseña incorrecta - usar mensaje de sesión
            $_SESSION['login_error'] = 'Contraseña incorrecta';
            $_SESSION['login_username'] = $username;
            redirect('login');
        }
    }
    
    /**
     * Cerrar sesión
     */
    public function logout() {
        // Destruir sesión
        session_destroy();
        
        // Redirigir al login
        redirect('login');
    }
    
    /**
     * Mostrar página de acceso denegado
     */
    public function accessDenied() {
        $title = 'Acceso Denegado';
        $this->render('auth/access-denied', compact('title'));
    }
    
    /**
     * Mostrar página de perfil de usuario
     */
    public function profile() {
        $this->requireAuth();
        
        $title = 'Mi Perfil';
        $user = $this->userModel->getById($this->user['id']);
        
        $this->render('auth/profile', compact('title', 'user'));
    }
    
    /**
     * Actualizar perfil de usuario
     */
    public function updateProfile() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('profile');
        }
        
        // Obtener datos del formulario
        $fullName = $this->getPost('full_name');
        $username = $this->getPost('username');
        $email = $this->getPost('email');
        $currentPassword = $this->getPost('current_password');
        $newPassword = $this->getPost('new_password');
        $confirmPassword = $this->getPost('confirm_password');
        
        // Validaciones
        $errors = [];
        
        if (empty($fullName)) {
            $errors[] = 'El nombre completo es obligatorio';
        }
        
        if (empty($username)) {
            $errors[] = 'El nombre de usuario es obligatorio';
        }
        
        if (empty($email)) {
            $errors[] = 'El email es obligatorio';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El formato del email no es válido';
        }
        
        // Verificar si el username ya existe (excluyendo el usuario actual)
        if ($this->userModel->usernameExists($username, $this->user['id'])) {
            $errors[] = 'El nombre de usuario ya está en uso';
        }
        
        // Si se quiere cambiar la contraseña
        if (!empty($newPassword)) {
            if (empty($currentPassword)) {
                $errors[] = 'Debe ingresar su contraseña actual';
            } else {
                // Verificar contraseña actual
                $currentUser = $this->userModel->getById($this->user['id']);
                if (!password_verify($currentPassword, $currentUser['password_hash'])) {
                    $errors[] = 'La contraseña actual es incorrecta';
                }
            }
            
            if (strlen($newPassword) < 6) {
                $errors[] = 'La nueva contraseña debe tener al menos 6 caracteres';
            }
            
            if ($newPassword !== $confirmPassword) {
                $errors[] = 'Las contraseñas no coinciden';
            }
        }
        
        // Si hay errores, mostrar formulario con errores
        if (!empty($errors)) {
            $title = 'Mi Perfil';
            $user = $this->userModel->getById($this->user['id']);
            $this->render('auth/profile', compact('title', 'user', 'errors'));
            return;
        }
        
        // Actualizar datos
        $data = [
            'full_name' => $fullName,
            'username' => $username,
            'email' => $email
        ];
        
        if (!empty($newPassword)) {
            $data['password'] = $newPassword;
        }
        
        $this->userModel->updateUser($this->user['id'], $data);
        
        // Actualizar sesión
        $_SESSION['user_name'] = $fullName;
        $_SESSION['user_email'] = $email;
        
        $this->redirectWithMessage('profile', 'Perfil actualizado correctamente');
    }
}
