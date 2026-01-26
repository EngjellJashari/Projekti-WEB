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
  
    // Contact form submit
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
      contactForm.addEventListener('submit', (e) => {
        let valid = true;
        // Check all fields...
        if (!valid) e.preventDefault();
      });
    }
  });