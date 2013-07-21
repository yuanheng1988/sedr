<?php
@include "inc.page.php";
$SIDU['page']['nav'] = 1;
@uppe();
@main();
@down();

function navi()
{
	global $SIDU;
	echo "<a href='#' title='",@lang(1701),"' onclick=\"setv('cmd','del0');dataTab.submit()\">",@html_img('img/menu-open'),"</a>",
	"<a href='#' title='",@lang(1702),"' onclick=\"setv('cmd','del');dataTab.submit()\">",@html_img('img/tool-del'),"</a>",
	"<a href='#' title='",@lang(1703),"' onclick=\"setv('cmd','del1');dataTab.submit()\">",@html_img('img/menu-close'),"</a>",
	"<a href='#' title='",@lang(1704),"' onclick=\"setv('cmd','delall');dataTab.submit()\">",@html_img('img/tool-flush'),"</a>";
	@navi_obj($SIDU['cook'][$SIDU[0]]);
	echo " $SIDU[sep] ".@date('Y-m-d H:i:s');
}
function main()
{
	global $SIDU;
	$typ = @array("B"=>@lang(1705),"S"=>"SQL","E"=>@lang(1706),"D"=>@lang(1707));
	$css = @array("B"=>"grey","S"=>"","E"=>"red","D"=>"green");
	if ($_POST['cmd']) @save_data($_POST['cmd'],$SIDU[0]);
	echo "<form id='dataTab' name='dataTab' action='his.php?id=$SIDU[0]' method='post'>
	<table class='grid' id='dataTable'><tr class='th'><td class='cbox'><input type='checkbox' onclick='checkedAll()'/></td>
	<td align='right'><a href='his.php?id=$SIDU[0]",($_GET['sort'] ? "" : "&#38;sort=a"),"'>ID</a></td><td>",@lang(1708),"</td><td>",@lang(1709),"</td><td align='right'>ms</td><td>",@lang(1710),"</td></tr>";
	foreach ($_SESSION['siduhis'][$SIDU[0]] as $i=>$his){
		$arr = @explode(" ",$his,5);
		$cur = "<tr><td class='cbox'><input type='checkbox' name='his[]' value='$i'/></td><td align='right'>$i</td><td>$arr[1]</td><td>{$typ[$arr[2]]}</td><td align='right'>$arr[3]</td><td class='{$css[$arr[2]]}'>".@nl2br(@html8($arr[4]))."</td></tr>";
		$str = ($_GET['sort'] ? $str.$cur : $cur.$str);
	}
	echo $str,"</table><input type='hidden' name='cmd' id='cmd'/></form>";
}
function save_data($cmd,$id)
{
	$log = &$_SESSION['siduhis'][$id];
	if ($cmd=='delall') unset($_SESSION['siduhis'][$id]);
	elseif ($cmd=='del'){
		foreach ($_POST['his'] as $v) unset($log[$v]);
	}elseif (($cmd=='del0' || $cmd=='del1') && $_POST['his'][0]<>''){
		$x=$_POST['his'][0];
		foreach ($log as $i=>$v){
			if (($cmd=='del0' && $i<$x) || ($cmd=='del1' && $i>$x)) unset($log[$i]);
		}
	}
}
?>
