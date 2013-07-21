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
<%
  	request.setCharacterEncoding("utf-8");
   %>
    <center><strong>Your feedback information</strong>
     <form action="feedback" method="POST" onSubmit="return validateFeedback()"><table width="55%" border="1">
		<tr>
			<td align="right" width="20%">Your Email:</td>
			<td align="left"><input size="30" name="email" type="text"
			value="<%=request.getParameter("email") %>" readonly="readonly" />
			</td>
		</tr>
		<tr>
			<td align="right" width="20%">Title:</td>
			<td align="left"><input size="50" name="title" type="text" 
			value="<%=request.getParameter("title") %>" readonly="readonly" /></td>
		</tr>
		<tr>
			<td align="right" width="20%" valign="middle">Content:</td>
			<td align="left"><textarea rows="5" cols="40" name="content" readonly="readonly"><%=request.getParameter("content") %>
			</textarea>
			</td>
		</tr>
	 </table><br/>
	 	Are you sure to submit your feedback?<br/>
	 <br/><a href="javascript:history.go(-1)">Back</a>
	 <input type="submit" value="Confirm and Submit"/>
	 </form></center>
</div>

<div class="div2"><iframe src="left_slide.html" scrolling="no" frameborder="0" height="650" width="195"></iframe></div>
<div><iframe src="bottom.html" width="100%" height="80" frameborder="0" scrolling="no"></iframe></div>
</body>
</html>