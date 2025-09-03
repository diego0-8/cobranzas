<?php
/**
 * Controlador del Coordinador
 * CRM de Cobranzas - Funcionalidades específicas del coordinador
 */

require_once 'controllers/Controller.php';
require_once 'models/UserModel.php';
require_once 'models/AccountModel.php';
require_once 'models/InteractionModel.php';

class CoordinatorController extends Controller {
    private $userModel;
    private $accountModel;
    private $interactionModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new UserModel();
        $this->accountModel = new AccountModel();
        $this->interactionModel = new InteractionModel();
    }
    
    /**
     * Listar mis asesores
     */
    public function myAdvisors() {
        $this->requireAuth();
        if (!isCoordinator()) {
            $this->render('errors/403');
            return;
        }
        
        $title = 'Mis Asesores';
        $user = $this->user;
        
        // Obtener asesores asignados al coordinador
        $advisors = $this->userModel->getAdvisorsByCoordinator($user['id']);
        
        $this->render('coordinator/my-advisors', compact('title', 'user', 'advisors'));
    }
    
    /**
     * Ver cuentas asignadas
     */
    public function assignedAccounts() {
        $this->requireAuth();
        if (!isCoordinator()) {
            $this->render('errors/403');
            return;
        }
        
        $title = 'Cuentas Asignadas';
        $user = $this->user;
        
        // Obtener cuentas asignadas a los asesores del coordinador
        $accounts = $this->accountModel->getAccountsByCoordinator($user['id']);
        
        $this->render('coordinator/assigned-accounts', compact('title', 'user', 'accounts'));
    }
    
    /**
     * Ver interacciones del equipo
     */
    public function teamInteractions() {
        $this->requireAuth();
        if (!isCoordinator()) {
            $this->render('errors/403');
            return;
        }
        
        $title = 'Interacciones del Equipo';
        $user = $this->user;
        
        // Obtener interacciones de los asesores del coordinador
        $interactions = $this->interactionModel->getInteractionsByCoordinator($user['id']);
        
        // Obtener estadísticas
        $stats = [
            'total_interactions' => count($interactions),
            'successful_interactions' => count(array_filter($interactions, fn($i) => $i['result'] === 'successful')),
            'pending_interactions' => count(array_filter($interactions, fn($i) => $i['result'] === 'pending')),
            'avg_response_time' => 2.5 // Ejemplo
        ];
        
        $this->render('coordinator/team-interactions', compact('title', 'user', 'interactions', 'stats'));
    }
    
    /**
     * Reportes del equipo
     */
    public function teamReports() {
        $this->requireAuth();
        if (!isCoordinator()) {
            $this->render('errors/403');
            return;
        }
        
        $title = 'Reportes del Equipo';
        $user = $this->user;
        
        // Obtener métricas del equipo
        $metrics = [
            'total_collected' => 15000.00,
            'success_rate' => 75,
            'avg_interactions' => 12,
            'pending_accounts' => 25
        ];
        
        // Obtener rendimiento por asesor
        $advisorPerformance = $this->userModel->getAdvisorPerformanceByCoordinator($user['id']);
        
        // Datos de ejemplo para gráficos
        $collectionsTrend = [
            ['date' => '2024-01-01', 'amount' => 5000],
            ['date' => '2024-01-02', 'amount' => 7500],
            ['date' => '2024-01-03', 'amount' => 6000],
            ['date' => '2024-01-04', 'amount' => 8000],
            ['date' => '2024-01-05', 'amount' => 9000]
        ];
        
        $topTypifications = [
            ['name' => 'Promesa de Pago', 'count' => 45],
            ['name' => 'Cliente No Responde', 'count' => 32],
            ['name' => 'Pago Realizado', 'count' => 28],
            ['name' => 'Reclamo', 'count' => 15]
        ];
        
        $this->render('coordinator/team-reports', compact('title', 'user', 'metrics', 'advisorPerformance', 'collectionsTrend', 'topTypifications'));
    }
    
    /**
     * Asignar cuentas a asesores
     */
    public function assignAccounts() {
        $this->requireAuth();
        if (!isCoordinator()) {
            $this->render('errors/403');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accountIds = $this->getPost('account_ids', []);
            $advisorId = $this->getPost('advisor_id');
            
            if (empty($accountIds) || empty($advisorId)) {
                $this->redirectWithMessage('coordinator/assigned-accounts', 'Datos requeridos faltantes', 'error');
                return;
            }
            
            $success = 0;
            foreach ($accountIds as $accountId) {
                if ($this->accountModel->assignToAdvisor($accountId, $advisorId)) {
                    $success++;
                }
            }
            
            if ($success > 0) {
                $this->redirectWithMessage('coordinator/assigned-accounts', "Se asignaron {$success} cuentas exitosamente");
            } else {
                $this->redirectWithMessage('coordinator/assigned-accounts', 'Error al asignar las cuentas', 'error');
            }
        } else {
            $title = 'Asignar Cuentas';
            $user = $this->user;
            
            // Obtener asesores y cuentas disponibles
            $advisors = $this->userModel->getAdvisorsByCoordinator($user['id']);
            $availableAccounts = $this->accountModel->getAvailableAccounts();
            
            $this->render('coordinator/assign-accounts', compact('title', 'user', 'advisors', 'availableAccounts'));
        }
    }
    
    /**
     * Obtener estadísticas del equipo
     */
    private function getTeamStats($coordinatorId) {
        $db = Database::getInstance();
        
        try {
            return [
                'total_advisors' => $this->getTableCount('users', "role_id = 4 AND coordinator_id = {$coordinatorId}"),
                'active_advisors' => $this->getTableCount('users', "role_id = 4 AND coordinator_id = {$coordinatorId} AND is_active = 1"),
                'total_accounts' => $this->getTableCount('accounts', "assigned_to IN (SELECT id FROM users WHERE coordinator_id = {$coordinatorId})"),
                'total_interactions' => $this->getTableCount('interactions', "user_id IN (SELECT id FROM users WHERE coordinator_id = {$coordinatorId})"),
                'pending_promises' => $this->getTableCount('payment_promises', "status = 'pending' AND user_id IN (SELECT id FROM users WHERE coordinator_id = {$coordinatorId})"),
                'completed_payments' => $this->getTableCount('payments', "status = 'completed' AND user_id IN (SELECT id FROM users WHERE coordinator_id = {$coordinatorId})")
            ];
        } catch (Exception $e) {
            return [
                'total_advisors' => 0,
                'active_advisors' => 0,
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
     * Reasignar cuenta
     */
    public function reassignAccount() {
        $this->requireAuth();
        if (!isCoordinator()) {
            $this->render('errors/403');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accountId = $this->getPost('account_id');
            $advisorId = $this->getPost('advisor_id');
            
            if ($accountId && $advisorId) {
                if ($this->accountModel->assignToAdvisor($accountId, $advisorId)) {
                    $this->redirectWithMessage('coordinator/assigned-accounts', 'Cuenta reasignada exitosamente');
                } else {
                    $this->redirectWithMessage('coordinator/assigned-accounts', 'Error al reasignar la cuenta', 'error');
                }
            } else {
                $this->redirectWithMessage('coordinator/assigned-accounts', 'Datos incompletos', 'error');
            }
        }
        
        $this->redirect('coordinator/assigned-accounts');
    }

    /**
     * Exportar reporte de rendimiento
     */
    public function exportPerformanceReport() {
        $this->requireAuth();
        if (!isCoordinator()) {
            $this->render('errors/403');
            return;
        }
        
        $user = $this->user;
        $advisorPerformance = $this->userModel->getAdvisorPerformanceByCoordinator($user['id']);
        
        // Generar CSV
        $filename = 'reporte_rendimiento_equipo_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // BOM para UTF-8
        echo "\xEF\xBB\xBF";
        
        $output = fopen('php://output', 'w');
        
        // Encabezados
        fputcsv($output, [
            'Asesor',
            'Cuentas Asignadas',
            'Interacciones',
            'Monto Recaudado',
            'Tasa de Éxito (%)',
            'Promesas de Pago'
        ], ';');
        
        // Datos
        foreach ($advisorPerformance as $advisor) {
            fputcsv($output, [
                $advisor['name'] ?? 'Sin nombre',
                $advisor['assigned_accounts'] ?? 0,
                $advisor['total_interactions'] ?? 0,
                $advisor['collected_amount'] ?? 0,
                $advisor['success_rate'] ?? 0,
                $advisor['payment_promises'] ?? 0
            ], ';');
        }
        
        fclose($output);
        exit;
    }

    /**
     * Exportar reporte de cobranzas
     */
    public function exportCollectionsReport() {
        $this->requireAuth();
        if (!isCoordinator()) {
            $this->render('errors/403');
            return;
        }
        
        $user = $this->user;
        $collections = $this->accountModel->getCollectionsByCoordinator($user['id']);
        
        // Generar CSV
        $filename = 'reporte_cobranzas_equipo_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // BOM para UTF-8
        echo "\xEF\xBB\xBF";
        
        $output = fopen('php://output', 'w');
        
        // Encabezados
        fputcsv($output, [
            'Número de Cuenta',
            'Deudor',
            'Monto',
            'Fecha Vencimiento',
            'Asesor',
            'Estado',
            'Última Interacción'
        ], ';');
        
        // Datos
        foreach ($collections as $collection) {
            fputcsv($output, [
                $collection['account_number'] ?? 'Sin número',
                $collection['debtor_name'] ?? 'Sin deudor',
                $collection['current_amount'] ?? 0,
                $collection['due_date'] ?? 'Sin fecha',
                $collection['advisor_name'] ?? 'Sin asignar',
                $collection['status'] ?? 'Sin estado',
                $collection['last_interaction'] ?? 'Sin interacciones'
            ], ';');
        }
        
        fclose($output);
        exit;
    }

    /**
     * Exportar reporte de interacciones
     */
    public function exportInteractionsReport() {
        $this->requireAuth();
        if (!isCoordinator()) {
            $this->render('errors/403');
            return;
        }
        
        $user = $this->user;
        $interactions = $this->interactionModel->getInteractionsByCoordinator($user['id']);
        
        // Generar CSV
        $filename = 'reporte_interacciones_equipo_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // BOM para UTF-8
        echo "\xEF\xBB\xBF";
        
        $output = fopen('php://output', 'w');
        
        // Encabezados
        fputcsv($output, [
            'ID',
            'Fecha',
            'Asesor',
            'Deudor',
            'Tipo',
            'Resultado',
            'Duración (min)',
            'Notas'
        ], ';');
        
        // Datos
        foreach ($interactions as $interaction) {
            fputcsv($output, [
                $interaction['id'] ?? '',
                $interaction['created_at'] ?? '',
                $interaction['advisor_name'] ?? 'Sin asesor',
                $interaction['debtor_name'] ?? 'Sin deudor',
                $interaction['type'] ?? 'Sin tipo',
                $interaction['result'] ?? 'Sin resultado',
                $interaction['duration'] ?? 0,
                $interaction['notes'] ?? 'Sin notas'
            ], ';');
        }
        
        fclose($output);
        exit;
    }
}
