// Modal component helper functions
export class Modal {
  constructor(modalId) {
    this.modal = document.getElementById(modalId);
  }

  show() {
    if (this.modal) {
      this.modal.style.display = 'block';
    }
  }

  hide() {
    if (this.modal) {
      this.modal.style.display = 'none';
    }
  }
}
