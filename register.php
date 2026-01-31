<?php
require_once 'includes/session.php';
require_once 'classes/User.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm = $_POST['confirm_password'] ?? '';

  // Basic validation
  if (strlen($name) < 3) {
    $message = 'Emri duhet të ketë të paktën 3 karaktere.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = 'Email i pavlefshëm.';
  } elseif ($password !== $confirm) {
    $message = 'Passwordet nuk përputhen!';
  } elseif (strlen($password) < 6) {
    $message = 'Password duhet të jetë të paktën 6 karaktere.';
  } else {
    $user = new User();
    if ($user->existsByEmail($email)) {
      $message = 'Email është regjistruar tashmë.';
    } else {
      if ($user->register($name, $email, $password)) {
        $message = 'Regjistrim i suksesshëm! <a href="login.php">Kyçuni këtu</a>';
      } else {
        $message = 'Gabim gjatë regjistrit.';
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register - Auto Heaven</title>
  <link rel="icon" type="image/svg+xml" href="assets/img/favicon.svg" />
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
  <!-- Hidden element to store user info from session -->
  <?php if (isLoggedIn()): ?>
    <div id="userInfo" data-user-name="<?php echo htmlspecialchars($_SESSION['user_name']); ?>"></div>
  <?php endif; ?>
  
  <nav class="navbar">
    <div class="logo">Auto Heaven</div>
    <div class="nav-links">
      <a href="index.php">Home</a>
      <a href="about.php">About</a>
      <a href="news.php">Lajme</a>
      <a href="products.php">Products</a>
      <a href="contact.php">Contact</a>
    </div>
    <div class="user-area">
      <span id="userGreeting"></span>
      <a href="login.php" id="loginLink" class="btn">Login</a>
      <button id="logoutBtn" class="btn logout">Logout</button>
    </div>
  </nav>

  <section class="form-section">
    <h1>Regjistrohu</h1>
    <?php if ($message) echo '<p style="color:' . (strpos($message, 'suksesshëm') !== false ? 'green' : 'red') . ';">' . $message . '</p>'; ?>
    <form id="registerForm" method="POST">
      <input type="text" name="name" placeholder="Emri" required />
      <input type="email" name="email" placeholder="Email" required />
      <input type="password" name="password" placeholder="Password" required />
      <input type="password" name="confirm_password" placeholder="Konfirmo Password" required />
      <button type="submit">Regjistrohu</button>
    </form>
  </section>
  
  <script src="assets/js/forms.js"></script>
  <script src="assets/js/auth.js"></script>
</body>
</html>