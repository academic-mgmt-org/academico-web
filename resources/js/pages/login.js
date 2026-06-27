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
          // Guardar el token en el localStorage
          localStorage.setItem('user_token', response.token);
          
          // Redirigir si el rol es ESTUDIANTE
          if (response.user && response.user.role === 'ESTUDIANTE') {
            window.location.href = '/home';
          } else {
            alert('Inicio de sesión exitoso. Pero el rol (' + (response.user ? response.user.role : 'Ninguno') + ') no está configurado para esta pantalla.');
          }
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

