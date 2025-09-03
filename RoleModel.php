<?php
/**
 * Modelo de Roles
 * CRM de Cobranzas - Gestión de roles
 */

require_once 'config/database.php';

class RoleModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Obtener todos los roles
     */
    public function getAll() {
        $sql = "SELECT * FROM roles ORDER BY name ASC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Obtener rol por ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM roles WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Verificar si el nombre del rol existe
     */
    public function nameExists($name, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM roles WHERE name = ?";
        $params = [$name];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] > 0;
    }
    
    /**
     * Crear nuevo rol
     */
    public function create($data) {
        $sql = "INSERT INTO roles (name, description) VALUES (?, ?)";
        return $this->db->query($sql, [$data['name'], $data['description']]);
    }
    
    /**
     * Actualizar rol
     */
    public function update($id, $data) {
        $sql = "UPDATE roles SET name = ?, description = ? WHERE id = ?";
        return $this->db->query($sql, [$data['name'], $data['description'], $id]);
    }
    
    /**
     * Eliminar rol
     */
    public function delete($id) {
        $sql = "DELETE FROM roles WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
    
    /**
     * Verificar si el rol está en uso
     */
    public function isInUse($id) {
        $sql = "SELECT COUNT(*) as count FROM users WHERE role_id = ?";
        $result = $this->db->fetch($sql, [$id]);
        return $result['count'] > 0;
    }
    
    /**
     * Obtener roles con conteo de usuarios
     */
    public function getRolesWithUserCount() {
        $sql = "SELECT r.*, COUNT(u.id) as user_count 
                FROM roles r 
                LEFT JOIN users u ON r.id = u.role_id 
                GROUP BY r.id 
                ORDER BY r.name ASC";
        return $this->db->fetchAll($sql);
    }
}

