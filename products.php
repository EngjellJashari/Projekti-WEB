<?php
require_once 'includes/session.php';
require_once 'classes/Product.php';

$product = new Product();
$products = $product->getAll();
?>
<!DOCTYPE html>
<html lang="sq">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Products - Auto Heaven</title>
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
  
  <script>
    // Immediate check - force correct button state
    (function() {
      const userInfoDiv = document.getElementById('userInfo');
      const loginLink = document.getElementById('loginLink');
      const logoutBtn = document.getElementById('logoutBtn');
      const greeting = document.getElementById('userGreeting');
      
      if (userInfoDiv) {
        const userName = userInfoDiv.getAttribute('data-user-name');
        if (userName && userName.trim()) {
          // Logged in
          if (greeting) {
            greeting.textContent = 'Përshëndetje, ' + userName + '!';
            greeting.style.setProperty('display', 'inline-block', 'important');
          }
          if (loginLink) loginLink.style.setProperty('display', 'none', 'important');
          if (logoutBtn) logoutBtn.style.setProperty('display', 'inline-block', 'important');
        } else {
          // Not logged in
          if (greeting) greeting.style.setProperty('display', 'none', 'important');
          if (loginLink) loginLink.style.setProperty('display', 'inline-block', 'important');
          if (logoutBtn) logoutBtn.style.setProperty('display', 'none', 'important');
        }
      } else {
        // No userInfo - definitely not logged in
        if (greeting) greeting.style.setProperty('display', 'none', 'important');
        if (loginLink) loginLink.style.setProperty('display', 'inline-block', 'important');
        if (logoutBtn) logoutBtn.style.setProperty('display', 'none', 'important');
      }
    })();
  </script>

  <section class="products-section">
    <h2 class="section-title">Të Gjitha Produktet</h2>
    <p class="section-subtitle">Shiko koleksionin tonë të plotë.</p>

    <div class="products-grid">
      <?php if ($products): ?>
        <?php 
          $images = array('assets/img/exhaust.jpg', 'assets/img/turbo.jpg', 'assets/img/ferrari.jpg', 'assets/img/rims.jpg');
          $imageIndex = 0;
        ?>
        <?php foreach ($products as $p): ?>
          <div class="product-card animate-card">
            <?php $imgSrc = !empty($p['image']) ? 'assets/' . $p['image'] : $images[$imageIndex % count($images)]; ?>
            <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
            <h3><?php echo htmlspecialchars($p['name']); ?></h3>
            <p><?php echo htmlspecialchars(substr($p['description'], 0, 50)); ?></p>
            <p style="color: #c00; font-weight: bold; margin-top: 10px;">€<?php echo number_format($p['price'], 2); ?></p>
            <a href="product-detail.php?id=<?php echo $p['id']; ?>" class="btn-small">Learn More</a>
          </div>
          <?php $imageIndex++; ?>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="grid-column: 1 / -1; text-align: center; color: #999;">Nuk ka produkte të disponueshme</p>
      <?php endif; ?>
    </div>
  </section>

  <footer>
    <p>&copy; 2026 Auto Heaven. All rights reserved.</p>
  </footer>

  <script src="assets/js/auth.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.animate-card').forEach((card, i) => {
        card.style.animationDelay = `${i * 0.2}s`;
      });
    });
  </script>
</body>
</html>