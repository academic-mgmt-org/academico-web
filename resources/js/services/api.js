// API Client Service
function getCookie(name) {
  const cookie = document.cookie
    .split('; ')
    .find((row) => row.startsWith(`${name}=`));

  if (!cookie) {
    return null;
  }

  return cookie.slice(name.length + 1);
}

export async function apiFetch(endpoint, options = {}) {
  const csrfToken = getCookie('XSRF-TOKEN');
  const { headers: optionHeaders = {}, ...fetchOptions } = options;

  const response = await fetch(`/api/${endpoint}`, {
    credentials: 'same-origin',
    ...fetchOptions,
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
      ...(csrfToken ? { 'X-XSRF-TOKEN': decodeURIComponent(csrfToken) } : {}),
      ...optionHeaders,
    },
  });

  return response.json();
}
