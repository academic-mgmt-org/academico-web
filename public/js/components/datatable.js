// DataTable helper class for academic lists
export class DataTable {
  constructor(tableId) {
    this.table = document.getElementById(tableId);
  }

  loadData(data) {
    console.log('Loading table data...', data);
  }
}
