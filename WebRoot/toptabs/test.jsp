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

		<li><span>Downloads</span>
			<ul id="secondary">
				<li><a href="year_down.html">Last year</a></li>
				<li><a href="month_down.html">Last month</a></li>
				<li><a href="day_down.html">Last day</a></li>
		
			</ul>
		</li>
		<li><a href="portfolio.html">Contributor</a></li>
	</ul>
	</div>
	<div id="main">
		<%
	UploadedFile[] weekFiles = null;
	weekFiles = (UploadedFile[])TopGetter.getTop(TopGetter.DOWNLOAD, TopGetter.MONTH, weekFiles);
	 %>
		<table width="190" border="0" id="toplist" height="300">
  				<%
				for(int i = 0; i < Constants.TOP_CONTRIBUTORS; i++) {
				%>
				<tr>
				<%
				if(weekFiles[i] != null) {
				%>
				<%="<td>" + weekFiles[i].getOriginName() + "</td>"%>
				<%
				}
				%>
				</tr>
				<%
				}%>

</table>

		</div>
	</div>
</body>
</html>
