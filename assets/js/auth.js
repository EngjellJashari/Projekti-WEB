const users = JSON.parse(localStorage.getItem('users')) || [];

// Register
const registerForm = document.getElementById('registerForm');
if (registerForm) {
  registerForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const name = document.getElementById('regName').value.trim();
    const email = document.getElementById('regEmail').value.trim();
    const password = document.getElementById('regPassword').value;
    const confirm = document.getElementById('regConfirm').value;

    if (password !== confirm) {
      alert('Password-et nuk përputhen!');
      return;
    }
    if (users.find(u => u.email === email)) {
      alert('Ky email është regjistruar tashmë!');
      return;
    }

    users.push({ name, email, password });
    localStorage.setItem('users', JSON.stringify(users));
    localStorage.setItem('currentUser', JSON.stringify({ name, email }));

    alert('Regjistrim i suksesshëm!');
    window.location.href = 'index.html';
  });
}

// Login
const loginForm = document.getElementById('loginForm');
if (loginForm) {
  loginForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const email = document.getElementById('loginEmail').value.trim();
    const password = document.getElementById('loginPassword').value;

    const user = users.find(u => u.email === email && u.password === password);
    if (user) {
      localStorage.setItem('currentUser', JSON.stringify({ name: user.name, email }));
      window.location.href = 'index.html';
    } else {
      alert('Email ose password gabim!');
    }
  });
}

// Check Auth
function checkAuth() {
  const currentUser = JSON.parse(localStorage.getItem('currentUser'));
  const greeting = document.getElementById('userGreeting');
  const loginLink = document.getElementById('loginLink');
  const logoutBtn = document.getElementById('logoutBtn');

  if (currentUser && greeting) {
    greeting.textContent = `Përshëndetje, ${currentUser.name}!`;
    loginLink.style.display = 'none';
    logoutBtn.style.display = 'inline-block';
  }
}

document.getElementById('logoutBtn')?.addEventListener('click', () => {
  localStorage.removeItem('currentUser');
  window.location.href = 'index.html';
});

document.addEventListener('DOMContentLoaded', checkAuth);