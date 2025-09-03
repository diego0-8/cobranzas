<?php
/**
 * Controlador de Reportes
 * CRM de Cobranzas - Generación de reportes y estadísticas
 */

require_once 'controllers/Controller.php';

class ReportController extends Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Página principal de reportes
     */
    public function index() {
        $this->requireAuth();
        
        $title = 'Reportes y Estadísticas';
        $user = $this->user;
        
        // Obtener datos para reportes (simulados)
        $reportData = $this->getReportData();
        
        $this->render('reports/index', compact('title', 'user', 'reportData'));
    }
    
    /**
     * Generar reporte de usuarios
     */
    public function users() {
        $this->requireAuth();
        
        if (!isAdmin()) {
            $this->render('errors/403');
            return;
        }
        
        $title = 'Reporte de Usuarios';
        $user = $this->user;
        
        // Datos simulados para el reporte de usuarios
        $userReport = [
            'total_users' => 25,
            'active_users' => 22,
            'inactive_users' => 3,
            'users_by_role' => [
                ['role' => 'Super Administrador', 'count' => 1],
                ['role' => 'Administrador', 'count' => 2],
                ['role' => 'Coordinador', 'count' => 5],
                ['role' => 'Asesor', 'count' => 17]
            ],
            'recent_users' => [
                ['name' => 'Juan Pérez', 'role' => 'Asesor', 'created_at' => '2024-01-15'],
                ['name' => 'María García', 'role' => 'Coordinador', 'created_at' => '2024-01-10'],
                ['name' => 'Carlos López', 'role' => 'Asesor', 'created_at' => '2024-01-08']
            ]
        ];
        
        $this->render('reports/users', compact('title', 'user', 'userReport'));
    }
    
    /**
     * Generar reporte de campañas
     */
    public function campaigns() {
        $this->requireAuth();
        
        $title = 'Reporte de Campañas';
        $user = $this->user;
        
        // Datos simulados para el reporte de campañas
        $campaignReport = [
            'total_campaigns' => 8,
            'active_campaigns' => 3,
            'completed_campaigns' => 5,
            'total_revenue' => 125000.00,
            'campaigns_performance' => [
                ['name' => 'Campaña Q1 2024', 'target' => 60000, 'collected' => 45000, 'percentage' => 75],
                ['name' => 'Campaña Q2 2024', 'target' => 80000, 'collected' => 32000, 'percentage' => 40],
                ['name' => 'Campaña Especial Navidad', 'target' => 25000, 'collected' => 18000, 'percentage' => 72]
            ]
        ];
        
        $this->render('reports/campaigns', compact('title', 'user', 'campaignReport'));
    }
    
    /**
     * Generar reporte de cobranzas
     */
    public function collections() {
        $this->requireAuth();
        
        $title = 'Reporte de Cobranzas';
        $user = $this->user;
        
        // Datos simulados para el reporte de cobranzas
        $collectionReport = [
            'total_debtors' => 450,
            'active_accounts' => 380,
            'collected_amount' => 85000.00,
            'pending_amount' => 120000.00,
            'collection_rate' => 41.5,
            'monthly_collections' => [
                ['month' => 'Enero', 'amount' => 15000],
                ['month' => 'Febrero', 'amount' => 18000],
                ['month' => 'Marzo', 'amount' => 22000],
                ['month' => 'Abril', 'amount' => 19000],
                ['month' => 'Mayo', 'amount' => 11000]
            ]
        ];
        
        $this->render('reports/collections', compact('title', 'user', 'collectionReport'));
    }
    
    /**
     * Exportar reporte a CSV
     */
    public function export() {
        $this->requireAuth();
        
        $type = $this->getGet('type', 'users');
        $format = $this->getGet('format', 'csv');
        
        // Solo permitir CSV
        if ($format !== 'csv') {
            $this->redirectWithMessage('reports', 'Formato no soportado. Solo se permite CSV.', 'error');
            return;
        }
        
        $filename = "reporte_{$type}_" . date('Y-m-d_H-i-s') . ".csv";
        
        // Configurar headers para descarga CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        
        // Crear el archivo CSV
        $output = fopen('php://output', 'w');
        
        // Agregar BOM para UTF-8 (para que Excel abra correctamente los caracteres especiales)
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        switch ($type) {
            case 'users':
                $this->exportUsersCSV($output);
                break;
            case 'campaigns':
                $this->exportCampaignsCSV($output);
                break;
            case 'collections':
                $this->exportCollectionsCSV($output);
                break;
            default:
                $this->exportUsersCSV($output);
                break;
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Exportar reporte de usuarios a CSV
     */
    private function exportUsersCSV($output) {
        // Encabezados
        fputcsv($output, [
            'ID',
            'Usuario',
            'Nombre Completo',
            'Email',
            'Rol',
            'Estado',
            'Fecha Creación'
        ], ';');
        
        // Datos simulados de usuarios
        $users = [
            [1, 'superadmin', 'Super Administrador', 'superadmin@empresa.com', 'Super Administrador', 'Activo', '2024-01-01'],
            [2, 'admin', 'Administrador Principal', 'admin@empresa.com', 'Administrador', 'Activo', '2024-01-02'],
            [3, 'coord1', 'Coordinador 1', 'coord1@empresa.com', 'Coordinador', 'Activo', '2024-01-03'],
            [4, 'coord2', 'Coordinador 2', 'coord2@empresa.com', 'Coordinador', 'Activo', '2024-01-04'],
            [5, 'asesor1', 'Asesor 1', 'asesor1@empresa.com', 'Asesor', 'Activo', '2024-01-05'],
            [6, 'asesor2', 'Asesor 2', 'asesor2@empresa.com', 'Asesor', 'Activo', '2024-01-06'],
            [7, 'asesor3', 'Asesor 3', 'asesor3@empresa.com', 'Asesor', 'Inactivo', '2024-01-07'],
            [8, 'asesor4', 'Asesor 4', 'asesor4@empresa.com', 'Asesor', 'Activo', '2024-01-08']
        ];
        
        foreach ($users as $user) {
            fputcsv($output, $user, ';');
        }
    }
    
    /**
     * Exportar reporte de campañas a CSV
     */
    private function exportCampaignsCSV($output) {
        // Encabezados
        fputcsv($output, [
            'ID',
            'Campaña',
            'Meta ($)',
            'Recaudado ($)',
            'Progreso (%)',
            'Estado',
            'Fecha Inicio',
            'Fecha Fin'
        ], ';');
        
        // Datos simulados de campañas
        $campaigns = [
            [1, 'Campaña Q1 2024', 60000, 45000, 75, 'En Progreso', '2024-01-01', '2024-03-31'],
            [2, 'Campaña Q2 2024', 80000, 32000, 40, 'En Progreso', '2024-04-01', '2024-06-30'],
            [3, 'Campaña Especial Navidad', 25000, 18000, 72, 'En Progreso', '2024-12-01', '2024-12-31'],
            [4, 'Campaña Q3 2023', 50000, 50000, 100, 'Completada', '2023-07-01', '2023-09-30'],
            [5, 'Campaña Q4 2023', 45000, 42000, 93, 'Completada', '2023-10-01', '2023-12-31']
        ];
        
        foreach ($campaigns as $campaign) {
            fputcsv($output, $campaign, ';');
        }
    }
    
    /**
     * Exportar reporte de cobranzas a CSV
     */
    private function exportCollectionsCSV($output) {
        // Encabezados
        fputcsv($output, [
            'Mes',
            'Total Deudores',
            'Cuentas Activas',
            'Recaudado ($)',
            'Pendiente ($)',
            'Tasa Cobranza (%)'
        ], ';');
        
        // Datos simulados de cobranzas mensuales
        $collections = [
            ['Enero 2024', 450, 380, 15000, 85000, 15.0],
            ['Febrero 2024', 445, 375, 18000, 82000, 18.0],
            ['Marzo 2024', 440, 370, 22000, 78000, 22.0],
            ['Abril 2024', 435, 365, 19000, 75000, 20.2],
            ['Mayo 2024', 430, 360, 11000, 74000, 12.9]
        ];
        
        foreach ($collections as $collection) {
            fputcsv($output, $collection, ';');
        }
    }
    
    /**
     * Obtener datos generales para reportes
     */
    private function getReportData() {
        return [
            'summary' => [
                'total_users' => 25,
                'total_campaigns' => 8,
                'total_debtors' => 450,
                'total_collected' => 125000.00
            ],
            'charts' => [
                'users_by_role' => [
                    'labels' => ['Super Admin', 'Admin', 'Coordinador', 'Asesor'],
                    'data' => [1, 2, 5, 17]
                ],
                'monthly_collections' => [
                    'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May'],
                    'data' => [15000, 18000, 22000, 19000, 11000]
                ],
                'campaign_performance' => [
                    'labels' => ['Q1 2024', 'Q2 2024', 'Navidad'],
                    'data' => [75, 40, 72]
                ]
            ],
            'recent_activities' => [
                ['type' => 'user', 'description' => 'Nuevo usuario creado: Juan Pérez', 'date' => '2024-01-15 10:30'],
                ['type' => 'campaign', 'description' => 'Campaña Q1 2024 completada', 'date' => '2024-01-14 16:45'],
                ['type' => 'collection', 'description' => 'Pago recibido: $2,500', 'date' => '2024-01-14 14:20'],
                ['type' => 'user', 'description' => 'Usuario María García actualizado', 'date' => '2024-01-13 09:15']
            ]
        ];
    }
}
