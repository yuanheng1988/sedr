filesFormat = new Array(".doc", ".xls", ".csv");

function validateFileName() {
	var fileName = document.getElementsByName("file_name")[0];
	if(fileName.value == "") {
		if(!confirm("Convert to DEFAULT.arff?")) {
			fileName.focus();
			return false;
		}
	}
	
	return true;
}

function validateConvert() {
	var files = document.getElementsByName("file");
	if(files[0].value == "") {
		alert("Please choose a file!");
		return false;
	}
	var index = 0, flag = 0;
	var file, format, flag;
	for(; index < files.length; index++) {
		flag = 0;
		file = files[index].value;
		if(!file) continue;
		while(file.indexOf("\\") != -1)
			file = file.slice(file.indexOf("\\") + 1);
		format = file.slice(file.indexOf(".")).toLowerCase();
		var i = 0;
		for(i = 0; i < filesFormat.length; i++)
			if(filesFormat[i] == format) flag = 1;
		if(flag == 0 && i == filesFormat.length) {
			alert("The format of the file is invalid!");
			return false;
		}
	}
	
	document.getElementsByName("file_type")[0].value = format;
	
	return validateFileName();
}