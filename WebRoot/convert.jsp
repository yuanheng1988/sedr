<%@ page language="java" import="java.util.*, iscas.nfs.itechs.ese.db.*,java.sql.*,com.jspsmart.upload.*" 
	buffer="64kb" contentType="text/html; charset=UTF-8" %>
<%@ page import="iscas.nfs.itechs.ese.beans.UploadedFile, iscas.nfs.itechs.ese.utils.*" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
<script type="text/javascript" src="js/convert.js"></script>
<link href="css/default.css" rel="stylesheet" type="text/css">
<link href="css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<iframe src="top.jsp" scrolling="no" frameborder="0" width="100%" height="230"></iframe>
<div class="div1"> <br/>
   <p><font size="2">Your cute online <strong>ARFF</strong> conversion tool.</font></p>
   <hr/>
   <p>Supported file extension list:&nbsp;&nbsp;<em><font color="#FF0000">.doc</font></em>&nbsp;<em><font color="#FF0000">.xls</font></em>&nbsp;<em><font color="#FF0000">.csv</font></em></p>
   <br/>
   <br/>

  <form action="convert" method="POST" enctype="multipart/form-data" name="convert_form"
    onsubmit="return validateConvert()" accept-charset="gbk">
    <table  width="564" class="convert_table">
      <tr>
        <td width="40%" height="38" align="right">File:&nbsp;&nbsp;</td>
        <td align="left" width="60%"><input name="file" type="file" class="input_file" size="41"/></td>
      </tr>
      <tr>
        <td width="40%" height="45" align="right">New file's name:&nbsp;&nbsp;</td>
        <td align="left" width="60%"><input name="file_name" type="text"  class="input_file"/>
          .zip</td>
      </tr>
      <tr>
      <td></td>
      <td><input type="submit" value="Convert" name="convert" class="input5"/>&nbsp;&nbsp;<input type="reset" value="Reset" class="input5"/></td>
      </tr>
    </table>
    <input type="hidden" name="file_type" value=""/>
    
  </form>
  <br/>
  <%String flag = request.getParameter("flag"); %>
  <input type="hidden" id="hidden_zip" value="<%=request.getParameter("zip") %>"/>
  <%
    	if(flag != null && flag.equals("false")) 
    		out.write("<strong>The content of the file you want to convert is invalid! </strong>");
    	else if(flag != null){
    		%>
  <script type="text/javascript">
    		var zip = document.getElementById("hidden_zip");
    		window.open("download.jsp?zip=" + zip.value);
    	</script>
  <%
    	} else {
    	}
     %>
</div>
<div class="div2"><iframe src="left_slide.html" scrolling="no" frameborder="0" height="650" width="195"></iframe></div>
<div>
  <iframe src="bottom.html" width="100%" height="80" frameborder="0" scrolling="no"></iframe>
</div>
</body>
</html>