<?php
@include "inc.page.php";
@uppe();
@main($imp,$cmd);
@down();

function main($imp,$cmd)
{
	global $SIDU;
	if (!$SIDU[1]) $err = @lang(2201);
	elseif ($SIDU['eng']=='pg' && !$SIDU[2]) $err = @lang(2202);
	echo "<form action='imp.php?id=$SIDU[0],$SIDU[1],$SIDU[2]' method='post' enctype='multipart/form-data'>
	<div class='web'><p class='dot'><b>",@lang(2203),": <i class='red'>DB = $SIDU[1]",($SIDU[2] ? ".$SIDU[2]" : ""),"</i></b></p>";
	if ($err){
		echo "<p class='err'>$err</p></div></form>";
		return;
	}
	if ($cmd) $SIDU[4] = $imp['tab'];
	if ($SIDU[4]){
		$res = @tm("SQL","SELECT * FROM ".@goodname($SIDU[4])." LIMIT 1");
		$col = @get_sql_col($res,$SIDU['eng']);
		foreach ($col as $v) $imp['cols'][] = $v[0];
	}
	if (!$imp['col']) $imp['col'] = @implode("\n",$imp['cols']);
	if ($cmd){
		$err = @valid_data($SIDU,$imp);
		if ($err) echo "<p class='err'>$err</p>";
		else return @save_data($SIDU,$imp);
	}
	if ($SIDU['eng']=='my') $sql = "SHOW TABLES from `$SIDU[1]`";
	elseif ($SIDU['eng']=='sl') $sql = "SELECT name FROM sqlite_master WHERE type='table' ORDER BY 1";
	else $sql = "SELECT relname FROM pg_class a,pg_namespace b\nWHERE a.relnamespace=b.oid AND b.nspname='public' AND a.relkind='r' ORDER BY 1";
	$arr = @sql2arr($sql,1);
	$tabs[0] = @lang(2204);
	foreach ($arr as $v) $tabs[$v]=$v;
	echo "<table><tr><td>",@lang(2205),":</td><td>",@html_form("select","imp[tab]",$SIDU[4],"","",
		"onchange=\"location='imp.php?id=$SIDU[0],$SIDU[1],$SIDU[2],r,'+this.options[this.selectedIndex].value\"",$tabs),"</td></tr>";
	if ($SIDU[4]) echo "<tr><td valign='top'>",@lang(2206),":</td><td>",@html_form("textarea","imp[col]",$imp['col'],350,90),"</td></tr>";
	echo "<tr><td valign='top'>",@lang(2207),":</td><td><input type='file' name='f'/> ",@lang(2208,'2MB'),"</td></tr></table>
	<p class='dot'><br/><b>",@lang(2209),":</b></p>
	<p class='dot'>",@lang(2210),": ",@html_form("text","imp[sepC]",($imp['sepC'] ? $imp['sepC'] : ','),50)," eg \\t , ; « | »
	<br/>",@lang(2211)," ",@html_form("text","imp[cut1]",$imp['cut1'],50)," ",@lang(2212)," ",@html_form("text","imp[cut2]",$imp['cut2'],50)," ",@lang(2213),"
	<br/>",@lang(2214),": ",@html_form("text","imp[pk]",$imp['pk'],150)," eg. c1;c2
	<br/>",@html_form("checkbox","imp[del]",$imp['del'],"","","",array(1=>'')),@lang(2215),"
	<br/>",@html_form("checkbox","imp[merge]",$imp['merge'],"","","",array(1=>'')),@lang(2216),"
	<br/>",@html_form("checkbox","imp[stop]",$imp['stop'],"","","",array(1=>'')),@lang(2217),"</p>
	<p>",@html_form("submit","cmd",@lang(2218)),"</p></div></form>";
}
function valid_data($SIDU,&$imp)
{
	$imp['sepC'] = @trim(@stripslashes($imp['sepC']));
	if (!$imp['sepC']) $imp['sepC'] = ',';
	if (!$SIDU[4]) return @lang(2219);
	$imp['cut1'] = @ceil($imp['cut1']);
	if ($imp['cut1']<1) $imp['cut1']='';
	$imp['cut2'] = @ceil($imp['cut2']);
	if ($imp['cut2']<1) $imp['cut2']='';
	$col = @explode("\n",$imp['col']);
	foreach ($col as $v){
		$v = @trim($v);
		if ($v){
			if (!@in_array($v,$imp['cols'])) return @lang(2220,$v);
			$arr[] = $v;
		}
	}
	$imp['col'] = @implode("\n",$arr);
	if (!$imp['col']){
		$arr = $imp['cols'];
		$imp['col'] = @implode("\n",$arr);
	}
	$imp['pk'] = @strip($imp['pk'],1,1,1);
	if ($imp['pk']){
		$arrPK = @explode(";",$imp['pk']);
		foreach ($arrPK as $k=>$v){
			$v = @trim($v);
			if ($v=='') unset($arrPK[$k]);
			elseif (!@in_array($v,$arr)) return @lang(2221,$v);
		}
		$imp['pk'] = @implode(";",$arrPK);
	}
	if (!$_FILES['f']['error'] && $_FILES['f']['tmp_name']){
		if (@substr($_FILES['f']['type'],0,4)<>'text') return @lang(2222);
		if ($_FILES['f']['size']>2000000) return @lang(2223,'2MB');
		$imp['file'] = @file($_FILES['f']['tmp_name']);
	}else return @lang(2224);
}
function clean_str($str)
{
	$str = @trim($str);
	if ($str=='') return $str;
	$s1 = @substr($str,0,1);
	if ($s1=="'" || $s1=='"') $str = @substr($str,1);
	else{
		$s1 = @substr($str,-1);
		if ($s1=="'" || $s1=='"') $str = @substr($str,0,-1);
		else return $str;
	}
	return @clean_str($str);
}
function save_data($SIDU,$imp)
{
	echo "<p align='center'><img src='img/loading.gif'/>
	<br/><br/><span class='green'>",@lang(2225),"</span>
	<br/><br/><span class='red'>",@lang(2226),"</span></p>";
	if ($imp['del']) @save_data_sql_run(0,"DELETE FROM ".@goodname($SIDU[4]),$imp);
	$cols = @explode("\n",$imp['col']);
	if ($imp['pk']) $pk = @explode(";",$imp['pk']);
	foreach ($cols as $k=>$v){
		if (@in_array($v,$pk)) $arrPK[] = $k;
		$cols[$k]=@goodname($v);
	}
	if (!$imp['pk']){
		$sql = "INSERT INTO ";
		if ($SIDU['eng']=='my') $sql .= "`$SIDU[1]`.`$SIDU[4]`";
		elseif ($SIDU['eng']=='pg') $sql .= "\"$SIDU[2]\".\"$SIDU[4]\"";
		else $sql .= $SIDU[4];
		$sql .= "(".@implode(",",$cols).") VALUES ";
	}else{
		$sql = "UPDATE ";
		if ($SIDU['eng']=='my') $sql .= "`$SIDU[1]`.`$SIDU[4]`";
		elseif ($SIDU['eng']=='pg') $sql .= "\"$SIDU[2]\".\"$SIDU[4]\"";
		else $sql .= $SIDU[4];
		$sql .= " SET ";
	}
	$numCM = $numC = count($cols);
	if (!$imp['merge']) $numCM++;
	$numR = count($imp['file']);
	$numL = ($SIDU['eng']=='sl' ? 1 : 200);//each insert max lines
	$numIns = 0;
	for ($i=$imp['cut1']+0;$i<$numR-$imp['cut2'];$i++){
		$txt = @trim($imp['file'][$i]);
		if ($txt) @save_data_sql($i,$SQL,$imp,$txt,$numCM,$numC,$arrPK,$cols,$numIns,$numL,$sql);
	}
	if (!$imp['pk'] && $SQL){
		if (@substr($SQL,-2)==",\n") $SQL = @substr($SQL,0,-2);
		if ($SQL) @save_data_sql_run($i,$SQL,$imp);
	}
	echo "<br/><p class='ok'>",@lang(2227),"</p>";
}
function save_data_sql($i,&$SQL,$imp,$txt,$numCM,$numC,$arrPK,$cols,&$numIns,$numL,$sql)
{
	$arr = @explode($imp['sepC'],$txt,$numCM);
	foreach ($arr as $j=>$v) $arr[$j] = @clean_str($v);
	for ($k=0;$k<$numC;$k++){
		$v = $arr[$k];
		if (@strtoupper($v)=='NULL') $v = 'NULL';
		elseif (!@is_numeric($v)) $v = "'".@addslashes($v)."'";
		if (!$imp['pk']) $arrD[]=$v;
		elseif (@in_array($k,$arrPK)) $arrWhere[]=$cols[$k].($v=='NULL' ? ' IS ' : '=').$v;
		else $arrD[]=$cols[$k]."=$v";
	}
	if (!$imp['pk']){
		$numIns++;
		if (!$SQL) $SQL = $sql;
		$SQL .= "(".@implode(",",$arrD).")";
		if ($numIns==$numL){
			@save_data_sql_run($i,$SQL,$imp);
			$SQL = "";
			$numIns = 0;
		}else $SQL .= ",\n";
	}else @save_data_sql_run($i,$sql.@implode(",",$arrD)." WHERE ".@implode(" AND ",$arrWhere),$imp);
}
function save_data_sql_run($i,$SQL,$imp)
{
	global $SIDU;
	if ($SIDU['eng']=='my') @mysql_query($SQL);
	elseif ($SIDU['eng']=='pg') @pg_query($SQL);
	else @sqlite_query($SIDU['dbL'],$SQL);
	$err = @sidu_err(1);
	if ($err) echo "<p class='err'>",@lang(2228,$i),"<br/>$err</p><pre>$SQL</pre><br/>";
	if ($err && $imp['stop']){
		echo "<br/><p class='err'>",@lang(2229),"</p><p class='ok'>",@lang(2230),"</p>";
		exit;
	}
}
?>
