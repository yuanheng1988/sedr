<%@ page language="java" import="java.util.*" pageEncoding="utf-8"%>
<%@ page import="iscas.nfs.itechs.ese.merge.ArffFile, iscas.nfs.itechs.ese.merge.ArffParser,iscas.nfs.itechs.ese.convert.PropertiesManager" %>
<%@ page import="weka.core.Attribute, weka.core.Instance, weka.core.Instances,weka.core.converters.ArffLoader" %>
<%
String path = request.getContextPath();
String basePath = request.getScheme()+"://"+request.getServerName()+":"+request.getServerPort()+path+"/";
%>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
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
    
    <title><%=request.getParameter("on") %></title>
    
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
   			<table width="75%" border="1">
   			<tr>
   				<td align="center"><font face="Papyrus" size="2">Row Number:</font></td>
   				<%
   					for(int i = 0; i < attrs.length; i++) {
   					%>
   					<td align="center"><font face="Papyrus" size="2">Attribute name:</font>
   					<strong><%=attrs[i].name() %></strong><br/>
   					<font face="Papyrus" size="2">Attribute type:</font>
   					<strong><%=Attribute.typeToString(attrs[i]) %></strong>
   					</td>
   					<%
   					}
   				 %>
   			</tr>
   			<%
   				for(int i = 0; i < data.length; i++) {
   				%>
   					<tr>
   						<td align="center"><%=(i + 1) %></td>
   						<%
   							for(int j = 0; j < attrs.length; j++) {
   							%>
   							<td align="center"><%=data[i].toString(attrs[j]) %></td>
   							<%
   							}
   						 %>
   					</tr>
   				<%
   				}
   			 %>
   		</table>
   			<%
   			} else {
   				out.write("<font size='4' face='Papyrus'><strong>No data</strong></font>");
   			}
   		 %>
   	</center>
  </body>
</html>
