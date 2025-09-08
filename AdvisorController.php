<?php
/**
 * Controlador de Asesor
 * CRM de Cobranzas - Funcionalidades específicas del asesor
 */

require_once 'controllers/Controller.php';
require_once 'models/AccountModel.php';
require_once 'models/InteractionModel.php';
require_once 'models/TypificationModel.php';
require_once 'models/ContactChannelModel.php';
require_once 'models/ObligationModel.php';

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
        
        // Obtener categorías de acción (nivel 1) - solo las activas para asesor
        $action_categories = $typificationModel->getActionCategories();
        
        // Obtener todas las tipificaciones para el JavaScript
        $typifications_data = $typificationModel->getAllForHierarchy();
        
        // Obtener canales autorizados
        $authorized_channels = $typificationModel->getAuthorizedChannels();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            
            // Obtener datos del formulario
            $account_id = $this->getPost('account_id');
            $action_category = $this->getPost('action_category');
            $contact_typification = $this->getPost('contact_typification');
            $profile_typification = $this->getPost('profile_typification');
            $notes = $this->getPost('notes');
            $phone_number = $this->getPost('phone_number');
            $scheduled_date = $this->getPost('scheduled_date');
            $obligation_frame = $this->getPost('obligation_frame');
            
            // Obtener datos específicos de canales autorizados
            $telefono_autorizado = $this->getPost('telefono_autorizado');
            $whatsapp_autorizado = $this->getPost('whatsapp_autorizado');
            $correo_autorizado = $this->getPost('correo_autorizado');
            $direccion_autorizada = $this->getPost('direccion_autorizada');
            $bot_autorizado = $this->getPost('bot_autorizado');
            $sms_autorizado = $this->getPost('sms_autorizado');
            
            // Preparar datos de canales autorizados
            $authorized_channels_data = [
                'telefono' => $telefono_autorizado,
                'whatsapp' => $whatsapp_autorizado,
                'correo' => $correo_autorizado,
                'direccion' => $direccion_autorizada,
                'bot' => $bot_autorizado,
                'sms' => $sms_autorizado
            ];
            
            // Obtener datos de obligaciones
            $obligation_accounts = $this->getPost('obligation_account', []);
            $obligation_payment_dates = $this->getPost('obligation_payment_date', []);
            $obligation_total_installments = $this->getPost('obligation_total_installments', []);
            $obligation_total_values = $this->getPost('obligation_total_value', []);
            $obligation_installment_numbers = $this->getPost('obligation_installment_number', []);
            $obligation_installment_values = $this->getPost('obligation_installment_value', []);
            
            // Validaciones básicas
            if (empty($account_id) || empty($action_category) || 
                empty($contact_typification) || empty($profile_typification) || empty($notes)) {
                $this->redirectWithMessage('advisor/new-interaction', 'error', 'Todos los campos obligatorios son requeridos');
                return;
            }
            
            // Validar que las observaciones tengan contenido mínimo
            if (strlen(trim($notes)) < 10) {
                $this->redirectWithMessage('advisor/new-interaction', 'error', 'Las observaciones deben tener al menos 10 caracteres para ser descriptivas');
                return;
            }
            
            // Preparar datos para la interacción
            $data = [
                'account_id' => $account_id,
                'advisor_id' => $this->user['id'],
                'channel_id' => 1, // Usar canal por defecto
                'action_category_id' => $action_category,
                'contact_typification_id' => $contact_typification,
                'profile_typification_id' => $profile_typification,
                'notes' => $notes,
                'phone_number' => $phone_number,
                'scheduled_date' => $scheduled_date,
                'authorized_channels_data' => json_encode($authorized_channels_data),
                'obligation_frame_data' => $obligation_frame,
                'promise_amount' => null,
                'promise_due_date' => null,
                'next_contact_at' => null,
                'contacted' => 0
            ];
            
            $interactionModel = new InteractionModel();
            $interactionId = $interactionModel->createWithHierarchy($data);
            
            if ($interactionId) {
                // Procesar obligaciones si existen
                if (!empty($obligation_accounts) && is_array($obligation_accounts)) {
                    $obligationModel = new ObligationModel();
                    
                    for ($i = 0; $i < count($obligation_accounts); $i++) {
                        // Validar que todos los campos de la obligación estén presentes
                        if (!empty($obligation_accounts[$i]) && 
                            !empty($obligation_payment_dates[$i]) && 
                            !empty($obligation_total_installments[$i]) && 
                            !empty($obligation_total_values[$i]) && 
                            !empty($obligation_installment_numbers[$i]) && 
                            !empty($obligation_installment_values[$i])) {
                            
                            $obligationData = [
                                'interaction_id' => $interactionId,
                                'account_id' => $obligation_accounts[$i],
                                'payment_date' => $obligation_payment_dates[$i],
                                'total_installments' => $obligation_total_installments[$i],
                                'total_agreement_value' => $this->cleanCurrencyValue($obligation_total_values[$i]),
                                'installment_number' => $obligation_installment_numbers[$i],
                                'installment_value' => $this->cleanCurrencyValue($obligation_installment_values[$i]),
                                'status' => 'pending'
                            ];
                            
                            $obligationModel->create($obligationData);
                        }
                    }
                }
                
                $this->redirectWithMessage('advisor/my-interactions', 'success', 'Interacción registrada exitosamente');
            } else {
                $this->redirectWithMessage('advisor/new-interaction', 'error', 'Error al registrar la interacción');
            }
        }
        
        $this->render('advisor/new-interaction', [
            'title' => 'Nueva Interacción',
            'accounts' => $accounts,
            'action_categories' => $action_categories,
            'typifications_data' => $typifications_data,
            'authorized_channels' => $authorized_channels
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
    
    /**
     * Limpiar valor de moneda removiendo separadores de miles
     */
    private function cleanCurrencyValue($value) {
        // Remover separadores de miles y convertir a float
        $cleanValue = str_replace(',', '', $value);
        return (float) $cleanValue;
    }
}
?>

