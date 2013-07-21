var XlUpdateLinks = {xlUpdateLinksUserSetting: 1, xlUpdateLinksNever: 2, xlUpdateLinksAlways: 3};

function xls2arff() {
}

xls2arff.prototype.ego = xls2arff.prototype;
with(xls2arff.prototype) {
	ego.create = function () {
		this.iTableCount = 0;
		this.iTableIndex = 0;
		this.sTableName = 'No Table Name';
		this.sCurrentTable = 'No Such Worksheet';
		this.rTable = null;
		this.iRowCount = 0;
		this.iColumnCount = 0;
		this.ws = null;
		this.wb = null;
		this.ea = null;
		this.ea = new ActiveXObject('Excel.Application');
	};

	ego.readArguments = function () {
		sSrcFile = tsIn.ReadLine();
	};

	ego.open = function () {
		this.wb = this.ea.Workbooks.Open(sSrcFile, XlUpdateLinks.xlUpdateLinksNever, true);
		this.iTableCount = this.wb.Worksheets.Count;
	};

	ego.destroy = function () {
		if(this.wb != null)
			this.wb.Close(false);
		if(this.ea != null)
			this.ea.Quit();
	};

	ego.setTable = function (iTableId) {
		this.iTableIndex = iTableId + 1;
		this.rTable = null;
		this.iRowCount = 0;
		this.iColumnCount = 0;
		this.ws = this.wb.Worksheets.Item(this.iTableIndex);
		this.sTableName = this.ws.Name;
		this.sCurrentTable = 'Worksheet['  + this.iTableIndex + '] = ' + this.ws.Name;
	};

	ego.skipTable = function () {
		var r;

		r = this.ws.Range('A1');
		if(r.Value == null)
			return true;

		this.rTable = r.CurrentRegion();
		this.iRowCount = this.rTable.Rows.Count;
		this.iColumnCount = this.rTable.Columns.Count;
		return false;
	};

	ego.cellIndex = function (iRow, iCol) {
		return 'R' + (iRow + 1) + 'C' + (iCol + 1);
	};

//KNOW: Range.Value is typed, according to the cell format setting, the type can be String, Number, Date, etc.
	ego.cellValue = function (iRow, iCol) {
		var rCell;

		rCell = this.rTable.Item(iRow + 1, iCol + 1);
		return rCell.Value;
	};

	delete ego;
}