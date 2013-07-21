var attrAmount = 1;
var maxAttrAmount = attrAmount;
var selectedFiles = null;

function updateSelectedFiles() {
	selectedFiles = new Array();
	var files = document.getElementsByName("checkbox_file");
	var index = 0;
	for(var i = 0; i < files.length; i++) {
		if(files[i].checked) {
			selectedFiles[index] = i;
			index++;
		}
	}
}

function computeMaxAttrAmount() {
	var min = (selectedFiles.length == 0) ? 1 : data[selectedFiles[0]][0].length;
	for(var i = 1; i < selectedFiles.length; i++) {
		var size = data[selectedFiles[i]][0].length;
		if(size < min) min = size;
	}
	
	maxAttrAmount = min;
}

function addAttribute(fileSize) {
	updateSelectedFiles();
	computeMaxAttrAmount();
	if(attrAmount == maxAttrAmount) {
		alert("You can not create any attribute more!");
		return;
	}
	var mergeTable = document.getElementById("mergeTable");
	var row = mergeTable.rows[1];
	var newRow = mergeTable.insertRow(1);
	for(var j = 0; j < row.cells.length; j++) {
		var newCell = row.cells[j].cloneNode(true);
		newRow.appendChild(newCell);
	}
	attrAmount++;
}

function deleteAttribute() {
	if(attrAmount == 1) {
		alert("You can not delete any attribute more!");
		return;
	}
	var mergeTable = document.getElementById("mergeTable");
//	mergeTable.removeChild(mergeTable.lastChild);
	mergeTable.deleteRow(1);
	attrAmount--;
}

var data;

function mergeDIV() {
	var div = document.getElementById("mergeDIV");
	if(div.style.display == "none") {
		div.style.display = "block";
		insertOptions();
//		computeMaxAttrAmount();
	}
	else if(div.style.display == "block")
		div.style.display = "none";
}

function str2JSON(str, obj) {
	obj = eval("(" + str + ")"); 
    return obj; 
}

function init() {
	var JSONString = document.getElementById("JSONString").value;
	data = str2JSON(JSONString, data);
	
	/**
	 * data[i][j][k]
	 * i: file index
	 * j: 0 or 1. 0 index at ATTR, 1 index at TYPE
	 * k: the concrete value
	 */
}

function insertOptions() {
	for(var i = 0; i < data.length; i++) {
		var select = document.getElementsByName("select_" + i)[0];
		for(var j = 0; j < data[i][0].length; j++) {
			var attrName = data[i][0][j];
			var option = document.createElement("option");
			option.setAttribute("value", attrName);
			option.innerHTML = attrName + "(" + data[i][1][j] + ")";
			select.appendChild(option);
		}
	}
}

function validateAttributeNames() {
	var attrNames = document.getElementsByName("attr");
	for(var i = 0; i < attrNames.length; i++) {
		if(attrNames[i].value == "") {
			alert("Enter a name for attribute " + (i + 1));
			attrNames[i].focus();
			return false;
		}
		
		for(var j = i + 1; j < attrNames.length; j++) {
			if(attrNames[i].value == attrNames[j].value) {
				alert("Attribute name(" + (j + 1) + ") overlap!");
				attrNames[j].focus();
				return false;
			}
		}
	}
	return true;
}



function validateFiles() {
	updateSelectedFiles();
	if(selectedFiles.length == 0) {
		alert("Please choose at least one file to merge!");
		return false;
	} else {
		computeMaxAttrAmount();
		if(attrAmount > maxAttrAmount) {
			alert("Too many attributes!");
			return false;
		}
	}
	return true;
}

function validateType(expectedType, realType) {
	if(expectedType == "numeric" || expectedType == "real" || expectedType == "integer") {
		if(!(realType == "numeric" || realType == "real" || realType == "integer"))
			return false;
		else return true;
	} else return expectedType == realType;
}

function validateTypes() {
	var types = document.getElementsByName("attr_type");
	for(var i = 0; i < types.length; i++) {
		var type = types[i].value.toLowerCase();
		for(var j = 0; j < selectedFiles.length; j++) {
			var select = document.getElementsByName("select_" + selectedFiles[j])[i];
			var selectedType = data[selectedFiles[j]][1][select.selectedIndex].toLowerCase();
			if(!validateType(type, selectedType)) {
				alert("Type error!(file:" + 
						document.getElementsByName("checkbox_file")[selectedFiles[j]].id + 
						", attribute:" + (i + 1) + ")");
				return false;
			}
		}
	}
	
	return true;
}

function validateOverlap() {
	for(var i = 0; i < selectedFiles.length; i++) {
		var selects = document.getElementsByName("select_" + selectedFiles[i]);
		for(var j = 0; j < selects.length; j++) {
			for(var k = j + 1; k < selects.length; k++) {
				if(selects[j].value == selects[k].value) {
					alert("Attribute Overlap!(file:" + 
							document.getElementsByName("checkbox_file")[selectedFiles[i]].id + 
							", attribute:" + (k + 1) + ")");
					return false;
				}
			}
		}
	}
	return true;
}

function validateNewFileName() {
	var file = document.getElementsByName("newFileName")[0];
	if(file.value == "") {
		alert("Invalid file name!");
		file.focus();
		return false;
	}
	
	return true;
}

function generateFileIDs() {
	var ids = "";
	var i = 0;
	for(; i < selectedFiles.length - 1; i++) {
		ids += document.getElementsByName("checkbox_file")[selectedFiles[i]].id + "@";
	}
	ids += document.getElementsByName("checkbox_file")[selectedFiles[i]].id;
	return ids;
}

function generateAttrs() {
	var attrs = "";
	var i = 0, j = 0, nm = document.getElementsByName("attr").length;
	for(; i < selectedFiles.length - 1; i++) {
		for(j = 0; j < nm - 1; j++) {
			attrs += document.getElementsByName("select_" + selectedFiles[i])[j].value + "-_-";
		}
		attrs += document.getElementsByName("select_" + selectedFiles[i])[j].value + "@";
	}
	for(j = 0; j < nm - 1; j++) {
		attrs += document.getElementsByName("select_" + selectedFiles[i])[j].value + "-_-";
	}
	attrs += document.getElementsByName("select_" + selectedFiles[i])[j].value;
	
	return attrs;
}

function validateMerge() {
	if(!validateAttributeNames()) return false;
	if(!validateFiles()) return false;
	if(!validateTypes()) return false;
	if(!validateOverlap()) return false;
	if(!validateNewFileName()) return false;
	
	var mergeForm = document.getElementsByName("mergeForm")[0];
	mergeForm.action = "merge?Files=" + generateFileIDs() + "&Attrs=" + generateAttrs();
	
	return true;
}

