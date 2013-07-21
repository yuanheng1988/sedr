<%@ page language="java" import="java.util.*"%>
<%@ page import="iscas.nfs.itechs.ese.beans.DataBean" pageEncoding="utf-8"%>
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
<script type="text/javascript" src="js/util.js"></script>
</head>

<body>
<iframe src="top.jsp" scrolling="no" frameborder="0" width="100%" height="230"></iframe>

<div class="div4">
<p>
	Step
	<%
 	int stepFlag = Integer.valueOf(request.getAttribute("stepFlag").toString());
 	if(stepFlag == 1) {
 		out.print(" 4 of 4");
 	} else if(stepFlag == 2) {
 		out.print(" 3 of 3");
 	}
 %>
</p>                                                                       
<h1>
  Data Details
  </h1>
  <form name="submitForm" method="POST" action="confirm">
  <table border="1" width="800">
    <%
    DataBean data = (DataBean)session.getAttribute("data");
     %>
     <%=data.toString() %>
     </table><br>
     <input type="submit" value="Confirm & Submit"/>
  </form>
</div>

<div class="div2"><iframe src="left_slide.html" scrolling="no" frameborder="0" height="650" width="195"></iframe></div>
<div><iframe src="bottom.html" width="100%" height="80" frameborder="0" scrolling="no"></iframe></div>
</body>
</html>