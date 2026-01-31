<?php
require_once __DIR__ . '/../config/Database.php';

class News {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    // Get all news
    public function getAll() {
        $stmt = $this->db->prepare("SELECT n.*, u.name as author_name FROM news n 
                                   LEFT JOIN users u ON n.author_id = u.id 
                                   ORDER BY n.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get news by ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT n.*, u.name as author_name FROM news n 
                                   LEFT JOIN users u ON n.author_id = u.id 
                                   WHERE n.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Create news (admin only)
    public function create($title, $content, $author_id, $image = null) {
        $stmt = $this->db->prepare("INSERT INTO news (title, content, author_id, image) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$title, $content, $author_id, $image]);
    }

    // Update news (admin only)
    public function update($id, $title, $content, $image = null) {
        if ($image) {
            $stmt = $this->db->prepare("UPDATE news SET title = ?, content = ?, image = ? WHERE id = ?");
            return $stmt->execute([$title, $content, $image, $id]);
        } else {
            $stmt = $this->db->prepare("UPDATE news SET title = ?, content = ? WHERE id = ?");
            return $stmt->execute([$title, $content, $id]);
        }
    }

    // Delete news (admin only)
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM news WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Get latest news
    public function getLatest($limit = 5) {
        $stmt = $this->db->prepare("SELECT n.*, u.name as author_name FROM news n 
                                   LEFT JOIN users u ON n.author_id = u.id 
                                   ORDER BY n.created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}
?>