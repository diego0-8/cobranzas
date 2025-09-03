<?php
/**
 * Controlador Base
 * CRM de Cobranzas - Clase base para todos los controladores
 */

require_once 'config/config.php';
require_once 'config/database.php';

abstract class Controller {
    protected $db;
    protected $user;
    
    public function __construct() {
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_name(SESSION_NAME);
            session_start();
        }
        
        // Obtener instancia de base de datos
        $this->db = Database::getInstance();
        
        // Obtener información del usuario si está autenticado
        if (isAuthenticated()) {
            $this->user = [
                'id' => $_SESSION['user_id'],
                'user_name' => $_SESSION['user_name'],
                'user_role' => $_SESSION['user_role'],
                'user_role_id' => $_SESSION['user_role_id'],
                'user_email' => $_SESSION['user_email']
            ];
        }
    }
    
    /**
     * Renderizar vista
     */
    protected function render($view, $data = []) {
        // Extraer datos para que estén disponibles en la vista
        extract($data);
        
        // Incluir la vista
        $viewPath = "views/{$view}.php";
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            throw new Exception("Vista no encontrada: {$view}");
        }
    }
    
    /**
     * Renderizar JSON
     */
    protected function renderJson($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    
    /**
     * Verificar autenticación
     */
    protected function requireAuth() {
        if (!isAuthenticated()) {
            redirect('auth/login');
        }
    }
    
    /**
     * Verificar rol específico
     */
    protected function requireRole($role) {
        $this->requireAuth();
        if (!hasRole($role)) {
            $this->render('errors/403');
            exit();
        }
    }
    
    /**
     * Verificar permisos de administrador
     */
    protected function requireAdmin() {
        $this->requireAuth();
        if (!isAdmin()) {
            $this->render('errors/403');
            exit();
        }
    }
    
    /**
     * Verificar permisos de coordinador
     */
    protected function requireCoordinator() {
        $this->requireAuth();
        if (!isCoordinator() && !isAdmin()) {
            $this->render('errors/403');
            exit();
        }
    }
    
    /**
     * Obtener parámetros POST
     */
    protected function getPost($key, $default = null) {
        return isset($_POST[$key]) ? sanitize($_POST[$key]) : $default;
    }
    
    /**
     * Obtener parámetros GET
     */
    protected function getGet($key, $default = null) {
        return isset($_GET[$key]) ? sanitize($_GET[$key]) : $default;
    }
    
    /**
     * Validar token CSRF
     */
    protected function validateCSRF() {
        $token = $this->getPost('csrf_token');
        if (!$token || !verifyCSRFToken($token)) {
            $this->renderJson(['error' => 'Token CSRF inválido']);
            exit();
        }
    }
    
    /**
     * Redireccionar con mensaje
     */
    protected function redirectWithMessage($path, $message, $type = 'success') {
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = $type;
        redirect($path);
    }

    /**
     * Redirigir a una URL
     */
    protected function redirect($url) {
        header('Location: ' . baseUrl($url));
        exit;
    }
    
    /**
     * Obtener mensaje de sesión
     */
    protected function getSessionMessage() {
        $message = null;
        $type = 'success';
        
        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            $type = $_SESSION['message_type'] ?? 'success';
            unset($_SESSION['message'], $_SESSION['message_type']);
        }
        
        return ['message' => $message, 'type' => $type];
    }
}
