// Matriculas service data operations
import { apiFetch } from './api.js';

export async function getMatriculas() {
  return apiFetch('matriculas');
}
