// Sidebar component interaction logic
export class Sidebar {
  constructor(elementId) {
    this.sidebar = document.getElementById(elementId);
  }
  
  toggle() {
    if (this.sidebar) {
      this.sidebar.classList.toggle('active');
    }
  }
}
