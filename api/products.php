<?php
header('Content-Type: application/json');

require_once 'classes/Product.php';

$product = new Product();
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'all':
        echo json_encode($product->getAll());
        break;
    
    case 'get':
        $id = $_GET['id'] ?? 0;
        if ($id) {
            echo json_encode($product->getById($id));
        } else {
            echo json_encode(['error' => 'ID not provided']);
        }
        break;
    
    case 'search':
        $keyword = $_GET['keyword'] ?? '';
        if ($keyword) {
            echo json_encode($product->search($keyword));
        } else {
            echo json_encode([]);
        }
        break;
    
    default:
        echo json_encode(['error' => 'Action not found']);
}
?>
