<?php
/**
 * Modelo de Tipificaciones
 * CRM de Cobranzas - Gestión de tipificaciones
 */

require_once 'config/database.php';

class TypificationModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Obtener todas las tipificaciones
     */
    public function getAll() {
        $sql = "SELECT t.*, tc.name as category_name 
                FROM typifications t 
                LEFT JOIN typification_categories tc ON t.category_id = tc.id 
                ORDER BY t.name ASC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Obtener tipificaciones activas
     */
    public function getActive() {
        $sql = "SELECT t.*, tc.name as category_name 
                FROM typifications t 
                LEFT JOIN typification_categories tc ON t.category_id = tc.id 
                WHERE t.is_active = 1 
                ORDER BY t.name ASC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Obtener tipificaciones por categoría
     */
    public function getByCategory($categoryId) {
        $sql = "SELECT t.*, tc.name as category_name 
                FROM typifications t 
                LEFT JOIN typification_categories tc ON t.category_id = tc.id 
                WHERE t.category_id = ? AND t.is_active = 1 
                ORDER BY t.name ASC";
        return $this->db->fetchAll($sql, [$categoryId]);
    }
    
    /**
     * Obtener tipificación por ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM typifications WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Verificar si el nombre de la tipificación existe
     */
    public function nameExists($name, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM typifications WHERE name = ?";
        $params = [$name];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] > 0;
    }
    
    /**
     * Verificar si el código de la tipificación existe
     */
    public function codeExists($code, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM typifications WHERE code = ?";
        $params = [$code];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] > 0;
    }
    
    /**
     * Crear nueva tipificación
     */
    public function create($data) {
        $sql = "INSERT INTO typifications (name, code, category_id) VALUES (?, ?, ?)";
        return $this->db->query($sql, [
            $data['name'], 
            $data['code'] ?? '', 
            $data['category_id']
        ]);
    }
    
    /**
     * Actualizar tipificación
     */
    public function update($id, $data) {
        $sql = "UPDATE typifications SET name = ?, code = ?, category_id = ? WHERE id = ?";
        return $this->db->query($sql, [
            $data['name'], 
            $data['code'] ?? '', 
            $data['category_id'], 
            $id
        ]);
    }
    
    /**
     * Eliminar tipificación
     */
    public function delete($id) {
        $sql = "DELETE FROM typifications WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
    
    /**
     * Toggle estado de tipificación
     */
    public function toggleStatus($id) {
        $sql = "UPDATE typifications SET is_active = NOT is_active WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
    
    /**
     * Verificar si la tipificación está en uso
     */
    public function isInUse($id) {
        $sql = "SELECT COUNT(*) as count FROM interactions WHERE typification_id = ?";
        $result = $this->db->fetch($sql, [$id]);
        return $result['count'] > 0;
    }
    
    /**
     * Obtener tipificaciones con conteo de uso
     */
    public function getWithUsageCount() {
        $sql = "SELECT t.*, tc.name as category_name, COUNT(i.id) as usage_count 
                FROM typifications t 
                LEFT JOIN typification_categories tc ON t.category_id = tc.id 
                LEFT JOIN interactions i ON t.id = i.typification_id 
                GROUP BY t.id 
                ORDER BY t.name ASC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Obtener categorías disponibles
     */
    public function getCategories() {
        $sql = "SELECT * FROM typification_categories ORDER BY name ASC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Obtener categorías de acción (nivel 1)
     */
    public function getActionCategories() {
        $sql = "SELECT * FROM typification_categories WHERE level = 1 AND is_active = 1 ORDER BY sort_order ASC, name ASC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Obtener tipificaciones por categoría y nivel
     */
    public function getTypificationsByCategoryAndLevel($categoryId, $level, $parentId = null) {
        $sql = "SELECT * FROM typifications WHERE category_id = ? AND level = ? AND is_active = 1";
        $params = [$categoryId, $level];
        
        if ($parentId !== null) {
            $sql .= " AND parent_id = ?";
            $params[] = $parentId;
        }
        
        $sql .= " ORDER BY sort_order ASC, name ASC";
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Obtener todas las tipificaciones para la jerarquía (JavaScript)
     */
    public function getAllForHierarchy() {
        $sql = "SELECT t.*, tc.name as category_name 
                FROM typifications t 
                LEFT JOIN typification_categories tc ON t.category_id = tc.id 
                WHERE t.is_active = 1 
                ORDER BY t.category_id, t.level, t.sort_order, t.name";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Obtener canales autorizados
     */
    public function getAuthorizedChannels() {
        $sql = "SELECT * FROM authorized_channels WHERE is_active = 1 ORDER BY name ASC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Obtener tipificación con detalles completos
     */
    public function getTypificationWithDetails($id) {
        $sql = "SELECT t.*, tc.name as category_name, 
                       parent.name as parent_name,
                       parent.code as parent_code
                FROM typifications t 
                LEFT JOIN typification_categories tc ON t.category_id = tc.id 
                LEFT JOIN typifications parent ON t.parent_id = parent.id
                WHERE t.id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Obtener jerarquía completa de tipificaciones
     */
    public function getTypificationHierarchy($actionCategoryId) {
        $sql = "SELECT t.*, tc.name as category_name,
                       CASE 
                           WHEN t.level = 2 THEN 'contact'
                           WHEN t.level = 3 THEN 'profile'
                           ELSE 'action'
                       END as typification_type
                FROM typifications t 
                LEFT JOIN typification_categories tc ON t.category_id = tc.id 
                WHERE t.category_id = ? AND t.is_active = 1 
                ORDER BY t.level, t.sort_order, t.name";
        return $this->db->fetchAll($sql, [$actionCategoryId]);
    }
}

