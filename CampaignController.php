<?php
/**
 * Controlador de Gestión de Campañas
 * CRM de Cobranzas - CRUD de campañas
 */

require_once 'controllers/Controller.php';

class CampaignController extends Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Listar campañas
     */
    public function index() {
        $this->requireAuth();
        
        $title = 'Gestión de Campañas';
        $user = $this->user;
        
        // Obtener campañas (simuladas por ahora)
        $campaigns = $this->getCampaigns();
        
        $this->render('campaigns/index', compact('title', 'user', 'campaigns'));
    }
    
    /**
     * Crear nueva campaña
     */
    public function create() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('campaigns');
        }
        
        // Obtener datos del formulario
        $name = $this->getPost('name');
        $description = $this->getPost('description');
        $startDate = $this->getPost('start_date');
        $endDate = $this->getPost('end_date');
        $isActive = $this->getPost('is_active') === 'on';
        
        // Validaciones
        $errors = [];
        
        if (empty($name)) {
            $errors[] = 'El nombre de la campaña es obligatorio';
        }
        
        if (empty($description)) {
            $errors[] = 'La descripción es obligatoria';
        }
        
        if (empty($startDate)) {
            $errors[] = 'La fecha de inicio es obligatoria';
        }
        
        if (empty($endDate)) {
            $errors[] = 'La fecha de fin es obligatoria';
        }
        
        if (!empty($startDate) && !empty($endDate) && $startDate > $endDate) {
            $errors[] = 'La fecha de inicio no puede ser posterior a la fecha de fin';
        }
        
        // Si hay errores, redirigir con mensaje
        if (!empty($errors)) {
            $this->redirectWithMessage('campaigns', implode(', ', $errors), 'error');
            return;
        }
        
        // Crear campaña (simulado)
        $campaignId = $this->createCampaign([
            'name' => $name,
            'description' => $description,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_active' => $isActive,
            'created_by' => $this->user['id']
        ]);
        
        if ($campaignId) {
            $this->redirectWithMessage('campaigns', 'Campaña creada exitosamente');
        } else {
            $this->redirectWithMessage('campaigns', 'Error al crear la campaña', 'error');
        }
    }
    
    /**
     * Actualizar campaña
     */
    public function update() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('campaigns');
        }
        
        $campaignId = $this->getPost('campaign_id');
        $name = $this->getPost('name');
        $description = $this->getPost('description');
        $startDate = $this->getPost('start_date');
        $endDate = $this->getPost('end_date');
        $isActive = $this->getPost('is_active') === 'on';
        
        // Validaciones
        $errors = [];
        
        if (empty($campaignId)) {
            $errors[] = 'ID de campaña requerido';
        }
        
        if (empty($name)) {
            $errors[] = 'El nombre de la campaña es obligatorio';
        }
        
        if (empty($description)) {
            $errors[] = 'La descripción es obligatoria';
        }
        
        if (empty($startDate)) {
            $errors[] = 'La fecha de inicio es obligatoria';
        }
        
        if (empty($endDate)) {
            $errors[] = 'La fecha de fin es obligatoria';
        }
        
        if (!empty($startDate) && !empty($endDate) && $startDate > $endDate) {
            $errors[] = 'La fecha de inicio no puede ser posterior a la fecha de fin';
        }
        
        // Si hay errores, redirigir con mensaje
        if (!empty($errors)) {
            $this->redirectWithMessage('campaigns', implode(', ', $errors), 'error');
            return;
        }
        
        // Actualizar campaña (simulado)
        $success = $this->updateCampaign($campaignId, [
            'name' => $name,
            'description' => $description,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_active' => $isActive
        ]);
        
        if ($success) {
            $this->redirectWithMessage('campaigns', 'Campaña actualizada exitosamente');
        } else {
            $this->redirectWithMessage('campaigns', 'Error al actualizar la campaña', 'error');
        }
    }
    
    /**
     * Eliminar campaña
     */
    public function delete() {
        $this->requireAuth();
        
        $campaignId = $this->getPost('campaign_id');
        
        if (empty($campaignId)) {
            $this->redirectWithMessage('campaigns', 'ID de campaña requerido', 'error');
            return;
        }
        
        // Eliminar campaña (simulado)
        $success = $this->deleteCampaign($campaignId);
        
        if ($success) {
            $this->redirectWithMessage('campaigns', 'Campaña eliminada exitosamente');
        } else {
            $this->redirectWithMessage('campaigns', 'Error al eliminar la campaña', 'error');
        }
    }
    
    /**
     * Obtener campañas (simulado)
     */
    private function getCampaigns() {
        // Datos simulados - en un sistema real vendrían de la base de datos
        return [
            [
                'id' => 1,
                'name' => 'Campaña Q1 2024',
                'description' => 'Campaña de cobranza para el primer trimestre del 2024',
                'start_date' => '2024-01-01',
                'end_date' => '2024-03-31',
                'is_active' => true,
                'created_by' => 1,
                'created_at' => '2024-01-01 10:00:00',
                'total_accounts' => 150,
                'collected_amount' => 45000.00,
                'target_amount' => 60000.00
            ],
            [
                'id' => 2,
                'name' => 'Campaña Q2 2024',
                'description' => 'Campaña de cobranza para el segundo trimestre del 2024',
                'start_date' => '2024-04-01',
                'end_date' => '2024-06-30',
                'is_active' => true,
                'created_by' => 1,
                'created_at' => '2024-04-01 10:00:00',
                'total_accounts' => 200,
                'collected_amount' => 32000.00,
                'target_amount' => 80000.00
            ],
            [
                'id' => 3,
                'name' => 'Campaña Especial Navidad',
                'description' => 'Campaña especial para la temporada navideña',
                'start_date' => '2024-12-01',
                'end_date' => '2024-12-31',
                'is_active' => false,
                'created_by' => 1,
                'created_at' => '2024-11-15 10:00:00',
                'total_accounts' => 75,
                'collected_amount' => 18000.00,
                'target_amount' => 25000.00
            ]
        ];
    }
    
    /**
     * Crear campaña (simulado)
     */
    private function createCampaign($data) {
        // En un sistema real, aquí se insertaría en la base de datos
        return rand(100, 999); // ID simulado
    }
    
    /**
     * Actualizar campaña (simulado)
     */
    private function updateCampaign($id, $data) {
        // En un sistema real, aquí se actualizaría en la base de datos
        return true;
    }
    
    /**
     * Eliminar campaña (simulado)
     */
    private function deleteCampaign($id) {
        // En un sistema real, aquí se eliminaría de la base de datos
        return true;
    }
}

