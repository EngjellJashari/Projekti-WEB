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