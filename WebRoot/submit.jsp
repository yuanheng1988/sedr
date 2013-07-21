<%@ page language="java" import="java.util.*" pageEncoding="utf-8"%>
<%@ page import="iscas.nfs.itechs.ese.utils.Utilities" %>
<%
String path = request.getContextPath();
String basePath = request.getScheme()+"://"+request.getServerName()+":"+request.getServerPort()+path+"/";
%>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Welcome to iTechs SEDR!</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/share.js"></script>
</head>

<body>
<iframe src="top.jsp" scrolling="no" frameborder="0" width="100%" height="230"></iframe>

<div class="div4">
<form name="final_form" id="final_form" method="POST" action="record" enctype="multipart/form-data" 
    onsubmit="return validateUpload()" accept-charset="gbk">
    
    <h3>Brief Guide</h3>
    
<p align="left">
<!-- 
<a href="javascript:addFile('submit_para', 'file_table')">Add Data File</a>
 -->
 Step
 <%
 	int stepFlag = Integer.valueOf(request.getAttribute("stepFlag").toString());
 	if(stepFlag == 1) {
 		out.print(" 3 of 4");
 	} else if(stepFlag == 2) {
 		out.print(" 2 of 3");
 	}
 %>
 <input type="hidden" name="stepFlag" value="<%=stepFlag %>" />
</p>
<div id="fileDIV">
<table  width="700" ><tr>
<td width="26"></td><td width="229"></td>
<td width="429"><input type="button" value="Add Files" onClick="addFile()" class="input5"/>
<input type="button" value="Delete" onClick="deleteFile()"  class="input5"/></td>
</tr></table>
<table id="file_table" width="700"  class="shareTable">

	<tr>
		<td >
		
		<p align="right">Descriptive Name</td>
		<td width="0"><input type="text" name="file_descriptive_name" size="68"></td>
	</tr>
	<tr>
		<td>
		<p align="right">File Format</td>
		<td><select size="1" name="file_format">
		<option value="ARFF, Attribute-Relation File Format">ARFF, Attribute-Relation File Format</option>
		<!-- 
		<option value="CSV, Comma-Separated Values">CSV, Comma-Separated Values</option>
		<option value="Other">Other</option>
		 -->
		</select></td>
	</tr>
	<tr>
		<td>
		<p align="right">Pathname of File</td>
		<td><input type="file" name="file_pathname" size="68"></td>
	</tr>
 
</table>
</div>

	<div style="float:right; margin-right:30px;"><input type="submit" value="OK"  class="input5"/></div>
    </form>
</div>

<div class="div2"><iframe src="left_slide.html" scrolling="no" frameborder="0" height="650" width="195"></iframe></div>
<div><iframe src="bottom.html" width="100%" height="80" frameborder="0" scrolling="no"></iframe></div>
</body>
</html>