<?php
/**
 * Modelo de Interacciones
 * CRM de Cobranzas - Gestión de interacciones
 */

require_once 'config/database.php';

class InteractionModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Obtener todas las interacciones
     */
    public function getAll() {
        $sql = "SELECT i.*, a.id as account_id, d.full_name as debtor_name, u.full_name as user_name, 
                       ac.name as action_name, ct.name as contact_name, pt.name as profile_name
                FROM interactions i
                LEFT JOIN accounts a ON i.account_id = a.id
                LEFT JOIN debtors d ON a.debtor_id = d.id
                LEFT JOIN users u ON i.advisor_id = u.id
                LEFT JOIN typification_categories ac ON i.action_category_id = ac.id
                LEFT JOIN typifications ct ON i.contact_typification_id = ct.id
                LEFT JOIN typifications pt ON i.profile_typification_id = pt.id
                ORDER BY i.created_at DESC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Obtener interacciones por coordinador
     */
    public function getInteractionsByCoordinator($coordinatorId) {
        $sql = "SELECT i.*, a.id as account_id, d.full_name as debtor_name, u.full_name as user_name,
                       ac.name as action_name, ct.name as contact_name, pt.name as profile_name
                FROM interactions i
                LEFT JOIN accounts a ON i.account_id = a.id
                LEFT JOIN debtors d ON a.debtor_id = d.id
                LEFT JOIN users u ON i.advisor_id = u.id
                LEFT JOIN typification_categories ac ON i.action_category_id = ac.id
                LEFT JOIN typifications ct ON i.contact_typification_id = ct.id
                LEFT JOIN typifications pt ON i.profile_typification_id = pt.id
                WHERE i.advisor_id IN (
                    SELECT id FROM users WHERE coordinator_id = ?
                )
                ORDER BY i.created_at DESC";
        return $this->db->fetchAll($sql, [$coordinatorId]);
    }
    
    /**
     * Crear interacción con jerarquía de tipificaciones
     */
    public function createWithHierarchy($data) {
        $sql = "INSERT INTO interactions (
                    account_id, advisor_id, coordinator_id, campaign_id, channel_id,
                    action_category_id, contact_typification_id, profile_typification_id,
                    notes, phone_number, scheduled_date, authorized_channels_data,
                    obligation_frame_data, promise_amount, promise_due_date,
                    next_contact_at, contacted, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        // Obtener información de la cuenta para el coordinador y campaña
        $accountInfo = $this->getAccountInfo($data['account_id']);
        
        $result = $this->db->query($sql, [
            $data['account_id'],
            $data['advisor_id'],
            $accountInfo['assigned_coordinator_id'] ?? null,
            $accountInfo['campaign_id'] ?? null,
            $data['channel_id'],
            $data['action_category_id'],
            $data['contact_typification_id'],
            $data['profile_typification_id'],
            $data['notes'],
            $data['phone_number'] ?? null,
            $data['scheduled_date'] ?? null,
            $data['authorized_channels_data'] ?? null,
            $data['obligation_frame_data'] ?? null,
            $data['promise_amount'] ?? null,
            $data['promise_due_date'] ?? null,
            $data['next_contact_at'] ?? null,
            $data['contacted'] ?? 0
        ]);
        
        // Retornar el ID de la interacción creada
        return $result ? $this->db->lastInsertId() : false;
    }
    
    /**
     * Obtener información de la cuenta
     */
    private function getAccountInfo($accountId) {
        $sql = "SELECT assigned_coordinator_id, campaign_id FROM accounts WHERE id = ?";
        return $this->db->fetch($sql, [$accountId]);
    }
    
    /**
     * Obtener interacciones con jerarquía de tipificaciones
     */
    public function getInteractionsWithHierarchy($advisorId = null) {
        $sql = "SELECT i.*, 
                       a.id as account_id, 
                       d.full_name as debtor_name, 
                       u.full_name as user_name,
                       ch.name as channel_name,
                       ac.name as action_category_name,
                       ct.name as contact_typification_name,
                       pt.name as profile_typification_name
                FROM interactions i
                LEFT JOIN accounts a ON i.account_id = a.id
                LEFT JOIN debtors d ON a.debtor_id = d.id
                LEFT JOIN users u ON i.advisor_id = u.id
                LEFT JOIN contact_channels ch ON i.channel_id = ch.id
                LEFT JOIN typification_categories ac ON i.action_category_id = ac.id
                LEFT JOIN typifications ct ON i.contact_typification_id = ct.id
                LEFT JOIN typifications pt ON i.profile_typification_id = pt.id";
        
        $params = [];
        if ($advisorId) {
            $sql .= " WHERE i.advisor_id = ?";
            $params[] = $advisorId;
        }
        
        $sql .= " ORDER BY i.created_at DESC";
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Obtener interacciones por asesor
     */
    public function getInteractionsByAdvisor($advisorId) {
        $sql = "SELECT i.*, a.id as account_id, d.full_name as debtor_name, 
                       ac.name as action_name, ct.name as contact_name, pt.name as profile_name
                FROM interactions i
                LEFT JOIN accounts a ON i.account_id = a.id
                LEFT JOIN debtors d ON a.debtor_id = d.id
                LEFT JOIN typification_categories ac ON i.action_category_id = ac.id
                LEFT JOIN typifications ct ON i.contact_typification_id = ct.id
                LEFT JOIN typifications pt ON i.profile_typification_id = pt.id
                WHERE i.advisor_id = ?
                ORDER BY i.created_at DESC";
        return $this->db->fetchAll($sql, [$advisorId]);
    }
    
    /**
     * Obtener interacciones por cuenta
     */
    public function getInteractionsByAccount($accountId) {
        $sql = "SELECT i.*, u.full_name as user_name, 
                       ac.name as action_name, ct.name as contact_name, pt.name as profile_name
                FROM interactions i
                LEFT JOIN users u ON i.advisor_id = u.id
                LEFT JOIN typification_categories ac ON i.action_category_id = ac.id
                LEFT JOIN typifications ct ON i.contact_typification_id = ct.id
                LEFT JOIN typifications pt ON i.profile_typification_id = pt.id
                WHERE i.account_id = ?
                ORDER BY i.created_at DESC";
        return $this->db->fetchAll($sql, [$accountId]);
    }
    
    /**
     * Obtener interacción por ID
     */
    public function getById($id) {
        $sql = "SELECT i.*, a.id as account_id, d.full_name as debtor_name, u.full_name as user_name,
                       ac.name as action_name, ct.name as contact_name, pt.name as profile_name
                FROM interactions i
                LEFT JOIN accounts a ON i.account_id = a.id
                LEFT JOIN debtors d ON a.debtor_id = d.id
                LEFT JOIN users u ON i.advisor_id = u.id
                LEFT JOIN typification_categories ac ON i.action_category_id = ac.id
                LEFT JOIN typifications ct ON i.contact_typification_id = ct.id
                LEFT JOIN typifications pt ON i.profile_typification_id = pt.id
                WHERE i.id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Crear nueva interacción
     */
    public function create($data) {
        $sql = "INSERT INTO interactions (account_id, advisor_id, coordinator_id, campaign_id, channel_id,
                    action_category_id, contact_typification_id, profile_typification_id,
                    notes, phone_number, scheduled_date, authorized_channels_data,
                    obligation_frame_data, promise_amount, promise_due_date,
                    next_contact_at, contacted, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        // Obtener información de la cuenta para el coordinador y campaña
        $accountInfo = $this->getAccountInfo($data['account_id']);
        
        return $this->db->query($sql, [
            $data['account_id'],
            $data['advisor_id'],
            $accountInfo['assigned_coordinator_id'] ?? null,
            $accountInfo['campaign_id'] ?? null,
            $data['channel_id'] ?? 1,
            $data['action_category_id'],
            $data['contact_typification_id'],
            $data['profile_typification_id'],
            $data['notes'],
            $data['phone_number'] ?? null,
            $data['scheduled_date'] ?? null,
            $data['authorized_channels_data'] ?? null,
            $data['obligation_frame_data'] ?? null,
            $data['promise_amount'] ?? null,
            $data['promise_due_date'] ?? null,
            $data['next_contact_at'] ?? null,
            $data['contacted'] ?? 0
        ]);
    }
    
    /**
     * Actualizar interacción
     */
    public function update($id, $data) {
        $sql = "UPDATE interactions SET account_id = ?, advisor_id = ?, 
                action_category_id = ?, contact_typification_id = ?, profile_typification_id = ?,
                notes = ?, phone_number = ?, scheduled_date = ?, authorized_channels_data = ?,
                obligation_frame_data = ?, promise_amount = ?, promise_due_date = ?, 
                next_contact_at = ?, contacted = ?
                WHERE id = ?";
        return $this->db->query($sql, [
            $data['account_id'],
            $data['advisor_id'],
            $data['action_category_id'],
            $data['contact_typification_id'],
            $data['profile_typification_id'],
            $data['notes'],
            $data['phone_number'] ?? null,
            $data['scheduled_date'] ?? null,
            $data['authorized_channels_data'] ?? null,
            $data['obligation_frame_data'] ?? null,
            $data['promise_amount'] ?? null,
            $data['promise_due_date'] ?? null,
            $data['next_contact_at'] ?? null,
            $data['contacted'] ?? 0,
            $id
        ]);
    }
    
    /**
     * Eliminar interacción
     */
    public function delete($id) {
        $sql = "DELETE FROM interactions WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
    
    /**
     * Obtener estadísticas de interacciones
     */
    public function getInteractionStats($coordinatorId = null) {
        $whereClause = $coordinatorId ? "WHERE i.advisor_id IN (SELECT id FROM users WHERE coordinator_id = {$coordinatorId})" : "";
        
        $sql = "SELECT 
                    COUNT(*) as total_interactions,
                    SUM(CASE WHEN contacted = 1 THEN 1 ELSE 0 END) as contacted,
                    SUM(CASE WHEN contacted = 0 THEN 1 ELSE 0 END) as no_answer,
                    SUM(CASE WHEN promise_amount > 0 THEN 1 ELSE 0 END) as promises,
                    SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today_interactions
                FROM interactions i {$whereClause}";
        
        return $this->db->fetch($sql);
    }
    
    /**
     * Obtener interacciones del día
     */
    public function getTodayInteractions($userId = null) {
        $whereClause = $userId ? "WHERE advisor_id = ? AND DATE(created_at) = CURDATE()" : "WHERE DATE(created_at) = CURDATE()";
        $params = $userId ? [$userId] : [];
        
        $sql = "SELECT i.*, a.id as account_id, d.full_name as debtor_name, 
                       ac.name as action_name, ct.name as contact_name, pt.name as profile_name
                FROM interactions i
                LEFT JOIN accounts a ON i.account_id = a.id
                LEFT JOIN debtors d ON a.debtor_id = d.id
                LEFT JOIN typification_categories ac ON i.action_category_id = ac.id
                LEFT JOIN typifications ct ON i.contact_typification_id = ct.id
                LEFT JOIN typifications pt ON i.profile_typification_id = pt.id
                {$whereClause}
                ORDER BY i.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
}

