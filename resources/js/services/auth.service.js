// Authentication Service
import { apiFetch } from './api.js';

export async function login(username, password) {
  return apiFetch('auth/login', {
    method: 'POST',
    body: JSON.stringify({ username, password }),
  });
}

export async function refreshSession() {
  const refreshToken = localStorage.getItem('user_refresh_token');

  const response = await apiFetch('auth/refresh', {
    method: 'POST',
    body: JSON.stringify({ refreshToken }),
  });

  if (response.token) {
    localStorage.setItem('user_token', response.token);
  }

  if (response.refreshToken) {
    localStorage.setItem('user_refresh_token', response.refreshToken);
  }

  return response;
}

export async function forgotPassword(email) {
  return apiFetch('auth/forgot-password', {
    method: 'POST',
    body: JSON.stringify({ email }),
  });
}

export async function logout() {
  const token = localStorage.getItem('user_token');
  const refreshToken = localStorage.getItem('user_refresh_token');

  const response = await apiFetch('auth/logout', {
    method: 'POST',
    body: JSON.stringify({ token, refreshToken }),
  });

  localStorage.removeItem('user_token');
  localStorage.removeItem('user_refresh_token');

  return response;
}
