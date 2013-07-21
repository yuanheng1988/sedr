<?php
@include "inc.page.php";
@main($exp,$cmd);

function main($exp,$cmd)
{
	global $SIDU;
	if ($_GET['sql']){
		$cook = $SIDU['cook'][$SIDU[0]];
		@tm_use_db($cook[1],$cook[2]);
		$mode = "SQL";
		$_GET['sql'] = @stripslashes($_GET['sql']);
	}else $mode = "DB = $SIDU[1]".($SIDU[2] ? ".$SIDU[2]" : "");
	@valid_data($SIDU,$exp,$cmd);
	if ($cmd) @main_cout($SIDU,$exp,$mode);
	else @main_form($SIDU,$exp,$mode);
}
function main_cout_str($str,$fp)
{
	if ($fp) @fwrite($fp,$str);
	else echo $str;
}
function main_cout($SIDU,$exp,$mode)
{
	if ($mode=="SQL") $file = "sidu-sql";
	else{
		$file = @str_replace("/","_",$SIDU[1]).($SIDU[2] ? "_$SIDU[2]" : "");
		if (!$exp['sql'][1]) $file .= "_".$exp['tabs'][0];
	}
	$file .= "_".@date("YmdHis").".".$exp['ext'];
	if ($exp['zip']) $fp = @fopen("/tmp/$file",'w');
	if (!$exp['zip'] || $exp['ext']=="html") @main_cout_str("<html>\n<head>\n<title>SIDU Export: $file</title>\n<style>*{font-family:monospace}",$fp);
	if ($exp['ext']=="html") @main_cout_str("\n.n{color:#888;font-style:italic}\n.th td{background:#ddd}\ntd{vertical-align:top;border:solid 1px #ccc}",$fp);
	if (!$exp['zip'] || $exp['ext']=="html") @main_cout_str("\n</style>\n</head>\n<body><pre>\n",$fp);
	@main_cout_str("/*SIDU Export Start-------------------".@date("Y-m-d H:i:s")."*/\n",$fp);
	if ($mode<>"SQL"){
		if ($exp['db']){
			if ($SIDU['eng']=='my') @main_cout_str("\nUSE `$SIDU[1]`;\n",$fp);
			elseif ($SIDU['eng']=='pg') @main_cout_str("\nSET search_path to \"$SIDU[2]\";\n",$fp);
		}
		if ($exp['drop']){
			foreach ($exp['tabs'] as $v){
				if ($SIDU['eng']<>'sl' || $v<>'sqlite_master') @main_cout_str("\nDROP ".($SIDU[3]=='r' ? "TABLE " : "VIEW ").@goodname($v).";",$fp);
			}
			@main_cout_str("\n",$fp);
		}
		if ($exp['desc']){
			$typ = ($SIDU[3]=='r' ? "TABLE" : "VIEW");
			foreach ($exp['tabs'] as $v) @main_cout_desc($SIDU,$typ,$v,$fp);
			@main_cout_str("\n",$fp);
		}
	}
	if (!$exp['data']){
		@main_cout_str("\n/*SIDU Export End-------------------*/</pre>\n</body></html>",$fp);
		return;
	}
	if ($exp['ext']=="html") @main_cout_str("</pre>",$fp);
	foreach ($exp['sql'] as $i=>$v){
		if ($exp['ext']<>'sql') @main_cout_str("\n\n".($exp['ext']=='html' ? "<br/>" : "/* ").@nl2br(($exp['zip'] && $exp['ext']<>'html' ? $v : @html8($v))).($exp['ext']=='html' ? "" : " */")."\n",$fp);
		$res = @tm("SQL",$v);
		$err = @sidu_err(1);
		if ($err) @main_cout_str("\n".($exp['ext']=='html' ? "" : "/* ")."<font color='red'>$err</font>".($exp['ext']=='html' ? "" : " */")."\n",$fp);
		else @main_cout_data($SIDU,$exp,$res,$exp['tabs'][$i],$fp);
	}
	@main_cout_str("\n".($exp['ext']=="html" ? "<p>" : "")."/*SIDU Export End-------------------*/".($exp['ext']=="html" ? "</p>" : ""),$fp);
	if ($exp['ext']<>"html" && !$exp['zip']) @main_cout_str("\n</pre>",$fp);
	if ($exp['ext']=="html" || !$exp['zip']) @main_cout_str("\n\n</body></html>",$fp);
	if (!$fp) return;
	@fclose($fp);
	$zip = new ZipArchive();
	$zipFile = $file.".zip";
	if($zip->open("/tmp/$zipFile",ZIPARCHIVE::CREATE)!==true) return;
	$zip->addFile("/tmp/$file",$file);
	$zip->close();
	@header("Expires: 0");
	@header("Content-Description: File Transfer");
	@header("Content-Type: application/zip");
	@header("Content-Disposition: attachment; filename=\"$zipFile\"");
	$fp = @fopen("/tmp/$zipFile","rb");
	if ($fp){
		while(!feof($fp)){
			print(fread($fp,1024*8));
			flush();
			if (connection_status()!=0){
				@fclose($fp);
				die();
			}
		}
		@fclose($fp);
	}
}
function main_cout_desc($SIDU,$typ,$tab,$fp)
{
	if ($typ=='VIEW'){
		if ($SIDU['eng']=='sl'){
			$row = @sqlite_fetch_array(@tm("SQL","SELECT sql FROM sqlite_master WHERE type='view' AND name='$tab'"));
			@main_cout_str("\n$row[0];",$fp);
		}elseif ($SIDU['eng']=='my'){
			$row = @mysql_fetch_row(@tm("SQL","SELECT VIEW_DEFINITION FROM information_schema.VIEWS\nWHERE TABLE_SCHEMA='$SIDU[1]' AND TABLE_NAME='$tab'"));
			$row[0] = @trim(@str_replace("/* ALGORITHM=UNDEFINED */","",$row[0]));
			@main_cout_str("\nCREATE VIEW `$tab` AS $row[0];",$fp);
		}else{
			$oid = @pg_fetch_row(@tm("SQL","SELECT a.oid FROM pg_class a,pg_namespace b\nWHERE a.relkind='v' AND a.relnamespace=b.oid\nAND a.relname='$tab' AND b.nspname='$SIDU[2]'"));
			$row = @pg_fetch_row(@tm("SQL","SELECT pg_get_viewdef($oid[0])"));
			@main_cout_str("\nCREATE VIEW \"$tab\" AS $row[0]",$fp);
		}
		return;
	}
	if ($SIDU['eng']=='pg'){
		$info = @pg_fetch_row(@tm("SQL","SELECT a.oid,a.relnamespace,a.relhasoids,obj_description(a.oid,'pg_class')\nFROM pg_class a,pg_namespace b WHERE a.relkind='r' AND a.relnamespace=b.oid\nAND a.relname='$tab' AND b.nspname='$SIDU[2]'"));
		$defa = @sql2arr("SELECT adnum,adsrc FROM pg_attrdef WHERE adrelid=$info[0]",2);
		$typs=@sql2arr("SELECT oid,typname FROM pg_type",2);
		$comm = @sql2arr("SELECT objsubid,description FROM pg_description\nWHERE objoid=$info[0] AND objsubid>0",2);
		$tran = @array("'"=>"''","\\"=>"\\\\");
		@main_cout_str("\nCREATE TABLE \"$tab\"(",$fp);
		$res = @tm("SQL","SELECT attname,atttypid,attnotnull,atthasdef,\nCASE attlen WHEN -1 THEN atttypmod ELSE attlen END,\nattnum,format_type(atttypid,atttypmod) FROM pg_attribute\nWHERE attrelid=$info[0] AND attnum>0 AND attisdropped=FALSE ORDER BY attnum");
		$i=0;
		while ($row = @pg_fetch_row($res)){
			$row[3] = ($row[3]=='t' ? $defa[$row[5]] : '');
			$row[1] = $typs[$row[1]];
			if ($row[1]=="numeric") $row[1]=$row[6];
			elseif ($row[1]=='int2') $row[1]="smallint";
			elseif ($row[1]=='int4') $row[1]="int";
			elseif ($row[1]=='int8') $row[1]="bigint";
			elseif ($row[1]=='bpchar') $row[1]="char";
			if ($row[4]>4 && ($row[1]=='varchar' || $row[1]=='char')) $row[1] .= "(".($row[4]-4).")";
			if (@substr($row[3],0,9)=="nextval('") $row[1]=($row[1]=="int" ? "serial" : "bigserial");
			if ($i++) @main_cout_str(",",$fp);
			@main_cout_str("\n\t\"$row[0]\" $row[1]".($row[2]=='t' ? " NOT NULL" : "").($row[3]<>'' && @substr($row[3],0,9)<>"nextval('" ? " DEFAULT $row[3]" : ""),$fp);
			if ($comm[$i]) $commStr .= "\nCOMMENT ON COLUMN \"$tab\".\"$row[0]\" IS '".@strtr($comm[$i],$tran)."';";
		}
		$fkmatch = @array("f"=>"FULL","p"=>"PARTIAL","u"=>"SIMPLE");
		$fkact = @array("a"=>"NO ACTION","r"=>"RESTRICT","c"=>"CASCADE","n"=>"SET NULL","d"=>"SET DEFAULT");
		$res = @tm("SQL","SELECT *,pg_get_constraintdef(oid,TRUE) AS kstr FROM pg_constraint\nWHERE conrelid=$info[0] AND connamespace=$info[1]");
		while ($row=@pg_fetch_assoc($res)){
			@main_cout_str(",\nCONSTRAINT \"$row[conname]\" $row[kstr]",$fp);
			if ($row['contype']=='f') @main_cout_str(" MATCH {$fkmatch[$row[confmatchtype]]}\n\tON UPDATE {$fkact[$row[confupdtype]]} ON DELETE {$fkact[$row[confdeltype]]}",$fp);
		}
		@main_cout_str("\n) WITH (OIDS=".($info[2]=='t' ? "TRUE" : "FALSE").");",$fp);
		if ($info[3]) @main_cout_str("\nCOMMENT ON TABLE \"$tab\" IS '".@strtr($info[3],$tran)."';",$fp);
		@main_cout_str($commStr,$fp);
		$res = @tm("SQL","SELECT pg_get_indexdef(indexrelid) FROM pg_index\nWHERE indrelid=$info[0] AND indisprimary='f'");
		while ($row=@pg_fetch_row($res)) @main_cout_str("\n$row[0];",$fp);
		return;
	}
	if ($SIDU['eng']=='my') $desc = @mysql_fetch_row(@tm("SQL","SHOW CREATE TABLE `$SIDU[1]`.`$tab`"));
	elseif ($SIDU['eng']=='sl') $desc = @sqlite_fetch_array(@tm("SQL","SELECT name,sql FROM sqlite_master WHERE name=tbl_name AND name='$tab' LIMIT 1"),SQLITE_NUM);
	@main_cout_str("\n$desc[1];",$fp);
	if ($SIDU['eng']=='sl'){
		$res = @tm("SQL","SELECT sql FROM sqlite_master WHERE type='index' AND tbl_name='$tab' AND sql IS NOT NULL");
		while ($row = @sqlite_fetch_array($res,SQLITE_NUM)) @main_cout_str("\n$row[0];",$fp);
	}
}
function main_cout_data($SIDU,$exp,$res,$tab,$fp)
{
	$col = @get_sql_col($res,$SIDU['eng']);
	$arr = @get_sql_data($res,$SIDU['eng']);
	if ($exp['ext']=="html"){
		@init_pg_col_align($arr,$col);
		@main_cout_str("<table style='border:solid 1px #888'>\n<tr class='th'>",$fp);
		foreach ($col as $v) @main_cout_str("<td".($v[8]=="i" ? " align='right'" : "").">$v[0]</td>",$fp);
		@main_cout_str("</tr>",$fp);
	}else{
		$tran = @array(chr(13)=>'\r',chr(10)=>'\n');
		$num = count($arr[0])-1;
		if ($exp['ext']=="sql"){
			foreach ($col as $k=>$v) $COL[] = @goodname($v[0]);
			$head = "\nINSERT INTO ".@goodname($tab)."(".@implode(",",$COL).") VALUES ";
			$ttl = count($arr)-1;
			$size = ($SIDU['eng']=='sl' ? 1 : 200);//commit at each 200 lines for select
		}else{
			foreach ($col as $k=>$v) $COL[] = $v[0];
			@main_cout_str("\n/*".@implode(",",$COL)."*/",$fp);
		}
	}
	if ($exp['ext']=='html'){
		foreach ($arr as $i=>$row){
			@main_cout_str("\n<tr>",$fp);
			foreach ($row as $j=>$val) @main_cout_str("<td".($col[$j][8]=='i' ? " align='right'" : "").(is_null($val) ? " class='n'" : "").">".(is_null($val) ? "NULL" : ($val=='' ? '&nbsp;' : @nl2br(@html8($val))))."</td>",$fp);
			@main_cout_str("</tr>",$fp);
		}
		@main_cout_str("\n</table>",$fp);
	}else{
		foreach ($arr as $i=>$row){
			if ($exp['ext']=='sql' && ($i%$size)==0) @main_cout_str($head,$fp);
			@main_cout_str(($exp['ext']=='sql' ? "(" : "\n"),$fp);
			foreach ($row as $j=>$val){
				if (is_null($val)) @main_cout_str("NULL",$fp);
				elseif (is_numeric($val)) @main_cout_str($val,$fp);
				else{
					$val = @strtr(@addslashes($val),$tran);
					@main_cout_str("'".($exp['zip'] ? $val : @html8($val))."'",$fp);
				}
				if ($j<$num) @main_cout_str(",",$fp);
			}
			if ($exp['ext']=='sql')	@main_cout_str(")".($i==$ttl || ($i%$size)==($size-1) ? ";" : ",")."\n",$fp);
		}
	}
}
function main_form($SIDU,$exp,$mode)
{
	@uppe();
	$obj = ($SIDU[3]=='r' ? @lang(1502) : @lang(1503));
	echo "<form action='exp.php' method='get'>",@html_form("hidden","id","$SIDU[0],$SIDU[1],$SIDU[2],$SIDU[3],$SIDU[4]"),"
		<div class='web'><p class='dot'><b>SIDU ",@lang(1501),":</b> <i class='b red'>$mode</i></p>";
	if ($mode=="SQL") echo "<p class='green'>",@nl2br(@html8($_GET['sql'])),"</p>",@html_form("hidden","sql",$_GET['sql']);
	elseif ($_GET['tab']) echo "<p>$obj = <span class='green'>",@str_replace(",",", ",$_GET['tab']),"</span></p>",@html_form("hidden","tab",$_GET['tab']);
	elseif (!$SIDU[4]){
		echo "<p class='err'>",@lang(1504,$obj),"</p></div></form>";
		return;
	}
	$arr_ext = @array("html"=>"HTML","csv"=>"CSV");
	if ($mode<>"SQL"){
		$arr_ext['sql'] = "SQL";
		echo "<p class='dot b'>",@lang(1505),"</p><p>";
		if ($SIDU['eng']<>'sl') echo @html_form("checkbox","exp[db]",$exp['db'],"","","",@array(1=>'Use ')),($SIDU['eng']=='my' ? 'DB' : 'Sch'),' &nbsp; ';
		echo @html_form("checkbox","exp[drop]",$exp['drop'],"","","",@array(1=>@lang(1506,$obj).' &nbsp; ')),
		@html_form("checkbox","exp[desc]",$exp['desc'],"","","",@array(1=>@lang(1507,$obj).' &nbsp; ')),
		@html_form("checkbox","exp[data]",$exp['data'],"","","",@array(1=>@lang(1508,$obj))),"</p>";
	}
	echo "<p class='dot b'>",@lang(1509),"</p><p>",
	@html_form("radio","exp[ext]",$exp['ext']," &nbsp; ","","",$arr_ext)," &nbsp; ",
	@html_form("checkbox","exp[zip]",$exp['zip'],"","","",@array(1=>@lang(1510))),"</p>";
	if ($mode<>"SQL" && !$exp['sql'][1]){
		echo "<p class='b dot'>",@lang(1511,$obj),": <i class='red'>{$exp[tabs][0]}</i></p><p>";
		foreach ($exp['tab_col'] as $v) echo "<input type='checkbox' name='exp[col][]' value='$v'",(!isset($exp['col']) || @in_array($v,$exp['col']) ? " checked='checked'" : ""),"/> $v &nbsp; ";
		echo "</p><p>where ",@html_form("text","exp[where]",$exp['where'],300),"</p>";
	}
	echo "<p class='dot'></p><p>",@html_form("submit","cmd",@lang(1501))," Max 25290 Lines</p>";
	echo "</div></form>";
	@down();
}
function valid_data($SIDU,&$exp,$cmd)
{
	if (!$exp['db'] && !$exp['drop'] && !$exp['desc'] && !$exp['data']) $exp['desc'] = $exp['data'] = 1;
	if ($exp['drop']) $exp['desc'] = 1;
	if ($exp['ext']<>'html' && $exp['ext']<>'sql') $exp['ext'] = 'csv';
//	if (!$cmd) $exp['zip'] = 1;//default save as zip
	$exp['where'] = @trim(@stripslashes($exp['where']));
	if (!$_GET['sql']){
		if ($SIDU[4]) $exp['tabs'][0] = $SIDU[4];
		else $exp['tabs'] = @explode(",",$_GET['tab']);
		if ($SIDU['eng']=='my'){
			foreach ($exp['tabs'] as $tab) $exp['sql'][] = "SELECT * FROM `$SIDU[1]`.`$tab`";
		}elseif ($SIDU['eng']=='pg'){
			foreach ($exp['tabs'] as $tab) $exp['sql'][] = "SELECT * FROM \"$SIDU[2]\".\"$tab\"";
		}else{
			foreach ($exp['tabs'] as $tab) $exp['sql'][] = "SELECT * FROM $tab";
		}
		if (!$exp['sql'][1]){
			$res = @tm("SQL",$exp['sql'][0]." LIMIT 1");
			$col = @get_sql_col($res,$SIDU['eng']);
			foreach ($col as $v) $exp['tab_col'][] = $v[0];
			if ($exp['tab_col']<>$exp['col']){
				foreach ($exp['col'] as $k=>$v) $exp['col'][$k] = @goodname($v);
				$exp['sql'][0] = "SELECT ".@implode(",",$exp['col']).@substr($exp['sql'][0],8);
			}
			if ($exp['where']) $exp['sql'][0] .= " WHERE $exp[where]";
		}
	}else $exp['sql'][0] = $_GET['sql'];
}
?>
