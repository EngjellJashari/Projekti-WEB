document.addEventListener('DOMContentLoaded', () => {
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    const nameRegex = /^[A-Za-z\s]{3,30}$/;
    const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/;
  
    function showError(input, msg) {
      const errorSpan = input.nextElementSibling;
      if (errorSpan && errorSpan.classList.contains('error')) {
        errorSpan.textContent = msg;
        errorSpan.style.display = 'block';
      }
    }
  
    function clearError(input) {
      const errorSpan = input.nextElementSibling;
      if (errorSpan && errorSpan.classList.contains('error')) {
        errorSpan.style.display = 'none';
      }
    }
  
    document.querySelectorAll('input, textarea').forEach(input => {
      input.addEventListener('blur', () => {
        if (input.type === 'email' && input.value && !emailRegex.test(input.value)) {
          showError(input, 'Email i pavlefshëm');
        } else if (input.id.includes('Name') && input.value && !nameRegex.test(input.value)) {
          showError(input, 'Emri duhet të ketë vetëm shkronja dhe të paktën 3 karaktere');
        } else if (input.type === 'password' && input.value && !passwordRegex.test(input.value)) {
          showError(input, 'Password min. 6 karaktere, 1 shkronjë dhe 1 numër');
        } else {
          clearError(input);
        }
      });
    });
  
    // Register form validation
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
      registerForm.addEventListener('submit', (e) => {
        const name = registerForm.querySelector('input[name="name"]');
        const email = registerForm.querySelector('input[name="email"]');
        const pass = registerForm.querySelector('input[name="password"]');
        const confirm = registerForm.querySelector('input[name="confirm_password"]');
        if (!name.value || name.value.trim().length < 3) { showError(name, 'Emri duhet të ketë të paktën 3 karaktere'); e.preventDefault(); return; }
        if (!emailRegex.test(email.value)) { showError(email, 'Email i pavlefshëm'); e.preventDefault(); return; }
        if (!passwordRegex.test(pass.value)) { showError(pass, 'Password min. 6 karaktere, 1 shkronjë dhe 1 numër'); e.preventDefault(); return; }
        if (pass.value !== confirm.value) { showError(confirm, 'Passwordet nuk përputhen'); e.preventDefault(); return; }
      });
    }

    // Login form validation
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
      loginForm.addEventListener('submit', (e) => {
        const email = loginForm.querySelector('input[name="email"]');
        const pass = loginForm.querySelector('input[name="password"]');
        if (!emailRegex.test(email.value)) { showError(email, 'Email i pavlefshëm'); e.preventDefault(); return; }
        if (!pass.value) { showError(pass, 'Vendosni password'); e.preventDefault(); return; }
      });
    }

    // Contact form validation
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
      contactForm.addEventListener('submit', (e) => {
        const subject = contactForm.querySelector('select[name="subject"]');
        const msg = contactForm.querySelector('textarea[name="message"]');
        if (!subject.value) { alert('Zgjidhni temën'); e.preventDefault(); return; }
        if (!msg.value || msg.value.trim().length < 10) { alert('Mesazhi duhet të ketë të paktën 10 karaktere'); e.preventDefault(); return; }
      });
    }

    // Admin forms simple validation
    const adminNewsForm = document.getElementById('adminNewsForm');
    if (adminNewsForm) {
      adminNewsForm.addEventListener('submit', (e) => {
        const title = adminNewsForm.querySelector('input[name="title"]');
        const content = adminNewsForm.querySelector('textarea[name="content"]');
        if (!title.value || title.value.trim().length < 3) { alert('Titulli i lajmit duhet të ketë të paktën 3 karaktere'); e.preventDefault(); return; }
        if (!content.value || content.value.trim().length < 10) { alert('Përmbajtja duhet të ketë të paktën 10 karaktere'); e.preventDefault(); return; }
      });
    }

    const adminProductForm = document.getElementById('adminProductForm');
    if (adminProductForm) {
      adminProductForm.addEventListener('submit', (e) => {
        const name = adminProductForm.querySelector('input[name="name"]');
        const price = adminProductForm.querySelector('input[name="price"]');
        if (!name.value || name.value.trim().length < 2) { alert('Emri i produktit është i nevojshëm'); e.preventDefault(); return; }
        if (!price.value || isNaN(price.value) || Number(price.value) <= 0) { alert('Vendosni një çmim të vlefshëm'); e.preventDefault(); return; }
      });
    }
  });