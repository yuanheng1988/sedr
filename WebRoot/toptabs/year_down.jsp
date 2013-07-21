<%@ page language="java" import="java.util.*, iscas.nfs.itechs.ese.db.*,java.sql.*" buffer="64kb" contentType="text/html; charset=utf-8" %>
<%@ page import="iscas.nfs.itechs.ese.beans.*, iscas.nfs.itechs.ese.utils.*" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Example Tabs</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style type="text/css" media="screen">@import "basic.css";</style>
	<style type="text/css" media="screen">@import "tabs.css";</style>
	<script type="text/javascript" src="../js/browse.js"></script>
</head>

<body>
	<div id="header">
	<ul id="primary">

		<li><span>Downloads</span>
			<ul id="secondary">
			<li><a href="year_down.jsp" target="_self" style="color:#609; text-decoration:none">Last year</a></li>
				<li><a href="month_down.jsp" target="_self">Last month</a></li>
				<li><a href="week_down.jsp" target="_self" >Last week</a></li>
		
			</ul>
		</li>
		<li><a href="year_contri.jsp">Contributor</a></li>
	</ul>
	</div>
	<div id="main">
	<%
	UploadedFile[] yearFiles = null;
	yearFiles = (UploadedFile[])TopGetter.getTop(TopGetter.DOWNLOAD, TopGetter.YEAR, yearFiles);
	 %>
		<table width="190" border="0" id="toplist">
  				<%
				for(int i = 0; i < Constants.TOP_CONTRIBUTORS; i++) {
				%>
				
				<%
				if(yearFiles[i] != null) {
				%>
				
				<%
                User user = (User)session.getAttribute("user");
	            if(user == null || !AuthorityUtil.isDownloadable(user)) { 
	            %>
				<tr><td>&nbsp;&nbsp;·&nbsp;<a href="register.jsp" onClick="javascript:return confirm('you haven\'t login,would you like to register first? ');"><%=yearFiles[i].getOriginName() %></a></td></tr>
				<%
	            } else{ 
	            session.setAttribute("user", user);
	            %>
	            <tr><td>&nbsp;&nbsp;·&nbsp;<a href="javascript:downloadInRightPanel(<%=yearFiles[i].getId() %>, '<%=yearFiles[i].getCurrentName() %>')"><%=yearFiles[i].getOriginName() %></a></td></tr>
	            <%
                }
                %>
				<%
				}
				%>
				
				<%
				}%>
</table>
		</div>
	</div>
</body>
</html>
