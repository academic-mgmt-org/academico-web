@php
// Verificar autenticación y rol
$session_user = session('user');
if (!$session_user || ($session_user['role'] !== 'ESTUDIANTE' && $session_user['role'] !== 'admin')) {
    // Si no es estudiante ni admin, redirigir a la pantalla de login
    header("Location: /");
    exit();
}

// User details
$user_name = $session_user['username'] ?? "Juan Pérez";
// Obtener primer nombre a partir del correo o usar directamente userName/name de la sesión si están presentes
$user_first_name = $session_user['userName'] ?? $session_user['name'] ?? "Juan";
if (empty($session_user['userName']) && empty($session_user['name']) && !empty($session_user['username'])) {
    $email_parts = explode('@', $session_user['username']);
    $name_parts = explode('.', $email_parts[0]);
    $user_first_name = ucwords($name_parts[0]);
}
$user_role = ($session_user['role'] === 'ESTUDIANTE') ? 'Estudiante' : $session_user['role'];
$academic_period = "2026-A";

// Active statistics
$active_courses_count = 5;
$current_average = 8.75;
$requests_count = 2;

// Active courses array
$courses = [
    [
        "name" => "Programación I",
        "instructor" => "Paralelo A • Ing. Carlos Ramírez",
        "progress" => 78,
        "grad_class" => "blue-grad",
        "icon_type" => "svg",
        "icon_id" => "i-code"
    ],
    [
        "name" => "Base de Datos",
        "instructor" => "Paralelo B • Ing. María López",
        "progress" => 65,
        "grad_class" => "green-grad",
        "icon_type" => "svg",
        "icon_id" => "i-db"
    ],
    [
        "name" => "Ingeniería de Software",
        "instructor" => "Paralelo A • Ing. Diego Torres",
        "progress" => 82,
        "grad_class" => "purple-grad",
        "icon_type" => "svg",
        "icon_id" => "i-gear"
    ],
    [
        "name" => "Matemáticas Discretas",
        "instructor" => "Paralelo C • Ing. Ana Ruiz",
        "progress" => 56,
        "grad_class" => "orange-grad",
        "icon_type" => "text",
        "icon_text" => "Σ"
    ],
    [
        "name" => "Inglés Técnico I",
        "instructor" => "Paralelo B • Lic. Laura Gómez",
        "progress" => 71,
        "grad_class" => "green-grad",
        "icon_type" => "text",
        "icon_text" => "◎"
    ]
];

// Upcoming activities array
$activities = [
    [
        "title" => "Entrega de Proyecto - Programación I",
        "time" => "20 de junio, 2026 • 23:59",
        "badge" => "Próxima",
        "badge_class" => "badge-green",
        "icon_id" => "i-calendar"
    ],
    [
        "title" => "Examen Parcial - Base de Datos",
        "time" => "24 de junio, 2026 • 08:00",
        "badge" => "Examen",
        "badge_class" => "badge-orange",
        "icon_id" => "i-list"
    ],
    [
        "title" => "Taller Práctico - Ingeniería de Software",
        "time" => "26 de junio, 2026 • 14:00",
        "badge" => "Taller",
        "badge_class" => "badge-purple",
        "icon_id" => "i-brief"
    ]
];

// Timeline events
$timeline_events = [
    [
        "time" => "08:00 - 10:00",
        "title" => "Programación I",
        "location" => "Aula 205",
        "event_class" => "event-blue"
    ],
    [
        "time" => "10:15 - 12:15",
        "title" => "Base de Datos",
        "location" => "Aula 301",
        "event_class" => "event-green"
    ],
    [
        "time" => "14:00 - 16:00",
        "title" => "Ingeniería de Software",
        "location" => "Aula 402",
        "event_class" => "event-purple"
    ],
    [
        "time" => "16:15 - 18:15",
        "title" => "Matemáticas Discretas",
        "location" => "Aula 103",
        "event_class" => "event-orange"
    ]
];

// Notifications
$notifications = [
    [
        "text" => "Nueva calificación publicada en Base de Datos",
        "time" => "Hace 2 horas",
        "icon_id" => "i-bell"
    ],
    [
        "text" => "Tu solicitud de revisión fue aprobada",
        "time" => "Hace 1 día",
        "icon_id" => "i-list"
    ],
    [
        "text" => "Recordatorio: Examen Parcial - Programación I",
        "time" => "Hace 2 días",
        "icon_id" => "i-calendar"
    ]
];

if (!empty($notifications_payload) && !empty($notifications_payload['notifications'])) {
    $notifications = $notifications_payload['notifications'];
}

$notifications_count = !empty($notifications_payload)
    ? (int) ($notifications_payload['unreadCount'] ?? count($notifications))
    : count($notifications);
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema Académico - Gestión Integral</title>
  <meta name="description" content="Dashboard del sistema académico para la gestión integral de procesos académicos.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

  <!-- Reusable SVG Icons Sprite Sheet -->
  <svg style="display: none;">
    <symbol id="i-home" viewBox="0 0 24 24"><path d="M3 11.5 12 4l9 7.5"/><path d="M5 10.8V21h14V10.8"/><path d="M9 21v-6h6v6"/></symbol>
    <symbol id="i-book" viewBox="0 0 24 24"><path d="M4 5.5A3.5 3.5 0 0 1 7.5 2H20v18H7.5A3.5 3.5 0 0 0 4 23z"/><path d="M4 5.5A3.5 3.5 0 0 1 7.5 9H20"/><path d="M4 5.5v17"/></symbol>
    <symbol id="i-calendar" viewBox="0 0 24 24"><path d="M5 4h14a2 2 0 0 1 2 2v14H3V6a2 2 0 0 1 2-2z"/><path d="M8 2v4M16 2v4M3 10h18"/></symbol>
    <symbol id="i-list" viewBox="0 0 24 24"><path d="M5 5h14v14H5z"/><path d="M8 9h8M8 13h8M8 17h5"/></symbol>
    <symbol id="i-network" viewBox="0 0 24 24"><circle cx="6" cy="6" r="2"/><circle cx="18" cy="6" r="2"/><circle cx="12" cy="18" r="2"/><path d="M8 7l3 8M16 7l-3 8M8 6h8"/></symbol>
    <symbol id="i-user" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21c1.4-4.2 4.2-6.3 8-6.3s6.6 2.1 8 6.3"/></symbol>
    <symbol id="i-gear" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19 12a7.3 7.3 0 0 0-.1-1l2-1.5-2-3.4-2.4 1a8.7 8.7 0 0 0-1.8-1L14.4 3h-4.8l-.4 3.1a8.7 8.7 0 0 0-1.8 1l-2.4-1-2 3.4L5.1 11a7.3 7.3 0 0 0 0 2l-2 1.5 2 3.4 2.4-1a8.7 8.7 0 0 0 1.8 1l.4 3.1h4.8l.4-3.1a8.7 8.7 0 0 0 1.8-1l2.4 1 2-3.4-2.1-1.5a7.3 7.3 0 0 0 .1-1z"/></symbol>
    <symbol id="i-logout" viewBox="0 0 24 24"><path d="M13 5H5v14h8"/><path d="M12 12h9M18 9l3 3-3 3"/></symbol>
    <symbol id="i-menu" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></symbol>
    <symbol id="i-globe" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c3 3.4 3 14.6 0 18M12 3c-3 3.4-3 14.6 0 18"/></symbol>
    <symbol id="i-bell" viewBox="0 0 24 24"><path d="M18 9a6 6 0 1 0-12 0v5l-2 3h16l-2-3z"/><path d="M10 20a2 2 0 0 0 4 0"/></symbol>
    <symbol id="i-code" viewBox="0 0 24 24"><path d="M8 8 4 12l4 4M16 8l4 4-4 4M14 5l-4 14"/></symbol>
    <symbol id="i-db" viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="7" ry="3"/><path d="M5 5v11c0 1.7 3.1 3 7 3s7-1.3 7-3V5"/><path d="M5 10c0 1.7 3.1 3 7 3s7-1.3 7-3"/></symbol>
    <symbol id="i-brief" viewBox="0 0 24 24"><path d="M6 7h12a2 2 0 0 1 2 2v9H4V9a2 2 0 0 1 2-2z"/><path d="M9 7V5h6v2M9 13h6"/></symbol>
    <symbol id="i-chart" viewBox="0 0 24 24"><path d="M4 19h16"/><path d="M7 16v-5M12 16V7M17 16v-9"/><path d="M6 10l4-4 3 3 5-5"/></symbol>
    <symbol id="i-shield" viewBox="0 0 24 24"><path d="M12 3 20 6v6c0 5-3.3 8-8 9-4.7-1-8-4-8-9V6z"/><path d="m8 12 3 3 5-6"/></symbol>
  </svg>

  <div class="dashboard-layout">

    <!-- Sidebar Section -->
    <aside class="sidebar">
      <div class="sidebar-header">
        <img class="logo" src="{{ asset('images/logos/logo-academico.png') }}" alt="Logo Cap and Book">
        <div class="sidebar-title-container">
          <h2 class="sidebar-title">Sistema Académico</h2>
          <p class="sidebar-subtitle">Gestión integral de<br>procesos académicos</p>
        </div>
      </div>

      <nav class="sidebar-nav">
        <a href="#" class="nav-item active">
          <svg class="icon"><use xlink:href="#i-home"></use></svg>
          <span>Inicio</span>
        </a>
        <a href="#" class="nav-item">
          <svg class="icon"><use xlink:href="#i-book"></use></svg>
          <span>Mis cursos</span>
        </a>
        <a href="#" class="nav-item">
          <svg class="icon"><use xlink:href="#i-calendar"></use></svg>
          <span>Mi horario</span>
        </a>
        <a href="#" class="nav-item">
          <svg class="icon"><use xlink:href="#i-list"></use></svg>
          <span>Calificaciones</span>
        </a>
        <a href="#" class="nav-item">
          <svg class="icon"><use xlink:href="#i-network"></use></svg>
          <span>Matrícula</span>
        </a>
        <a href="#" class="nav-item">
          <svg class="icon"><use xlink:href="#i-list"></use></svg>
          <span>Solicitudes</span>
        </a>
        <a href="#" class="nav-item">
          <svg class="icon"><use xlink:href="#i-book"></use></svg>
          <span>Biblioteca</span>
        </a>
        <a href="#" class="nav-item">
          <svg class="icon"><use xlink:href="#i-user"></use></svg>
          <span>Perfil</span>
        </a>
        <a href="#" class="nav-item">
          <svg class="icon"><use xlink:href="#i-gear"></use></svg>
          <span>Configuración</span>
        </a>
        <a href="#" class="nav-item logout">
          <svg class="icon"><use xlink:href="#i-logout"></use></svg>
          <span>Cerrar sesión</span>
        </a>
      </nav>

      <!-- Students Image Asset inside Sidebar Bottom -->
      <div class="sidebar-footer-image">
        <img src="{{ asset('images/placeholders/people.png') }}" alt="Estudiantes universitarios">
      </div>
    </aside>

    <!-- Main Workspace -->
    <main class="main-area">

      <!-- Header Section -->
      <header class="header">
        <div class="header-left">
          <button class="menu-toggle-btn" aria-label="Abrir menú de navegación">
            <svg class="icon header-icon"><use xlink:href="#i-menu"></use></svg>
          </button>
          <h1 class="page-title">Inicio</h1>
        </div>

        <div class="header-right">
          <!-- Language Selector -->
          <div class="language-selector">
            <svg class="icon header-icon"><use xlink:href="#i-globe"></use></svg>
            <span>Español</span>
            <span class="dropdown-arrow"></span>
          </div>

          <!-- Notifications Alert -->
          <button class="notification-btn" aria-label="Ver {{ $notifications_count }} notificaciones nuevas">
            <svg class="icon header-icon"><use xlink:href="#i-bell"></use></svg>
            <span class="notification-badge">{{ $notifications_count }}</span>
          </button>

          <!-- User Profile -->
          <div class="user-profile">
            <svg class="header-avatar" viewBox="0 0 44 44" aria-hidden="true">
              <circle cx="22" cy="22" r="22" fill="#cce3ff"/>
              <path d="M5 42c4-13 11-20 17-20s13 7 17 20" fill="#0e6ea8"/>
              <circle cx="22" cy="17" r="9" fill="#c98e67"/>
              <path d="M12 12c5-10 18-10 22 0-6 2-13 2-22 0Z" fill="#14213d"/>
            </svg>
            <div class="user-info">
              <span class="user-name">{{ $user_name }}</span>
              <span class="user-role">{{ $user_role }}</span>
            </div>
            <span class="dropdown-arrow"></span>
          </div>
        </div>
      </header>

      <!-- Scrollable Grid Content -->
      <div class="content-container">
        <div class="dashboard-grid">

          <!-- Left Column -->
          <div class="grid-left-col">
            <!-- Welcome Banner -->
            <section class="card welcome-banner">
              <div class="welcome-text-container">
                <h2 class="welcome-heading">¡Bienvenido de nuevo, {{ $user_first_name }}! 👋</h2>
                <div class="period-info">
                  <svg class="icon text-icon"><use xlink:href="#i-calendar"></use></svg>
                  <span>Periodo académico {{ $academic_period }}</span>
                </div>
              </div>
              <div class="welcome-illustration-container">
                <!-- Inline SVG representation of the banner illustration -->
                <svg class="welcome-illustration" viewBox="0 0 350 120" aria-hidden="true">
                  <path d="M20 70 L190 22 L330 70" stroke="#d6e6fb" fill="none" stroke-width="1.2"/>
                  <path d="M46 70 v-18 l70-22 l70 22 v18" stroke="#d6e6fb" fill="none" stroke-width="1.2"/>
                  <path d="M95 70 V40 h44 v30" stroke="#d6e6fb" fill="none" stroke-width="1.2"/>
                  <path d="M245 42 L310 22 L300 102 L226 103 Z" fill="#2478d8" opacity="0.95"/>
                  <path d="M230 101 L314 101 L321 108 L213 108 Z" fill="#9fc7ff"/>
                  <path d="M139 70 L207 94 L271 70 L203 48 Z" fill="#0b5fc9"/>
                  <path d="M172 81 v34" stroke="#073e87" stroke-width="3"/>
                  <circle cx="172" cy="116" r="5" fill="#073e87"/>
                </svg>
              </div>
            </section>

            <!-- Stats Grid -->
            <div class="stats-row">
              <!-- Stat Card 1 -->
              <div class="card stat-card">
                <div class="stat-icon-wrapper blue-grad">
                  <svg class="icon stroke-icon"><use xlink:href="#i-book"></use></svg>
                </div>
                <div class="stat-content">
                  <span class="stat-value">{{ $active_courses_count }}</span>
                  <span class="stat-label">Cursos activos</span>
                </div>
                <a href="#" class="stat-link">Ver detalles <span class="link-arrow">→</span></a>
              </div>

              <!-- Stat Card 2 -->
              <div class="card stat-card">
                <div class="stat-icon-wrapper green-grad">
                  <svg class="icon stroke-icon"><use xlink:href="#i-chart"></use></svg>
                </div>
                <div class="stat-content">
                  <span class="stat-value">{{ $current_average }}</span>
                  <span class="stat-label">Promedio actual</span>
                </div>
                <a href="#" class="stat-link">Ver calificaciones <span class="link-arrow">→</span></a>
              </div>

              <!-- Stat Card 3 -->
              <div class="card stat-card">
                <div class="stat-icon-wrapper purple-grad">
                  <svg class="icon stroke-icon"><use xlink:href="#i-brief"></use></svg>
                </div>
                <div class="stat-content">
                  <span class="stat-value">{{ $requests_count }}</span>
                  <span class="stat-label">Solicitudes</span>
                </div>
                <a href="#" class="stat-link">Ver solicitudes <span class="link-arrow">→</span></a>
              </div>

              <!-- Stat Card 4 -->
              <div class="card stat-card">
                <div class="stat-icon-wrapper orange-grad">
                  <svg class="icon stroke-icon"><use xlink:href="#i-bell"></use></svg>
                </div>
                <div class="stat-content">
                  <span class="stat-value">{{ $notifications_count }}</span>
                  <span class="stat-label">Notificaciones</span>
                </div>
                <a href="#" class="stat-link">Ver todas <span class="link-arrow">→</span></a>
              </div>
            </div>

            <!-- Period Courses Section -->
            <section class="card courses-card">
              <div class="card-header">
                <h3 class="card-title">Mis cursos del periodo</h3>
                <a href="#" class="card-header-link">Ver todos</a>
              </div>

              <div class="courses-list">
                @foreach ($courses as $course)
                <div class="course-item">
                  <div class="course-icon-bg {{ $course['grad_class'] }}">
                    @if ($course['icon_type'] === 'svg')
                      <svg class="icon stroke-icon"><use xlink:href="#{{ $course['icon_id'] }}"></use></svg>
                    @else
                      <span class="course-text-icon">{{ $course['icon_text'] }}</span>
                    @endif
                  </div>
                  <div class="course-details">
                    <h4 class="course-name">{{ $course['name'] }}</h4>
                    <span class="course-instructor">{{ $course['instructor'] }}</span>
                  </div>
                  <div class="course-progress-container">
                    <span class="progress-label">Progreso</span>
                    <div class="progress-bar-bg">
                      <div class="progress-bar-fill" style="width: {{ $course['progress'] }}%;"></div>
                    </div>
                  </div>
                  <span class="progress-percentage">{{ $course['progress'] }}%</span>
                  <button class="course-action-btn">Ver curso</button>
                </div>
                @endforeach
              </div>
            </section>

            <!-- Upcoming Activities Row -->
            <section class="card upcoming-activities">
              <div class="card-header">
                <h3 class="card-title">Próximas actividades</h3>
                <a href="#" class="card-header-link">Ver todas</a>
              </div>

              <div class="activities-list">
                @foreach ($activities as $activity)
                <div class="activity-item">
                  <div class="activity-icon-container">
                    <svg class="icon activity-icon"><use xlink:href="#{{ $activity['icon_id'] }}"></use></svg>
                  </div>
                  <div class="activity-details">
                    <h4 class="activity-title">{{ $activity['title'] }}</h4>
                    <span class="activity-time">{{ $activity['time'] }}</span>
                  </div>
                  <span class="activity-badge {{ $activity['badge_class'] }}">{{ $activity['badge'] }}</span>
                </div>
                @endforeach
              </div>
            </section>
          </div>

          <!-- Right Column -->
          <div class="grid-right-col">
            <!-- Schedule Widget -->
            <section class="card schedule-card">
              <div class="schedule-header">
                <h3 class="card-title">Mi horario</h3>
                <a href="#" class="card-header-link">Ver horario completo</a>
              </div>

              <!-- Day selector tabs -->
              <div class="weekdays-nav">
                <div class="day-tab active">
                  <span class="day-name">Lun</span>
                  <span class="day-num">16</span>
                </div>
                <div class="day-tab">
                  <span class="day-name">Mar</span>
                  <span class="day-num">17</span>
                </div>
                <div class="day-tab">
                  <span class="day-name">Mié</span>
                  <span class="day-num">18</span>
                </div>
                <div class="day-tab">
                  <span class="day-name">Jue</span>
                  <span class="day-num">19</span>
                </div>
                <div class="day-tab">
                  <span class="day-name">Vie</span>
                  <span class="day-num">20</span>
                </div>
                <div class="day-tab">
                  <span class="day-name">Sáb</span>
                  <span class="day-num">21</span>
                </div>
              </div>

              <!-- Schedule Timeline -->
              <div class="timeline-container">
                @foreach ($timeline_events as $event)
                <div class="timeline-event {{ $event['event_class'] }}">
                  <div class="event-time">{{ $event['time'] }}</div>
                  <h4 class="event-title">{{ $event['title'] }}</h4>
                  <span class="event-location">{{ $event['location'] }}</span>
                </div>
                @endforeach
              </div>
            </section>

            <!-- Latest Notifications Widget -->
            <section class="card notifications-card">
              <div class="card-header">
                <h3 class="card-title">Últimas notificaciones</h3>
                <a href="#" class="card-header-link">Ver todas</a>
              </div>

              <div class="notifications-list">
                @foreach ($notifications as $notification)
                <div class="notification-item">
                  <div class="notif-icon-container">
                    <svg class="icon notif-icon"><use xlink:href="#{{ $notification['icon_id'] }}"></use></svg>
                  </div>
                  <div class="notif-details">
                    <p class="notif-text">{{ $notification['text'] }}</p>
                    <span class="notif-time">{{ $notification['time'] }}</span>
                  </div>
                </div>
                @endforeach
              </div>
            </section>
          </div>

        </div>

        <!-- Dashboard Footer -->
        <footer class="footer">
          <div class="footer-left">
            <svg class="icon footer-shield-icon"><use xlink:href="#i-shield"></use></svg>
            <span>Sistema seguro y protegido</span>
          </div>
          <div class="footer-right">
            <span>&copy; {{ date("Y") }} Sistema Académico. Todos los derechos reservados.</span>
          </div>
        </footer>
      </div>

    </main>

  </div>

</body>
</html>
