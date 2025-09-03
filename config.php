<?php
/**
 * Configuración General del Sistema
 * CRM de Cobranzas - Configuración de la aplicación
 */

// Configuración de la aplicación
define('APP_NAME', 'CRM de Cobranzas');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/cobranzas_apex1/');

// Configuración de sesión
define('SESSION_NAME', 'cobranzas_session');
define('SESSION_LIFETIME', 3600); // 1 hora

// Configuración de roles
define('ROLE_SUPER_ADMIN', 1);
define('ROLE_ADMIN', 2);
define('ROLE_COORDINADOR', 3);
define('ROLE_ASESOR', 4);

// Configuración de archivos
define('UPLOAD_PATH', 'uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Configuración de paginación
define('ITEMS_PER_PAGE', 20);

// Configuración de timezone
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Configuración de errores (desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Función para obtener la URL base
function baseUrl($path = '') {
    return APP_URL . ltrim($path, '/');
}

// Función para redireccionar
function redirect($path) {
    header('Location: ' . baseUrl($path));
    exit();
}

// Función para verificar si el usuario está autenticado
function isAuthenticated() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Función para verificar el rol del usuario
function hasRole($role) {
    return isset($_SESSION['user_role_id']) && $_SESSION['user_role_id'] == $role;
}

// Función para verificar si tiene permisos de administrador
function isAdmin() {
    return hasRole(ROLE_SUPER_ADMIN) || hasRole(ROLE_ADMIN);
}

// Función para verificar si es coordinador
function isCoordinator() {
    return hasRole(ROLE_COORDINADOR);
}

// Función para verificar si es asesor
function isAdvisor() {
    return hasRole(ROLE_ASESOR);
}

// Función para sanitizar entrada
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Función para generar token CSRF
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Función para verificar token CSRF
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
