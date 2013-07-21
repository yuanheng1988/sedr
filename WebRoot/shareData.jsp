<%@ page import="iscas.nfs.itechs.ese.beans.User, iscas.nfs.itechs.ese.db.DBOperation,
				iscas.nfs.itechs.ese.utils.AuthorityUtil" %>
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

<div class="div1">
<%
	User user = (User)session.getAttribute("user");
	if(user == null || !AuthorityUtil.isUplodable(user)) {
	%>
		You are not eligible to upload data. 
		Please <a href="register.jsp">Register</a> or Login.
	<%
	} else {
	%>
	<form method="POST" action="single" onSubmit="return validate()">
	
	<p>Step 1 of 4</p>
	
<p align="left">Common Attributes</p>
<table  height="0" class="shareTable">
	<tr>
		<td width="122">
		<p align="right">Subject Area</td>
		<td colspan="2"><select size="1" name="data_subject">
		<option value="Unknown">Unknown</option>
		<option value="Effort">Effort</option>
		<option value="Defect">Defect</option>
		<option value="Trustworthiness">Trustworthiness</option>
		<option value="Measurement">Measurement</option></select></td>
	</tr>
	<tr>
		<td width="122">
		<p align="right">Generated Phase</td>
		<td colspan="2"><select size="1" name="data_phase">
		<option value="Unknown">Unknown</option>
		<option value="Requirement">Requirement</option>
		<option value="Design">Design</option>
		<option value="Coding">Coding</option>
		<option value="Testing">Testing</option>
		<option value="Maintenance">Maintenance</option>
		</select></td>
	</tr>
	<tr>
		<td width="122" align="right">Scope(Data From)</td>
		<td width="122">
			<select size="1" id="scope_scale" name="scope_scale">
				<option value="Multiple" selected="selected">Multiple</option>
				<option value="Single">Single</option>
			</select>
		</td>
		<td width="122">
			<select size="1" id="scope" name="scope">
				<option value="Organization" selected="selected">Organization</option>
				<option value="Project">Project</option>
			</select>
		</td>
	</tr>
	<tr>
		<td width="122">
		<p align="right">Support Analysis</td>
		<td colspan="2"><select size="1" name="support_analysis">
		<option value="Unknown">Unknown</option>
		<option>Prediction</option>
		<option>Correlation</option>
		<option>Text Mining</option>
		</select></td>
	</tr>
	<tr>
		<td align="right" width="30%">
			Research Area
		</td>
		<td align="left" width="70%" colspan="2">
			<input type="checkbox" name="researchArea" value="Defect Prediction"/>Defect Prediction<br/>
			<input type="checkbox" name="researchArea" value="Effort Prediction"/>Effort Prediction<br/>
			<input type="checkbox" name="researchArea" value="General"/>General<br/>
			<input type="checkbox" name="researchArea" value="Process Study"/>Process Study<br/>
			<input type="checkbox" name="researchArea" value="Requirement Study"/>Requirement Study
		</td>
	</tr>
	<tr>
		<td align="right" width="30%">
			Integrity
		</td>
		<td align="left" width="70%" colspan="2">
			<input type="text" name="integrity" id="integrity"/>&nbsp;&nbsp;%
		</td>
	</tr>
</table>
<p></p>
<p></p>
<p id="submit_para"><input type="submit" value="Next" name="button_submit"/></form>
	<%
	}
 %>
</div>

<div class="div2"><iframe src="left_slide.html" scrolling="no" frameborder="0" height="650" width="195"></iframe></div>
<div><iframe src="bottom.html" width="100%" height="80" frameborder="0" scrolling="no"></iframe></div>
</body>
</html>