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
        $sql = "SELECT i.*, a.id as account_id, d.full_name as debtor_name, u.full_name as user_name, t.name as typification_name
                FROM interactions i
                LEFT JOIN accounts a ON i.account_id = a.id
                LEFT JOIN debtors d ON a.debtor_id = d.id
                LEFT JOIN users u ON i.advisor_id = u.id
                LEFT JOIN typifications t ON i.typification_id = t.id
                ORDER BY i.created_at DESC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Obtener interacciones por coordinador
     */
    public function getInteractionsByCoordinator($coordinatorId) {
        $sql = "SELECT i.*, a.id as account_id, d.full_name as debtor_name, u.full_name as user_name, t.name as typification_name
                FROM interactions i
                LEFT JOIN accounts a ON i.account_id = a.id
                LEFT JOIN debtors d ON a.debtor_id = d.id
                LEFT JOIN users u ON i.advisor_id = u.id
                LEFT JOIN typifications t ON i.typification_id = t.id
                WHERE i.advisor_id IN (
                    SELECT id FROM users WHERE coordinator_id = ?
                )
                ORDER BY i.created_at DESC";
        return $this->db->fetchAll($sql, [$coordinatorId]);
    }
    
    /**
     * Obtener interacciones por asesor
     */
    public function getInteractionsByAdvisor($advisorId) {
        $sql = "SELECT i.*, a.id as account_id, d.full_name as debtor_name, t.name as typification_name
                FROM interactions i
                LEFT JOIN accounts a ON i.account_id = a.id
                LEFT JOIN debtors d ON a.debtor_id = d.id
                LEFT JOIN typifications t ON i.typification_id = t.id
                WHERE i.advisor_id = ?
                ORDER BY i.created_at DESC";
        return $this->db->fetchAll($sql, [$advisorId]);
    }
    
    /**
     * Obtener interacciones por cuenta
     */
    public function getInteractionsByAccount($accountId) {
        $sql = "SELECT i.*, u.full_name as user_name, t.name as typification_name
                FROM interactions i
                LEFT JOIN users u ON i.user_id = u.id
                LEFT JOIN typifications t ON i.typification_id = t.id
                WHERE i.account_id = ?
                ORDER BY i.created_at DESC";
        return $this->db->fetchAll($sql, [$accountId]);
    }
    
    /**
     * Obtener interacción por ID
     */
    public function getById($id) {
        $sql = "SELECT i.*, a.id as account_id, d.full_name as debtor_name, u.full_name as user_name, t.name as typification_name
                FROM interactions i
                LEFT JOIN accounts a ON i.account_id = a.id
                LEFT JOIN debtors d ON a.debtor_id = d.id
                LEFT JOIN users u ON i.advisor_id = u.id
                LEFT JOIN typifications t ON i.typification_id = t.id
                WHERE i.id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Crear nueva interacción
     */
    public function create($data) {
        $sql = "INSERT INTO interactions (account_id, advisor_id, typification_id, notes, promise_amount, promise_due_date, next_contact_at, contacted, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        return $this->db->query($sql, [
            $data['account_id'],
            $data['advisor_id'],
            $data['typification_id'],
            $data['notes'],
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
        $sql = "UPDATE interactions SET account_id = ?, advisor_id = ?, typification_id = ?, 
                notes = ?, promise_amount = ?, promise_due_date = ?, next_contact_at = ?, contacted = ?
                WHERE id = ?";
        return $this->db->query($sql, [
            $data['account_id'],
            $data['advisor_id'],
            $data['typification_id'],
            $data['notes'],
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
        
        $sql = "SELECT i.*, a.id as account_id, d.full_name as debtor_name, t.name as typification_name
                FROM interactions i
                LEFT JOIN accounts a ON i.account_id = a.id
                LEFT JOIN debtors d ON a.debtor_id = d.id
                LEFT JOIN typifications t ON i.typification_id = t.id
                {$whereClause}
                ORDER BY i.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
}

