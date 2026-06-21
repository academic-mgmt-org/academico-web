// Estudiantes service data operations
import { apiFetch } from './api.js';

export async function getEstudiantes() {
  return apiFetch('estudiantes');
}
