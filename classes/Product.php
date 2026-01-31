<?php
require_once __DIR__ . '/../config/Database.php';

class Product {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    // Get all products
    public function getAll() {
        $stmt = $this->db->prepare("SELECT p.*, u.name as author_name FROM products p LEFT JOIN users u ON p.author_id = u.id ORDER BY p.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get product by ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Create product (admin only)
    public function create($name, $description, $price, $stock, $image = null, $author_id = null) {
        $stmt = $this->db->prepare("INSERT INTO products (name, description, price, image, stock, author_id) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $description, $price, $image, $stock, $author_id]);
    }

    // Update product (admin only)
    public function update($id, $name, $description, $price, $stock, $image = null) {
        if ($image) {
            $stmt = $this->db->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ?, stock = ? WHERE id = ?");
            return $stmt->execute([$name, $description, $price, $image, $stock, $id]);
        } else {
            $stmt = $this->db->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ? WHERE id = ?");
            return $stmt->execute([$name, $description, $price, $stock, $id]);
        }
    }

    // Delete product (admin only)
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Search products
    public function search($keyword) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ? ORDER BY created_at DESC");
        $searchTerm = "%{$keyword}%";
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }
}
?>