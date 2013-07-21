<%@ page language="java" import="java.util.*, iscas.nfs.itechs.ese.db.*,java.sql.*,com.jspsmart.upload.*" buffer="64kb" contentType="text/html; charset=GB2312" %>



<html>
<head>
<title>Upload</title>
</head>
<body>
<table border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td height="45" align="center" valign="middle"><form action="uploadFile.jsp" method="post" enctype="multipart/form-data" name="form1">
<input type="file" name="file">
<input type="submit" name="Submit" value="Upload">
</form></td>
</tr>
</table>
</body>
</html>
