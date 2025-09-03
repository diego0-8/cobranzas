<?php
/**
 * Modelo Base
 * CRM de Cobranzas - Clase base para todos los modelos
 */

require_once 'config/database.php';

abstract class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Obtener todos los registros
     */
    public function getAll($orderBy = null, $limit = null) {
        $sql = "SELECT * FROM {$this->table}";
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Obtener registro por ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Crear nuevo registro
     */
    public function create($data) {
        $fields = array_keys($data);
        $placeholders = str_repeat('?,', count($fields) - 1) . '?';
        
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") VALUES ({$placeholders})";
        
        $this->db->query($sql, array_values($data));
        return $this->db->lastInsertId();
    }
    
    /**
     * Actualizar registro
     */
    public function update($id, $data) {
        $fields = array_keys($data);
        $setClause = implode(' = ?, ', $fields) . ' = ?';
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = ?";
        
        $values = array_values($data);
        $values[] = $id;
        
        return $this->db->query($sql, $values);
    }
    
    /**
     * Eliminar registro
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return $this->db->query($sql, [$id]);
    }
    
    /**
     * Buscar por campo específico
     */
    public function findBy($field, $value) {
        $sql = "SELECT * FROM {$this->table} WHERE {$field} = ?";
        return $this->db->fetch($sql, [$value]);
    }
    
    /**
     * Buscar múltiples registros por campo
     */
    public function findAllBy($field, $value, $orderBy = null) {
        $sql = "SELECT * FROM {$this->table} WHERE {$field} = ?";
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        return $this->db->fetchAll($sql, [$value]);
    }
    
    /**
     * Contar registros
     */
    public function count($where = null, $params = []) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['total'];
    }
    
    /**
     * Paginación
     */
    public function paginate($page = 1, $perPage = ITEMS_PER_PAGE, $orderBy = null, $where = null, $params = []) {
        $offset = ($page - 1) * $perPage;
        
        // Contar total de registros
        $total = $this->count($where, $params);
        
        // Obtener registros de la página
        $sql = "SELECT * FROM {$this->table}";
        
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";
        
        $data = $this->db->fetchAll($sql, $params);
        
        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ];
    }
    
    /**
     * Ejecutar consulta personalizada
     */
    public function query($sql, $params = []) {
        return $this->db->query($sql, $params);
    }
    
    /**
     * Ejecutar consulta personalizada y obtener todos los resultados
     */
    public function queryAll($sql, $params = []) {
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Ejecutar consulta personalizada y obtener un resultado
     */
    public function queryOne($sql, $params = []) {
        return $this->db->fetch($sql, $params);
    }
}
