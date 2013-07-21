<%@ page language="java" import="java.util.*" pageEncoding="utf-8"%>
<%@ page import="iscas.nfs.itechs.ese.utils.Utilities, iscas.nfs.itechs.ese.beans.UploadedFile,
				iscas.nfs.itechs.ese.merge.ArffParser" %>
<%
String path = request.getContextPath();
String basePath = request.getScheme()+"://"+request.getServerName()+":"+request.getServerPort()+path+"/";
%>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Insert title here</title>

<link href="css/default.css" rel="stylesheet" type="text/css">
<link href="css/tabs.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/json2.js"></script>
	<script type="text/javascript" src="js/merge.js"></script>
	<script type="text/javascript">
	function viewData(originName, currentName) {
		window.open("viewData.jsp?on=" + originName + "&fileName=" + currentName, '');
	}
	</script>
</head>

<body>
<iframe src="top.jsp" scrolling="no" frameborder="0" width="100%" height="230"></iframe>

<div class="div4">
<h3><font size="2">Marked Data</font></h3>
<font size="2">
    <%
    HashSet<Integer> markedSet = (HashSet<Integer>)session.getAttribute("markedSet");
	markedSet = (markedSet == null) ? new HashSet<Integer>() : markedSet;
	session.setAttribute("markedSet", markedSet);
	
    UploadedFile[] files = null;
    files = Utilities.getMarkedFiles(session, files);
    	if(files != null)
	    	for(int i = 0; i < files.length; i++) {
	    	%>
	    		<%=(i + 1) %>.&nbsp;<strong>Descriptive Name:</strong>
	    			<%=files[i].getDescription() %>,
	    		<strong>File Format:</strong>
	    			<%=files[i].getFormat() %>,
	    		<a href="javascript:viewData('<%=files[i].getOriginName() %>',
	    		 '<%=files[i].getCurrentName() %>')">View Data</a>
	    		 <br/>
	    	<%
	    	}
	    	
	    	ArffParser ar = new ArffParser();
     %>
</font>
<input type="hidden" id="JSONString" value="<%=ar.readArffFiles(files) %>" />
<script type="text/javascript">window.onload = init;</script>
<p></p>
<a href="javascript:mergeDIV()">Merge Marked Data</a>
<div id="mergeDIV" style="display:none">
	<input type="button" value="New" onClick="addAttribute(<%=files.length %>)"/>&nbsp;&nbsp;
	<input type="button" value="Delete" onClick="deleteAttribute()"/>
	<form name="mergeForm" action="" method="POST" onSubmit="return validateMerge()">
		<table border="1" width="100%" id="mergeTable">
			<tr>
				<td width="10%" align="center">Attribute Name</td>
				<td width="10%" align="center">Attribute Type</td>
				<%if(files != null)
					for(int i = 0; i < files.length; i++) {
					%>
						<td align="center">
						<input type="checkbox" name="checkbox_file" id="<%=files[i].getId() %>"/>
						File <%=files[i].getId() %>
						</td>
					<%
					}
				%>
			</tr>
			<tr>
				<td width="10%" align="center"><input type="text" name="attr"/></td>
				<td width="10%" align="center"><select name="attr_type" onChange="typeChanged(this)">
					<option value="Numeric">Numeric</option><option value="Integer">Integer</option>
					<option value="Real">Real</option><option value="String">String</option>
					<option value="Nominal">Nominal</option><option value="Date">Date</option>
				</select></td>
				<%if(files != null)
					for(int i = 0; i < files.length; i++) {
					%>
						<td align="center"><select name="select_<%=i %>"></select></td>
					<%
					}
				%>
			</tr>
		</table>
		<p><strong>Name of the merged file</strong>:<input type="text" name="newFileName" />.arff</p>
		<p><input type="submit" value="Merge" name="button_merge"></p>
	</form>
</div>

<%
	session.removeAttribute("arffs");
	session.setAttribute("arffs", ar.getFileMap());
 %>
<input type="hidden" id="flag" value="<%=request.getParameter("flag") %>" />


<%
	String flag = request.getParameter("flag"); 
	if(flag != null) {
	%>
		<script type="text/javascript">
			var flag = document.getElementById("flag");
			window.open("download.jsp?flag=" + flag.value);
		</script>
	<%
	}
%>
</div>

<div class="div2"><iframe src="left_slide.html" scrolling="no" frameborder="0" height="650" width="195"></iframe></div>
<div><iframe src="bottom.html" width="100%" height="80" frameborder="0" scrolling="no"></iframe></div>
</body>
</html>