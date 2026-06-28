import { logout } from '../services/auth.service.js';

function setupNotificationMenu() {
  const menu = document.querySelector('[data-notification-menu]');
  if (!menu) {
    return;
  }

  const toggle = menu.querySelector('[data-notification-toggle]');
  const dropdown = menu.querySelector('[data-notification-dropdown]');
  if (!toggle || !dropdown) {
    return;
  }

  const setOpen = (open) => {
    dropdown.hidden = !open;
    toggle.setAttribute('aria-expanded', String(open));

    if (open) {
      const firstItem = dropdown.querySelector('[role="menuitem"]');
      firstItem?.focus({ preventScroll: true });
    }
  };

  toggle.addEventListener('click', (event) => {
    event.stopPropagation();
    setOpen(dropdown.hidden);
  });

  dropdown.addEventListener('click', (event) => {
    event.stopPropagation();
  });

  document.addEventListener('click', (event) => {
    if (!dropdown.hidden && !menu.contains(event.target)) {
      setOpen(false);
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !dropdown.hidden) {
      setOpen(false);
      toggle.focus({ preventScroll: true });
    }
  });
}

function setupLogoutAction() {
  const logoutLink = document.querySelector('[data-logout-link]');
  if (!logoutLink) {
    return;
  }

  logoutLink.addEventListener('click', async (event) => {
    event.preventDefault();
    logoutLink.setAttribute('aria-busy', 'true');

    try {
      await logout();
    } catch (error) {
      localStorage.removeItem('user_token');
      localStorage.removeItem('user_refresh_token');
    } finally {
      window.location.href = '/';
    }
  });
}

document.addEventListener('DOMContentLoaded', setupNotificationMenu);
document.addEventListener('DOMContentLoaded', setupLogoutAction);
