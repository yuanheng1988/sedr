<%@ page language="java" import="java.util.*, iscas.nfs.itechs.ese.db.*,java.sql.*" buffer="64kb" contentType="text/html; charset=utf-8" %>
<%@ page import="iscas.nfs.itechs.ese.beans.*, iscas.nfs.itechs.ese.utils.*" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Example Tabs</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style type="text/css" media="screen">@import "basic.css";</style>
	<style type="text/css" media="screen">@import "tabs.css";</style>
</head>

<body>
	<div id="header">
	<ul id="primary">

		<li><a href="week_down.jsp">Downloads</a>
		</li>
		<li><span>Contributor</span>
        <ul id="secondary">
			<li><a href="year_contri.jsp" target="_self">Last year</a></li>
				<li><a href="month_contri.jsp" target="_self"  style="color:#609; text-decoration:none">Last month</a></li>
				<li><a href="week_contri.jsp" target="_self" >Last week</a></li>
		
			</ul>
        </li>
	</ul>
	</div>
<div id="main">
	<%
	String[] monthUsers = null;
	monthUsers = (String[])TopGetter.getTop(TopGetter.CONTRIBUTOR, TopGetter.MONTH, monthUsers);
	 %>
		<table width="190" border="0" id="toplist">
  				<%
				for(int i = 0; i < Constants.TOP_CONTRIBUTORS; i++) {
				%>
				
				<%
				if(monthUsers[i] != null) {
				%>
				<%="<tr><td>&nbsp;&nbsp;·&nbsp;" + monthUsers[i] + "</td></tr>"%>
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
