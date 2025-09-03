<?php
/**
 * Controlador del Dashboard Principal
 * CRM de Cobranzas - Dashboard y estadísticas
 */

require_once 'controllers/Controller.php';
require_once 'models/UserModel.php';

class HomeController extends Controller {
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new UserModel();
    }
    
    /**
     * Dashboard principal
     */
    public function dashboard() {
        $this->requireAuth();
        
        $title = 'Dashboard';
        $user = $this->user;
        
        // Obtener estadísticas según el rol
        $stats = $this->getDashboardStats();
        
        // Obtener datos para gráficos
        $chartData = $this->getChartData();
        
        // Determinar qué dashboard usar según el rol
        $roleId = $user['user_role_id'] ?? 0;
        $dashboardView = $this->getDashboardView($roleId);
        
        $this->render($dashboardView, compact('title', 'user', 'stats', 'chartData'));
    }
    
    /**
     * Obtener la vista del dashboard según el rol
     */
    private function getDashboardView($roleId) {
        switch ($roleId) {
            case 1: // Super Administrador
                return 'home/superAdmin_dashboard';
            case 2: // Administrador
                return 'home/admin_dashboard';
            case 3: // Coordinador
                return 'home/coordinator_dashboard';
            case 4: // Asesor
                return 'home/advisor_dashboard';
            default:
                return 'home/dashboard'; // Dashboard genérico como fallback
        }
    }
    
    /**
     * Obtener estadísticas del dashboard
     */
    private function getDashboardStats() {
        $roleId = $this->user['user_role_id'];
        $stats = [];
        
        try {
            switch ($roleId) {
                case 1: // Super Administrador
                    $stats = $this->getSuperAdminStats();
                    break;
                case 2: // Administrador
                    $stats = $this->getAdminStats();
                    break;
                case 3: // Coordinador
                    $stats = $this->getCoordinatorStats();
                    break;
                case 4: // Asesor
                    $stats = $this->getAdvisorStats();
                    break;
                default:
                    $stats = $this->getDefaultStats();
            }
        } catch (Exception $e) {
            // En caso de error, mostrar estadísticas básicas
            $stats = $this->getDefaultStats();
        }
        
        return $stats;
    }
    
    /**
     * Estadísticas para Super Administrador
     */
    private function getSuperAdminStats() {
        $db = Database::getInstance();
        
        try {
            return [
                'total_users' => $db->fetch("SELECT COUNT(*) as count FROM users WHERE is_active = 1")['count'] ?? 0,
                'active_users' => $db->fetch("SELECT COUNT(*) as count FROM users WHERE is_active = 1")['count'] ?? 0,
                'total_roles' => $db->fetch("SELECT COUNT(*) as count FROM roles")['count'] ?? 0,
                'active_campaigns' => $this->getTableCount('campaigns', 'is_active = 1'),
                'total_debtors' => $this->getTableCount('debtors'),
                'total_accounts' => $this->getTableCount('accounts'),
                'total_interactions' => $this->getTableCount('interactions'),
                'pending_promises' => $this->getTableCount('payment_promises', "status = 'pending'"),
                'completed_payments' => $this->getTableCount('payments', "status = 'completed'")
            ];
        } catch (Exception $e) {
            // Si hay error, devolver valores por defecto
            return [
                'total_users' => 0,
                'active_users' => 0,
                'total_roles' => 0,
                'active_campaigns' => 0,
                'total_debtors' => 0,
                'total_accounts' => 0,
                'total_interactions' => 0,
                'pending_promises' => 0,
                'completed_payments' => 0
            ];
        }
    }
    
    /**
     * Obtener conteo de tabla con condición opcional
     */
    private function getTableCount($table, $condition = null) {
        try {
            $db = Database::getInstance();
            $sql = "SELECT COUNT(*) as count FROM {$table}";
            if ($condition) {
                $sql .= " WHERE {$condition}";
            }
            $result = $db->fetch($sql);
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Estadísticas para Administrador
     */
    private function getAdminStats() {
        $db = Database::getInstance();
        
        return [
            'total_campaigns' => $db->fetch("SELECT COUNT(*) as count FROM campaigns")['count'],
            'active_campaigns' => $db->fetch("SELECT COUNT(*) as count FROM campaigns WHERE is_active = 1")['count'],
            'total_coordinators' => $db->fetch("SELECT COUNT(*) as count FROM users WHERE role_id = 3 AND is_active = 1")['count'],
            'total_advisors' => $db->fetch("SELECT COUNT(*) as count FROM users WHERE role_id = 4 AND is_active = 1")['count'],
            'total_debtors' => $db->fetch("SELECT COUNT(*) as count FROM debtors")['count'],
            'total_accounts' => $db->fetch("SELECT COUNT(*) as count FROM accounts")['count'],
            'total_interactions' => $db->fetch("SELECT COUNT(*) as count FROM interactions")['count'],
            'recovery_rate' => $this->calculateRecoveryRate()
        ];
    }
    
    /**
     * Estadísticas para Coordinador
     */
    private function getCoordinatorStats() {
        $db = Database::getInstance();
        $userId = $this->user['id'] ?? 0;
        
        try {
            return [
                'my_advisors' => $this->getTableCount('users', "role_id = 4 AND coordinator_id = {$userId} AND is_active = 1"),
                'assigned_accounts' => $this->getTableCount('accounts', "assigned_to IN (SELECT id FROM users WHERE coordinator_id = {$userId})"),
                'total_interactions' => $this->getTableCount('interactions', "user_id IN (SELECT id FROM users WHERE coordinator_id = {$userId})"),
                'team_performance' => 75.5, // Ejemplo
                'today_interactions' => $this->getTableCount('interactions', "DATE(created_at) = CURDATE() AND user_id IN (SELECT id FROM users WHERE coordinator_id = {$userId})"),
                'overdue_accounts' => $this->getTableCount('accounts', "due_date < CURDATE() AND status = 'active' AND assigned_to IN (SELECT id FROM users WHERE coordinator_id = {$userId})")
            ];
        } catch (Exception $e) {
            return [
                'my_advisors' => 0,
                'assigned_accounts' => 0,
                'total_interactions' => 0,
                'team_performance' => 0,
                'today_interactions' => 0,
                'overdue_accounts' => 0
            ];
        }
    }
    
    /**
     * Estadísticas para Asesor
     */
    private function getAdvisorStats() {
        $db = Database::getInstance();
        $userId = $this->user['id'];
        
        return [
            'my_accounts' => $db->fetch("SELECT COUNT(*) as count FROM accounts")['count'],
            'my_interactions' => $db->fetch("SELECT COUNT(*) as count FROM interactions WHERE user_id = ?", [$userId])['count'],
            'pending_promises' => $db->fetch("SELECT COUNT(*) as count FROM payment_promises WHERE status = 'pending'")['count'],
            'completed_payments' => $db->fetch("SELECT COUNT(*) as count FROM payments WHERE status = 'completed'")['count'],
            'my_performance' => $this->calculateMyPerformance($userId)
        ];
    }
    
    /**
     * Estadísticas por defecto
     */
    private function getDefaultStats() {
        return [
            'total_users' => 0,
            'total_campaigns' => 0,
            'total_debtors' => 0,
            'total_accounts' => 0,
            'total_interactions' => 0,
            'pending_promises' => 0,
            'completed_payments' => 0
        ];
    }
    
    /**
     * Obtener datos para gráficos
     */
    private function getChartData() {
        $db = Database::getInstance();
        
        try {
            // Datos de interacciones por día (últimos 7 días)
            $interactionsData = $db->fetchAll("
                SELECT DATE(created_at) as date, COUNT(*) as count 
                FROM interactions 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                GROUP BY DATE(created_at)
                ORDER BY date
            ");
            
            // Datos de pagos por mes (últimos 6 meses)
            $paymentsData = $db->fetchAll("
                SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
                FROM payments 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month
            ");
            
            return [
                'interactions' => $interactionsData,
                'payments' => $paymentsData
            ];
        } catch (Exception $e) {
            return [
                'interactions' => [],
                'payments' => []
            ];
        }
    }
    
    /**
     * Calcular tasa de recuperación
     */
    private function calculateRecoveryRate() {
        try {
            $db = Database::getInstance();
            $totalAmount = $db->fetch("SELECT SUM(amount) as total FROM accounts")['total'] ?? 0;
            $recoveredAmount = $db->fetch("SELECT SUM(amount) as total FROM payments WHERE status = 'completed'")['total'] ?? 0;
            
            if ($totalAmount > 0) {
                return round(($recoveredAmount / $totalAmount) * 100, 2);
            }
            
            return 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Calcular rendimiento del equipo
     */
    private function calculateTeamPerformance() {
        // Implementar lógica de rendimiento del equipo
        return 85.5; // Valor de ejemplo
    }
    
    /**
     * Calcular mi rendimiento
     */
    private function calculateMyPerformance($userId) {
        // Implementar lógica de rendimiento personal
        return 92.3; // Valor de ejemplo
    }
}