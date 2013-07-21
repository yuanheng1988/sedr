<%@ page language="java" import="java.util.*" pageEncoding="UTF-8"%>
<%@ page import="iscas.nfs.itechs.ese.utils.SystemInitializer, iscas.nfs.itechs.ese.beans.UploadedFile,
			iscas.nfs.itechs.ese.utils.Constants" %>
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
<script type="text/javascript" src="js/search.js"></script>
</head>

<body>
<iframe src="top.jsp" scrolling="no" frameborder="0" width="100%" height="230"></iframe>

<div class="div1">
<!-- SEARCH INTERFACE -->
   <%
   	SystemInitializer si = (SystemInitializer)application.getAttribute("SystemInitializer");
   	si = (si == null) ? new SystemInitializer() : si;
   	application.setAttribute("SystemInitializer", si);
   	String kws = request.getParameter("keyWords");
	String[] keyWords = null;
	keyWords = (kws == null || kws.equals("")) ? keyWords : kws.split("\\s+");
   	String[] ra = null;
   	if(request.getParameter("ra") != null) ra = request.getParameter("ra").split("@");
   	String[] phase = null;
   	if(request.getParameter("phase") != null) phase = request.getParameter("phase").split("@");
   	
   	HashSet<Integer> result = null;
   	long begin = System.currentTimeMillis();
   	result = si.getMatchedFiles(keyWords, ra, phase, result);
   	long end = System.currentTimeMillis();
   	
   	boolean searched = false;
   	if(request.getParameter("searched") != null) searched = true;
    %>
<form action="search" method="POST" name="searchForm">
<br/>

    <font face="Papyrus"><strong>Keywords:</strong></font>&nbsp;
		<input name="keyWords" type="text" value="<%=(kws == null) ? "" : kws %>" size="60" onChange="showHint(this)" />&nbsp;
    <input name="submit" type="submit" value="Search" class="input5"/>&nbsp;
    <%--if(kws != null) {
    --%>
    <a href="javascript:advanceDIV()"><font size="2">Advance</font></a>
    <%--
    } --%>
    <br>
    <%= searched ? "<font size='2'>" + result.size() + " files matched in " + 
    (end - begin) / 1000.0 + " seconds</font>": "" %>
<div id="advanceDIV" style="display:none">
<h3>Advanced options</h3>

    <table border="0" width="94%">
    	<tr>
    		<td width="15%" align="right">Research Area:</td>
    		<td align="left">
    		<input type="checkbox" name="researchArea" value="Defect Prediction"/>Defect Prediction</td>
            <td align="left">
    		<input type="checkbox" name="researchArea" value="Effort Prediction"/>Effort Prediction</td>
            <td align="left">
    		<input type="checkbox" name="researchArea" value="General"/>General</td>
            <td align="left">
    		<input type="checkbox" name="researchArea" value="Process Study"/>Process Study</td>
            <td align="left">
    		<input type="checkbox" name="researchArea" value="Requirement Study"/>Requirement Study
            
    		</td>
    	</tr>
    	<tr>
    		<td width="15%" align="right">Generated Phase:</td>
    		<td align="left">
    		<input type="checkbox" name="phase" value="Requirement"/>Requirement</td>
               <td align="left">
    		<input type="checkbox" name="phase" value="Design"/>Design</td>
               <td align="left">
    		<input type="checkbox" name="phase" value="Coding"/>Coding</td>
               <td align="left">
    		<input type="checkbox" name="phase" value="Testing"/>Testing</td>
               <td align="left">
    		<input type="checkbox" name="phase" value="Maintenance"/>Maintenance</td>
           
    		
    	</tr>
    </table>
</div>
</form>
 <div class="data_list">
<!-- SEARCH INTERFACE -->

<!-- FETCHING FILES -->
	<%
		String beginpg = request.getParameter("begin_page");
		int beginPage = (beginpg == null) ? 0 : Integer.valueOf(beginpg);
		UploadedFile[] list = null;
		list = si.getFilesList(beginPage, result, list);
		
		HashSet<Integer> markedSet = (HashSet<Integer>)session.getAttribute("markedSet");
		markedSet = (markedSet == null) ? new HashSet<Integer>() : markedSet;
		session.setAttribute("markedSet", markedSet);
	 %>
<!-- FETCHING FILES -->


<%
	int totalPageAmount = result.size() / Constants.FILES_AMOUNT_PER_PAGE;
	if(totalPageAmount * Constants.FILES_AMOUNT_PER_PAGE != result.size())
		totalPageAmount++;
	
	boolean last = (beginPage + 1) * Constants.FILES_AMOUNT_PER_PAGE >= result.size() ? true : false;
	String previous = (beginPage == 0) ? "" : 
		"<a href='searchRepository.jsp?begin_page=" + (beginPage - 1) + "&phase=" + phase +
		"&ra=" + request.getParameter("ra") + "&keyWords=" + kws + "'>Previous</a>";
	String next = last ? "" : "<a href='searchRepository.jsp?begin_page=" + (beginPage + 1) + 
	"&ra=" + request.getParameter("ra") + "&keyWords=" + kws + "&phase=" + phase + "'>Next</a>";
	
	if(list.length > 0) {
%>
<!-- PAGE INDEX -->

<p>
	<strong>Page</strong>&nbsp;<%=(beginPage + 1) %>&nbsp;&nbsp;

	<a href="searchRepository.jsp?begin_page=0&ra=<%=request.getParameter("ra") %>&keyWords=<%=kws %>&phase=<%=request.getParameter("phase")%>">
	First</a>
	<%=previous %>&nbsp;&nbsp;<%=next %>
	<a href="searchRepository.jsp?begin_page=<%=totalPageAmount - 1 %>&ra=<%=request.getParameter("ra") %>&keyWords=<%=kws %>&phase=<%=request.getParameter("phase") %>">Last</a>
	&nbsp;&nbsp;&nbsp;&nbsp;Jump to page&nbsp;&nbsp;
	<input type="text" size="4" id="jumpPage1" /> /<%=totalPageAmount %> 
	<input type="button" value="Go" onClick="validateJump(<%=totalPageAmount %>, 
		document.getElementById('jumpPage1'))"/>
</p>
<!-- PAGE INDEX -->
<%
	}
 %>
<input type="hidden" id="hidden_keyWords" value="<%=kws %>"/>
<input type="hidden" id="hidden_researchArea" value="<%=request.getParameter("ra") %>"/>
<input type="hidden" id="hidden_phase" value="<%=request.getParameter("phase") %>"/>



<!-- LISTING FILES -->
<ol>
	<%
	for(int i = 0; i < list.length; i++) {
		UploadedFile file = list[i];
		boolean marked = markedSet.contains(file.getId());
		%>
		<%=beginPage * Constants.FILES_AMOUNT_PER_PAGE + (i + 1) %>
		<font size="2"><strong>Descriptive Name</strong>:<%=file.getDescription() %>&nbsp;&nbsp;
			<a href="upload/<%=file.getCurrentName()%>" 
				onclick="download(<%=file.getId()%>)" target="_blank">Download</a>&nbsp;
			<a href="javascript:viewData('<%=file.getOriginName() %>', '<%=file.getCurrentName() %>')">
			View Data Sheet</a>&nbsp;&nbsp;
			<input type="button" value="<%=marked ? "Unmark" : "Mark" %>" 
				onclick="mark(this)" id="<%=file.getId() %>"/>
			&nbsp;&nbsp;
			<a href="">Export Data Sheet</a>
		</font>
		
		<table  width="500" class="dataItem">
			<tr>
				<td width="30%" >Original Filename</td>
				<td width="70%" align="left"><%=file.getOriginName() %></td>
			</tr>
			<tr>
				<td width="30%" >File Size</td>
				<td width="70%" align="left"><%=file.getSize() %></td>
			</tr>
			<tr>
				<td width="30%">File Format</td>
				<td width="70%" align="left"><%=file.getFormat() %></td>
			</tr>
			<tr>
				<td width="30%">Creation Time</td>
				<td width="70%" align="left"><%=file.getCreationTime() %></td>
			</tr>
			<tr>
				<td width="30%">Update Time</td>
				<td width="70%" align="left"><%=file.getUpdateTime() %></td>
			</tr>
			<tr>
				<td width="30%">Download Count</td>
				<td width="70%" align="left" id="download_count_<%=file.getId() %>">
				<%=file.getDownloadCount() %></td>
			</tr>
		</table>
		<%
	}
	 %>
</ol>
<!-- LISTING FILES -->

<%
	if(list.length > 0) {
	%>
	<!-- PAGE INDEX -->
<p>
	<strong>Page</strong>&nbsp;<%=(beginPage + 1) %>&nbsp;&nbsp;
<a href="searchRepository.jsp?begin_page=0&ra=<%=request.getParameter("ra") %>&keyWords=<%=kws %>&phase=<%=request.getParameter("phase") %>">
	First</a>
	<%=previous %>&nbsp;&nbsp;<%=next %>
	<a href="searchRepository.jsp?begin_page=<%=totalPageAmount - 1 %>
	&ra=<%=request.getParameter("ra") %>&keyWords=<%=kws %>&phase=<%=request.getParameter("phase") %>">Last</a>
	&nbsp;&nbsp;&nbsp;&nbsp;Jump to page&nbsp;&nbsp;
	<input type="text" size="4" id="jumpPage2" /> /<%=totalPageAmount %> 
	<input type="button" value="Go" onClick="validateJump(<%=totalPageAmount %>, 
		document.getElementById('jumpPage2'))"/>
</p>
<!-- PAGE INDEX -->
	<%
	}
%>

</div>
</div>
<div class="div2"><iframe src="left_slide.html" scrolling="no" frameborder="0" height="650" width="195"></iframe></div>
<div><iframe src="bottom.html" width="100%" height="80" frameborder="0" scrolling="no"></iframe></div>
</body>
</html>