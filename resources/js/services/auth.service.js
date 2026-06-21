// Authentication Service
import { apiFetch } from './api.js';

export async function login(username, password) {
  return apiFetch('auth/login', {
    method: 'POST',
    body: JSON.stringify({ username, password }),
  });
}

export function logout() {
  localStorage.removeItem('user_token');
  window.location.reload();
}
