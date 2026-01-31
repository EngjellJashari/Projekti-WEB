<?php
require_once 'includes/session.php';
require_once 'config/Database.php';

requireAdmin();

$database = new Database();
$db = $database->connect();
$message = '';

// Delete user
if ($_GET['delete'] ?? false) {
    $stmt = $db->prepare("DELETE FROM users WHERE id = ? AND id != ?");
    if ($stmt->execute([$_GET['delete'], $_SESSION['user_id']])) {
        $message = 'Përdoruesi u fshirë me sukses!';
    } else {
        $message = 'Gabim gjatë fshirjes!';
    }
}

// Change role
if ($_GET['make_admin'] ?? false) {
    $stmt = $db->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
    $stmt->execute([$_GET['make_admin']]);
    $message = 'Përdoruesi u bë admin!';
}

if ($_GET['make_user'] ?? false) {
    $stmt = $db->prepare("UPDATE users SET role = 'user' WHERE id = ? AND id != ?");
    if ($stmt->execute([$_GET['make_user'], $_SESSION['user_id']])) {
        $message = 'Përdoruesi u kthye në përdorues të rregullt!';
    }
}

// Get all users
$stmt = $db->prepare("SELECT * FROM users ORDER BY created_at DESC");
$stmt->execute();
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menaxhoni Përdoruesit - Auto Heaven</title>
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
        .users-table {
            width: 100%;
            border-collapse: collapse;
            background: #1a1a1a;
            border: 2px solid #333;
            border-radius: 10px;
            overflow: hidden;
        }
        .users-table th {
            background: #c00;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: bold;
        }
        .users-table td {
            padding: 15px;
            border-bottom: 1px solid #333;
            color: #ccc;
        }
        .users-table tr:hover {
            background: #222;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn-admin, .btn-user, .btn-delete {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.85rem;
            transition: background 0.3s;
        }
        .btn-admin {
            background: #0a6;
            color: white;
        }
        .btn-admin:hover {
            background: #048;
        }
        .btn-user {
            background: #666;
            color: white;
        }
        .btn-user:hover {
            background: #777;
        }
        .btn-delete {
            background: #c00;
            color: white;
        }
        .btn-delete:hover {
            background: #900;
        }
        .role-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        .role-admin {
            background: #c00;
            color: white;
        }
        .role-user {
            background: #333;
            color: #ccc;
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
            <h1>Menaxhoni Përdoruesit</h1>
            <p>Gjithësej: <?php echo count($users); ?> përdorues</p>
        </div>

        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'Gabim') !== false ? 'error' : ''; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <h2>Lista e të Gjithë Përdoruesve</h2>
        <table class="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Emri</th>
                    <th>Email</th>
                    <th>Roli</th>
                    <th>I Regjistruar</th>
                    <th>Veprimet</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($users): ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?php echo $u['id']; ?></td>
                            <td><?php echo htmlspecialchars($u['name']); ?></td>
                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                            <td>
                                <span class="role-badge role-<?php echo $u['role']; ?>">
                                    <?php echo ucfirst($u['role']); ?>
                                </span>
                            </td>
                            <td><?php echo date('d.m.Y', strtotime($u['created_at'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <?php if ($u['role'] === 'user'): ?>
                                        <a href="?make_admin=<?php echo $u['id']; ?>" class="btn-admin">Bëj Admin</a>
                                    <?php else: ?>
                                        <?php if ($u['id'] !== $_SESSION['user_id']): ?>
                                            <a href="?make_user=<?php echo $u['id']; ?>" class="btn-user">Bëj Përdorues</a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if ($u['id'] !== $_SESSION['user_id']): ?>
                                        <a href="?delete=<?php echo $u['id']; ?>" class="btn-delete" onclick="return confirm('Jeni i sigurt?')">Fshi</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #999;">Nuk ka përdorues</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="assets/js/auth.js"></script>
</body>
</html>