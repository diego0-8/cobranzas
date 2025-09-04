<?php

class ObligationModel
{
    protected $table = 'obligations';
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (interaction_id, account_id, payment_date, total_installments, 
                 total_agreement_value, installment_number, installment_value, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['interaction_id'],
            $data['account_id'],
            $data['payment_date'],
            $data['total_installments'],
            $data['total_agreement_value'],
            $data['installment_number'],
            $data['installment_value'],
            $data['status'] ?? 'pending'
        ];
        
        $result = $this->db->query($sql, $params);
        return $result ? $this->db->lastInsertId() : false;
    }

    public function getByInteraction($interactionId)
    {
        $sql = "SELECT o.*, a.id as account_id, d.full_name as debtor_name 
                FROM {$this->table} o
                JOIN accounts a ON o.account_id = a.id
                JOIN debtors d ON a.debtor_id = d.id
                WHERE o.interaction_id = ? 
                ORDER BY o.created_at DESC";
        
        return $this->db->fetchAll($sql, [$interactionId]);
    }

    public function getById($id)
    {
        $sql = "SELECT o.*, a.id as account_id, d.full_name as debtor_name 
                FROM {$this->table} o
                JOIN accounts a ON o.account_id = a.id
                JOIN debtors d ON a.debtor_id = d.id
                WHERE o.id = ?";
        
        return $this->db->fetch($sql, [$id]);
    }

    public function update($id, $data)
    {
        $fields = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            $fields[] = "{$key} = ?";
            $params[] = $value;
        }
        
        $params[] = $id;
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";
        
        return $this->db->query($sql, $params);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }

    public function getByAccount($accountId)
    {
        $sql = "SELECT o.*, i.created_at as interaction_date, u.full_name as advisor_name
                FROM {$this->table} o
                JOIN interactions i ON o.interaction_id = i.id
                JOIN users u ON i.advisor_id = u.id
                WHERE o.account_id = ? 
                ORDER BY o.created_at DESC";
        
        return $this->db->fetchAll($sql, [$accountId]);
    }

    public function getPendingByAccount($accountId)
    {
        $sql = "SELECT o.*, i.created_at as interaction_date, u.full_name as advisor_name
                FROM {$this->table} o
                JOIN interactions i ON o.interaction_id = i.id
                JOIN users u ON i.advisor_id = u.id
                WHERE o.account_id = ? AND o.status = 'pending'
                ORDER BY o.payment_date ASC";
        
        return $this->db->fetchAll($sql, [$accountId]);
    }

    public function markAsPaid($id)
    {
        $sql = "UPDATE {$this->table} SET status = 'paid', updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }

    public function markAsCancelled($id)
    {
        $sql = "UPDATE {$this->table} SET status = 'cancelled', updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }

    public function getStatsByAdvisor($advisorId, $startDate = null, $endDate = null)
    {
        $whereClause = "i.advisor_id = ?";
        $params = [$advisorId];
        
        if ($startDate) {
            $whereClause .= " AND o.created_at >= ?";
            $params[] = $startDate;
        }
        
        if ($endDate) {
            $whereClause .= " AND o.created_at <= ?";
            $params[] = $endDate;
        }
        
        $sql = "SELECT 
                    COUNT(*) as total_obligations,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as paid,
                    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
                    SUM(CASE WHEN status = 'paid' THEN installment_value ELSE 0 END) as total_collected
                FROM {$this->table} o
                JOIN interactions i ON o.interaction_id = i.id
                WHERE {$whereClause}";
        
        return $this->db->fetch($sql, $params);
    }
}
?>
