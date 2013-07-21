<%@ page language="java" import="java.util.*" pageEncoding="gbk"%>
<%@ page import="iscas.nfs.itechs.ese.beans.*, iscas.nfs.itechs.ese.db.DBOperation,
				iscas.nfs.itechs.ese.utils.* ,iscas.nfs.itechs.ese.utils.UpDownfileGetter,
				iscas.nfs.itechs.ese.beans.User"%>
<%
String path = request.getContextPath();
String basePath = request.getScheme()+"://"+request.getServerName()+":"+request.getServerPort()+path+"/";
%>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <base href="<%=basePath%>">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Edit Information</title>
    <script type="text/javascript" src="js/util.js"></script>
	<!--
	<link rel="stylesheet" type="text/css" href="styles.css">
	-->

  </head>
  
  <body>
  <iframe src="top.jsp" scrolling="no" frameborder="0" width="100%" height="230"></iframe>
   
    <%
      User user = (User)session.getAttribute("user"); 
      %>
      <form name="editForm" action="editInfo" method="post" onSubmit="return validateEdit()">
      <input type="button" value="Edit" onclick="edit()"/><input id="savebutton" type="submit" value="Save" onclick="save() " disabled="true"/><br>
      <table border="0">
      <tr><th align="left">Username£º</th><td width="370"><input id="username" name="username" type="text" value="<%=user.getName() %>" disabled="disabled" ></td></tr>
      <tr><th align="left">Affiliation:</th><td width="370"><input id="affiliation" name="affiliation" type="text" value="<%=(user.getAffiliation()!=null && !user.getAffiliation().equals("") && user.getAffiliation()!="") ? user.getAffiliation() : " none information" %>" disabled="true" ></td></tr>
      <tr><th align="left">Title:</th><td width="370"><input id="title" name="title" type="text" value="<%=(user.getTitle()!=null && !user.getTitle().equals("") && user.getTitle()!="") ? user.getTitle() : " none information"%>" disabled="true" ></td></tr>
      <tr><th align="left">Email:</th><td width="370"><input id="email" name="email" type="text" value="<%=(user.getEmail()!=null && !user.getEmail().equals("") && user.getEmail()!="" ) ? user.getEmail() : " none information" %>" disabled="true" ></td></tr>
      <tr><th align="left">Interest:</th><td width="370"><input id="interest" name="interest" type="text" value="<%=(user.getInterest()!=null && !user.getInterest().equals("") && user.getInterest()!="") ? user.getInterest() : " none information" %> " disabled="true" ></td></tr>
      <tr><th align="left">Country:</th><td width="370"><input id="country" name="country" type="text" value="<%=(user.getCountry()!=null && !user.getCountry().equals("") && user.getCountry()!="") ? user.getCountry() : " none information" %>" disabled="true" ></td></tr><br>
      </table>
      <br>
      </form>

      <!-- ÐÞ¸ÄÃÜÂë -->
      <!-- <a href="#" onclick="javascript:editpsw(<%=user.getPwd() %>)">modify password</a>  -->
      
      My upload records:<br>
      <%
      List<UploadedFile> myuploadedfileslist = new ArrayList<UploadedFile>();
      myuploadedfileslist = UpDownfileGetter.getupfile(user);
      UploadedFile[] myuploadedfiles = new UploadedFile[myuploadedfileslist.size()];
      myuploadedfileslist.toArray(myuploadedfiles);
       %>
       
       <%
       for(int i=0;i < myuploadedfiles.length;i++){
       %>
       <form name="fileEdit" onSubmit="return validateFileEdit()" >
       <table border="2" width="800">
       <tr><th>file<%=i+1%>:</th>
       <td align="right">
       <%String divId = "renameDIV" + i;%>
       <div id=<%=divId %> style="Display:none">
       Rename£º<input type="text" name="newname" value="<%=myuploadedfiles[i].getDescription() %>"/>
       <input type="button" name="fileEditButton" value=" OK " onclick="renameFile(<%=i%>,<%=myuploadedfiles[i].getId()%>)"/>
       </div>
       <input type="button" name ="renameButton" value="Edit" onclick="filenameEdit(<%=i%>)">
       <input type="button" value="Delete" onclick="deleteFile(<%=i%>,<%=myuploadedfiles[i].getId() %>);"/><br></td></tr>
       <tr><td width='20%' align='right'>Descriptive Name:</td><td width='80%' align='left'><%=myuploadedfiles[i].getDescription() %></td></tr>
       <tr><td width='20%' align='right'>Format:</td><td width='80%' align='left'><%=myuploadedfiles[i].getFormat() %></td></tr>
       <tr><td width='20%' align='right'>Size:</td><td width='80%' align='left'><%=myuploadedfiles[i].getSize() %></td></tr>
       <tr><td width='20%' align='right'>CreationTime:</td><td width='80%' align='left'><%=myuploadedfiles[i].getCreationTime() %></td></tr>
       <tr><td width='20%' align='right'>UpdateTime:</td><td width='80%' align='left'><%=myuploadedfiles[i].getUpdateTime() %></td></tr>
       <tr><td width='20%' align='right'>OriginName:</td><td width='80%' align='left'><%=myuploadedfiles[i].getOriginName() %></td></tr>
       <tr><td width='20%' align='right'>Download Count:</td><td width='80%' align='left'><%=myuploadedfiles[i].getDownloadCount() %></td></tr>
       </table>
       </form>
       <!--<a href="javascript:reupload"   -->
        <%
       }
        %>
        <br>
        <h2>Reload Here!</h2>
        <form action="reload" name="reloadForm" method="post" onSubmit="return validateReload()" enctype="multipart/form-data">
        Original File£º
        <select name="reloadFileSelect" size="1">
        <option value="" selected="selected">-- Select One --</option>
        <%
       for(int i=0;i < myuploadedfiles.length;i++){
       %>
       <option value="<%=myuploadedfiles[i].getId() %>"><%=myuploadedfiles[i].getDescription() %>
        <%
        }
         %>
        </select>
       
       Reloaded File£º<input type="file" name="file_pathname1" size="50" />
       <input type="submit" name="reloadButton" value="Reload"/> 
        
        </form>

       My download records:
      <%
      List<UploadedFile> mydownloadedfileslist = new ArrayList<UploadedFile>();
      mydownloadedfileslist = UpDownfileGetter.getdownfile(user);
      UploadedFile[] mydownloadedfiles = new UploadedFile[mydownloadedfileslist.size()];
      mydownloadedfileslist.toArray(mydownloadedfiles);;
      %>
      <table border="2" width="800">
       <%
       for(int i=0;i < mydownloadedfiles.length;i++){
       %>
       <tr><th>file<%=i+1%>:</th></tr><%=mydownloadedfiles[i].toString()%>
        <%
       }
        %>
       </table><br>
       
       My Customize Information:
       <%
       List<CustomBean> cb = new ArrayList<CustomBean>();
       cb = Utilities.getCustomItems(user.getId(),cb);       
        %>
        <%
        for(int i=0; i<cb.size() && cb.get(i)!= null;i++){
         %>
        <form name="customEdit" onSubmit="return validateCustomEdit()" >
        <table border="2" width="800" >
        <tr><th>custom Item<%=i+1%>:</th>
       <td align="right">
       <input type="button" value="Delete" onclick="deleteCustomItem(<%=i%>,<%=cb.get(i).getId() %>);"/><br></td></tr>
       <%= cb.get(i).toString()%>
        </table>
        </form>
       <%
       }
        %>
       
       
       
  </body>
</html>
