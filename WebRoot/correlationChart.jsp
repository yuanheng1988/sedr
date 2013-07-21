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
    
    <title>scatter chart</title>
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
   <% 
    int index_x = Integer.valueOf(request.getParameter("index_x"));
  	int index_y = Integer.valueOf(request.getParameter("index_y"));
  	
  	System.out.println(index_x + "||" + index_y);
  	Attribute attr_x = attrs[index_x];
  	Attribute attr_y = attrs[index_y];
  	String xAxis = "";
  	String yAxis = "";
  	%>
  	Data of axis_x and axis_y:
  	<table border="1">
 		<tr><th>AXIS_X:<%=attr_x.name() %></th>
 		<%
 		for(int i=0;i<data.length;i++){
 		 %>
 		 <td><%=data[i].toString(attrs[index_x]) %></td>
 		<%
 		xAxis += data[i].toString(attrs[index_x]);
 		if(i != data.length-1) xAxis += ",";
 		}
 		 %>
 		 </tr>
 		 <tr><th>AXIS_Y:<%=attr_y.name() %></th>
 		<%
 		for(int i=0;i<data.length;i++){
 		 %>
 		 <td><%=data[i].toString(attrs[index_y]) %></td>
 		<%
 		yAxis += data[i].toString(attrs[index_y]);
 		if(i != data.length-1) yAxis += ",";
 		}
 		 %>
 		 <%
 		 System.out.println(xAxis);
 		 System.out.println(yAxis);
 		  %>
 		 </tr>
 	</table>    
     <div id="scatterChart_div"></div>
    <script type="text/javascript">
   	 drawScatterChart('<%=xAxis %>','<%=yAxis %>',document.getElementById('scatterChart_div'));
    </script>
  </body>
</html>
