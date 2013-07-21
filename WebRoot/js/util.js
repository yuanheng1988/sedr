function rtrim(str) {
	return str.replace(/(\s*$)/g, "");
}

function ltrim(str) {
	return str.replace(/(^\s*)/g, "");
}

function trim(str) {
	return ltrim(rtrim(str));
}

function validateFeedback() {
	var email = document.getElementsByName("email")[0];
	var format = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
	if(email.value == "" || !format.test(email.value)) {
		alert("Invalid email address!\n");
		email.focus();
		return false;
	}
	var title = document.getElementsByName("title")[0];
	if(title.value.length == 0) {
		alert("Invalid title!");
		title.focus();
		return false;
	}
	var content = document.getElementsByName("content")[0];
	if(content.value.length == 0) {
		alert("Invalid content!");
		content.focus();
		return false;
	}
//	return isConfirm(email.value, title.value, content.value);
	return true;
}

function switchDownload(id) {
	var adiv = document.getElementById("topDownloadDIV" + id);
	var bdiv = document.getElementById("topDownloadDIV" + ((id + 1) % 3));
	var cdiv = document.getElementById("topDownloadDIV" + ((id + 2) % 3));
	
	adiv.style.display = "block";
	bdiv.style.display = "none";
	cdiv.style.display = "none";
}

function switchContributor(id) {
	var adiv = document.getElementById("topContributorDIV" + id);
	var bdiv = document.getElementById("topContributorDIV" + ((id + 1) % 3));
	var cdiv = document.getElementById("topContributorDIV" + ((id + 2) % 3));
	
	adiv.style.display = "block";
	bdiv.style.display = "none";
	cdiv.style.display = "none";
}

function validateRegister() {
	var username = document.getElementsByName("username")[0];
	var password = document.getElementsByName("password")[0];
	var pwd = document.getElementsByName("pwd")[0];
	var email = document.getElementsByName("email")[0];
	
	if(trim(username.value) == "") {
		alert("Invalid username!");
		username.focus();
		return false;
	}
	
	if(trim(password.value) == "") {
		alert("Invalid password!");
		password.focus();
		return false;
	}
	
	if(password.value.length < 6) {
		alert("Your password is too short!");
		password.focus();
		return false;
	}
	
	if(trim(pwd.value) == "") {
		alert("Please confirm your password!");
		pwd.focus();
		return false;
	}
	
	if(password.value != pwd.value) {
		alert("Please confirm your password!");
		pwd.focus();
		return false;
	}
	
	var format = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
	if(email.value == "" || !format.test(email.value)) {
		alert("Invalid email address!\n");
		email.focus();
		return false;
	}
	
	return true;
}

var AJAX_Object = false;

function initial() {
	try {
		AJAX_Object = new ActiveXObject("Msxml2.XMLHTTP");
	} catch(e) {
		try {
			AJAX_Object = new ActiveXObject("Microsoft.XMLHTTP");
		} catch(ee) {
			AJAX_Object = false;
		}
	}
	
	if(!AJAX_Object && typeof XMLHttpRequest != 'undefined') 
		AJAX_Object = new XMLHttpRequest();
}

window.onload = initial;

function deleteFile(index,fileID) {
	var url = "delete?fileID=" + fileID;
	AJAX_Object.open("POST", url, true);
	AJAX_Object.onreadystatechange = function() {
		if(AJAX_Object.readyState == 4) {
			var deleteform = document.getElementsByName("fileEdit")[parseInt(index)];
			deleteform.parentNode.removeChild(deleteform);
		}
	}
	
	AJAX_Object.send(null);
}

function edit()
{
	var username = document.getElementById("username");
	var affiliation = document.getElementById("affiliation");
	var title = document.getElementById("title");
	var email = document.getElementById("email");
	var interest = document.getElementById("interest");
	var country = document.getElementById("country");
	var savebutton = document.getElementById("savebutton");
	username.disabled = false;
	affiliation.disabled = false;
	title.disabled = false;
	email.disabled = false;
	interest.disabled = false;
	country.disabled = false;
	savebutton.disabled = false;
}

function save()
{
	var username = document.getElementById("username");
	var affiliation = document.getElementById("affiliation");
	var title = document.getElementById("title");
	var email = document.getElementById("email");
	var interest = document.getElementById("interest");
	var country = document.getElementById("country");
	username.readonly = "readonly";
	affiliation.readonly = "readonly";
	title.readonly = "readonly";
	email.readonly = "readonly";
	interest.readonly = "readonly";
	country.readonly = "readonly";
}

function validateReload()
{
	var sites = document.getElementsByName("reloadFileSelect")[0];
	var country = sites.options[sites.selectedIndex].value;
	if(country == '') {
		alert("Choose the file to reload!");
		return false;
	}
	
	var file = document.getElementsByName("file_pathname1")[0];
	if(file.value == ""){
		alert("no file!");
		return false;
	}
	
	return true;
}

function filenameEdit(index)
{
	var divId = "renameDIV"+index;
	var div = document.getElementById(divId);
	var button = document.getElementsByName("renameButton")[parseInt(index)];
	if(div.style.display == "none"){
		div.style.display = "block";
		button.value = "Complete";
	}
	else if(div.style.display == "block"){
		div.style.display = "none";
		button.value = "Edit";
	}
}

function renameFile(index,fileId)
{
	var newname = document.getElementsByName("newname")[parseInt(index)].value;
    var url = "rename?newname=" + newname + "&fileId=" + fileId;
    
    document.location = url;
}

function deleteCustomItem(index,customId)
{
	var url = "deleteCustom?customId=" + customId;
//	alert(url);
	AJAX_Object.open("POST", url, true);
	AJAX_Object.onreadystatechange = function() {
		if(AJAX_Object.readyState == 4) {
			var deleteform = document.getElementsByName("customEdit")[parseInt(index)];
			deleteform.parentNode.removeChild(deleteform);
		}
	}
	
	AJAX_Object.send(null);
}

function validateVisualItemSelected(){
	/*var axis_x = document.getElementsByName("axis_x")[0];
	var axis_y = document.getElementsByName("axis_y")[0];
	
	var x_value = axis_x.options[axis_x.selectedIndex].value;
	var y_value = axis_y.options[axis_y.selectedIndex].value;
	
	if(true)
		return true;*/
	
	return true;
}