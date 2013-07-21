var WdSaveOptions = {wdDoNotSaveChanges: 0, wdPromptToSaveChanges: -2, wdSaveChanges: -1}
	, WdUnits = {wdCell: 12, wdCharacter: 1, wdCharacterFormatting: 13, wdColumn: 9, wdItem: 16, wdLine: 5
	, wdParagraph: 4, wdParagraphFormatting: 14, wdRow: 10, wdScreen: 7, wdSection: 8,  wdSentence: 3
	, wdStory: 6, wdTable: 15, wdWindow: 11, wdWord: 2};

function doc2arff() {
}

doc2arff.prototype.ego = doc2arff.prototype;
with(doc2arff.prototype) {
	ego.create = function () {
		this.iTableCount = 0;
		this.iTableIndex = 0;
		this.sTableName = 'No Table Name';
		this.sCurrentTable = 'No Such Table';
		this.iRowCount = 0;
		this.iColumnCount = 0;
		this.t = null;
		this.d = null;
		this.wa = null;
		this.wa = new ActiveXObject('Word.Application');
	};

	ego.readArguments = function () {
		sSrcFile = tsIn.ReadLine();
	};

	ego.open = function () {
		this.d = this.wa.Documents.Open(sSrcFile, false, true, false);
		this.iTableCount = this.d.Tables.Count;
	};

	ego.destroy = function () {
		if(this.d != null)
			this.d.Close(WdSaveOptions.wdDoNotSaveChanges);
		if(this.wa != null)
			this.wa.Quit(WdSaveOptions.wdDoNotSaveChanges);
	};

	ego.setTable = function (iTableId) {
		this.iTableIndex = iTableId + 1;
		this.iRowCount = 0;
		this.iColumnCount = 0;
		this.t = this.d.Tables.Item(this.iTableIndex);
		this.sTableName = 'AutoTable' + this.iTableIndex;
		this.sCurrentTable = 'WordTable['  + this.iTableIndex + ']';
	};

	ego.skipTable = function () {
		var r;

		if(!this.t.Uniform)
			return true;
		r = this.t.Cell(1, 1).Range;
		if(r.Text == null || r.Text.length == 1)
			return true;

		this.iRowCount = this.t.Rows.Count;
		this.iColumnCount = this.t.Columns.Count;
		return false;
	};

	ego.cellIndex = function (iRow, iCol) {
		return 'TableCell[' + [iRow + 1, iCol + 1].join(', ') + ']';
	};

//KNOW: Range.Text is always of String type
	ego.cellValue = function (iRow, iCol) {
		var rCell, sValue;

		rCell = this.t.Cell(iRow + 1, iCol + 1).Range;
		rCell.MoveEnd(WdUnits.wdCharacter, -1);
		sValue = rCell.Text;
		if(sValue.length == 0)
			sValue = null;
		return sValue;
	};

	delete ego;
}