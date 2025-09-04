<?php
/**
 * Modelo de Canales de Contacto
 * CRM de Cobranzas - Gestión de canales de comunicación
 */

require_once 'config/database.php';

class ContactChannelModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Obtener todos los canales de contacto
     */
    public function getAll() {
        $sql = "SELECT * FROM contact_channels WHERE is_active = 1 ORDER BY name ASC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Obtener canal por ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM contact_channels WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Obtener canal por código
     */
    public function getByCode($code) {
        $sql = "SELECT * FROM contact_channels WHERE code = ?";
        return $this->db->fetch($sql, [$code]);
    }
    
    /**
     * Crear nuevo canal
     */
    public function create($data) {
        $sql = "INSERT INTO contact_channels (code, name, is_active) VALUES (?, ?, ?)";
        return $this->db->query($sql, [
            $data['code'],
            $data['name'],
            $data['is_active'] ?? 1
        ]);
    }
    
    /**
     * Actualizar canal
     */
    public function update($id, $data) {
        $sql = "UPDATE contact_channels SET code = ?, name = ?, is_active = ? WHERE id = ?";
        return $this->db->query($sql, [
            $data['code'],
            $data['name'],
            $data['is_active'] ?? 1,
            $id
        ]);
    }
    
    /**
     * Eliminar canal
     */
    public function delete($id) {
        $sql = "DELETE FROM contact_channels WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
    
    /**
     * Toggle estado del canal
     */
    public function toggleStatus($id) {
        $sql = "UPDATE contact_channels SET is_active = NOT is_active WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
}
