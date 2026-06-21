// Cursos service data operations
import { apiFetch } from './api.js';

export async function getCursos() {
  return apiFetch('cursos');
}
