<?php
/**
 * Controlador de Asesor
 * CRM de Cobranzas - Funcionalidades específicas del asesor
 */

require_once 'controllers/Controller.php';
require_once 'models/AccountModel.php';
require_once 'models/InteractionModel.php';
require_once 'models/TypificationModel.php';

class AdvisorController extends Controller {
    
    public function __construct() {
        parent::__construct();
        $this->requireRole(4); // Solo asesores
    }
    
    /**
     * Mis cuentas asignadas
     */
    public function myAccounts() {
        $accountModel = new AccountModel();
        $accounts = $accountModel->getAccountsByAdvisor($this->user['id']);
        
        $this->render('advisor/my-accounts', [
            'title' => 'Mis Cuentas',
            'accounts' => $accounts
        ]);
    }
    
    /**
     * Mis interacciones
     */
    public function myInteractions() {
        $interactionModel = new InteractionModel();
        $interactions = $interactionModel->getInteractionsByAdvisor($this->user['id']);
        
        $this->render('advisor/my-interactions', [
            'title' => 'Mis Interacciones',
            'interactions' => $interactions
        ]);
    }
    
    /**
     * Nueva interacción
     */
    public function newInteraction() {
        $accountModel = new AccountModel();
        $typificationModel = new TypificationModel();
        
        $accounts = $accountModel->getAccountsByAdvisor($this->user['id']);
        $typifications = $typificationModel->getAll();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            
            $data = [
                'account_id' => $this->getPost('account_id'),
                'advisor_id' => $this->user['id'],
                'typification_id' => $this->getPost('typification_id'),
                'notes' => $this->getPost('notes'),
                'promise_amount' => $this->getPost('promise_amount') ?: null,
                'promise_due_date' => $this->getPost('promise_due_date') ?: null,
                'next_contact_at' => $this->getPost('next_contact_at') ?: null,
                'contacted' => $this->getPost('contacted') ? 1 : 0
            ];
            
            $interactionModel = new InteractionModel();
            if ($interactionModel->create($data)) {
                $this->redirectWithMessage('advisor/my-interactions', 'success', 'Interacción registrada exitosamente');
            } else {
                $this->redirectWithMessage('advisor/new-interaction', 'error', 'Error al registrar la interacción');
            }
        }
        
        $this->render('advisor/new-interaction', [
            'title' => 'Nueva Interacción',
            'accounts' => $accounts,
            'typifications' => $typifications
        ]);
    }
    
    /**
     * Promesas de pago
     */
    public function paymentPromises() {
        $interactionModel = new InteractionModel();
        $interactions = $interactionModel->getInteractionsByAdvisor($this->user['id']);
        
        // Filtrar solo las que tienen promesas de pago
        $promises = array_filter($interactions, function($interaction) {
            return !empty($interaction['promise_amount']) && $interaction['promise_amount'] > 0;
        });
        
        $this->render('advisor/payment-promises', [
            'title' => 'Promesas de Pago',
            'promises' => $promises
        ]);
    }
    
    /**
     * Mis reportes
     */
    public function myReports() {
        $interactionModel = new InteractionModel();
        $accountModel = new AccountModel();
        
        $stats = $interactionModel->getInteractionStats($this->user['id']);
        $accounts = $accountModel->getAccountsByAdvisor($this->user['id']);
        
        $this->render('advisor/my-reports', [
            'title' => 'Mis Reportes',
            'stats' => $stats,
            'accounts' => $accounts
        ]);
    }
    
    /**
     * Exportar mis reportes
     */
    public function exportMyReports() {
        $interactionModel = new InteractionModel();
        $accountModel = new AccountModel();
        
        $interactions = $interactionModel->getInteractionsByAdvisor($this->user['id']);
        $accounts = $accountModel->getAccountsByAdvisor($this->user['id']);
        
        $filename = 'mis_reportes_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // BOM para UTF-8
        echo "\xEF\xBB\xBF";
        
        $output = fopen('php://output', 'w');
        
        // Encabezados
        fputcsv($output, [
            'Fecha',
            'Cuenta ID',
            'Deudor',
            'Tipificación',
            'Notas',
            'Monto Promesa',
            'Fecha Promesa',
            'Contactado',
            'Próximo Contacto'
        ]);
        
        // Datos
        foreach ($interactions as $interaction) {
            fputcsv($output, [
                $interaction['created_at'],
                $interaction['account_id'],
                $interaction['debtor_name'] ?? 'N/A',
                $interaction['typification_name'] ?? 'N/A',
                $interaction['notes'],
                $interaction['promise_amount'] ?? '0',
                $interaction['promise_due_date'] ?? 'N/A',
                $interaction['contacted'] ? 'Sí' : 'No',
                $interaction['next_contact_at'] ?? 'N/A'
            ]);
        }
        
        fclose($output);
        exit;
    }
}
?>

