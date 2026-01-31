<?php
require_once 'includes/session.php';
require_once 'classes/News.php';

requireAdmin();

$news = new News();
$action = $_GET['action'] ?? '';
$message = '';

// Delete news
if ($_GET['delete'] ?? false) {
    $news->delete($_GET['delete']);
    $message = 'Lajmi u fshirë me sukses!';
}

// Add news
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_news'])) {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    if (strlen($title) < 3) {
        $message = 'Titulli duhet të ketë të paktën 3 karaktere.';
    } elseif (strlen($content) < 10) {
        $message = 'Përmbajtja duhet të ketë të paktën 10 karaktere.';
    } else {
        $uploadedName = null;
        if (!empty($_FILES['image']['name'])) {
            $allowed = ['image/jpeg','image/png','image/gif','application/pdf'];
            if (in_array($_FILES['image']['type'], $allowed) && $_FILES['image']['size'] <= 5 * 1024 * 1024) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $uploadedName = 'uploads/' . uniqid('news_', true) . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/assets/' . $uploadedName);
            }
        }
        if ($news->create($title, $content, $_SESSION['user_id'], $uploadedName)) {
            $message = 'Lajmi u shtua me sukses!';
        } else {
            $message = 'Gabim gjatë shtimit të lajmit!';
        }
    }
}

// Update news
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_news'])) {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    if (strlen($title) < 3) {
        $message = 'Titulli duhet të ketë të paktën 3 karaktere.';
    } elseif (strlen($content) < 10) {
        $message = 'Përmbajtja duhet të ketë të paktën 10 karaktere.';
    } else {
        $uploadedName = null;
        if (!empty($_FILES['image']['name'])) {
            $allowed = ['image/jpeg','image/png','image/gif','application/pdf'];
            if (in_array($_FILES['image']['type'], $allowed) && $_FILES['image']['size'] <= 5 * 1024 * 1024) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $uploadedName = 'uploads/' . uniqid('news_', true) . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/assets/' . $uploadedName);
            }
        }
        if ($news->update($_POST['news_id'], $title, $content, $uploadedName)) {
            $message = 'Lajmi u përditësua me sukses!';
        } else {
            $message = 'Gabim gjatë përditësimit të lajmit!';
        }
    }
}

$all_news = $news->getAll();
$edit_news = null;

if ($action === 'edit' && isset($_GET['id'])) {
    $edit_news = $news->getById($_GET['id']);
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menaxhoni Lajmet - Auto Heaven</title>
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
            min-height: 200px;
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
        .news-table {
            width: 100%;
            border-collapse: collapse;
            background: #1a1a1a;
            border: 2px solid #333;
            border-radius: 10px;
            overflow: hidden;
        }
        .news-table th {
            background: #c00;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: bold;
        }
        .news-table td {
            padding: 15px;
            border-bottom: 1px solid #333;
            color: #ccc;
        }
        .news-table tr:hover {
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
            <h1>Menaxhoni Lajmet</h1>
        </div>

        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'Gabim') !== false ? 'error' : ''; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="admin-form">
            <h2><?php echo $edit_news ? 'Përditëso Lajmin' : 'Shto Lajm të Ri'; ?></h2>
            <form id="adminNewsForm" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Titulli *</label>
                    <input type="text" id="title" name="title" value="<?php echo $edit_news['title'] ?? ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="content">Përmbajtja *</label>
                    <textarea id="content" name="content" required><?php echo $edit_news['content'] ?? ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Foto / PDF (opsional)</label>
                    <input type="file" id="image" name="image" accept="image/*,application/pdf">
                </div>

                <div class="form-buttons">
                    <?php if ($edit_news): ?>
                        <input type="hidden" name="news_id" value="<?php echo $edit_news['id']; ?>">
                        <button type="submit" name="update_news" class="btn-submit">Përditëso Lajmin</button>
                        <a href="admin-news.php" class="btn-cancel">Anulo</a>
                    <?php else: ?>
                        <button type="submit" name="add_news" class="btn-submit">Shto Lajmin</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <h2>Lista e Lajmeve</h2>
        <table class="news-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titulli</th>
                    <th>Autori</th>
                    <th>E Krijuar</th>
                    <th>Veprimet</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($all_news): ?>
                    <?php foreach ($all_news as $n): ?>
                        <tr>
                            <td><?php echo $n['id']; ?></td>
                            <td><?php echo htmlspecialchars(substr($n['title'], 0, 50)); ?></td>
                            <td><?php echo $n['author_name'] ?? 'I panjohur'; ?></td>
                            <td><?php echo date('d.m.Y', strtotime($n['created_at'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?action=edit&id=<?php echo $n['id']; ?>" class="btn-edit">Redakto</a>
                                    <a href="?delete=<?php echo $n['id']; ?>" class="btn-delete" onclick="return confirm('Jeni i sigurt?')">Fshi</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: #999;">Nuk ka lajme të regjistruara</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="assets/js/forms.js"></script>
    <script src="assets/js/auth.js"></script>
</body>
</html>