<%@ page import="com.jspsmart.upload.*" %>
<%
String path = request.getContextPath();
String basePath = request.getScheme()+"://"+request.getServerName()+":"+request.getServerPort()+path+"/";
%>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <base href="<%=basePath%>">
    <%
    	String fileName = request.getParameter("fileName");
    	String flag = request.getParameter("flag");
    	String zip = request.getParameter("zip");
     %>
    <title><%=fileName %></title>
    
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
    	SmartUpload su = new SmartUpload();
    	
    	su.initialize(pageContext);
    	
    	su.setContentDisposition(null);
    	
    	if(fileName != null) su.downloadFile("upload/" + fileName);
    	else if(flag != null) su.downloadFile("temp/" + flag);
    	else if(zip != null) su.downloadFile("temp/" + zip);
    	
    	out.clear();
    	out = pageContext.pushBody();
     %>
  </body>
</html>
