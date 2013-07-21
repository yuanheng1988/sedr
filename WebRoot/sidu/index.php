<?php if ($_SERVER['QUERY_STRING']=='') echo "<script language='JavaScript' type='text/javascript'>top.location='conn.php'</script>"; ?>
<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Frameset//EN' 'http://www.w3.org/TR/html4/frameset.dtd'>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
<script language='JavaScript' type='text/javascript'>
if (top.location!=location) top.location.href=document.location.href;
</script>
<title>SIDU 3 Database Web GUI: MySQL + Postgres + SQLite</title>
<meta name='description' content='MySQL SIDU, Postgres SIDU: Free SQL client front-end GUI - Select Insert Delete Update'/>
<meta name='keywords' content='MySQL,SIDU,SQL client,font-end GUI,Postgres'/>
<meta name='author' content='Topnew Geo'/>
<link rel='shortcut icon' href='img/tool-run.png'/>
</head>
<frameset cols='200,*'>
	<frame src='menu.php?<?php echo $_SERVER['QUERY_STRING']; ?>' name='menu'>
	<frameset id='sqlsmain' rows='1,1'>
		<frame src='sqls.php?<?php echo $_SERVER['QUERY_STRING']; ?>' name='sqls'><frame src='db.php?<?php echo $_SERVER['QUERY_STRING']; ?>' name='main'>
	</frameset>
</frameset>
</html>
