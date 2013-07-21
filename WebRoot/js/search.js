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

function advanceDIV() {
	var div = document.getElementById("advanceDIV");
	if(div.style.display == "none")
		div.style.display = "block";
	else if(div.style.display == "block")
		div.style.display = "none";
}

function refresh(beginPage){
	var researchArea = document.getElementById("hidden_researchArea").value;
	var phase = document.getElementById("hidden_phase").value;
	var keyWords = document.getElementById("hidden_keyWords").value;
	location.href= "searchRepository.jsp?ra=" + researchArea + "&begin_page=" + beginPage + 
		"&phase=" + phase + "&keyWords=" + keyWords;
}

function validateJump(totalPageAmount, input) {
	var toPage = input.value;
	var validator = /^[0-9]*[1-9][0-9]*$/;
	if(!validator.test(toPage) || parseInt(toPage) > totalPageAmount) {
		alert("Invalid page number!");
		return;
	}
	
	refresh(parseInt(toPage) - 1);
}

function viewData(originName, currentName) {
	window.open("viewData.jsp?on=" + originName + "&fileName=" + currentName, '');
}

function mark(link) {
	var fileID = link.getAttribute("id");
	var url = "browse?file_id=" + fileID;
	AJAX_Object.open("GET", url, true);
	AJAX_Object.onreadystatechange = function() {
		if(AJAX_Object.readyState == 4) {
			var marked = AJAX_Object.responseText;
			if(marked == 'false' || !marked)
				link.value = "Mark";
			else 
				link.value = "Unmark";
		}
	}
	
	AJAX_Object.send(null);
}

function download(fileID) {
	var url = "browse?file_id=" + fileID;
	AJAX_Object.open("POST", url, true);
	AJAX_Object.onreadystatechange = function() {
		if(AJAX_Object.readyState == 4) {
			var td = document.getElementById("download_count_" + fileID);
			var count = parseInt(AJAX_Object.responseText.split("$$$")[0]);
			td.innerHTML = count;
		}
	}
	
	AJAX_Object.send(null);
}

function showHint(text) {
	
}

window.onload = initial;