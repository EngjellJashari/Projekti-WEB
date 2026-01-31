// Check Auth - Get user info from PHP session via hidden element
function checkAuth() {
  const greeting = document.getElementById('userGreeting');
  const loginLink = document.getElementById('loginLink');
  const logoutBtn = document.getElementById('logoutBtn');

  // Check if user info is provided via PHP (in a hidden div)
  const userInfoElement = document.getElementById('userInfo');
  let userName = null;

  if (userInfoElement) {
    userName = userInfoElement.getAttribute('data-user-name');
  }

  // If userName exists and is not empty, user is logged in
  if (userName && userName.trim()) {
    if (greeting) {
      greeting.textContent = `Përshëndetje, ${userName}!`;
      greeting.style.setProperty('display', 'inline-block', 'important');
    }
    if (loginLink) loginLink.style.setProperty('display', 'none', 'important');
    if (logoutBtn) logoutBtn.style.setProperty('display', 'inline-block', 'important');
  } else {
    // User is NOT logged in - hide greeting and logout, show login
    if (greeting) greeting.style.setProperty('display', 'none', 'important');
    if (loginLink) loginLink.style.setProperty('display', 'inline-block', 'important');
    if (logoutBtn) logoutBtn.style.setProperty('display', 'none', 'important');
  }
}

// Setup logout button event
function setupLogoutButton() {
  const logoutBtn = document.getElementById('logoutBtn');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', (e) => {
      e.preventDefault();
      window.location.href = 'logout.php';
    });
  }
}

// Run checkAuth immediately when script loads
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    checkAuth();
    setupLogoutButton();
  });
} else {
  // DOM is already loaded
  checkAuth();
  setupLogoutButton();
}