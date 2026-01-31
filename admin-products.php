<?php
require_once 'includes/session.php';
require_once 'classes/Product.php';

requireAdmin();

$product = new Product();
$action = $_GET['action'] ?? '';
$message = '';

// Delete product
if ($_GET['delete'] ?? false) {
    $product->delete($_GET['delete']);
    $message = 'Produkti u fshirë me sukses!';
}

// Add product
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    // Server-side validation
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = $_POST['price'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    if (strlen($name) < 2) {
        $message = 'Emri i produktit duhet të jetë të paktën 2 karaktere.';
    } elseif (!is_numeric($price) || floatval($price) <= 0) {
        $message = 'Vendosni një çmim të vlefshëm.';
    } elseif (!is_numeric($stock) || intval($stock) < 0) {
        $message = 'Sasia duhet të jetë një numër i vlefshëm.';
    } else {
        // Handle file upload
        $uploadedName = null;
        if (!empty($_FILES['image']['name'])) {
            $allowed = ['image/jpeg','image/png','image/gif','application/pdf'];
            if (in_array($_FILES['image']['type'], $allowed) && $_FILES['image']['size'] <= 5 * 1024 * 1024) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $uploadedName = 'uploads/' . uniqid('prod_', true) . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/assets/' . $uploadedName);
            }
        }
        $author_id = $_SESSION['user_id'] ?? null;
        if ($product->create($name, $description, $price, $stock, $uploadedName, $author_id)) {
            $message = 'Produkti u shtua me sukses!';
        } else {
            $message = 'Gabim gjatë shtimit të produktit!';
        }
    }
}

// Update product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    // Server-side validation for update
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = $_POST['price'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    if (strlen($name) < 2) {
        $message = 'Emri i produktit duhet të jetë të paktën 2 karaktere.';
    } elseif (!is_numeric($price) || floatval($price) <= 0) {
        $message = 'Vendosni një çmim të vlefshëm.';
    } elseif (!is_numeric($stock) || intval($stock) < 0) {
        $message = 'Sasia duhet të jetë një numër i vlefshëm.';
    } else {
        $uploadedName = null;
        if (!empty($_FILES['image']['name'])) {
            $allowed = ['image/jpeg','image/png','image/gif','application/pdf'];
            if (in_array($_FILES['image']['type'], $allowed) && $_FILES['image']['size'] <= 5 * 1024 * 1024) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $uploadedName = 'uploads/' . uniqid('prod_', true) . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/assets/' . $uploadedName);
            }
        }
        if ($product->update($_POST['product_id'], $name, $description, $price, $stock, $uploadedName)) {
            $message = 'Produkti u përditësua me sukses!';
        } else {
            $message = 'Gabim gjatë përditësimit të produktit!';
        }
    }
}

$products = $product->getAll();
$edit_product = null;

if ($action === 'edit' && isset($_GET['id'])) {
    $edit_product = $product->getById($_GET['id']);
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menaxhoni Produktet - Auto Heaven</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .admin-form {
            background: #1a1a1a;
            border: 2px solid #333;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 40px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #c00;
            font-weight: bold;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #333;
            border-radius: 5px;
            background: #0a0a0a;
            color: #fff;
            font-family: Arial, sans-serif;
            box-sizing: border-box;
        }
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }
        .form-buttons {
            display: flex;
            gap: 10px;
        }
        .btn-submit {
            background: #c00;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-submit:hover {
            background: #900;
        }
        .btn-cancel {
            background: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-cancel:hover {
            background: #444;
        }
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            background: #0d3d0d;
            color: #0f0;
            border: 1px solid #0f0;
        }
        .message.error {
            background: #3d0d0d;
            color: #f00;
            border: 1px solid #f00;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            background: #1a1a1a;
            border: 2px solid #333;
            border-radius: 10px;
            overflow: hidden;
        }
        .products-table th {
            background: #c00;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: bold;
        }
        .products-table td {
            padding: 15px;
            border-bottom: 1px solid #333;
            color: #ccc;
        }
        .products-table tr:hover {
            background: #222;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .btn-edit, .btn-delete {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background 0.3s;
        }
        .btn-edit {
            background: #333;
            color: #fff;
        }
        .btn-edit:hover {
            background: #555;
        }
        .btn-delete {
            background: #c00;
            color: white;
        }
        .btn-delete:hover {
            background: #900;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #c00;
            text-decoration: none;
            font-weight: bold;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <!-- Hidden element to store user info for auth.js -->
        <?php if (isLoggedIn()): ?>
            <div id="userInfo" data-user-name="<?php echo htmlspecialchars($_SESSION['user_name']); ?>"></div>
        <?php endif; ?>
        <div class="logo">Auto Heaven Admin</div>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="admin-products.php">Produktet</a>
            <a href="admin-news.php">Lajmet</a>
            <a href="admin-users.php">Përdoruesit</a>
            <a href="admin-contracts.php">Mesazhet</a>
        </div>
        <div class="user-area">
            <span id="userGreeting" <?php if (isLoggedIn()) { echo 'style="display:inline-block;"'; } ?>>Përshëndetje, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
            <button id="logoutBtn" class="btn logout" <?php if (isLoggedIn()) { echo 'style="display:inline-block;"'; } ?>>Logout</button>
        </div>
    </nav>

    <div class="admin-container">
        <a href="dashboard.php" class="back-link">← Kthehu në Dashboard</a>

        <div class="admin-header">
            <h1>Menaxhoni Produktet</h1>
        </div>

        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'Gabim') !== false ? 'error' : ''; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="admin-form">
            <h2><?php echo $edit_product ? 'Përditëso Produktin' : 'Shto Produkt të Ri'; ?></h2>
            <form id="adminProductForm" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Emri i Produktit *</label>
                    <input type="text" id="name" name="name" value="<?php echo $edit_product['name'] ?? ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="description">Përshkrimi</label>
                    <textarea id="description" name="description"><?php echo $edit_product['description'] ?? ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="price">Çmimi (€) *</label>
                    <input type="number" id="price" name="price" step="0.01" value="<?php echo $edit_product['price'] ?? ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="stock">Sasia në Stok *</label>
                    <input type="number" id="stock" name="stock" value="<?php echo $edit_product['stock'] ?? '0'; ?>" required>
                </div>
                <div class="form-group">
                    <label for="image">Foto / PDF (opsional)</label>
                    <input type="file" id="image" name="image" accept="image/*,application/pdf">
                </div>

                <div class="form-buttons">
                    <?php if ($edit_product): ?>
                        <input type="hidden" name="product_id" value="<?php echo $edit_product['id']; ?>">
                        <button type="submit" name="update_product" class="btn-submit">Përditëso Produktin</button>
                        <a href="admin-products.php" class="btn-cancel">Anulo</a>
                    <?php else: ?>
                        <button type="submit" name="add_product" class="btn-submit">Shto Produktin</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <h2>Lista e Produkteve</h2>
        <table class="products-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Emri</th>
                    <th>Çmimi</th>
                    <th>Sasia</th>
                    <th>E Krijuar</th>
                    <th>Veprimet</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($products): ?>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td><?php echo $p['id']; ?></td>
                            <td><?php echo htmlspecialchars($p['name']); ?></td>
                            <td>€<?php echo number_format($p['price'], 2); ?></td>
                            <td><?php echo $p['stock']; ?></td>
                            <td><?php echo date('d.m.Y', strtotime($p['created_at'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?action=edit&id=<?php echo $p['id']; ?>" class="btn-edit">Redakto</a>
                                    <a href="?delete=<?php echo $p['id']; ?>" class="btn-delete" onclick="return confirm('Jeni i sigurt?')">Fshi</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #999;">Nuk ka produkte të regjistruara</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="assets/js/forms.js"></script>
    <script src="assets/js/auth.js"></script>
</body>
</html>