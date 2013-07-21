<%@ page language="java" import="java.util.*" pageEncoding="UTF-8"%>
<%@ page import="iscas.nfs.itechs.ese.beans.User, iscas.nfs.itechs.ese.beans.Feedback" %>
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
<link href="css/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/util.js"></script>
</head>

<body>
<iframe src="top.jsp" scrolling="no" frameborder="0" width="100%" height="230"></iframe>
<%
	User user = (User)session.getAttribute("user");
 %>
<div class="div1">
<br/>
<br/>
<form action="showfeedback.jsp" method="POST" onSubmit="return validateFeedback()">
<table height="166">
		<tr>
			<td align="right" width="20%">Your Email:</td>
			<td align="left"><input size="30" name="email" type="text" 
			value="<%=(user == null) ? "" : user.getEmail() %>" /></td>
		</tr>
		<tr>
			<td width="20%" height="43" align="right">Title:</td>
		  <td align="left"><input size="50" name="title" type="text" /></td>
		</tr>
		<tr>
			<td align="right" width="20%" valign="middle">Content:</td>
			<td align="left"><textarea rows="5" cols="40" name="content"></textarea></td>
		</tr>
   </table><br/><input type="submit" value="Submit"/>
	 </form>
	 <%
	 String fb = request.getParameter("feedback");
	 String str = "Thanks for your feedback!";
	  %>
	  <%=(fb == null) ? "" : str %>
</div>

<div class="div2"><iframe src="left_slide.html" scrolling="no" frameborder="0" height="650" width="195"></iframe></div>
<div><iframe src="bottom.html" width="100%" height="80" frameborder="0" scrolling="no"></iframe></div>
</body>
</html>