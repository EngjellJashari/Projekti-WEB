<?php
require_once 'includes/session.php';
require_once 'classes/User.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Email i pavlefshëm.';
    } elseif (empty($password)) {
        $message = 'Vendosni password.';
    } else {
        $user = new User();
        if ($user->login($email, $password)) {
            if (User::isAdmin()) {
                header('Location: dashboard.php');
            } else {
                header('Location: index.php');
            }
            exit;
        } else {
            $message = 'Email ose password gabim!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Auto Heaven</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .login-container {
            background: #1a1a1a;
            padding: 40px;
            border-radius: 10px;
            border: 2px solid #c00;
            max-width: 400px;
            width: 100%;
        }
        .login-container h1 {
            color: #c00;
            margin-bottom: 30px;
            text-align: center;
        }
        .login-container form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .login-container input {
            padding: 12px;
            background: #0a0a0a;
            border: 1px solid #333;
            color: #fff;
            border-radius: 5px;
            font-size: 1rem;
        }
        .login-container input:focus {
            outline: none;
            border-color: #c00;
        }
        .login-container button {
            padding: 12px;
            background: #c00;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        .login-container button:hover {
            background: #900;
        }
        .login-container p {
            text-align: center;
            margin-top: 20px;
        }
        .login-container a {
            color: #c00;
            text-decoration: none;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
        .error-message {
            color: #f00;
            padding: 10px;
            background: #3d0d0d;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php if ($message): ?>
            <p class="error-message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form id="loginForm" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Kyçuni</button>
        </form>
        <p>Nuk ke llogari? <a href="register.php">Regjistrohu</a></p>
    </div>
    <script src="assets/js/forms.js"></script>
    <script src="assets/js/auth.js"></script>
</body>
</html>