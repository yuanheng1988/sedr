/**
 * When scope and it's scale have been changed, display
 * different research area and attributes respectively
 * 
 * @author Xihao XIE
 * @param scope
 * @param scope_scale
 * @return
 */
function scopeChanged(scp, scp_scale)
{
	/*
	var scope, scope_scale;
	scope = document.getElementById(scp);
	scope_scale = document.getElementById(scp_scale);

	var scope_value = scope.options[scope.selectedIndex].value;
	var scope_scale_value = scope_scale.options[scope_scale.selectedIndex].value;
	*/
	var hidden = document.getElementById("hidden_scope");
	
	if(scope_value == 1 && scope_scale_value == 2) {
		//Scope:single organization
		hidden.value = "1";
		/*
		self.open("single.jsp?scope_id=1", "Data Attributes", 
				"width=800,height=500,toolbar=no,menubar=no");
		*/
	} else if(scope_value == 2 && scope_scale_value == 2) {
		//Scope:single project
		hidden.value = "2";
		/*
		self.open("single.jsp?scope_id=2", "Data Attributes", 
				"width=800,height=500,toolbar=no,menubar=no");
		*/
	} else {
		
	}
}

var fileCount = 1;

function addFile() {
	var div = document.getElementById("fileDIV");
	var fileTable = document.getElementById("file_table");
	var newTable = fileTable.cloneNode(true);
	div.appendChild(newTable);
	newTable.getElementsByTagName("p")[0].innerHTML = (++fileCount);
}

function deleteFile() {
	if(fileCount == 1) {
		alert("You cannot delete file any more!");
		return ;
	}
	var div = document.getElementById("fileDIV");
	div.removeChild(div.lastChild);
	fileCount--;
}

function validate() {
	var integrity = document.getElementById("integrity").value;
	var validator = /^[0-9]+.?[0-9]*$/;
	
	if(validator.test(integrity) && parseFloat(integrity) >= 0 && parseFloat(integrity) <= 100) {
		return true;
	} else {
		alert("Invalid Integrity!");
		return false;
	}
}

function showProvinces(box)
{
	var value = box.options[box.selectedIndex].value;
	var provinces = document.getElementsByName("devProvince")[0];
	
	if(value == 'CN') provinces.style.display = "";
	else provinces.style.display = "none";
	
//	box.options[box.selectedIndex].value = box.options[box.selectedIndex].innerHTML;
}

function validateSingleProject() {
	var validator = /^[0-9]+.?[0-9]*$/;
	var totalEffort = document.getElementsByName("totalEffort")[0].value;
	if(!validator.test(totalEffort) || parseFloat(totalEffort) < 0) {
		alert("Invalid Total Effort!");
		return false;
	}
	
	var totalTime = document.getElementsByName("totalTime")[0].value;
	if(!validator.test(totalTime) || parseFloat(totalTime) < 0) {
		alert("Invalid Total Time!");
		return false;
	}
	
	var totalMember = document.getElementsByName("totalMember")[0].value;
	if(!/^[0-9]+/.test(totalMember) || parseFloat(totalMember) < 0) {
		alert("Invalid Total Member!");
		return false;
	}
	
	var totalSize = document.getElementsByName("totalSize")[0].value;
	if(!validator.test(totalSize) || parseFloat(totalSize) < 0) {
		alert("Invalid Total Size!");
		return false;
	}
	
	var totalDefect = document.getElementsByName("totalDefect")[0].value;
	if(!/^[0-9]+/.test(totalDefect) || parseFloat(totalDefect) < 0) {
		alert("Invalid Total Defect!");
		return false;
	}
	
	var sites = document.getElementsByName("devCountry")[0];
	var country = sites.options[sites.selectedIndex].value;
	if(country == '') {
		alert("Choose your country!");
		return false;
	}
	
	return true;
}

filesFormat = new Array(".arff");
function validateUpload() {
	var files = document.getElementsByName("file_pathname");
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
			alert("The format of the file indexed at " + (index + 1) + " is invalid!");
			return false;
		}
	}
	
	return true;
}