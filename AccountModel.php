<?php
/**
 * Modelo de Cuentas
 * CRM de Cobranzas - Gestión de cuentas
 */

require_once 'config/database.php';

class AccountModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Obtener todas las cuentas
     */
    public function getAll() {
        $sql = "SELECT a.*, d.full_name as debtor_name, c.name as campaign_name, u.full_name as assigned_to_name
                FROM accounts a
                LEFT JOIN debtors d ON a.debtor_id = d.id
                LEFT JOIN campaigns c ON a.campaign_id = c.id
                LEFT JOIN users u ON a.assigned_advisor_id = u.id
                ORDER BY a.created_at DESC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Obtener cuentas por coordinador
     */
    public function getAccountsByCoordinator($coordinatorId) {
        $sql = "SELECT a.*, d.full_name as debtor_name, c.name as campaign_name, u.full_name as assigned_to_name
                FROM accounts a
                LEFT JOIN debtors d ON a.debtor_id = d.id
                LEFT JOIN campaigns c ON a.campaign_id = c.id
                LEFT JOIN users u ON a.assigned_advisor_id = u.id
                WHERE a.assigned_advisor_id IN (
                    SELECT id FROM users WHERE coordinator_id = ?
                )
                ORDER BY a.created_at DESC";
        return $this->db->fetchAll($sql, [$coordinatorId]);
    }
    
    /**
     * Obtener cuentas disponibles (sin asignar)
     */
    public function getAvailableAccounts() {
        $sql = "SELECT a.*, d.full_name as debtor_name, c.name as campaign_name
                FROM accounts a
                LEFT JOIN debtors d ON a.debtor_id = d.id
                LEFT JOIN campaigns c ON a.campaign_id = c.id
                WHERE a.assigned_advisor_id IS NULL AND a.status = 'active'
                ORDER BY a.created_at ASC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Obtener cuentas por asesor
     */
    public function getAccountsByAdvisor($advisorId) {
        $sql = "SELECT a.*, d.full_name as debtor_name, c.name as campaign_name
                FROM accounts a
                LEFT JOIN debtors d ON a.debtor_id = d.id
                LEFT JOIN campaigns c ON a.campaign_id = c.id
                WHERE a.assigned_advisor_id = ? AND a.status = 'active'
                ORDER BY a.created_at ASC";
        return $this->db->fetchAll($sql, [$advisorId]);
    }
    
    /**
     * Obtener cuenta por ID
     */
    public function getById($id) {
        $sql = "SELECT a.*, d.full_name as debtor_name, c.name as campaign_name, u.full_name as assigned_to_name
                FROM accounts a
                LEFT JOIN debtors d ON a.debtor_id = d.id
                LEFT JOIN campaigns c ON a.campaign_id = c.id
                LEFT JOIN users u ON a.assigned_advisor_id = u.id
                WHERE a.id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Asignar cuenta a asesor
     */
    public function assignToAdvisor($accountId, $advisorId) {
        $sql = "UPDATE accounts SET assigned_advisor_id = ?, updated_at = NOW() WHERE id = ?";
        return $this->db->query($sql, [$advisorId, $accountId]);
    }
    
    /**
     * Crear nueva cuenta
     */
    public function create($data) {
        $sql = "INSERT INTO accounts (debtor_id, campaign_id, original_amount, current_balance, status, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
        return $this->db->query($sql, [
            $data['debtor_id'],
            $data['campaign_id'],
            $data['original_amount'],
            $data['current_balance'] ?? $data['original_amount'],
            $data['status'] ?? 'active'
        ]);
    }
    
    /**
     * Actualizar cuenta
     */
    public function update($id, $data) {
        $sql = "UPDATE accounts SET debtor_id = ?, campaign_id = ?, 
                original_amount = ?, current_balance = ?, status = ?, updated_at = NOW()
                WHERE id = ?";
        return $this->db->query($sql, [
            $data['debtor_id'],
            $data['campaign_id'],
            $data['original_amount'],
            $data['current_balance'],
            $data['status'],
            $id
        ]);
    }
    
    /**
     * Eliminar cuenta
     */
    public function delete($id) {
        $sql = "DELETE FROM accounts WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
    
    /**
     * Obtener estadísticas de cuentas
     */
    public function getAccountStats($coordinatorId = null) {
        $whereClause = $coordinatorId ? "WHERE a.assigned_advisor_id IN (SELECT id FROM users WHERE coordinator_id = {$coordinatorId})" : "";
        
        $sql = "SELECT 
                    COUNT(*) as total_accounts,
                    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_accounts,
                    SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as paid_accounts,
                    SUM(CASE WHEN status = 'overdue' THEN 1 ELSE 0 END) as overdue_accounts,
                    SUM(current_balance) as total_amount,
                    SUM(CASE WHEN status = 'paid' THEN current_balance ELSE 0 END) as collected_amount
                FROM accounts a {$whereClause}";
        
        return $this->db->fetch($sql);
    }

    /**
     * Obtener cobranzas por coordinador
     */
    public function getCollectionsByCoordinator($coordinatorId) {
        $sql = "SELECT a.*, d.full_name as debtor_name, u.full_name as advisor_name,
                       MAX(i.created_at) as last_interaction
                FROM accounts a 
                LEFT JOIN debtors d ON a.debtor_id = d.id 
                LEFT JOIN users u ON a.assigned_advisor_id = u.id
                LEFT JOIN interactions i ON i.account_id = a.id
                WHERE u.coordinator_id = ? 
                GROUP BY a.id
                ORDER BY a.created_at DESC";
        return $this->db->fetchAll($sql, [$coordinatorId]);
    }
}
