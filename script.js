const products = [
  { id: 1, name: "Stage 3 Exhaust System", desc: "Akrapovic Titanium – +45hp", price: "€2,499", img: "./exhaust.jpg" },
  { id: 2, name: "Hybrid Turbo Upgrade", desc: "500+ HP capable", price: "€3,999", img: "./turbo.jpg" },
  { id: 3, name: "Carbon Fiber Bodykit", desc: "BMW M4 Aero Kit", price: "€4,999", img: "./ferrari.jpg" },
  { id: 4, name: "20\" Forged Wheels", desc: "BBS FI-R", price: "€5,499", img: "./rims.jpg" }
];

function renderProducts(containerId, list = products) {
  const container = document.getElementById(containerId);
  if (!container) return;
  container.innerHTML = '';
  list.forEach(p => {
    const card = document.createElement('div');
    card.className = 'product-card';
    card.innerHTML = `
      <img src="${p.img}" alt="${p.name}">
      <h3>${p.name}</h3>
      <p>${p.desc}</p>
      <strong>${p.price}</strong>
    `;
    card.onclick = () => openModal(p);
    container.appendChild(card);
  });
}
const productModal = document.getElementById('productModal');
const modalName = document.getElementById('modalName');
const modalDesc = document.getElementById('modalDesc');
const modalPrice = document.getElementById('modalPrice');
const modalImg = document.getElementById('modalImg');

function openModal(p) {
  modalName.textContent = p.name;
  modalDesc.textContent = p.desc;
  modalPrice.textContent = p.price;
  modalImg.src = p.img;
  productModal.style.display = 'flex';
}

document.querySelector('.close').onclick = () => {
  productModal.style.display = 'none';
};

productModal.onclick = (e) => {
  if (e.target === productModal) productModal.style.display = 'none';
};

function showSection(id) {
  document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
  document.getElementById(id)?.classList.add('active');
}

document.querySelectorAll('[data-section]').forEach(link => {
  link.onclick = (e) => {
    e.preventDefault();
    showSection(link.dataset.section);
  };
});

document.getElementById('homeLink').onclick = (e) => {
  e.preventDefault();
  showSection('home');
};
function checkAuth() {
  const user = localStorage.getItem('autoHeavenUser');
  const greeting = document.getElementById('userGreeting');
  const loginLink = document.getElementById('loginLink');
  const userArea = document.querySelector('.user-area');

  const oldLogout = document.getElementById('logoutBtn');
  if (oldLogout) oldLogout.remove();

  if (user) {
    greeting.textContent = `Mirë se erdhe, ${user}!`;
    loginLink.style.display = 'none';

    const logoutBtn = document.createElement('button');
    logoutBtn.id = 'logoutBtn';
    logoutBtn.className = 'btn';
    logoutBtn.textContent = 'Logout';
    logoutBtn.onclick = () => {
      localStorage.removeItem('autoHeavenUser');
      checkAuth();
      showSection('home');
    };
    userArea.appendChild(logoutBtn);
    showSection('home');
  } else {
    greeting.textContent = '';
    loginLink.style.display = 'inline-block';
  }

  updateLoginSection(user);
  updateRegisteredList();
}

function updateLoginSection(user) {
  const form = document.getElementById('loginForm');
  const panel = document.getElementById('loggedInPanel');
  const msg = document.getElementById('loggedInMsg');
  const sectionLogoutBtn = document.getElementById('sectionLogoutBtn');

  if (user) {
    form.style.display = 'none';
    panel.style.display = 'block';
    msg.textContent = `Jeni i kyçur si ${user}`;
    sectionLogoutBtn.onclick = () => {
      localStorage.removeItem('autoHeavenUser');
      checkAuth();
    };
  } else {
    form.style.display = 'block';
    panel.style.display = 'none';
  }
}

document.getElementById('loginForm').addEventListener('submit', e => {
  e.preventDefault();
  const email = document.getElementById('loginEmail').value.trim();
  if (!email) {
    alert('Shkruaj email!');
    return;
  }
  const username = email.split('@')[0];
  localStorage.setItem('autoHeavenUser', username);
  document.getElementById('loginForm').reset();
  checkAuth();
  showSection('home');
});

function getRegisteredUsers() {
  return JSON.parse(localStorage.getItem('autoHeavenUsers') || '[]');
}

function saveRegisteredUsers(users) {
  localStorage.setItem('autoHeavenUsers', JSON.stringify(users));
}

function updateRegisteredList() {
  const list = document.getElementById('registeredList');
  const users = getRegisteredUsers();
  list.innerHTML = '';
  users.forEach(name => {
    const li = document.createElement('li');
    li.textContent = name;
    list.appendChild(li);
  });
}

document.getElementById('registerForm').addEventListener('submit', e => {
  e.preventDefault();
  const name = document.getElementById('regName').value.trim();
  const password = document.getElementById('regPassword').value;
  const confirm = document.getElementById('regConfirm').value;

  if (!name || !password) {
    alert('Plotëso të gjitha fushat!');
    return;
  }
  if (password !== confirm) {
    alert('Password-et nuk përputhen!');
    return;
  }
  const users = getRegisteredUsers();
  if (users.includes(name)) {
    alert('Ky emër është regjistruar tashmë!');
    return;
  }
  users.push(name);
  saveRegisteredUsers(users);
  updateRegisteredList();
  alert(`Regjistrimi u krye me sukses, ${name}! Tani mund të kyçesh.`);
  document.getElementById('registerForm').reset();
  showSection('login');
});