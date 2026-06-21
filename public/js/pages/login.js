// Login Page Logic
document.addEventListener('DOMContentLoaded', () => {
  const passwordInput = document.getElementById('password');
  const passwordToggle = document.getElementById('passwordToggle');

  if (passwordInput && passwordToggle) {
    // Password visibility toggle logic
    passwordToggle.addEventListener('click', () => {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      
      // Toggle Tabler Icon class between eye and eye-off
      if (type === 'text') {
        passwordToggle.classList.replace('ti-eye', 'ti-eye-off');
        passwordToggle.setAttribute('aria-label', 'Ocultar contraseña');
      } else {
        passwordToggle.classList.replace('ti-eye-off', 'ti-eye');
        passwordToggle.setAttribute('aria-label', 'Mostrar contraseña');
      }
    });

    // Support keyboard activation for password toggle (accessibility)
    passwordToggle.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        passwordToggle.click();
      }
    });
  }
});
