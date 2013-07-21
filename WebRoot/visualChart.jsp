<%@ page language="java" import="java.util.*" pageEncoding="utf-8"%>
<%@ page import="iscas.nfs.itechs.ese.merge.ArffFile, iscas.nfs.itechs.ese.merge.ArffParser" %>
<%@ page import="weka.core.Attribute, weka.core.Instance, weka.core.Instances" %>
<%
String path = request.getContextPath();
String basePath = request.getScheme()+"://"+request.getServerName()+":"+request.getServerPort()+path+"/";
%>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<%
	String fileName = request.getParameter("fileName");
	ArffFile file = null;
	Attribute[] attrs = null;
	Instance[] data = null;
	if(fileName != null){
		ArffParser parser = new ArffParser();
		try {
			file = parser.getArffFileWithName(fileName, file);
			attrs = file.getAttributes();
			data = file.getData();
			System.out.println("hehe");
		}  catch(Exception e) {
			out.write("The content of the file you want to view is invalid!");
		}
	}
 %>
  <head>
    <base href="<%=basePath%>">
    
    <title>visual Chart</title>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript" src="js/visualize.js"></script>
    
	<!--
	<link rel="stylesheet" type="text/css" href="styles.css">
	-->

  </head>
  
  <body>
  	<center>
  	<% 
//    try{
    int index_x = Integer.valueOf(request.getParameter("axis_x"));
  	int index_y = Integer.valueOf(request.getParameter("axis_y"));
  	
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
 	
 	<div id="table_div"></div>
    <div id="scatterChart_div"></div>
    <div id="columnChart_div"></div>
    <%
    if(Attribute.typeToString(attrs[index_x]).equals("numeric") && Attribute.typeToString(attrs[index_y]).equals("numeric")){
     %>
    <script type="text/javascript">drawScatterChart('<%=xAxis %>','<%=yAxis %>');</script>
    <%
    }
    else if((Attribute.typeToString(attrs[index_x]).equals("string") || Attribute.typeToString(attrs[index_x]).equals("nominal")) && Attribute.typeToString(attrs[index_y]).equals("numeric")){
     %>
    <script type="text/javascript">drawColumnChart('<%=xAxis %>','<%=yAxis %>');</script>
     <%
     }
     else if( Attribute.typeToString(attrs[index_x]).equals("date") && Attribute.typeToString(attrs[index_y]).equals("numeric")){
     %>
     <script type="text/javascript">drawColumnChart("<%=xAxis %>",'<%=yAxis %>');</script>
     <%
     }
     else{
         out.write("<font size='4' face='Papyrus'><strong>No chart</strong></font>");
     }
      %>
      
      
  </center>
  </body>
</html>
