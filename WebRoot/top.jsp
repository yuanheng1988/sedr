<%@ page language="java" contentType="text/html; charset=UTF-8" import="iscas.nfs.itechs.ese.beans.User"
    pageEncoding="UTF-8"%>
<%
String path = request.getContextPath();
String basePath = request.getScheme()+"://"+request.getServerName()+":"+request.getServerPort()+path+"/";
%>       
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/log.js"></script>
</head>
<body>
<div class="loginForm">
<% 
User user = (User)session.getAttribute("user"); 
if(user == null) { 
%>

<!--<a href="register.jsp" onClick="javascript:p" title="warnning"><img src="images/download-button.jpg" width="15" height="15" border="0" /></a>&nbsp;  -->  
<form name="loginForm" action="login" method="post" onSubmit="return validateLogin()">
  <UL class="loginForm">
    <li>
      <a href="register.jsp" target="_parent">register </a>
    </li>
    <li>&nbsp;&nbsp;ID&nbsp;&nbsp;</li>
    <li>
      <input name="username" type="text" class="input0"/>
    </li>
    <li>&nbsp;&nbsp;Password&nbsp;&nbsp;</li>
    <li>
     &nbsp;&nbsp; <input name="password" type="password"  class="input0"/>
    </li>
    <li>
      &nbsp;&nbsp;<input name="" type="submit" value="Login" class="input4"/>
    </li>
  </UL>
</form>
<%
} else {
%>
<form name="logoutForm" action="login" method="get">
 <UL class="loginForm">
    <li>
      <a href="editInfo.jsp" target="_parent">modify my information </a>
    </li>
    <li>Welcome:&nbsp;&nbsp;</li>
    <li>
    
    </li>
    <li><font color="#000099"><strong><%=user.getName() %></strong></font>&nbsp;&nbsp;|&nbsp;</li>
    <li>
     
    </li>
    <li>
      <input name="" type="submit" value="Logout" class="input3"/>
    </li>
  </UL>
</form>
<%
}
 %>
</div>
<img src="images/logo3.png" width="980"/>
<div class="menucontainer">
   <div class="menu">
    <ul>
      <li><a href="index.jsp" target="_top">Home</a></li>
      <li><a href="browseRepository.jsp" target="_top">Browse Repository</a></li>
      <li><a href="shareData.jsp" target="_top">Share Data</a></li>
      <li><a href="exportData.jsp" target="_top">Export Data</a></li>
      <li><a href="searchRepository.jsp" target="_top">Search Repository</a></li>
      <li><a href="convert.jsp" target="_top">Conversion Online</a></li>
      <li><a href="customize.jsp" target="_top">Customize</a></li>
      <li><a href="feedback.jsp" target="_top">Feedback</a></li>
      <li><a href="faq.jsp" target="_top">FAQ </a></li>
    </ul>
  </div>
</div>
</body>
</html>
