<%@ page language="java" import="java.util.*, iscas.nfs.itechs.ese.db.*,java.sql.*" buffer="64kb" contentType="text/html; charset=UTF-8" %>
<%@ page import="iscas.nfs.itechs.ese.beans.*, iscas.nfs.itechs.ese.utils.*" %>
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
<div class="searchForm">
<ul>
<li><input name="keyWords" type="text"  class="input2"/></li>
<li><a href="index.jsp"><img src="images/search1.png" width="67" height="24" align="absmiddle" style="border:0"><a></li>
</ul>
</div>
<!--/*左边的介绍和数据信息*/-->
<div class="div1">
  <div> <img src="images/intro.png" width="760" height="25" />
    <p> <span  style="color:#039;"><strong>What is iTechs Software Data Repository?</strong></span></p>
    <p> &nbsp;&nbsp;&nbsp; The central task of empirical software engineering (ESE) studies is the use of data to address a research question.</p>
    <p> The task of ESE researchers is to understand the nature of the process data, artifacts and their relationships. Thus, </p><p>empirical data is indispensable for ESE studies to analyze, manipulate and draw conclusions from empirical studies.</p><p> To establish the ground theory of ESE, we need to collect empirical data as much as possible and to make our</p><p> experiments and case studies repeatable and comparable. With this idea, the ESE researchers in iTeches developed </p><p>the Software Data Repository which may enable ESE researcher to</p>
    <p> &nbsp;&nbsp;&nbsp;&nbsp;<img src="images/arrow2.gif" width="10" height="10" /> &nbsp;store their data in uniform format in the Internet and retrieve the data in the future;</p>
    <p> &nbsp;&nbsp;&nbsp; <img src="images/arrow2.gif" width="10" height="10" /> &nbsp;share their data with the ESE researchers over the world;</p>
    <p> &nbsp;&nbsp;&nbsp; <img src="images/arrow2.gif" width="10" height="10" /> &nbsp;search, filter, merge and reuse the data in the repository on their research interest.</p>
  </div>
  <div style="height:300px; margin-top:60px"> <img src="images/guide.png" width="760" height="25" />
    <p> <span style="color:#039"><strong>The functions in the Software Data Repository are provided as below.</strong></span></p>
 
    <p> &nbsp;&nbsp;&nbsp;&nbsp;<img src="images/arrow2.gif" width="10" height="10" />&nbsp; &ldquo;<strong class="p3">Home</strong>&rdquo;is the index page (i.e. current page).</p>
    <p> &nbsp;&nbsp;&nbsp;&nbsp;<img src="images/arrow2.gif" width="10" height="10" />&nbsp; In &ldquo;<strong class="p3">Browse Repository</strong>&rdquo;, you may browse all the data in the repository.</p>
    <p> &nbsp;&nbsp;&nbsp;&nbsp;<img src="images/arrow2.gif" width="10" height="10" />&nbsp; User may upload their data in &ldquo;<strong class="p3">Share Data</strong>&rdquo;</p>
    <p> &nbsp;&nbsp;&nbsp;&nbsp;<img src="images/arrow2.gif" width="10" height="10" />&nbsp; Data may be previewed, filtered, merged and exported in &ldquo;<strong class="p3">Export Data</strong>&rdquo;.</p>
    <p> &nbsp;&nbsp;&nbsp;&nbsp;<img src="images/arrow2.gif" width="10" height="10" />&nbsp; Users may present us with their comments, advices and questions in &ldquo;<strong class="p3">Feedback &amp; FAQ</strong>&rdquo;. </p>
  </div>
</div>
<!--右边侧栏-->
<div class="div2"><iframe src="left_slide.html" scrolling="no" frameborder="0" height="800" width="195"></iframe></div>
			<%
				UploadedFile[] yearFiles = null;
				yearFiles = (UploadedFile[])TopGetter.getTop(TopGetter.DOWNLOAD, TopGetter.YEAR, yearFiles);
				
				UploadedFile[] monthFiles = null;
				monthFiles = (UploadedFile[])TopGetter.getTop(TopGetter.DOWNLOAD, TopGetter.MONTH, monthFiles);
				
				UploadedFile[] weekFiles = null;
				weekFiles = (UploadedFile[])TopGetter.getTop(TopGetter.DOWNLOAD, TopGetter.WEEK, weekFiles);
			 %>
			
			
<div><iframe src="bottom.html" width="100%" height="40" frameborder="0" scrolling="no"></iframe></div>
</body>
</html>