<?php
/**
 * Modelo de Usuario
 * CRM de Cobranzas - Gestión de usuarios y autenticación
 */

require_once 'models/Model.php';

class UserModel extends Model {
    protected $table = 'users';
    
    /**
     * Autenticar usuario por username y contraseña
     */
    public function authenticate($username, $password) {
        $sql = "SELECT u.*, r.name as role_name 
                FROM users u 
                JOIN roles r ON u.role_id = r.id 
                WHERE u.username = ? AND u.is_active = 1";
        
        $user = $this->db->fetch($sql, [$username]);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        
        return false;
    }
    
    /**
     * Crear usuario con contraseña hasheada
     */
    public function createUser($data) {
        // Hash de la contraseña
        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password']); // Remover contraseña en texto plano
        
        return $this->create($data);
    }
    
    /**
     * Actualizar usuario con contraseña hasheada (si se proporciona)
     */
    public function updateUser($id, $data) {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            unset($data['password']);
        }
        
        return $this->update($id, $data);
    }
    
    /**
     * Obtener usuarios con información de rol
     */
    public function getUsersWithRoles() {
        $sql = "SELECT u.*, r.name as role_name, r.description as role_description 
                FROM users u 
                JOIN roles r ON u.role_id = r.id 
                ORDER BY u.created_at DESC";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Obtener usuario por username
     */
    public function findByUsername($username) {
        return $this->findBy('username', $username);
    }
    
    /**
     * Verificar si el username existe
     */
    public function usernameExists($username, $excludeId = null) {
        $sql = "SELECT COUNT(*) as total FROM users WHERE username = ?";
        $params = [$username];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['total'] > 0;
    }
    
    /**
     * Verificar si el email existe
     */
    public function emailExists($email, $excludeId = null) {
        $sql = "SELECT COUNT(*) as total FROM users WHERE email = ?";
        $params = [$email];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['total'] > 0;
    }
    
    /**
     * Obtener usuarios por rol
     */
    public function getUsersByRole($roleName) {
        $sql = "SELECT u.*, r.name as role_name 
                FROM users u 
                JOIN roles r ON u.role_id = r.id 
                WHERE r.name = ? AND u.is_active = 1 
                ORDER BY u.full_name";
        
        return $this->db->fetchAll($sql, [$roleName]);
    }
    
    /**
     * Eliminar usuario
     */
    public function deleteUser($id) {
        $sql = "DELETE FROM users WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
    
    /**
     * Habilitar/Deshabilitar usuario
     */
    public function toggleUserStatus($id) {
        $sql = "UPDATE users SET is_active = NOT is_active WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
    
    /**
     * Obtener coordinadores con sus asesores
     */
    public function getCoordinatorsWithAdvisors() {
        $sql = "SELECT 
                    c.id as coordinator_id,
                    c.full_name as coordinator_name,
                    c.email as coordinator_email,
                    a.id as advisor_id,
                    a.full_name as advisor_name,
                    a.email as advisor_email
                FROM users c
                LEFT JOIN coordinator_advisor ca ON c.id = ca.coordinator_id
                LEFT JOIN users a ON ca.advisor_id = a.id
                WHERE c.role_id = (SELECT id FROM roles WHERE name = 'coordinador')
                AND c.is_active = 1
                ORDER BY c.full_name, a.full_name";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Obtener conteo de usuarios por rol
     */
    public function getUserCountByRole() {
        $sql = "SELECT r.name as role_name, COUNT(u.id) as user_count 
                FROM roles r 
                LEFT JOIN users u ON r.id = u.role_id AND u.is_active = 1
                GROUP BY r.id, r.name 
                ORDER BY r.name ASC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Obtener asesores por coordinador
     */
    public function getAdvisorsByCoordinator($coordinatorId) {
        $sql = "SELECT u.*, r.name as role_name
                FROM users u
                LEFT JOIN roles r ON u.role_id = r.id
                WHERE u.coordinator_id = ? AND u.role_id = 4 AND u.is_active = 1
                ORDER BY u.full_name ASC";
        return $this->db->fetchAll($sql, [$coordinatorId]);
    }
    
    /**
     * Obtener coordinadores
     */
    public function getCoordinators() {
        $sql = "SELECT u.*, r.name as role_name
                FROM users u
                LEFT JOIN roles r ON u.role_id = r.id
                WHERE u.role_id = 3 AND u.is_active = 1
                ORDER BY u.full_name ASC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Asignar asesor a coordinador
     */
    public function assignAdvisorToCoordinator($advisorId, $coordinatorId) {
        $sql = "UPDATE users SET coordinator_id = ? WHERE id = ? AND role_id = 4";
        return $this->db->query($sql, [$coordinatorId, $advisorId]);
    }

    public function getAdvisorPerformanceByCoordinator($coordinatorId) {
        $sql = "SELECT 
                    u.id,
                    u.full_name as name,
                    COUNT(DISTINCT a.id) as assigned_accounts,
                    COUNT(DISTINCT i.id) as total_interactions,
                    COALESCE(SUM(p.amount), 0) as collected_amount,
                    ROUND((COUNT(DISTINCT CASE WHEN i.contacted = 1 THEN i.id END) * 100.0 / NULLIF(COUNT(DISTINCT i.id), 0)), 2) as success_rate,
                    COUNT(DISTINCT CASE WHEN i.promise_amount > 0 THEN i.id END) as payment_promises
                FROM users u
                LEFT JOIN accounts a ON a.assigned_advisor_id = u.id
                LEFT JOIN interactions i ON i.advisor_id = u.id
                LEFT JOIN payments p ON p.reported_by = u.id
                WHERE u.coordinator_id = ? AND u.role_id = 4 AND u.is_active = 1
                GROUP BY u.id, u.full_name
                ORDER BY collected_amount DESC";
        return $this->db->fetchAll($sql, [$coordinatorId]);
    }
}
