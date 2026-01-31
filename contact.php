<?php
require_once 'includes/session.php';
require_once 'classes/Contract.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_SESSION['user_id'] ?? 0;
  $subject = trim($_POST['subject'] ?? '');
  $msg = trim($_POST['message'] ?? '');

  if (!$user_id) {
    $message = 'Duhet të jeni të kyçur për të dërguar mesazh. <a href="login.php">Kyçuni këtu</a>';
  } elseif (strlen($subject) < 3) {
    $message = 'Zgjidhni një temë të vlefshme.';
  } elseif (strlen($msg) < 10) {
    $message = 'Mesazhi duhet të ketë të paktën 10 karaktere.';
  } else {
    $contract = new Contract();
    if ($contract->create($user_id, $subject, $msg)) {
      $message = 'Mesazhi u dërgua me sukses! Do t\'ju kthehemi shpejt.';
    } else {
      $message = 'Gabim gjatë dërgimit të mesazhit!';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact - Auto Heaven</title>
  <link rel="icon" type="image/svg+xml" href="assets/img/favicon.svg" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">
  <style>
    .success-message {
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 5px;
      background: #0d3d0d;
      color: #0f0;
      border: 1px solid #0f0;
    }
    .error-message {
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 5px;
      background: #3d0d0d;
      color: #f00;
      border: 1px solid #f00;
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

  <section class="contact-section">
    <div class="contact-fullwidth">
  
      <!-- Hero-style Header për Contact -->
      <div class="contact-header">
        <h1 class="section-title">Kontakto</h1>
        <p class="section-subtitle">Jemi këtu për të ndihmuar! Na kontaktoni për çdo pyetje apo konsultim rreth produkteve tona.</p>
      </div>
  
      <!-- Main Layout – tani 100% width -->
      <div class="main-layout">
        <!-- Form -->
        <div class="contact-form-card">
          <h2>Dërgo Mesazh</h2>
          <?php if ($message): ?>
            <div class="<?php echo strpos($message, 'suksesshëm') !== false ? 'success-message' : 'error-message'; ?>">
              <?php echo $message; ?>
            </div>
          <?php endif; ?>
          <form id="contactForm" method="POST">
            <input type="text" name="name" placeholder="Emri juaj" value="<?php echo $_SESSION['user_name'] ?? ''; ?>" required disabled />
            <input type="email" name="email" placeholder="Email juaj" required disabled />
            <input type="tel" name="phone" placeholder="Telefoni (opsional)" />
            <select name="subject" required>
              <option value="">Zgjidhni Temën</option>
              <option value="Informacion për Produkte">Informacion për Produkte</option>
              <option value="Konsultim për Tuning">Konsultim për Tuning</option>
              <option value="Problemi me Produktin">Problemi me Produktin</option>
              <option value="Përgjithësisht">Përgjithësisht</option>
            </select>
            <textarea name="message" placeholder="Mesazhi juaj..." rows="6" required></textarea>
            <button type="submit" style="background: #c00; color: white; padding: 12px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; transition: background 0.3s;">Dërgo</button>
          </form>
        </div>
  
        <!-- Info + Social -->
        <div class="contact-info-wrapper">
          <div class="info-card">
            <h2>Informacione Kontakti</h2>
            <div class="info-item"><span class="icon">📍</span><div><h3>Adresa</h3><p>Rruga Dëshmorët e Kombit<br>Tirana, Shqipëri<br>1001</p></div></div>
            <div class="info-item"><span class="icon">📞</span><div><h3>Telefoni</h3><p><a href="tel:+355691234567">+355 69 123 4567</a><br><a href="tel:+35542234567">+355 4 223 4567</a></p></div></div>
            <div class="info-item"><span class="icon">✉️</span><div><h3>Email</h3><p><a href="mailto:info@autoheaven.al">info@autoheaven.al</a><br><a href="mailto:support@autoheaven.al">support@autoheaven.al</a></p></div></div>
            <div class="info-item"><span class="icon">🕐</span><div><h3>Orët e Punës</h3><p>E Hënë - E Premte: 08:00 - 18:00<br>E Shtunë: 09:00 - 15:00<br>E Dielë: E Mbyllur</p></div></div>
          </div>
  
          <div class="social-card">
            <h2>Na Ndiqni</h2>
            <p>Ndiqni neve në rrjetet sociale për oferta speciale.</p>
            <div class="social-links">
              <a href="#" class="social-link"><span class="icon">📘</span><div><strong>Facebook</strong><span>@AutoHeaven</span></div></a>
              <a href="#" class="social-link"><span class="icon">📷</span><div><strong>Instagram</strong><span>@autoheaven</span></div></a>
              <a href="#" class="social-link"><span class="icon">💬</span><div><strong>WhatsApp</strong><span>+355 69 123 4567</span></div></a>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Stats + Services + FAQ – full width -->
      <div class="bottom-sections">
        <div class="stats-services">
          <!-- Statistics Section -->
          <div class="stats-card">
            <h2>Statistikat Tona</h2>
            <div class="stats-grid">
              <div class="stat-item">
                <div class="stat-number">10+</div>
                <h3>Vjet Përvojë</h3>
              </div>
              <div class="stat-item">
                <div class="stat-number">5000+</div>
                <h3>Klientë</h3>
              </div>
              <div class="stat-item">
                <div class="stat-number">15K+</div>
                <h3>Pjesë</h3>
              </div>
              <div class="stat-item">
                <div class="stat-number">24/7</div>
                <h3>Mbështetje</h3>
              </div>
            </div>
          </div>
  
          <!-- Services Section -->
          <div class="services-card">
            <h2>Shërbimet Tona</h2>
            <div class="services-grid">
              <div class="service-item">
                <div class="service-icon">🔧</div>
                <h3>Instalim</h3>
              </div>
              <div class="service-item">
                <div class="service-icon">💻</div>
                <h3>ECU Tuning</h3>
              </div>
              <div class="service-item">
                <div class="service-icon">🎯</div>
                <h3>Konsultim</h3>
              </div>
              <div class="service-item">
                <div class="service-icon">🛡️</div>
                <h3>Garancion</h3>
              </div>
            </div>
          </div>
        </div>
  
        <div class="faq-card">
          <h2>Pyetje të Shpeshta</h2>
          <div class="faq-grid">
            <div class="faq-item">
              <h3>Sa kohë duhet për instalim?</h3>
              <p>Instalimi i sistemeve shkarkimi zgjat 2-4 orë, varësisht nga modeli i makinës.</p>
            </div>
            <div class="faq-item">
              <h3>A ofroni garancion?</h3>
              <p>Po, të gjitha produktet vijnë me garancion 1-3 vjet, varësisht nga produkti.</p>
            </div>
            <div class="faq-item">
              <h3>A mund të porosisni pjesë të veçanta?</h3>
              <p>Po, mund të porosisim pjesë të veçanta për çdo model makine.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="assets/js/forms.js"></script>
  <script src="assets/js/auth.js"></script>
</body>
</html>