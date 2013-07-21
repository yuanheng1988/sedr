<%@ page language="java" import="java.util.*, iscas.nfs.itechs.ese.db.*,java.sql.*,com.jspsmart.upload.*" buffer="64kb" contentType="text/html; charset=GB2312" %>

<%
SmartUpload mySmartUpload =new SmartUpload();
long file_size_max=4000000;
String fileName2="", ext="", testvar="";
String url="upload/";
mySmartUpload.initialize(pageContext);

try {
  mySmartUpload.setAllowedFilesList("doc,xls,arff,txt,csv");
  mySmartUpload.upload();
} catch (Exception e){
%>
<script language=javascript type="text/javascript">
alert("doc,xls,arff,txt,csv are permitted!");
window.location.href='upload.jsp';
</script>

<%
}
try{
  com.jspsmart.upload.File myFile = mySmartUpload.getFiles().getFile(0);
  if (myFile.isMissing()){%>
<script language=javascript type="text/javascript">
  alert("Please select a file firstly!");
  window.location.href='upload.jsp';
</script>
<%}
else{
  ext= myFile.getFileExt(); //extension of the file;
  int file_size=myFile.getSize(); //size of the file;
  String saveurl="";
  if(file_size<file_size_max){
    Calendar calendar = Calendar.getInstance();
    String filename = String.valueOf(calendar.getTimeInMillis());

    String originalFileName = myFile.getFileName();

    java.text.DateFormat dateFormat = new java.text.SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
    String creationTime = dateFormat.format(calendar.getTime());
    String updateTime = "2010-09-10 22:04:30";


    //String sql = "insert into file_desc(orignalfilename, ) values("+originalFile+","+file_size+","+")";;
    String sql = "insert into file_desc(original_filename, file_size, file_format, current_filename, creation_time, update_time, download_count) values('"
    + originalFileName + "',"+ file_size +",'"  +ext +"','"+ filename + "." + ext +"','"+ creationTime + "','"  + updateTime +"', 0)";

    out.println(sql);

    Connection connection = DBOperation.getConnection();
    Statement statement = connection.createStatement();
    statement.execute(sql);
    connection.close();
    //this.emailDate = dateFormat.parse(calendar.getTime());

    saveurl=application.getRealPath("/")+url;
    saveurl+=filename+"."+ext;
    myFile.saveAs(saveurl,SmartUpload.SAVE_PHYSICAL);
    //mySmartUpload.setContentDisposition(null);
    //mySmartUpload.downloadFile("upload/"+filename+"."+ext);
%>
<script language=javascript type="text/javascript">
document.write('<a href=upload/' + '<%=filename+"."+ext%>' + '>'+ '<%=filename+"."+ext%>' + '</a>');
</script>
<%    //out.println(saveurl);
    //String ret = "parent.HtmlEdit.focus();";
    //ret += "var range = parent.HtmlEdit.document.selection.createRange();" ;
    //ret += "range.pasteHTML(<Strong>" + request.getContextPath() + "/upload/" + filename + "." + ext + "</Strong>)" ;
    //ret += "alert('Upload Sucessfully!');";
    //ret += "window.location='upload.jsp';";
    //out.println("<script language=javascript>" + ret + "</script>");
  }
}
}catch (Exception e){
  out.print(e.toString());
}
%>
