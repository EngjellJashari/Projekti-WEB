<?php
require_once 'includes/session.php';
?>
<!DOCTYPE html>
<html lang="sq">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>About - Auto Heaven</title>
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

  <section class="about-section">
    <h1 class="section-title">Rreth Nesh</h1>
    <p class="section-subtitle">Auto Heaven është specializuar në pjesë tuning për makina sportive. Me +10 vjet përvojë, ofrojmë cilësi premium.</p>

    <div style="max-width: 1200px; margin: 0 auto; text-align: left;">
      <div style="margin-bottom: 80px;">
        <h2 style="font-size: 2.5rem; color: #c00; margin-bottom: 30px; text-align: center;">Historia Jonë</h2>
        <p style="font-size: 1.2rem; line-height: 1.8; opacity: 0.9; margin-bottom: 20px;">
          Auto Heaven u themelua në vitin 2014 me një vizion të thjeshtë: të ofrojmë pjesë tuning me cilësi të lartë për entuziastët e makinave sportive në rajon. Fillimisht ishim një punëtori e vogël me një ekip prej 3 personash, por pasionit dhe dedikimit tonë na çoi në rritje të vazhdueshme.
        </p>
        <p style="font-size: 1.2rem; line-height: 1.8; opacity: 0.9;">
          Sot, pas më shumë se 10 vjetësh përvoje, jemi një nga shitësit më të besueshëm të pjesëve tuning në rajon. Kemi bashkëpunuar me mbi 5000 klientë dhe kemi instaluar mbi 15,000 pjesë në makina të ndryshme, nga Golf R deri te BMW M4 dhe Audi RS3.
        </p>
      </div>

      <div style="margin-bottom: 80px;">
        <h2 style="font-size: 2.5rem; color: #c00; margin-bottom: 30px; text-align: center;">Misioni dhe Vlerat Tona</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-top: 40px;">
          <div style="background: #1a1a1a; padding: 30px; border-radius: 15px; border: 2px solid #333;">
            <h3 style="color: #c00; font-size: 1.8rem; margin-bottom: 15px;">Misioni</h3>
            <p style="line-height: 1.6; opacity: 0.9;">
              Të ofrojmë pjesë tuning me cilësi premium dhe shërbim profesional që i lejojnë klientët tanë të arrijnë performancën maksimale të makinave të tyre, duke respektuar sigurinë dhe standardet më të larta.
            </p>
          </div>
          <div style="background: #1a1a1a; padding: 30px; border-radius: 15px; border: 2px solid #333;">
            <h3 style="color: #c00; font-size: 1.8rem; margin-bottom: 15px;">Vlerat</h3>
            <ul style="line-height: 2; opacity: 0.9; list-style: none; padding: 0;">
              <li>✓ Cilësi Premium</li>
              <li>✓ Transparencë</li>
              <li>✓ Pasion për Detaje</li>
              <li>✓ Besueshmëri</li>
              <li>✓ Inovacion</li>
            </ul>
          </div>
        </div>
      </div>

      <div style="margin-bottom: 80px;">
        <h2 style="font-size: 2.5rem; color: #c00; margin-bottom: 30px; text-align: center;">Çfarë Ofrojmë</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-top: 40px;">
          <div style="background: #1a1a1a; padding: 25px; border-radius: 15px; text-align: center; transition: 0.3s;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 15px 30px rgba(200,0,0,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <h3 style="color: #c00; font-size: 1.5rem; margin-bottom: 15px;">Sisteme Shkarkimi</h3>
            <p style="opacity: 0.9; line-height: 1.6;">
              Akrapovic, Milltek, Remus dhe shumë të tjera. Sistemet më të mira të shkarkimit për rritje fuqie dhe zë.
            </p>
          </div>
          <div style="background: #1a1a1a; padding: 25px; border-radius: 15px; text-align: center; transition: 0.3s;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 15px 30px rgba(200,0,0,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <h3 style="color: #c00; font-size: 1.5rem; margin-bottom: 15px;">Turbo Upgrades</h3>
            <p style="opacity: 0.9; line-height: 1.6;">
              Turbo hibrid dhe kompleta për rritje drastike të fuqisë. Përshtatur për çdo model.
            </p>
          </div>
          <div style="background: #1a1a1a; padding: 25px; border-radius: 15px; text-align: center; transition: 0.3s;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 15px 30px rgba(200,0,0,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <h3 style="color: #c00; font-size: 1.5rem; margin-bottom: 15px;">Bodykits Karboni</h3>
            <p style="opacity: 0.9; line-height: 1.6;">
              Kompleta të plota aerodinamike prej karboni për pamje agresive dhe performancë.
            </p>
          </div>
          <div style="background: #1a1a1a; padding: 25px; border-radius: 15px; text-align: center; transition: 0.3s;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 15px 30px rgba(200,0,0,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <h3 style="color: #c00; font-size: 1.5rem; margin-bottom: 15px;">Rrota Forged</h3>
            <p style="opacity: 0.9; line-height: 1.6;">
              BBS, HRE, Rotiform dhe marka të tjera premium. Rrota të lehta dhe të forta.
            </p>
          </div>
          <div style="background: #1a1a1a; padding: 25px; border-radius: 15px; text-align: center; transition: 0.3s;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 15px 30px rgba(200,0,0,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <h3 style="color: #c00; font-size: 1.5rem; margin-bottom: 15px;">ECU Tuning</h3>
            <p style="opacity: 0.9; line-height: 1.6;">
              Remapping profesional për rritje fuqie dhe optimizim performancë. Stage 1, 2 dhe 3.
            </p>
          </div>
          <div style="background: #1a1a1a; padding: 25px; border-radius: 15px; text-align: center; transition: 0.3s;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 15px 30px rgba(200,0,0,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <h3 style="color: #c00; font-size: 1.5rem; margin-bottom: 15px;">Instalim Profesional</h3>
            <p style="opacity: 0.9; line-height: 1.6;">
              Punëtoria jonë e pajisur me teknologji moderne ofron instalim profesional për të gjitha pjesët.
            </p>
          </div>
        </div>
      </div>

      <div style="margin-bottom: 80px;">
        <h2 style="font-size: 2.5rem; color: #c00; margin-bottom: 30px; text-align: center;">Pse Na Zgjidhni</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 40px; margin-top: 40px;">
          <div style="text-align: center;">
            <div style="font-size: 4rem; color: #c00; font-weight: bold; margin-bottom: 10px;">10+</div>
            <h3 style="color: #fff; font-size: 1.3rem; margin-bottom: 10px;">Vjet Përvojë</h3>
            <p style="opacity: 0.8;">Eksperiencë e gjatë në industrinë e tuning</p>
          </div>
          <div style="text-align: center;">
            <div style="font-size: 4rem; color: #c00; font-weight: bold; margin-bottom: 10px;">5000+</div>
            <h3 style="color: #fff; font-size: 1.3rem; margin-bottom: 10px;">Klientë të Kënaqur</h3>
            <p style="opacity: 0.8;">Besim dhe kënaqësi në çdo projekt</p>
          </div>
          <div style="text-align: center;">
            <div style="font-size: 4rem; color: #c00; font-weight: bold; margin-bottom: 10px;">15K+</div>
            <h3 style="color: #fff; font-size: 1.3rem; margin-bottom: 10px;">Pjesë të Instaluara</h3>
            <p style="opacity: 0.8;">Instalime të suksesshme dhe profesionale</p>
          </div>
          <div style="text-align: center;">
            <div style="font-size: 4rem; color: #c00; font-weight: bold; margin-bottom: 10px;">100%</div>
            <h3 style="color: #fff; font-size: 1.3rem; margin-bottom: 10px;">Garancion</h3>
            <p style="opacity: 0.8;">Garancion i plotë për të gjitha produktet</p>
          </div>
        </div>
      </div>

      <div style="text-align: center; padding: 40px; background: #1a1a1a; border-radius: 15px; border: 2px solid #c00;">
        <h2 style="font-size: 2rem; color: #c00; margin-bottom: 20px;">Gati për të Filluar?</h2>
        <p style="font-size: 1.2rem; margin-bottom: 30px; opacity: 0.9;">
          Shiko koleksionin tonë të plotë të pjesëve tuning ose na kontakto për konsultim personalizuar.
        </p>
        <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
          <a href="products.html" class="btn" style="display: inline-block;">Shiko Produktet</a>
          <a href="contact.html" class="btn" style="display: inline-block; background: transparent; border: 2px solid #c00;">Na Kontakto</a>
        </div>
      </div>
    </div>
  </section>

  <script src="assets/js/auth.js"></script>
</body>
</html>