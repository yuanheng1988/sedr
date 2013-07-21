<%@ page language="java" import="java.util.*" pageEncoding="utf-8"%>
<%@ page import="iscas.nfs.itechs.ese.merge.ArffFile, iscas.nfs.itechs.ese.merge.ArffParser,iscas.nfs.itechs.ese.convert.PropertiesManager" %>
<%@ page import="weka.core.Attribute, weka.core.Instance, weka.core.Instances,weka.core.converters.ArffLoader" %>
<%
String path = request.getContextPath();
String basePath = request.getScheme()+"://"+request.getServerName()+":"+request.getServerPort()+path+"/";
%>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<%
	String fileName = request.getParameter("fileName");
	ArffFile file = null;
	
	String relationName = new String();
	ArffLoader loader = new ArffLoader();
	
	Attribute[] attrs = null;
	Instance[] data = null;
	if(fileName != null){
		ArffParser parser = new ArffParser();
		try {
		    loader.setSource(new java.io.File(PropertiesManager.getApp("Constants.FILE_STORAGE"),fileName));
			relationName = loader.getDataSet().relationName();
		
			file = parser.getArffFileWithName(fileName, file);
			attrs = file.getAttributes();
			data = file.getData();
		} catch(Exception e) {
			out.write("The content of the file you want to view is invalid!");
		}
	}
 %>
  <head>
    <base href="<%=basePath%>">
    
    <title><%=request.getParameter("on") %>--Visualization</title>
    <script type="text/javascript" src="js/browse.js"></script>
    <script type="text/javascript" src="js/util.js"></script>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript" src="js/visualize.js"></script>
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">    
	<meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
	<meta http-equiv="description" content="This is my page">
	<!--
	<link rel="stylesheet" type="text/css" href="styles.css">
	-->

  </head>
  
  <body>
   	<center>
   		<%
   			if(file != null){
   			%>
   			<font face="Papyrus" size="4">Relation: <%=relationName %></font>
   			<!-- <form name="visualItemSelectedForm" method="post" action="visualChart.jsp?fileName=<%--=request.getParameter("fileName")--%>"  onSubmit="return validateVisualItemSelected()"> -->
   			<table width="75%" border="0">
   			<tr>
   				<td width="25%" align="right"><font face="Papyrus" size="4">Attribute name 1(Axis X):</font></td>
		        <td width="75%" align="left">
			    <select size="1" name="axis_x" >
				<%
   				for(int i = 0; i < attrs.length; i++) {
   				%>
   				<option value="<%=i %>"><%=attrs[i].name() %>--<%=Attribute.typeToString(attrs[i]) %></option>
   				<%
   					}
   				 %>
   				 </select>
   				 </td>	
   			</tr>
   			<tr>
   				<td width="25%" align="right"><font face="Papyrus" size="4">Attribute name 2(Axis Y):</font></td>
		        <td width="75%" align="left">
			    <select size="1" name="axis_y" >
				<%
   				for(int i = 0; i < attrs.length; i++) {
   				%>
   				<option value="<%=i %>"><%=attrs[i].name() %>--<%=Attribute.typeToString(attrs[i]) %></option>
   				<%
   					}
   				 %>
   				 </select>
   				 </td>	
   			</tr>
   			</table>
   			<!-- <input type="submit" name="visualItemSelectedButtom" value="Draw Chart">  -->
   			<!-- </form> -->
   			<input type="button" value="Draw Chart" onclick="drawVisualChart('<%=fileName %>')">
   			<%
   			} else {
   				out.write("<font size='4' face='Papyrus'><strong>No data</strong></font>");
   			}
   		 %>
   	</center>
   
<div id="showChartDIV" style="display:none">
    
</div>
  </body>
</html>
