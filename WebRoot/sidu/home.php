<?php
@include "inc.page.php";
@main_close();
$SIDU['page']['nav'] = 'defa';
@uppe();
@main();
@down();

function main_close()
{
	$id = @ceil($_GET['close']);
	if (!$id) return;
	@close_sidu_conn($id);
}
function main()
{
	global $SIDU;
	$conn = $SIDU['conn'][$SIDU[0]];
	$eng=$conn[1];
	echo "<div class='web'>
	<div class='box right hand' style='margin-left:10px' onclick=\"top.location='conn.php?cmd=quit'\">",@html_img("img/tool-exit","","class='vm'")," ",@lang(2101),"</div>
	<div class='box right hand' style='margin-left:10px'>",@html_img("img/tool-add","","class='vm'")," <a href='conn.php' target='_blank' style='text-decoration:none'>",@lang(2102),"</a></div>
	<div class='box left'>",@html_img("img/tool-pc","","class='vm'")," <b>",@lang(2103),":</b></div>
	<p class='clear' style='margin-left:40px'>";
	foreach ($SIDU['conn'] as $conn){
		echo "<br/><a href=",($SIDU[0]==$conn[0] ? "'#' onclick=\"top.location='conn.php?cmd=close&#38;id=$conn[0]'\"" : "'home.php?id=$SIDU[0]&#38;close=$conn[0]'"),">",
		@html_img("img/tool-x",@lang(2104),"class='vm'"),"</a> ",
		@html_img("img/eng-$conn[1]","","class='vm'"),
		" <a href='./?id=$conn[0]'",($SIDU[0]==$conn[0] ? " class='red b'" : "")," target='_blank' title='",@lang(2105),"'>",($conn[1]=="sl" ? "SQLite" : "$conn[3] @ $conn[2]"),"</a>";
		if ($conn[1]=='pg' && !$conn[5]) $conn[5] = "<i class='grey'>(5432)</i>";
		elseif ($conn[1]=='my' && !$conn[5]) $conn[5] = "<i class='grey'>(3306)</i>";
		elseif ($conn[1]<>"sl") $conn[5] = "($conn[5])";
		echo " $conn[5]";
		if ($conn[6]) echo " {DB=<i class='green'>$conn[6]</i>}";
		if ($conn[8]) echo " {",@lang(2106),"=<i class='blue'>$conn[8]</i>}";
	}
	$ip = @SIDU_IP();
	echo "<br/><br/>",@html_img("img/tool-security")," ",($ip ? "<b class='green'>".@lang(2107).": $ip</b>" : "<b class='red'>".@lang(2108)."</b>"),"
	<br/>",@html_img("img/tool-info")," ",@lang(2109,@array($_SERVER['REMOTE_ADDR'],"inc.page.php")),"</p>
	<p class='box hand' onclick=\"showHide('sidumenu')\" title='",@lang(2110),"'>",@html_img("img/sidu","","class='vm'")," <b>",@lang(2111),"</b></p>
	<p id='sidumenu' style='display:none' class='ml30'>Additional menus not listed on tool bars";
	if ($eng=='my'){
		$mysql = @array("SHOW STATUS","SHOW GRANTS","SHOW PROCESSLIST","FLUSH ALL","FLUSH LOGS","FLUSH HOSTS","FLUSH PRIVILEGES","FLUSH TABLES","FLUSH STATUS","FLUSH DES_KEY_FILE","FLUSH QUERY CACHE","FLUSH USER_RESOURCES","FLUSH TABLES WITH READ LOCK");
		foreach ($mysql as $v) echo "<br/><a href='sql.php?id=$SIDU[0]&sql=$v'>$v</a>";
	}else echo "<br/>Table relationship map--in next release";
	echo "</p>
	<p class='box hand' onclick=\"showHide('HK')\" title='",@lang(2110),"'>",@html_img("img/tr.gif","","class='vm'")," <b>",@lang(2112)," (Fn):</b> ",@html_img("img/tool-web","Firfox","class='vm'")," Alt+Shift+",@lang(2113)," ",@html_img("img/tool-web-ie","IE","class='vm'")," Alt+",@lang(2114)," ",@html_img("img/tool-web-o","Opera","class='vm'")," Shift+Esc
		» http://en.wikipedia.org/wiki/Access_key</p>
	<pre id='HK' class='ml30' style='display:none'>",@lang(2115),"\n\n</pre>
	<div class='box' style='margin-bottom:15px'>
	<span class='hand' onclick=\"showHide('thankyou')\" title='",@lang(2110),"'>",@html_img("img/tr.gif","","class='vm'")," <b>",@lang(2116),"</b></span>
	<p class='ml30' id='thankyou' style='display:none;margin-bottom:0'>www.cross-browser.com/x/examples/drag3.php for grid drag resize
	</p>
	</div>
	<p class='box'><b>SQL SIDU : May You be Happy and at Ease</b><br/>土星善度：国土遍七宝，欢喜日日生；善护身口意，平等度一心。</p>
	",@lang(2117)," <i class='green'>http://sidu.sf.net</i><br/>",@lang(2118),": <i class='green'>topnew@hotmail.com</i> ? subject=<i class='green'>sidu</i>
	</div>";
}
?>
