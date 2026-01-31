<?php
require_once 'includes/session.php';
require_once 'classes/User.php';
require_once 'classes/Product.php';
require_once 'classes/News.php';
require_once 'classes/Contract.php';

// Require admin
requireAdmin();

$product = new Product();
$news = new News();
$contract = new Contract();

$total_products = count($product->getAll());
$total_news = count($news->getAll());
$pending_contracts = $contract->getPendingCount();
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Auto Heaven Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: #1a1a1a;
            border: 2px solid #333;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .stat-number {
            font-size: 2.5rem;
            color: #c00;
            font-weight: bold;
        }
        .stat-label {
            color: #999;
            margin-top: 10px;
        }
        .admin-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 30px;
        }
        .admin-link {
            background: #c00;
            color: white;
            padding: 15px;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            transition: background 0.3s;
            display: inline-block;
            cursor: pointer;
            font-weight: bold;
        }
        .admin-link:hover {
            background: #900;
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
            <span id="userGreeting">Përshëndetje, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
            <a href="login.php" id="loginLink" class="btn">Login</a>
            <button id="logoutBtn" class="btn logout">Logout</button>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Admin Dashboard</h1>
            <p>Mirë se vini, <?php echo $_SESSION['user_name']; ?></p>
        </div>

        <div class="dashboard-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_products; ?></div>
                <div class="stat-label">Produkte</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_news; ?></div>
                <div class="stat-label">Lajme</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $pending_contracts; ?></div>
                <div class="stat-label">Mesazhe në Pritje</div>
            </div>
        </div>

        <h2 style="margin-bottom: 20px;">Menaxhimi</h2>
        <div class="admin-links">
            <a href="admin-products.php" class="admin-link">Menaxhoni Produktet</a>
            <a href="admin-news.php" class="admin-link">Menaxhoni Lajmet</a>
            <a href="admin-users.php" class="admin-link">Menaxhoni Përdoruesit</a>
            <a href="admin-contracts.php" class="admin-link">Menaxhoni Mesazhet</a>
        </div>
    </div>

    <script src="assets/js/auth.js"></script>
</body>
</html>