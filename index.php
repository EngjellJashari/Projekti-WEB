<?php
require_once 'includes/session.php';
?>
<!DOCTYPE html>
<html lang="sq">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Auto Heaven | High-Performance Parts & Tuning</title>
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

  <section class="hero">
    <div class="hero-text">
      <h1 class="fade-in-up">Auto Heaven</h1>
      <p class="fade-in-up delay-1">Your one-stop shop for high-performance parts and tuning solutions.</p>
    </div>
    <img src="assets/img/hero.jpg" alt="BMW M4" class="hero-car">
  </section>

  <section class="featured">
    <h2 class="section-title">Featured Products</h2>
    <p class="section-subtitle">Your one-stop shop for high-performance parts and tuning solutions.</p>

    <div class="products-grid">
      <div class="product-card animate-card">
        <img src="assets/img/exhaust.jpg" alt="Exhaust">
        <h3>Stage 3 Exhaust System</h3>
        <p>Akrapovic Titanium – +45hp & sound brutal</p>
        <a href="product-detail.html" class="btn-small">Learn More</a>
      </div>
      <div class="product-card animate-card delay-2">
        <img src="assets/img/turbo.jpg" alt="Turbo">
        <h3>Hybrid Turbo Upgrade</h3>
        <p>500+ HP capable – Golf R / S3 / RS3</p>
        <a href="#" class="btn-small">Learn More</a>
      </div>
      <div class="product-card animate-card delay-4">
        <img src="assets/img/ferrari.jpg" alt="Ferrari">
        <h3>Carbon Fiber Bodykit</h3>
        <p>Full aero package for BMW M4</p>
        <a href="#" class="btn-small">Learn More</a>
      </div>
      <div class="product-card animate-card delay-6">
        <img src="assets/img/rims.jpg" alt="Rims">
        <h3>20" Forged Wheels</h3>
        <p>BBS FI-R – lightweight & strong</p>
        <a href="#" class="btn-small">Learn More</a>
      </div>
    </div>
  </section>

  <script src="assets/js/auth.js"></script>
  <script>
    // Force check auth immediately
    document.addEventListener('DOMContentLoaded', function() {
      const userInfoElement = document.getElementById('userInfo');
      const greeting = document.getElementById('userGreeting');
      const loginLink = document.getElementById('loginLink');
      const logoutBtn = document.getElementById('logoutBtn');
      
      if (userInfoElement) {
        const userName = userInfoElement.getAttribute('data-user-name');
        
        if (userName) {
          greeting.textContent = 'Përshëndetje, ' + userName + '!';
          greeting.style.display = 'inline-block';
          loginLink.style.display = 'none';
          logoutBtn.style.display = 'inline-block';
        } else {
          // No userName - keep logout hidden
          greeting.style.display = 'none';
          loginLink.style.display = 'inline-block';
          logoutBtn.style.display = 'none';
        }
      } else {
        // No userInfo element - not logged in
        greeting.style.display = 'none';
        loginLink.style.display = 'inline-block';
        logoutBtn.style.display = 'none';
      }
      
      // Setup logout button
      if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
          e.preventDefault();
          window.location.href = 'logout.php';
        });
      }
    });
    
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.animate-card').forEach((card, i) => {
        card.style.animationDelay = `${i * 0.2}s`;
      });
    });
  </script>
</body>
</html>