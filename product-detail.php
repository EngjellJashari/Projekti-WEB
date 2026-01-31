<?php
require_once 'includes/session.php';
require_once 'classes/Product.php';

$product_class = new Product();
$product = null;
$id = $_GET['id'] ?? 0;

if ($id) {
    $product = $product_class->getById($id);
}

if (!$product) {
    header('Location: products.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Product Detail - Auto Heaven</title>
  <link rel="icon" type="image/svg+xml" href="assets/img/favicon.svg" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">
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

  <section class="detail-section">
    <div style="max-width: 1000px; margin: 0 auto; text-align: left;">
      <h1 class="section-title"><?php echo htmlspecialchars($product['name']); ?></h1>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 40px; align-items: start;">
        <div>
          <img src="assets/img/product-placeholder.jpg" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 100%; border-radius: 15px; box-shadow: 0 20px 40px rgba(200,0,0,0.3);">
        </div>
        <div>
          <h2 style="font-size: 2.5rem; color: #c00; margin-bottom: 20px;"><?php echo htmlspecialchars($product['name']); ?></h2>
          <p style="font-size: 1.2rem; opacity: 0.9; margin-bottom: 30px; line-height: 1.6;"><?php echo htmlspecialchars($product['description']); ?></p>
          <div style="background: #1a1a1a; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
            <p style="margin-bottom: 10px;"><strong style="color: #c00;">ID Produkti:</strong> <span><?php echo $product['id']; ?></span></p>
            <p style="margin-bottom: 10px;"><strong style="color: #c00;">Çmimi:</strong> <span>€<?php echo number_format($product['price'], 2); ?></span></p>
            <p><strong style="color: #c00;">Disponueshmëria:</strong> <span style="color: #0f0;"><?php echo $product['stock'] > 0 ? 'Në Stok' : 'Nuk ka stok'; ?></span></p>
          </div>
          <a href="products.php" class="btn" style="display: inline-block; text-align: center; margin-top: 20px;">Kthehu te Produktet</a>
        </div>
      </div>
    </div>
  </section>

  <footer>
    <p>&copy; 2026 Auto Heaven. All rights reserved.</p>
  </footer>

  <script src="assets/js/auth.js"></script>
</body>
</html>