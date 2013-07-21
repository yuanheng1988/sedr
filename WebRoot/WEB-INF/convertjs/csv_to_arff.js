var XlTextParsingType = {xlDelimited: 1, xlFixedWidth: 2}
	, XlTextQualifier = {xlTextQualifierDoubleQuote: 1, xlTextQualifierNone: -4142, xlTextQualifierSingleQuote: 2}
	, CODE_PAGE = {GB2312: 936, GBK: 936, 'UTF-8': 65001, 'ISO-8859-1': 1252, 'WINDOWS-1252': 1252
	, DEFAULT: 1252};

function csv2arff() {
}

csv2arff.prototype = new xls2arff();
csv2arff.prototype.ego = csv2arff.prototype;
with(csv2arff.prototype) {
	ego.readArguments = function () {
		sSrcFile = tsIn.ReadLine();
		sCodePage = tsIn.ReadLine();
	};

	ego.open = function () {
//KNOW: OpenText returns boolean value to indicate success/failure. "Auto Detect" code page does not work.
		this.ea.Workbooks.OpenText(sSrcFile, CODE_PAGE[sCodePage.toUpperCase()], 1, XlTextParsingType.xlDelimited
			, XlTextQualifier.xlTextQualifierDoubleQuote, false, false, false, true, false, false);
		this.wb = this.ea.ActiveWorkbook;
		this.iTableCount = this.wb.Worksheets.Count;
	};

	delete ego;
}