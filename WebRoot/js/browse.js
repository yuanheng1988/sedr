function advance() {
	var div = document.getElementById("advanceDIV");
	if(div.style.display == "none")
		div.style.display = "block";
	else if(div.style.display == "block")
		div.style.display = "none";
}

function refresh(beginPage){
	var sort_order = document.getElementsByName("sort_order")[0].value;
	var sort_flag = document.getElementsByName("sort_flag")[0].value;
	var keepFresh = document.getElementsByName("keep_fresh")[0].value;
	var researchArea = document.getElementById("researchArea").value;
	location.href= "browseRepository.jsp?sort_order=" + sort_order + "&keepFresh=" + keepFresh + 
			"&sort_flag=" + sort_flag + "&begin_page=" + beginPage + "&researchArea=" + researchArea;
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

function visualData(originName, currentName){
	window.open("visualItemSelected.jsp?on=" + originName + "&fileName=" + currentName,"","");
}

function viewCorrelation(originName, currentName){
	window.open("viewCorrelation.jsp?on=" + originName + "&fileName=" + currentName, '')
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

function drawVisualChart(fileName){
	var showChartDIV = document.getElementById("showChartDIV");
	
	var xAxis_select = document.getElementsByName("axis_x")[0];
	var yAxis_select = document.getElementsByName("axis_y")[0];
	var xAxis_index = xAxis_select.selectedIndex;
	var yAxis_index = yAxis_select.selectedIndex;
	
	var url = "visualChart?fileName=" + fileName + "&index_x=" + xAxis_index + "&index_y=" + yAxis_index;
	AJAX_Object.open("POST", url, true);
	AJAX_Object.onreadystatechange = function() {
		if(AJAX_Object.readyState == 4) {
			showChartDIV.innerHTML = AJAX_Object.responseText.split("$$$")[0] + AJAX_Object.responseText.split("$$$")[2];
			eval(AJAX_Object.responseText.split("$$$")[1]);
			
			showChartDIV.style.display = "";
		}
	}
	
	AJAX_Object.send();	
}

function drawCorrelationChart(fileName,index_x,index_y){
	window.open("correlationChart.jsp?fileName=" + fileName + "&index_x=" + index_x + "&index_y=" + index_y);
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
	
	AJAX_Object.send();
}

function download(fileID, fileName,userID) {
	window.open("download.jsp?fileName=" + fileName, '');
	
	var url = "browse?file_id=" + fileID + "&user_id=" + userID;
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

function more(fileID, fileName) {
	window.open("more.jsp?fileID=" + fileID + "&fileName=" + fileName);
}

function downloadInRightPanel(fileID, fileName,userID) {
	window.open("../download.jsp?fileName=" + fileName, '');
	
	var url = "../browse?file_id=" + fileID + "&user_id=" + userID;
	AJAX_Object.open("POST", url, true);
	AJAX_Object.onreadystatechange = function() {
		if(AJAX_Object.readyState == 4) {
			var td = document.getElementById("download_count_" + fileID);
			//alert(td == null);
			var count = parseInt(AJAX_Object.responseText.split("$$$")[0]);
			td.innerHTML = count;
		}
	}
	
	AJAX_Object.send(null);
}



function check() {
	
}

window.onload = initial;