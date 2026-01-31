<?php
echo "<h1>Test Lidhje Database</h1>";

$host = 'localhost';
$db = 'auto_heaven';
$user = 'root';
$pass = '';

try {
    $conn = new PDO(
        "mysql:host={$host};dbname={$db};charset=utf8mb4",
        $user,
        $pass
    );
    echo "<h2 style='color: green;'>✅ Lidhja OK!</h2>";
    echo "<p>Database: <strong>auto_heaven</strong></p>";
    
    // Kontrolloni tabela
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM products");
    $stmt->execute();
    $result = $stmt->fetch();
    echo "<p>Produkte në DB: <strong>" . $result['count'] . "</strong></p>";
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>❌ Error:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
