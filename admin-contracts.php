<?php
require_once 'includes/session.php';
require_once 'classes/Contract.php';

requireAdmin();

$contract = new Contract();
$message = '';

// Update status
if ($_POST['update_status'] ?? false) {
    if ($contract->updateStatus($_POST['contract_id'], $_POST['status'])) {
        $message = 'Statusi u përditësua me sukses!';
    } else {
        $message = 'Gabim gjatë përditësimit!';
    }
}

// Delete contract
if ($_GET['delete'] ?? false) {
    $contract->delete($_GET['delete']);
    $message = 'Mesazhi u fshirë me sukses!';
}

$contracts = $contract->getAll();
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menaxhoni Mesazhet - Auto Heaven</title>
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
        .contracts-table {
            width: 100%;
            border-collapse: collapse;
            background: #1a1a1a;
            border: 2px solid #333;
            border-radius: 10px;
            overflow: hidden;
        }
        .contracts-table th {
            background: #c00;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: bold;
        }
        .contracts-table td {
            padding: 15px;
            border-bottom: 1px solid #333;
            color: #ccc;
        }
        .contracts-table tr:hover {
            background: #222;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
        }
        .modal-content {
            background-color: #1a1a1a;
            margin: 5% auto;
            padding: 30px;
            border: 2px solid #c00;
            border-radius: 10px;
            width: 80%;
            max-width: 600px;
            color: #fff;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #333;
            padding-bottom: 15px;
        }
        .close-modal {
            color: #c00;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close-modal:hover {
            color: #fff;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        .status-pending {
            background: #c00;
            color: white;
        }
        .status-reviewed {
            background: #666;
            color: white;
        }
        .status-completed {
            background: #0a6;
            color: white;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .btn-view, .btn-delete {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background 0.3s;
        }
        .btn-view {
            background: #333;
            color: #fff;
        }
        .btn-view:hover {
            background: #555;
        }
        .btn-delete {
            background: #c00;
            color: white;
        }
        .btn-delete:hover {
            background: #900;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            color: #c00;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .form-group select,
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #333;
            border-radius: 5px;
            background: #0a0a0a;
            color: #fff;
            box-sizing: border-box;
        }
        .form-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
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
            <h1>Menaxhoni Mesazhet</h1>
            <p>Gjithësej: <?php echo count($contracts); ?> mesazhe</p>
        </div>

        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'Gabim') !== false ? 'error' : ''; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <h2>Lista e Mesazheve</h2>
        <table class="contracts-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nga</th>
                    <th>Email</th>
                    <th>Tema</th>
                    <th>Statusi</th>
                    <th>Data</th>
                    <th>Veprimet</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($contracts): ?>
                    <?php foreach ($contracts as $c): ?>
                        <tr>
                            <td><?php echo $c['id']; ?></td>
                            <td><?php echo htmlspecialchars($c['name']); ?></td>
                            <td><?php echo htmlspecialchars($c['email']); ?></td>
                            <td><?php echo htmlspecialchars(substr($c['subject'], 0, 30)); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $c['status']; ?>">
                                    <?php 
                                        $status_text = [
                                            'pending' => 'Në Pritje',
                                            'reviewed' => 'Shqyrtuar',
                                            'completed' => 'Përfunduar'
                                        ];
                                        echo $status_text[$c['status']] ?? $c['status'];
                                    ?>
                                </span>
                            </td>
                            <td><?php echo date('d.m.Y H:i', strtotime($c['created_at'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-view" onclick="openModal(<?php echo $c['id']; ?>)">Shikoni</button>
                                    <a href="?delete=<?php echo $c['id']; ?>" class="btn-delete" onclick="return confirm('Jeni i sigurt?')">Fshi</a>
                                </div>
                            </td>
                        </tr>
                        <!-- Modal for viewing contract -->
                        <div id="modal-<?php echo $c['id']; ?>" class="modal">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2><?php echo htmlspecialchars($c['subject']); ?></h2>
                                    <span class="close-modal" onclick="closeModal(<?php echo $c['id']; ?>)">&times;</span>
                                </div>
                                
                                <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #333;">
                                    <p><strong style="color: #c00;">Nga:</strong> <?php echo htmlspecialchars($c['name']); ?></p>
                                    <p><strong style="color: #c00;">Email:</strong> <?php echo htmlspecialchars($c['email']); ?></p>
                                    <p><strong style="color: #c00;">Data:</strong> <?php echo date('d.m.Y H:i', strtotime($c['created_at'])); ?></p>
                                </div>

                                <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #333;">
                                    <p><strong style="color: #c00;">Mesazhi:</strong></p>
                                    <p style="white-space: pre-wrap; color: #ccc;"><?php echo htmlspecialchars($c['message']); ?></p>
                                </div>

                                <form method="POST" style="margin-top: 20px;">
                                    <input type="hidden" name="contract_id" value="<?php echo $c['id']; ?>">
                                    <div class="form-group">
                                        <label for="status-<?php echo $c['id']; ?>">Ndryshoni Statusin:</label>
                                        <select id="status-<?php echo $c['id']; ?>" name="status">
                                            <option value="pending" <?php echo $c['status'] === 'pending' ? 'selected' : ''; ?>>Në Pritje</option>
                                            <option value="reviewed" <?php echo $c['status'] === 'reviewed' ? 'selected' : ''; ?>>Shqyrtuar</option>
                                            <option value="completed" <?php echo $c['status'] === 'completed' ? 'selected' : ''; ?>>Përfunduar</option>
                                        </select>
                                    </div>
                                    <div class="form-buttons">
                                        <button type="submit" name="update_status" class="btn-submit">Përditëso Statusin</button>
                                        <button type="button" class="btn-submit" style="background: #333;" onclick="closeModal(<?php echo $c['id']; ?>)">Mbyllni</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: #999;">Nuk ka mesazhe</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function openModal(id) {
            document.getElementById('modal-' + id).style.display = 'block';
        }

        function closeModal(id) {
            document.getElementById('modal-' + id).style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
    <script src="assets/js/auth.js"></script>
</body>
</html>