// Login Page Logic
import { login } from '../services/auth.service.js';

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

  // Handle form submission
  const form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const emailInput = document.getElementById('email');
      const email = emailInput ? emailInput.value : '';
      const password = passwordInput ? passwordInput.value : '';

      try {
        const response = await login(email, password);
        console.log('Login API Response:', response);

        if (response.token) {
          // Guardar el token ficticio en el localStorage
          localStorage.setItem('user_token', response.token);
          alert('¡Inicio de sesión exitoso! (Token guardado)');
          // Aquí puedes redirigir al usuario, por ejemplo:
          // window.location.href = '/dashboard';
        } else {
          alert('Error de inicio de sesión: ' + (response.message || 'Credenciales incorrectas.'));
        }
      } catch (error) {
        console.error('Error durante el inicio de sesión:', error);
        alert('Ocurrió un error al intentar comunicarse con el servidor.');
      }
    });
  }
});

