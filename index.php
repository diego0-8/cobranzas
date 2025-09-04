<?php
/**
 * Punto de entrada principal del sistema
 * CRM de Cobranzas - Sin dependencia de .htaccess
 */

// Configuración específica para XAMPP
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir configuración principal
require_once 'config/config.php';

// Función para obtener la ruta actual
function getCurrentRoute() {
    $request_uri = $_SERVER['REQUEST_URI'];
    $script_name = $_SERVER['SCRIPT_NAME'];
    
    // Remover el directorio base
    $base_path = dirname($script_name);
    if ($base_path !== '/') {
        $request_uri = substr($request_uri, strlen($base_path));
    }
    
    // Limpiar la ruta
    $route = parse_url($request_uri, PHP_URL_PATH);
    $route = trim($route, '/');
    
    // Si la ruta está vacía, usar 'home'
    if (empty($route)) {
        $route = 'home';
    }
    
    return $route;
}

// Función para manejar el routing
function handleRouting($route) {
    // Mapeo de rutas a controladores
    $routes = [
        'home' => ['HomeController', 'dashboard'],
        'login' => ['AuthController', 'login'],
        'auth/login' => ['AuthController', 'login'],
        'logout' => ['AuthController', 'logout'],
        'auth/logout' => ['AuthController', 'logout'],
        'authenticate' => ['AuthController', 'authenticate'],
        'auth/authenticate' => ['AuthController', 'authenticate'],
        'profile' => ['AuthController', 'profile'],
        'auth/profile' => ['AuthController', 'profile'],
        'update-profile' => ['AuthController', 'updateProfile'],
        'auth/update-profile' => ['AuthController', 'updateProfile'],
        'users' => ['UserController', 'index'],
        'users/create' => ['UserController', 'create'],
        'users/update' => ['UserController', 'update'],
        'users/delete' => ['UserController', 'delete'],
        'users/toggle' => ['UserController', 'toggle'],
        'campaigns' => ['CampaignController', 'index'],
        'campaigns/create' => ['CampaignController', 'create'],
        'campaigns/update' => ['CampaignController', 'update'],
        'campaigns/delete' => ['CampaignController', 'delete'],
        'reports' => ['ReportController', 'index'],
        'reports/users' => ['ReportController', 'users'],
        'reports/campaigns' => ['ReportController', 'campaigns'],
        'reports/collections' => ['ReportController', 'collections'],
        'reports/export' => ['ReportController', 'export'],
        
        // Rutas de roles
        'roles' => ['RoleController', 'index'],
        'roles/create' => ['RoleController', 'create'],
        'roles/update' => ['RoleController', 'update'],
        'roles/delete' => ['RoleController', 'delete'],
        
            // Rutas de tipificaciones
    'typifications' => ['TypificationController', 'index'],
    'typifications/create' => ['TypificationController', 'create'],
    'typifications/update' => ['TypificationController', 'update'],
    'typifications/delete' => ['TypificationController', 'delete'],
    'typifications/toggle' => ['TypificationController', 'toggle'],
    
    // Rutas del coordinador
    'coordinator/my-advisors' => ['CoordinatorController', 'myAdvisors'],
    'coordinator/assigned-accounts' => ['CoordinatorController', 'assignedAccounts'],
    'coordinator/team-interactions' => ['CoordinatorController', 'teamInteractions'],
    'coordinator/team-reports' => ['CoordinatorController', 'teamReports'],
    'coordinator/assign-accounts' => ['CoordinatorController', 'assignAccounts'],
    'coordinator/reassign-account' => ['CoordinatorController', 'reassignAccount'],
    'coordinator/export-performance-report' => ['CoordinatorController', 'exportPerformanceReport'],
    'coordinator/export-collections-report' => ['CoordinatorController', 'exportCollectionsReport'],
    'coordinator/export-interactions-report' => ['CoordinatorController', 'exportInteractionsReport'],
    
    // Rutas del asesor
    'advisor/my-accounts' => ['AdvisorController', 'myAccounts'],
    'advisor/my-interactions' => ['AdvisorController', 'myInteractions'],
    'advisor/new-interaction' => ['AdvisorController', 'newInteraction'],
    'advisor/payment-promises' => ['AdvisorController', 'paymentPromises'],
    'advisor/my-reports' => ['AdvisorController', 'myReports'],
    'advisor/export-my-reports' => ['AdvisorController', 'exportMyReports'],
        
        'debtors' => ['DebtorController', 'index'],
        'accounts' => ['AccountController', 'index'],
        'interactions' => ['InteractionController', 'index']
    ];
    
    // Verificar si la ruta existe
    if (isset($routes[$route])) {
        $controller_name = $routes[$route][0];
        $method_name = $routes[$route][1];
        
        // Cargar el controlador
        $controller_file = "controllers/{$controller_name}.php";
        if (file_exists($controller_file)) {
            require_once $controller_file;
            $controller = new $controller_name();
            
            if (method_exists($controller, $method_name)) {
                return $controller->$method_name();
            }
        }
    }
    
    // Si no se encuentra la ruta, mostrar error 404
    http_response_code(404);
    include 'views/errors/404.php';
}

// Función para verificar si es un archivo estático
function isStaticFile($route) {
    $static_extensions = ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'ico', 'pdf'];
    $extension = pathinfo($route, PATHINFO_EXTENSION);
    return in_array(strtolower($extension), $static_extensions);
}

// Función para servir archivos estáticos
function serveStaticFile($route) {
    $file_path = $route;
    
    // Mapear rutas de assets
    if (strpos($route, 'assets/') === 0) {
        $file_path = $route;
    } elseif (strpos($route, 'css/') === 0) {
        $file_path = 'assets/' . $route;
    } elseif (strpos($route, 'js/') === 0) {
        $file_path = 'assets/' . $route;
    }
    
    if (file_exists($file_path)) {
        $extension = pathinfo($file_path, PATHINFO_EXTENSION);
        $mime_types = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon',
            'pdf' => 'application/pdf'
        ];
        
        if (isset($mime_types[$extension])) {
            header("Content-Type: {$mime_types[$extension]}");
        }
        
        readfile($file_path);
        return true;
    }
    
    return false;
}

// Obtener la ruta actual
$current_route = getCurrentRoute();

// Verificar si es un archivo estático
if (isStaticFile($current_route)) {
    if (serveStaticFile($current_route)) {
        exit;
    }
}

// Manejar el routing
handleRouting($current_route);
?>
