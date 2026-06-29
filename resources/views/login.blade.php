@php
// Initialize session if needed
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Sistema Académico - Iniciar sesión";
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $page_title }}</title>
  
  <!-- Load Inter font from Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Load Tabler Icons webfont -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  
  <!-- Import styles and scripts using Vite -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

  <div class="app-container">
    
    <div class="login-area">
      
      <!-- LEFT PANEL -->
      <div class="left-panel">
        
        <!-- Header -->
        <div class="header-section">
          <img class="logo-img" src="{{ asset('images/logos/logo-academico.png') }}" alt="Logo Sistema Académico">
          <div class="header-text-container">
            <h1 class="header-title">Sistema Académico</h1>
            <span class="header-subtitle">Gestión integral de procesos académicos</span>
          </div>
        </div>

        <!-- Welcome Text -->
        <div class="welcome-container">
          <h2 class="welcome-title">Bienvenido de nuevo</h2>
          <div class="welcome-desc-wrapper">
            <p class="welcome-desc">Ingresa tus credenciales para acceder al sistema y continuar gestionando actividades académicas.</p>
          </div>
        </div>

        <!-- Blended Student/Building Graphics -->
        <div class="students-container" style="--login-bg-image: url('{{ asset('images/backgrounds/login-bg.jpg') }}')"></div>

        <!-- Bottom Icons Section -->
        <div class="features-section">
          <div class="feature-item">
            <i class="ti ti-school feature-icon" aria-hidden="true"></i>
            <div class="feature-text">Gestión de<br>estudiantes</div>
          </div>
          <div class="feature-item">
            <i class="ti ti-book feature-icon" aria-hidden="true"></i>
            <div class="feature-text">Cursos y<br>calificaciones</div>
          </div>
          <div class="feature-item">
            <i class="ti ti-clipboard-list feature-icon" aria-hidden="true"></i>
            <div class="feature-text">Trámites<br>administrativos</div>
          </div>
        </div>
        
      </div>

      <!-- RIGHT PANEL -->
      <div class="right-panel">
        
        <!-- Language Selector -->
        <div class="lang-selector-container">
          <button class="lang-selector" aria-label="Cambiar idioma">
            <i class="ti ti-world" aria-hidden="true"></i>
            Español
            <i class="ti ti-chevron-down" aria-hidden="true"></i>
          </button>
        </div>

        <!-- Form Card -->
        <main class="login-card">
          
          <h2 class="card-title">Iniciar sesión</h2>
          <p class="card-subtitle">Por favor, ingresa tus datos para continuar</p>

          <form action="#" method="POST" onsubmit="event.preventDefault();">
            
            <!-- Email Input -->
            <div class="form-group">
              <label for="email" class="form-label">Correo electrónico</label>
              <div class="input-container">
                <i class="ti ti-mail input-icon" aria-hidden="true"></i>
                <input type="email" id="email" class="form-input" placeholder="nombre@institucion.edu.ec" required autocomplete="username">
              </div>
            </div>

            <!-- Password Input -->
            <div class="form-group">
              <label for="password" class="form-label">Contraseña</label>
              <div class="input-container">
                <i class="ti ti-lock input-icon" aria-hidden="true"></i>
                <input type="password" id="password" class="form-input" placeholder="Ingresa tu contraseña" required autocomplete="current-password">
                <i class="ti ti-eye password-toggle" id="passwordToggle" aria-label="Mostrar contraseña" role="button" tabindex="0"></i>
              </div>
            </div>

            <!-- Remember me + Forgot password -->
            <div class="actions-line">
              <label class="remember-me">
                <input type="checkbox" id="rememberMe">
                Recordarme
              </label>
              <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="login-btn">
              <i class="ti ti-login" aria-hidden="true"></i>
              Iniciar sesión
            </button>
            
          </form>



          <!-- Help Info -->
          <div class="help-section">
            <span>
              <i class="ti ti-headset" aria-hidden="true"></i>
              ¿Necesitas ayuda? Contacta a <a href="#" class="help-link">soporte académico</a>
            </span>
          </div>
          
        </main>

      </div>

    </div>

    <!-- FOOTER ROW -->
    <footer class="footer-bar">
      <div class="footer-left">
        <i class="ti ti-shield-check" aria-hidden="true"></i>
        <span>Sistema seguro y protegido</span>
      </div>
      <div class="footer-right">
        <span>© {{ date('Y') }} Sistema Académico. Todos los derechos reservados.</span>
      </div>
    </footer>

  </div>


</body>
</html>
