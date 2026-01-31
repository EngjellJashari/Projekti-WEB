<?php
require_once 'includes/session.php';
require_once 'classes/News.php';

$news = new News();
$allNews = $news->getAll();
?>
<!DOCTYPE html>
<html lang="sq">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Lajme - Auto Heaven</title>
  <link rel="icon" type="image/svg+xml" href="assets/img/favicon.svg" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">
  <style>
    .news-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }
    .news-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 30px;
      margin: 60px 0;
    }
    .news-card {
      background: #1a1a1a;
      border-radius: 15px;
      overflow: hidden;
      transition: 0.3s;
      border: 1px solid #333;
    }
    .news-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 40px rgba(200,0,0,0.4);
      border-color: #c00;
    }
    .news-card-image {
      width: 100%;
      height: 220px;
      background: #333;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #666;
      font-size: 3rem;
    }
    .news-card-content {
      padding: 25px;
    }
    .news-card-date {
      color: #c00;
      font-size: 0.9rem;
      margin-bottom: 10px;
    }
    .news-card h3 {
      font-size: 1.4rem;
      margin-bottom: 15px;
      color: #fff;
    }
    .news-card p {
      opacity: 0.8;
      line-height: 1.6;
      margin-bottom: 15px;
    }
    .news-card a {
      display: inline-block;
      background: #c00;
      color: white;
      padding: 10px 20px;
      border-radius: 5px;
      text-decoration: none;
      transition: 0.3s;
    }
    .news-card a:hover {
      background: #a00;
    }
    .no-news {
      text-align: center;
      padding: 60px 20px;
      color: #999;
      font-size: 1.2rem;
    }
  </style>
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

  <section class="featured" style="padding-top: 120px;">
    <div class="news-container">
      <h1 class="section-title">Lajme</h1>
      <p class="section-subtitle">Ndjekni njoftime më të fundit nga Auto Heaven</p>

      <?php if ($allNews): ?>
        <div class="news-grid">
          <?php foreach ($allNews as $item): ?>
            <div class="news-card">
              <?php if (!empty($item['image'])): ?>
                <div class="news-card-image" style="background-size:cover; background-position:center; background-image:url('<?php echo 'assets/' . $item['image']; ?>')">
                </div>
              <?php else: ?>
                <div class="news-card-image">📰</div>
              <?php endif; ?>
              <div class="news-card-content">
                <div class="news-card-date">
                  <?php echo date('d.m.Y', strtotime($item['created_at'])); ?>
                </div>
                <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                <p><?php echo htmlspecialchars(substr($item['content'], 0, 150)); ?>...</p>
                <?php if (!empty($item['image']) && preg_match('/\.pdf$/i', $item['image'])): ?>
                  <a href="<?php echo 'assets/' . $item['image']; ?>" target="_blank">Shiko PDF</a>
                <?php else: ?>
                  <a href="#" onclick="alert('Detalet e plota: ' + <?php echo json_encode($item['content']); ?>); return false;">Lexo më shumë</a>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="no-news">
          <p>Nuk ka lajme të disponueshme aktualisht.</p>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <footer>
    <p>&copy; 2026 Auto Heaven. All rights reserved.</p>
  </footer>

  <script src="assets/js/auth.js"></script>
</body>
</html>
