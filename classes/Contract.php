<?php
require_once __DIR__ . '/../config/Database.php';

class Contract {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    // Create contract/message
    public function create($user_id, $subject, $message, $product_id = null) {
        $stmt = $this->db->prepare("INSERT INTO contracts (user_id, product_id, subject, message) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user_id, $product_id, $subject, $message]);
    }

    // Get all contracts
    public function getAll() {
        $stmt = $this->db->prepare("SELECT c.*, u.name, u.email FROM contracts c 
                                   JOIN users u ON c.user_id = u.id 
                                   ORDER BY c.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get user's contracts
    public function getUserContracts($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM contracts WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    // Get contract by ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT c.*, u.name, u.email FROM contracts c 
                                   JOIN users u ON c.user_id = u.id 
                                   WHERE c.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Update contract status (admin only)
    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE contracts SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    // Delete contract
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM contracts WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Get pending contracts count (admin only)
    public function getPendingCount() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM contracts WHERE status = 'pending'");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'];
    }
}
?>