var END_OF_TABLE = '\0', RESULT_ERROR = 'error', RESULT_SUCCESS = 'success', RESULT_END = 'end'
	, COMMAND_QUIT_SCRIPT = 'quit_script';
var sCodePage, sSrcFile, toArff, tsIn, tsOut, tsErr;

tsIn = WScript.StdIn;
tsOut = WScript.StdOut;
tsErr = WScript.StdErr;

//CHOICE: use # or other special characters to indicate an error line inserted into standard output.
function convertToArff() {
	var sResult, asError, iLp;

//TODO: limit the length of file pathname for open
	try {
		toArff.create();
		toArff.readArguments();
		toArff.open();
		sResult = RESULT_SUCCESS;
	} catch(e) {
		sResult = RESULT_ERROR;
		asError = [];
		asError.push(errorString(e));
		asError.push('Stage: Initialization');
	} finally {
		tsErr.WriteLine(sResult);
		if(sResult == RESULT_ERROR) {
			toArff.destroy();
			printError(asError);
			waitForCommand(COMMAND_QUIT_SCRIPT);
			return;
		}
	}

	for(iLp = 0; iLp < toArff.iTableCount; iLp++) {
		toArff.setTable(iLp);
		if(toArff.skipTable())
			continue;

		try {
			convertTable();
			sResult = RESULT_SUCCESS;
		} catch(e) {
			sResult = RESULT_ERROR;
			asError = [];
			asError.push(errorString(e));
			asError.push('Stage: Converting ' + toArff.sCurrentTable);
			if(e.cause != null)
				asError.push('Caused by: ' + e.cause);
		} finally {
			tsErr.WriteLine(sResult);
			if(sResult == RESULT_ERROR)
				printError(asError);
		}
		tsOut.Write(END_OF_TABLE);
	}
	tsOut.Write(END_OF_TABLE);
	tsErr.WriteLine(RESULT_END);

	toArff.destroy();
	waitForCommand(COMMAND_QUIT_SCRIPT);
}

function convertTable() {
	var sRelationName, sAttributeName, sCell, sValue, asCellRow, iRow, iCol, iAttrCnt;

	try {
		sRelationName = escapeString(toArff.sTableName);
		tsOut.WriteLine('@relation ' + sRelationName);
	} catch(e) {
		e.cause = 'Relation Name';
		throw e;
	}
	tsOut.WriteBlankLines(1);

	for(iRow = 0, iCol = 0, iAttrCnt = 1; iCol < toArff.iColumnCount; iCol++)
		try {
			sValue = toArff.cellValue(iRow, iCol);
			if(sValue == null)
				sValue = 'AutoAttr' + iAttrCnt++;
			sAttributeName = escapeString(String(sValue));
			tsOut.WriteLine('@attribute ' + sAttributeName + ' string');
		} catch(e) {
			e.cause = 'Attribute at ' + toArff.cellIndex(iRow, iCol);
			throw e;
		}
	tsOut.WriteBlankLines(1);

	tsOut.WriteLine('@data');
	for(iRow = 1, asCellRow = new Array(toArff.iColumnCount); iRow < toArff.iRowCount; iRow++) {
		for(iCol = 0; iCol < toArff.iColumnCount; iCol++)
			try {
				sValue = toArff.cellValue(iRow, iCol);
				if(sValue == null)
					sValue = '?';
				sCell = escapeString(String(sValue));
				asCellRow[iCol] = sCell;
			} catch(e) {
				e.cause = 'Data at ' + toArff.cellIndex(iRow, iCol);
				throw e;
			}
		tsOut.WriteLine(asCellRow.join(','));
	}
}

function escapeString(sRaw) {
	var iCode, sChar, iChar, iLen, sQuote, iQuote;

	if(sRaw == null || sRaw.length == 0)
		throw Error('Null or Empty String');

	for(iChar = 0, iLen = sRaw.length, sQuote = '', iQuote = 0; iChar < iLen; 
		iChar++, sQuote += sChar) {
		sChar = sRaw.charAt(iChar);
		iCode = sRaw.charCodeAt(iChar);
		if(iCode >= 0 && iCode < 0x20) {
			switch(iCode) {
			case 0x7:
				sChar = "\\\\a";
				break;
			case 0x8:
				sChar = "\\\\b";
				break;
			case 0x9:
				sChar = "\\t"
				break;
			case 0xa:
				sChar = "\\n";
				break;
			case 0xb:
				sChar = "\\\\v";
				break;
			case 0xc:
				sChar = "\\\\f";
				break;
			case 0xd:
				sChar = "\\r";
				break;
			default:
				sChar = "\\\\" + iCode.toString(8);
				break;
			}
			iQuote++;
			continue;
		}
		switch(sChar) {
		case '"':
		case '\'':
		case '\\':
			sChar = '\\' + sChar;
			break;
		case '{':
		case '}':
		case ',':
		case ' ':
		case '%':
			break;
		case '?':
		default:
			continue;
		}
		iQuote++;
	}
	if(iQuote > 0)
		sQuote = '\'' + sQuote + '\'';
	return sQuote;
}

function errorString(e) {
	return e.name + ': ' + e.message;
}

//MUST: asError.length > 0
function printError(asError) {
	var iLp;

	tsErr.WriteLine(asError.length);
	for(iLp = 0; iLp < asError.length; iLp++)
		tsErr.WriteLine(asError[iLp]);
}

function waitForCommand(sCommand, iInterval) {
	if(iInterval == null)
		iInterval = 1000;
	while(!tsIn.AtEndOfStream && tsIn.ReadLine() != sCommand)
		WScript.Sleep(iInterval);
}