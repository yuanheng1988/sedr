<%@ page import="java.util.*, iscas.nfs.itechs.ese.db.*,java.sql.*,com.jspsmart.upload.*" 
	buffer="64kb"%>
<%@ page import="iscas.nfs.itechs.ese.beans.UploadedFile, iscas.nfs.itechs.ese.beans.User,iscas.nfs.itechs.ese.utils.*" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Browse Repository</title>
<script type="text/javascript" src="js/browse.js"></script>
<link href="css/default.css" rel="stylesheet" type="text/css" />
<link href="css/tabs.css" rel="stylesheet" type="text/css" />
</head>
<body>
<iframe src="top.jsp" scrolling="no" frameborder="0" width="100%" height="230"></iframe>
<div class="div1">
  <div id="tabsA">
    <ul>
      <li><a href="browseRepository.jsp?researchArea=All&keepFresh=true"><span>All</span></a></li>
      <li><a href="browseRepository.jsp?researchArea=Defect Prediction&keepFresh=true"><span>Defect Prediction</span></a></li>
      <li><a href="browseRepository.jsp?researchArea=Effort Prediction&keepFresh=true"><span>Effort Prediction</span></a></li>
      <li><a href="browseRepository.jsp?researchArea=Process Study&keepFresh=true"><span>Process Data</span></a></li>
      <li><a href="browseRepository.jsp?researchArea=Requirement Study&keepFresh=true"><span>Requirement Data</span></a></li>
      <li><a href="browseRepository.jsp?researchArea=General&keepFresh=true"><span>General</span></a></li>
    </ul>
  </div>
  <a href="javascript:advance()" class="advance">Advance Operations</a>
  <div id="advanceDIV" style="display:none"> <br />
    <strong>Keep the following list always fresh while browsing?&nbsp;&nbsp;</strong>
    <input type="radio" name="keep_fresh" value="true"/>
    Yes
    <input type="radio" name="keep_fresh" value="false" checked="checked"/>
    N0 <br/>
    <br/>
    <strong>Sort by:&nbsp;&nbsp;&nbsp;</strong>
    <select name="sort_flag" size="1">
      <option value="download_count" selected="selected">Download Count(Default)</option>
      <option value="creation_time">Creation Time</option>
      <option value="description">Descriptive Name</option>
    </select>
    <select name="sort_order" size="1">
      <option value="DESC" selected="selected">Descending(Default)</option>
      <option value="ASC">Ascending</option>
    </select>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" 
		onclick="refresh('<%=0 %>')" value="Browse" class="input_ad"/>
    <input type="hidden" id="researchArea" value="<%=request.getParameter("researchArea") %>" />
    <br />
    <hr />
  </div>
  <%
	HashSet<Integer> markedSet = (HashSet<Integer>)session.getAttribute("markedSet");
	markedSet = (markedSet == null) ? new HashSet<Integer>() : markedSet;
	session.setAttribute("markedSet", markedSet);

	String beginPage = request.getParameter("begin_page");
	int begin = (beginPage == null ? 0 : Integer.valueOf(beginPage));
%>
  <div class="div4">
    <!-- GETTING FILES -->
    <%
	String sortOrder = request.getParameter("sort_order");
	sortOrder = sortOrder == null ? "DESC" : sortOrder;
	String sortFlag = request.getParameter("sort_flag");
	sortFlag = sortFlag == null ? "download_count" : sortFlag;
	String fresh = request.getParameter("keepFresh");
	boolean keepFresh = false;
	if(fresh != null && fresh.equals("true")) keepFresh = true;
	String researchArea = request.getParameter("researchArea");
	
	int totalRecords = Utilities.browseFiles(sortFlag, sortOrder, keepFresh, researchArea).size();
	UploadedFile[] list = null;
	list = Utilities.getFilesSubList(begin, list);
%>
    <input type="hidden" id="researchArea" value="<%=(researchArea == null || 
	researchArea.equals("null") || researchArea.equals("")) ? "All" : researchArea %>"/>
    <!-- GETTING FILES -->
    <!-- PAGE INDEX -->
    <div class="data_list">
      <p> <strong>Page</strong>&nbsp;<%=(begin + 1) %>&nbsp;&nbsp;
        <%
	int totalPageAmount = totalRecords / Constants.FILES_AMOUNT_PER_PAGE;
	if(totalPageAmount * Constants.FILES_AMOUNT_PER_PAGE != totalRecords)
		totalPageAmount++;
	
	boolean last = (begin + 1) * Constants.FILES_AMOUNT_PER_PAGE >= totalRecords ? true : false;
	String previous = (begin == 0) ? "" : 
		"<a href='browseRepository.jsp?begin_page=" + (begin - 1) + 
		"&researchArea=" + researchArea + "'>Previous</a>";
	String next = last ? "" : "<a href='browseRepository.jsp?begin_page=" + (begin + 1) + 
	"&researchArea=" + researchArea + "'>Next</a>";
%>
        <a href="browseRepository.jsp?begin_page=0&researchArea=<%=researchArea %>"> First</a> <%=previous %>&nbsp;&nbsp;<%=next %> <a href="browseRepository.jsp?begin_page=<%=totalPageAmount-1 %>&researchArea=<%=researchArea %>">Last</a> &nbsp;&nbsp;&nbsp;&nbsp;Jump to page&nbsp;&nbsp;
        <input type="text" size="4" id="jumpPage1" />
        /<%=totalPageAmount %>
        <input type="button" value="Go" onClick="validateJump(<%=totalPageAmount %>, 
		document.getElementById('jumpPage1'))"/>
      </p>
      <!-- PAGE INDEX -->
    </div>
    <p></p>
    <center>
      <% String str = (researchArea == null || researchArea.equals("null") 
	|| researchArea.equals("") || researchArea.equals("All")) ? "" : 
"Data catagorized by research area with " + "<strong>" + researchArea + "</strong>"; %>
      <%=str %>
    </center>
    <!-- LISTING FILES -->
    <div class="data_list">
      <ol>
        <%
	for(int i = 0; i < list.length; i++) {
		UploadedFile file = list[i];
		boolean marked = markedSet.contains(file.getId());
		%>
        <%=begin * Constants.FILES_AMOUNT_PER_PAGE + (i + 1) %> 
       <strong>.Descriptive Name</strong>:&nbsp;&nbsp;<font color="#003399"><%=file.getDescription() %></font>&nbsp;&nbsp; 
       
       
        <%
        User user = (User)session.getAttribute("user");
	    if(user == null || !AuthorityUtil.isDownloadable(user)) { 
	    %>
	    <div style="float:right; margin-right:190px;">
	    <a href="register.jsp" onClick="javascript:return confirm('you haven\'t login,would you like to register first? ');" title="warnning"><img src="images/download-button.jpg" width="15" height="15" border="0" /></a>&nbsp;
	    <a href="register.jsp" onClick="javascript:return confirm('you haven\'t login,would you like to register first? ');">More</a>&nbsp;
        <a href="register.jsp" onClick="javascript:return confirm('you haven\'t login,would you like to register first? ');"><img src="images/view.png" width="15" height="15" border="0" /></a>&nbsp;
        <a href="register.jsp" onClick="javascript:return confirm('you haven\'t login,would you like to register first? ');"><input type="button" value="Unmark"/></a>&nbsp;
        <a href="register.jsp" onClick="javascript:return confirm('you haven\'t login,would you like to register first? ');">Visualization</a>&nbsp;
        <a href="register.jsp" onClick="javascript:return confirm('you haven\'t login,would you like to register first? ');">Correlation</a>&nbsp;
        &nbsp;&nbsp; </div>
	    
	    <%
	    } else{ 
	    session.setAttribute("user", user);
	    %>
        <div style="float:right; margin-right:190px;"><a href="javascript:download(<%=file.getId()%>, '<%=file.getCurrentName()%>','<%=user.getId() %>')" title="downloads"><img src="images/download-button.jpg" width="15" height="15" border="0" /></a>&nbsp; 
        <a href="javascript:more(<%=file.getId() %>, '<%=file.getOriginName() %>')" title="For more information">More</a>&nbsp;
        <a href="javascript:viewData('<%=file.getOriginName() %>', '<%=file.getCurrentName() %>')" title="View Data Sheet"><img src="images/view.png" width="15" height="15" border="0" /></a>&nbsp;&nbsp;
        <input type="button" value="<%=marked ? "Unmark" : "Mark" %>" onclick="mark(this)" id="<%=file.getId() %>"/>
        <a href="javascript:visualData('<%=file.getOriginName() %>', '<%=file.getCurrentName() %>')" title="View Visual Data">Visualization</a>&nbsp;&nbsp;
        <a href="javascript:viewCorrelation('<%=file.getOriginName() %>', '<%=file.getCurrentName() %>')" title="View Correlation Table">Correlation</a>&nbsp;&nbsp;
        &nbsp;&nbsp; </div>
        <%
        }
         %>
        
        
        <table width="500" class="dataItem">
          <tr>
            <td width="128">Original Filename</td>
            <td width="370"><%=file.getOriginName() %></td>
          </tr>
          <tr>
            <td>File Size</td>
            <td><%=file.getSize() %>byte</td>
          </tr>
          <tr>
            <td>File Format</td>
            <td><%=file.getFormat() %></td>
          </tr>
          <tr>
            <td>Creation Time</td>
            <td><%=file.getCreationTime() %></td>
          </tr>
          <tr>
            <td>Update Time</td>
            <td><%=file.getUpdateTime() %></td>
          </tr>
          <tr>
            <td>Download Count</td>
            <td id="download_count_<%=file.getId() %>"><%=file.getDownloadCount() %></td>
          </tr>
          <td colspan="2"><img src="images/contentStyle16b.jpg" width="502" height="12" /></td>
          </tr>
        </table>
        <%
	}
	 %>
      </ol>
    </div>
    <!-- LISTING FILES -->
    <!-- PAGE INDEX -->
    <div class="data_list">
      <p> <strong>Page</strong>&nbsp;<%=(begin + 1) %>&nbsp;&nbsp; <a href="browseRepository.jsp?begin_page=0&researchArea=<%=researchArea %>"> First</a> <%=previous %>&nbsp;&nbsp;<%=next %> <a href="browseRepository.jsp?begin_page=<%=totalPageAmount - 1 %>
	&researchArea=<%=researchArea %>">Last</a> &nbsp;&nbsp;&nbsp;&nbsp;Jump to page&nbsp;&nbsp;
        <input type="text" size="4" id="jumpPage2" />
        /<%=totalPageAmount %>
        <input type="button" value="Go" onClick="validateJump(<%=totalPageAmount %>,
		 document.getElementById('jumpPage2'))"/>
      </p>
    </div>
    <!-- PAGE INDEX -->
  </div>
</div>
<div class="div2">
  <iframe src="left_slide.html" scrolling="no" frameborder="0" height="1050" width="195"></iframe>
</div>
<div>
  <iframe src="bottom.html" width="100%" height="40" frameborder="0" scrolling="no"></iframe>
</div>
</body>
</html>