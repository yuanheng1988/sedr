<?php
@include "inc.page.php";
$SIDU['page']['nav'] = 1;
@uppe();
@main();
@down();

function navi()
{
	global $SIDU;
	echo "<a href='option.php?id=$SIDU[0]' ",@html_hkey("O",@lang(3401))," target='main'></a><a href='user.php?id=$SIDU[0]' ",@html_hkey("U",@lang(3402))," target='main'></a>";
	if ($SIDU['eng']=='my') echo "<a href='sql.php?id=$SIDU[0]&#38;sql=SHOW+PROCESSLIST' ",@html_hkey("P",@lang(3403))," target='main'></a>";
	echo "<a href='home.php?id=$SIDU[0]' target='main' ",@html_hkey("B",@lang(3405)),">",@html_img("img/sidu"),"</a>",
	"<a href='conn.php' target='_blank' ",@html_hkey("N",@lang(3406)),">",@html_img('img/tool-pc'),($SIDU['page']['menuTextSQL'] ? @lang(3407) : ""),"</a>
<a href='#' onclick=\"showHide('openfile')\" ",@html_hkey("L",@lang(3408)),">",@html_img('img/tool-imp'),($SIDU['page']['menuTextSQL'] ? @lang(3409) : ""),"</a>
<a href='#' onclick=\"sidu_sql('a',$SIDU[0])\" ",@html_hkey("A",@lang(3410)),">",@html_img("img/tool-run-all"),($SIDU['page']['menuTextSQL'] ? @lang(3411) : ""),"</a>
<a href='#' onclick=\"sidu_sql('r',$SIDU[0])\" ",@html_hkey("R",@lang(3412)),">",@html_img("img/tool-run"),($SIDU['page']['menuTextSQL'] ? @lang(3413) : ""),"</a>
<a href='#' onclick=\"sidu_sql('m',$SIDU[0])\" ",@html_hkey("M",@lang(3414)),">",@html_img("img/tool-run-m"),($SIDU['page']['menuTextSQL'] ? @lang(3415) : ""),"</a>
<a href='his.php?id=$SIDU[0]' target='main' ",@html_hkey("H",@lang(3416)),">",@html_img('img/tool-his'),($SIDU['page']['menuTextSQL'] ? @lang(3417) : ""),"</a>
<a href='#' onclick=\"top.location='./?id=$SIDU[0]'\" ",@html_hkey("W",@lang(3418)),">",@html_img('img/tool-refresh'),($SIDU['page']['menuTextSQL'] ? @lang(3419) : ""),"</a>
<a href='#' onclick=\"openClose('menu')\" ",@html_hkey("/",@lang(3420)),">",@html_img("img/menu-open","","id='menuopen'"),"</a>
<a href='#' onclick=\"openClose('tool')\" ",@html_hkey("\\",@lang(3421)),">",@html_img("img/tool-open","","id='toolopen'"),"</a>";
	if ($SIDU['eng']<>'sl') echo " <a href='sql.php?id=$SIDU[0]&#38;sql=show+vars' target='main' ",@html_hkey("V",@lang(3422)),">",@html_img("img/tool-sys"),($SIDU['page']['menuTextSQL'] ? @lang(3423) : ""),"</a>";
	if ($SIDU['eng']=='my') echo " <a href='sql.php?id=$SIDU[0]&#38;sql=FLUSH+ALL' target='main' title='",@lang(3424),"'>",@html_img("img/tool-flush"),($SIDU['page']['menuTextSQL'] ? @lang(3425) : ""),"</a>";
	echo " <a href='temp.php?id=$SIDU[0]' onclick='xwin(this.href);return false' ",@html_hkey("T",@lang(3426)),">",@html_img("img/tool-temp"),($SIDU['page']['menuTextSQL'] ? @lang(3427) : ""),"</a>
<a href='option.php?id=$SIDU[0]' target='main' title='",@lang(3428),"'>",@html_img("img/tool-option"),($SIDU['page']['menuTextSQL'] ? @lang(3429) : ""),"</a>";
	if ($SIDU['eng']<>'sl') echo " <a href='user.php?id=$SIDU[0]' target='main' title='",@lang(3430),"'>",@html_img("img/tool-user.gif"),($SIDU['page']['menuTextSQL'] ? @lang(3431) : ""),"</a>";
	echo "<div style='position:fixed;top:0;right:0;padding:3px;background:#eed'><a href='#' onclick=\"top.location='conn.php?cmd=quit'\" ",@html_hkey("Q",@lang(3404)),">",@html_img("img/tool-exit"),"</a></div>";
}
function main()
{
	global $SIDU;
	if (@substr($_FILES['fsql']['type'],0,4)=='text' && $_FILES['fsql']['size'] && !$_FILES['fsql']['error'])
		$file = @html8(@file_get_contents($_FILES['fsql']['tmp_name']));
	elseif ($SIDU['eng']=='sl') $file="SELECT * FROM sqlite_master\nLIMIT 10;";
	else $file="SELECT now();";
	echo "<textarea id='sqltxt' style='width:100%;border:0;padding:0;margin:0' spellcheck='false' cols='30' rows='2'>$file</textarea>
	<form name='sqlrun' action='sql.php?id=$_GET[id]' target='main' method='post'>"
	.@html_form("hidden","sqlcur").@html_form("hidden","sqlmore")."</form>
<script type='text/javascript'>
window.onresize=gridInit;window.onload=gridInit;
function gridInit(){
	if (self.innerHeight) h=self.innerHeight;
	else if (document.documentElement && document.documentElement.clientHeight) h=document.documentElement.clientHeight;//ie6
	else if (document.body) h=document.body.clientHeight;//other ie
	document.getElementById('sqltxt').style.height=h-35+'px';
}
function getSelectedText(box){
	if (box.setSelectionRange) return box.value.substring(box.selectionStart,box.selectionEnd);// Mozilla and compatible
	else if (document.selection) return document.selection.createRange().text;// IE and compatible
	else return;// Other broswers can't do it
}
function sidu_sql(mode,id){
	var sql;
	if (mode=='r' || mode=='m') sql=getSelectedText(document.getElementById('sqltxt'));
	if (!sql || mode=='a') sql=getv('sqltxt');
	if (sql){
		document.sqlrun.sqlcur.value=sql;
		if (mode=='m') document.sqlrun.sqlmore.value=1;
		document.sqlrun.submit();
		document.sqlrun.sqlmore.value=0;
		document.getElementById('sqltxt').focus();
	}
}
</script>
<div id='openfile' class='blobDiv' style='display:none;top:32px'><div class='web box'>",
@html_img("img/tool-close.gif",@lang(3435)." - Fn+L","class='right' onclick=\"showHide('openfile')\""),"
<form action='sqls.php?id=$SIDU[0]' method='post' enctype='multipart/form-data'>
<p><b>",@lang(3432),":</b></p>
<input type='file' name='fsql'/> <input type='button' name='cmd' value='",@lang(3433),"' onclick=\"showHide('openfile')\"/> <input type='submit' name='cmd' value='",@lang(3434),"'/>
</form></div></div>";
}
?>
