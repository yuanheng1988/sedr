<?php
//tab=0id 1db 2sch 3typ 4tab 5sort1 6sort2 7sort 8fm 9to 10num f g pk col data
@include "inc.page.php";
$SIDU['page']['xJS'] = $SIDU['page']['nav'] = 1;
@set_cook_tab();
@uppe();
@main();
@down();

function navi()
{
	global $SIDU;
	$conn = $SIDU['conn'][$SIDU[0]];
	$link = @explode(',',$_GET['id']);
	@init_tab($SIDU,$link,$conn);
	$tabs=@table2tabs($link[4],$SIDU['page']['tree']);
	$url = "tab.php?id=$link[0],$link[1],$link[2],$link[3],$link[4]";
	echo "<a href='exp.php?id=$SIDU[0],$SIDU[1],$SIDU[2],$SIDU[3]&#38;tab=$SIDU[4]' ",@html_hkey("E",@lang(3701))," onclick='xwin(this.href);return false'>",@html_img('img/tool-down'),($SIDU['page']['menuText'] ? @lang(3702) : ""),"</a>";
	if ($link[3]=='r') echo " <a href='imp.php?id=$SIDU[0],$SIDU[1],$SIDU[2],$SIDU[3],$SIDU[4]' ",@html_hkey("I",@lang(3703))," onclick='xwin(this.href);return false'>",@html_img('img/tool-imp'),($SIDU['page']['menuText'] ? @lang(3704) : ""),"</a>
		<a href='#' title='",@lang(3738),"' onclick=\"showHide('objTool')\">",@html_img('img/tool-sys'),($SIDU['page']['menuText'] ? @lang(3705) : ""),"</a>
		<a href='$url&#38;objcmd=Empty' ",@html_hkey("-",@lang(3706))," onclick=\"return confirm('",@lang(3707,$SIDU[4]),"?')\">",@html_img('img/tool-flush'),($SIDU['page']['menuText'] ? @lang(3708) : ""),"</a>";
	if ($_GET['desc']) echo " <a href='$url&#38;objcmd=Drop' ",@html_hkey("X",@lang(3711))," onclick=\"return confirm('".@lang(3710,$SIDU[4])."?')\">",@html_img('img/tool-x'),($SIDU['page']['menuText'] ? @lang(3711) : ""),"</a>";
	else{
		echo "$SIDU[sep]",@html_img("img/tool-eye.gif",@lang(3712),"onclick=\"showHide('trgrid');showHide('trhide');setv('gridShow',getv('gridShow')==1 ? 0 : 1)\""),
		"<a href='#' ",@html_hkey("Z",@lang(3713))," onclick=\"submitForm('gridMode',".($SIDU['gridMode'] ? 0 : 1).")\">",@html_img("img/tool-grid.gif"),"</a>",
		"<a href='#' ",@html_hkey("S",@lang(3714))," onclick=\"submitForm('cmd','data_save')\">",@html_img("img/tool-save"),"</a>",
		"<a href='#' ",@html_hkey("X",@lang(3715))," onclick=\"submitForm('cmd','data_del')\">",@html_img("img/tool-del"),"</a>",
		"<a href='#' ",@html_hkey("=",@lang(3716))," onclick='addRow()'>",@html_img("img/tool-add"),"</a>
		<input type='text' id='sidu8' value='$SIDU[8]' style='width:50px'/>
		<input type='text' id='sidu9' value='$SIDU[9]' style='width:50px' title='",@lang(3717),"'/><a href='#' ",@html_hkey("G",@lang(3718))," onclick=\"submitForm('cmd','Go')\">",@html_img('img/tool-run'),"</a>";
		if ($SIDU[9]==-1 || !$SIDU[8]) echo @html_img("img/arr1f.gif",@lang(3719)." Fn+[","class='grey'"),@html_img("img/arr2b.gif",@lang(3720)." Fn+<","class='grey'");
		else echo "<a href='#' ",@html_hkey("[",@lang(3719))," onclick=\"submitForm('cmd','p1')\">",@html_img("img/arr1f.gif"),"</a><a href='#' ",@html_hkey("<",@lang(3720))," onclick=\"submitForm('cmd','pback')\">",@html_img("img/arr2b.gif"),"</a>";
		echo "<span title='",@lang(3721,$SIDU[10]),"'>$SIDU[10]</span>";
		if ($SIDU[9]==-1 || $SIDU[8]+$SIDU[9]>=$SIDU[10]) echo @html_img("img/arr2n.gif",@lang(3722)." Fn+>","class='grey'"),@html_img("img/arr1l.gif",@lang(3723)." Fn+]","class='grey'");
		else echo "<a href='#' ",@html_hkey(">",@lang(3722))," onclick=\"submitForm('cmd','pnext')\">",@html_img("img/arr2n.gif"),"</a><a href='#' ",@html_hkey("]",@lang(3723))," onclick=\"submitForm('cmd','plast')\">",@html_img("img/arr1l.gif"),"</a>";
	}
	@navi_obj($SIDU);
	if ($_GET['desc']) echo " ($SIDU[10])";
}
function main()
{
	global $SIDU;
	$conn = $SIDU['conn'][$SIDU[0]];
	$link = @explode(',',$_GET['id']);
	@tab_tool();
	if ($_GET['desc']) @main_desc($SIDU,$link,$conn);
	else @cout_data($SIDU,$link,$conn);
}
function is_type_int($typ)
{//this function need to upgrade
	$ints = @array("int","serial","bigserial","oid","float","numeric","real","double","smallint");//"date","time"
	$typ = @strtolower($typ);
	foreach ($ints as $v){
		$pos = @strpos($typ,$v);
		if ($pos!==false && !$pos) return 1;
	}
}
function main_desc($SIDU,$link,$conn)
{
	if ($SIDU['eng']=='pg' && $SIDU['tabinfo'][2]=='t') unset($SIDU['col'][0]);
	echo "<table class='grid'>
	<tr class='th'><td>",@lang(3724),"</td><td>",@lang(3725),"</td><td>Null</td><td>",@lang(3726),"</td><td>",@lang(3727),"</td><td>",@lang(3728),"</td><td title='",@lang(3729),"'>",@lang(3730),"</td><td title='",@lang(3731),"'>",@lang(3732),"</td><td title='",@lang(3733),"'>",@lang(3734),"</td><td title='",@lang(3735),"'>",@lang(3736),"</td><td>",@lang(3737),"</td></tr>";
	if ($SIDU[10]){
		foreach ($SIDU['col'] as $col){
			$coln = @goodname($col[0]);
			$colz = (@is_type_int($col[1]) ? $coln : "length($coln)");
			$sql .= ",count(".($SIDU['eng']=='sl' ? "" : "distinct ")."$coln),min($colz),max($colz),avg($colz)\n";
		}
		$sql = "SELECT ".@substr($sql,1)." FROM ".@goodname($SIDU[4]);
		$stat = @sql2arr($sql,-1);
		$stat = $stat[0];
	}
	foreach ($SIDU['col'] as $i=>$col){
		echo "<tr><td><a href='sql.php?id=$SIDU[0]&#38;sql=STATScol:$col[0]'>$col[0]</a></td><td>";
		if (@strlen($col[1])>50) echo "<input type='text' value='",@html8($col[1]),"' size='1' style='width:100%;background:#ddc'/>";
		else echo $col[1];
		echo "</td><td>",($col[2]=="YES" || $col[2]=="f" ? "NULL" : ""),"</td><td>$col[3]</td><td>";
		if ($col[7]=='PRI' || $col[7]=='p') echo "<span class='blue'>PK</span>";
		elseif ($col[7]=='f') echo "<span class='red'>FK</span>";
		elseif ($col[7]=='u' || $col[7]=='UNI') echo "<span class='green'>UK</span>";
		else echo $col[7];
		$distinct = @ceil($stat[4*$i]);
		echo "</td><td>$col[5]</td>
		<td align='right'",($distinct<$SIDU[10] && $distinct ? " class='green'" : ""),">$distinct</td>
		<td align='right'>",@ceil($stat[4*$i+1]),"</td>
		<td align='right'>",@ceil($stat[4*$i+2]),"</td>
		<td align='right'>",@ceil($stat[4*$i+3]),"</td>
		<td>$col[6]</td></tr>";
	}
	echo "</table>";
	if ($link[3]=='v') return @main_desc_view($link,$conn[1],$SIDU['tabinfo'][0]);
	if ($conn[1]<>'pg'){
		if ($conn[1]=='my') $desc = @mysql_fetch_row(@tm("SQL","SHOW CREATE TABLE `$link[1]`.`$link[4]`"));
		else $desc = @sqlite_fetch_array(@tm("SQL","SELECT name,sql FROM sqlite_master WHERE name=tbl_name AND name='$link[4]' LIMIT 1"),SQLITE_NUM);
		$typ = @array('char','varchar','text','blob','smallint','int','timestamp','datetime','date','time','enum','tinyint','unsigned','bigint','mediumtext','longblob','set','longtext','decimal','float');
		foreach ($typ as $t) $mytran[" $t"]=" <span class='green'>$t</span>";
		$mytran[' DEFAULT ']=" <span class='blue'>DEFAULT</span> ";
		$mytran[' default ']=" <span class='blue'>default</span> ";
		$mytran[' CHARACTER SET ']=" <span class='red'>CHARACTER SET</span> ";
		$typ = @array('PRIMARY KEY','UNIQUE KEY','KEY');
		foreach ($typ as $t) $mytran[$t]="<b>$t</b>";
		$desc[1]=@strtr($desc[1],$mytran);
		if ($conn[1]=='sl'){
			$res = @tm("SQL","SELECT sql FROM sqlite_master WHERE type='index' AND tbl_name='$link[4]' AND sql IS NOT NULL");
			while ($row = @sqlite_fetch_array($res,SQLITE_NUM)) $idx .= "<i class='green'>$row[0];</i>\n";
		}
	}else{
		$tran = @array("'"=>"''","\\"=>"\\\\");
		if ($SIDU['tabinfo'][3]) $comm = "\n<b class='blue'>COMMENT ON TABLE \"$link[4]\" IS '".@strtr($SIDU['tabinfo'][3],$tran)."';</b>";
		$desc[1] = "<i class='grey'>--PG desc table is experimental--oid={$SIDU[tabinfo][0]}</i>\nCREATE TABLE \"$link[4]\"(";
		foreach ($SIDU['col'] as $i=>$v){
			$desc[1] .= "\n\t\"$v[0]\" <i class='green'>$v[1]</i>";
			if ($v[2]=='t') $desc[1] .=" NOT NULL";
			if ($v[3]<>''){
				if (!@is_numeric($v[3]) && @substr($v[3],0,8)<>'nextval(' && $v[3]<>'now()' && $v[3]<>'true' && $v[3]<>'false') $v[3]="'".@strtr($v[3],$tran)."'";
				$desc[1] .= " <span class='blue'>DEFAULT</span> $v[3]";
			}elseif ($v[11]) $desc[1] .= " <span class='blue'>DEFAULT</span> $v[11]";
			$desc[1] .= ",";
			if ($v[6]<>'') $comm .= "\n<i class='blue'>COMMENT ON COLUMN \"$link[4]\".\"$v[0]\" IS '".@strtr($v[6],$tran)."';</i>";
		}
		$fkmatch = @array("f"=>"FULL","p"=>"PARTIAL","u"=>"SIMPLE");
		$fkact = @array("a"=>"NO ACTION","r"=>"RESTRICT","c"=>"CASCADE","n"=>"SET NULL","d"=>"SET DEFAULT");
		$res = @tm("SQL","SELECT *,pg_get_constraintdef(oid,TRUE) AS kstr FROM pg_constraint\nWHERE conrelid={$SIDU[tabinfo][0]} AND connamespace={$SIDU[tabinfo][1]}");
		while ($row=@pg_fetch_assoc($res)){
			$desc[1] .= "\nCONSTRAINT \"<i class='green'>$row[conname]</i>\" <b>$row[kstr]</b>";
			if ($row['contype']=='f') $desc[1] .= " MATCH {$fkmatch[$row[confmatchtype]]}\n\tON UPDATE {$fkact[$row[confupdtype]]} ON DELETE {$fkact[$row[confdeltype]]},";
			else $desc[1] .= ",";
		}
		$desc[1] = @substr($desc[1],0,-1)."\n) WITH (OIDS=".($SIDU['tabinfo'][2]=='t' ? "TRUE" : "FALSE").");";
		$res = @tm("SQL","SELECT pg_get_indexdef(indexrelid) FROM pg_index\nWHERE indrelid={$SIDU[tabinfo][0]} AND indisprimary='f'");
		while ($row=@pg_fetch_row($res)) $idx .= "<i class='green'>$row[0];</i>\n";
	}
	echo "<pre>\n\n$desc[1]$comm\n\n$idx\n********** Grants not ready in this version **********\n\n********** SQL HELP **********\n";
	if ($conn[1]=='my') echo "
<b>RENAME</b> TABLE $SIDU[4] <b>TO</b> new_name
ALTER TABLE $SIDU[4] <b>ADD COLUMN</b> a INT(2),ADD COLUMN b INT(3),<b>DROP COLUMN</b> c
ALTER TABLE $SIDU[4] <b>CHANGE</b> a newname VARCHAR(10) NOT NULL DEFAULT '' <b>AFTER</b> c
ALTER TABLE $SIDU[4] <b>ADD PRIMARY KEY</b> (b)
ALTER TABLE $SIDU[4] <b>DROP PRIMARY KEY</b>

ALTER TABLE $SIDU[4] <b>ADD UNIQUE</b> uk (c)
ALTER TABLE $SIDU[4] <b>DROP KEY</b> uk
ALTER TABLE $SIDU[4] <b>ADD INDEX</b> idx (a,b)
ALTER TABLE $SIDU[4] <b>DROP KEY</b> idx";
	else echo "
ALTER TABLE $SIDU[4] <b>RENAME COLUMN</b> column TO new_column
ALTER TABLE $SIDU[4] <b>RENAME TO</b> new_name
ALTER TABLE $SIDU[4] SET SCHEMA new_schema

ALTER TABLE $SIDU[4] <b>ADD COLUMN</b> column type
ALTER TABLE $SIDU[4] <b>DROP COLUMN</b> column [ RESTRICT | CASCADE ]
ALTER TABLE $SIDU[4] <b>ALTER COLUMN</b> column TYPE type
ALTER TABLE $SIDU[4] ALTER COLUMN column <b>SET DEFAULT</b> expression
ALTER TABLE $SIDU[4] ALTER COLUMN column <b>DROP DEFAULT</b>
ALTER TABLE $SIDU[4] ALTER COLUMN column { SET | DROP } <b>NOT NULL</b>
ALTER TABLE $SIDU[4] <b>DROP CONSTRAINT</b> constraint_name [ RESTRICT | CASCADE ]
ALTER TABLE $SIDU[4] SET WITH OIDS
ALTER TABLE $SIDU[4] SET WITHOUT OIDS
ALTER TABLE $SIDU[4] OWNER TO new_owner
ALTER TABLE $SIDU[4] SET TABLESPACE new_tablespace

CREATE UNIQUE INDEX idx ON $SIDU[4] (col1,col2);
CREATE INDEX lower_title_idx ON $SIDU[4] ((lower(title)));
CREATE INDEX title_idx_nulls_low ON $SIDU[4] (title NULLS FIRST);
CREATE INDEX code_idx ON $SIDU[4] (code) TABLESPACE indexspace;

ALTER INDEX distributors RENAME TO suppliers;
ALTER INDEX distributors SET TABLESPACE fasttablespace;";
	echo "</pre>";
}
function main_desc_view($link,$eng,$oid)
{
	if ($eng=='sl'){
		$row = @sqlite_fetch_array(@tm("SQL","SELECT sql FROM sqlite_master WHERE type='view' AND name='$link[4]'"));
		echo "<p class='web green'>$row[0]</p>";
		return;
	}
	if ($eng=='my'){
		$row = @mysql_fetch_row(@tm("SQL","SELECT VIEW_DEFINITION FROM information_schema.VIEWS\nWHERE TABLE_SCHEMA='$link[1]' AND TABLE_NAME='$link[4]'"));
		$row[0] = @trim(@str_replace("/* ALGORITHM=UNDEFINED */","",$row[0]));
		$link[4] = "`$link[4]`";
	}elseif ($eng=='pg'){
		$row = @pg_fetch_row(@tm("SQL","SELECT pg_get_viewdef($oid)"));
		$link[4] = "\"$link[4]\"";
	}
	echo "<p class='web'><br/><span class='green'>CREATE VIEW $link[4] AS</span><br/>$row[0]</p>";
}
function init_tab(&$SIDU,$link,$conn)
{
	if ($conn[1]=="my"){
//0name 1type 2null 3defa 4maxchar 5extra 6comm 7pk 8align 9pos
		$res = @tm("SQL","SELECT COLUMN_NAME,COLUMN_TYPE,IS_NULLABLE,COLUMN_DEFAULT,\nifnull(CHARACTER_MAXIMUM_LENGTH,NUMERIC_PRECISION),EXTRA,COLUMN_COMMENT,\nCOLUMN_KEY,if(NUMERIC_PRECISION IS NULL,'','i'),ORDINAL_POSITION\nFROM information_schema.COLUMNS\nWHERE TABLE_SCHEMA='$SIDU[1]' AND TABLE_NAME='$SIDU[4]'\nORDER BY ORDINAL_POSITION");
		while ($row = @mysql_fetch_row($res)){
			$col[] = $row;
			if ($row[7]=='PRI') $SIDU['pk'][]=$row[9]-1;
		}
		$num = @mysql_fetch_row(@tm("SQL","SELECT COUNT(*) FROM `$link[1]`.`$link[4]`"));
	}elseif ($conn[1]=='sl'){
		$res = @tm("SQL","pragma table_info($link[4])");
		while ($row=@sqlite_fetch_array($res)){
			if ($row['type']=='integer') $row['type']='int';
			if ($row['pk']) $SIDU['pk'][]=$row['cid'];
			$col[]=@array($row['name'],$row['type'],($row['notnull'] ? 'NULL' : 'YES'),$row['dflt_value'],'','','',($row['pk'] ? 'PRI' : ''),"",$row['cid']+1);
		}
		$num = @sqlite_fetch_array(@tm("SQL","SELECT COUNT(*) FROM $link[4]"));
	}else{
		$tab = @pg_fetch_row(@tm("SQL","SELECT a.oid,a.relnamespace,a.relhasoids,obj_description(a.oid,'pg_class')\nFROM pg_class a,pg_namespace b WHERE a.relkind='$link[3]' AND a.relnamespace=b.oid\nAND a.relname='$link[4]' AND b.nspname='$link[2]'"));
		$SIDU['tabinfo']=$tab;
		$defa=@sql2arr("SELECT adnum,adsrc FROM pg_attrdef WHERE adrelid=$tab[0]",2);
		$defaRaw=$defa;
		foreach ($defa as $k=>$v){
			if (@substr($v,0,9)<>"nextval('"){
				$rowx = @explode("::",$v);
				if (@substr($rowx[0],0,1)=="'" && @substr($rowx[0],-1)=="'") $rowx[0]=@substr($rowx[0],1,-1);
				$rowx[0] = @str_replace("''","'",$rowx[0]);
				$defa[$k]=$rowx[0];
			}
		}
		if ($SIDU['page']['oid'] && $tab[2]=='t'){
			$col[0]=@array("oid","oid",'t');
			$hasOID="oid,";
		}else $colX = -1;
		$typ=@sql2arr("SELECT oid,typname FROM pg_type",2);
		$res = @tm("SQL","SELECT attname,atttypid,attnotnull,atthasdef,\nCASE attlen WHEN -1 THEN atttypmod ELSE attlen END,\n'','','','',attnum,format_type(atttypid,atttypmod) FROM pg_attribute\nWHERE attrelid=$tab[0] AND attnum>0 AND attisdropped=FALSE ORDER BY attnum");
		while ($row = @pg_fetch_row($res)){
			$row[3] = ($row[3]=='t' ? $defa[$row[9]] : '');
			$row[1] = $typ[$row[1]];
			if ($row[1]=="numeric") $row[1]=$row[10];
			elseif ($row[1]=='int2') $row[1]="smallint";
			elseif ($row[1]=='int4') $row[1]="int";
			elseif ($row[1]=='int8') $row[1]="bigint";
			elseif ($row[1]=='bpchar') $row[1]="char";
			if ($row[4]>4 && ($row[1]=='varchar' || $row[1]=='char')) $row[1] .= "(".($row[4]-4).")";
			if (@substr($row[3],0,9)=="nextval('") $row[1]=($row[1]=="int" ? "serial" : "bigserial");
			$row[11]=$defaRaw[$row[9]];//only used for default ''::varchar etc
			$col[] = $row;
		}
		$res = @tm("SQL","SELECT conkey,contype FROM pg_constraint\nWHERE connamespace=$tab[1] AND conrelid=$tab[0]");
		while ($row = @pg_fetch_row($res)){//pk uk fk
			$pucf = @explode(",",@substr($row[0],1,-1));
			foreach ($pucf as $v){
				$col[$v+$colX][7] = ($col[$v+$colX][7] ? $col[$v+$colX][7].",$row[1]" : $row[1]);
				if ($row[1]=='p') $SIDU['pk'][]=$v+$colX;
			}
		}
		$num = @pg_fetch_row(@tm("SQL","SELECT COUNT(*) FROM \"$link[2]\".\"$link[4]\""));
	}
	$SIDU[10] += $num[0];
	$SIDU['col']=$col;
	if ($_GET['desc']){//desc
		if ($conn[1]=='pg'){//set col comm
			$res = @tm("SQL","SELECT objsubid,description FROM pg_description\nWHERE objoid=$tab[0] AND objsubid>0");
			while ($row = @pg_fetch_row($res)) $SIDU['col'][$row[0]+$colX][6]=$row[1];
		}
		return;
	}
	$SIDU['gridShow'] = $_POST['gridShow'];
	$MODE=@explode('.',$_COOKIE['SIDUMODE']);//0lang 1gridmode 2pgsize=sidu9
	$SIDU['gridMode'] = $MODE[1];
	if ($MODE[2]<-1 || !$MODE[2]) $MODE[2]=15;
	$SIDU[9]=$MODE[2];
	$SIDU[8]=@ceil($_POST['sidu8']);
	if ($SIDU[8]<0) $SIDU[8]=0;
	$SIDU[7]=$_POST['sidu7'];//sort
	if (@substr($SIDU[7],0,4)=='del:'){
		$SIDU[7]=@substr($SIDU[7],4);
		if ($SIDU[5]==$SIDU[7] || $SIDU[5]=="$SIDU[7] desc") $SIDU[5]=$SIDU[7]='';
		elseif ($SIDU[6]==$SIDU[7] || $SIDU[6]=="$SIDU[7] desc") $SIDU[6]=$SIDU[7]='';
		else $SIDU[7]='';
	}
	$SIDU['f'] = @strip($_POST['f'],1,0,1);
	@sidu_sort($SIDU[5],$SIDU[6],$SIDU[7],$SIDU['page']['sortData']);
	$strSort = ($SIDU[5] ? " ORDER BY $SIDU[5]".($SIDU[6] ? ",$SIDU[6]" : "") : ($SIDU[6] ? " ORDER BY $SIDU[6]" : ""));
	foreach ($SIDU['f'] as $k=>$v){
		if ($k==='sql' && $v) $whereSQL = " AND ".$SIDU['f']['sql'];
		elseif ($v<>'') $where .= " AND ".$SIDU['col'][$k][0]." $v";
	}
	$where .= $whereSQL;
	if (!$strSort && (!$where || !@stripos($where," order by "))) $strSort = " ORDER BY 1 DESC";
	if ($where){
		$where = " WHERE ".@substr($where,5);
		if ($conn[1]=='my'){
			$res = @tm("SQL","SELECT COUNT(*) FROM `$SIDU[1]`.`$SIDU[4]`$where");
			$row = @mysql_fetch_row($res);
		}elseif ($conn[1]=='pg'){
			$res = @tm("SQL","SELECT COUNT(*) FROM \"$SIDU[2]\".\"$SIDU[4]\"$where");
			$row = @pg_fetch_row($res);
		}else{
			$res = @tm("SQL","SELECT COUNT(*) FROM $SIDU[4]$where");
			$row = @sqlite_fetch_array($res);
		}
		$SIDU[10] = $row[0];
	}
	if ($_POST['cmd']=='p1') $SIDU[8]=0;
	elseif ($SIDU[9]<>-1){
		if ($_POST['cmd']=='pback') $SIDU[8] -= $SIDU[9];
		elseif ($_POST['cmd']=='pnext') $SIDU[8] += $SIDU[9];
		elseif ($_POST['cmd']=='plast') $SIDU[8] = $SIDU[10]-$SIDU[9];
		if ($SIDU[8]>$SIDU[10]) $SIDU[8] = $SIDU[10]-$SIDU[9];
		if ($SIDU[8]<0) $SIDU[8]=0;
	}
	if ($SIDU[9]<>-1) $limit = " LIMIT $SIDU[9]".($SIDU[8] ? " OFFSET $SIDU[8]" : "");
	if ($conn[1]=='my'){
		$res = @tm("SQL","SELECT * FROM `$SIDU[1]`.`$SIDU[4]`$where$strSort$limit");
		while ($row = @mysql_fetch_row($res)) $arr[] = $row;
	}elseif ($conn[1]=='pg'){
		$res = @tm("SQL","SELECT $hasOID* FROM \"$SIDU[2]\".\"$SIDU[4]\"$where$strSort$limit");
		while ($row = @pg_fetch_row($res)) $arr[] = $row;
	}else{
		$res = @tm("SQL","SELECT * FROM $SIDU[4]$where$strSort$limit");
		while ($row = @sqlite_fetch_array($res,SQLITE_NUM)) $arr[] = $row;
	}
	$SIDU['data']=$arr;
	if ($conn[1]<>'my') @init_pg_col_align($SIDU['data'],$SIDU['col']);
	if ($_POST['hideCol']<>'') $_POST['g'][$_POST['hideCol']] = -1;
	@init_col_width($SIDU);
}
function save_data($SIDU,$eng,$cmd)
{
	foreach ($_POST as $k=>$v){if (@substr($k,0,5)=='data_' || @substr($k,0,10)=='cbox_data_'){
		$arr = @explode('_',$k,3);
		$data[$arr[0]][$arr[1]][$arr[2]]=$v;
	}}
	foreach ($SIDU['col'] as $i=>$v) $col[]=($eng=='pg' ? '"'.$v[0].'"' : $v[0]);
	$tab = ($eng=='pg' ? '"'.$SIDU[2].'"."'.$SIDU[4].'"' : ($eng=='my' ? "`$SIDU[1]`.`$SIDU[4]`" : $SIDU[4]));
	foreach ($data['cbox']['data'] as $i=>$v){//only need i
		unset($COL); unset($VAL); $where = '';
		$is_new = @substr($i,0,3)==='new';
		if (!$is_new || $cmd=='data_del'){
			foreach ($_POST['pkV'][$i] as $j=>$v) $where .= " and ".$col[$j].(@strtoupper($v)==='NULL' ? " IS NULL" : "='$v'");
			$where = "WHERE ".@substr($where,5);
		}
		if ($cmd=='data_save'){
			foreach ($data['data'][$i] as $j=>$v){
				$v = @strip($v,1,0,1);
				if (!$is_new || $eng<>'pg' || $v<>'' || @substr($SIDU['col'][$j][3],0,8)<>'nextval('){
					if ($eng=='pg' && $SIDU['page']['dataEasy']){
						if ($SIDU['col'][$j][1]=="smallint" || $SIDU['col'][$j][1]=="int") $v=@ceil($v);
						elseif ((@substr($SIDU['col'][$j][1],0,8)=="varchar(" || @substr($SIDU['col'][$j][1],0,5)=="char(") && @strtoupper($v)<>'NULL') $v=@trim(@substr($v,0,$SIDU['col'][$j][4]-4));
					}
					$COL[]=$col[$j]; $VAL[]=$v;
				}//above logic too complex - even myself forgot :D
			}
			if ($is_new && isset($COL)) $res = @tm("insert",$tab,$COL,$VAL);
			elseif (!$is_new) $res = @tm("update",$tab,$COL,$VAL,$where);
		}elseif ($cmd=='data_del') $res = @tm("delete",$tab,null,null,$where);
		$errno = @sidu_err(1);
		if ($errno) $err .= ($eng=='pg' ? $errno : "Err $errno")."\\n";
		elseif ($cmd=="data_save") echo @html_js("parent.document.dataTab.cbox_data_$i.checked=''");
		elseif ($cmd=="data_del") echo @html_js("parent.document.getElementById('tr_$i').style.display='none'");
	}
	if ($err) echo @html_js("alert('".@strtr($err,@array("'"=>"\'","\""=>"\\\"","\n"=>"\\n"))."')");
}
function set_cook_tab()
{
	global $SIDU;
	$MODE = @explode('.',$_COOKIE['SIDUMODE']);//0lang 1gridMode 2pgsize ...
	if (isset($_POST['gridMode'])) $MODE[1] = $_POST['gridMode'];
	if (isset($_POST['sidu9'])) $MODE[2]=@ceil($_POST['sidu9']);
	if (!$MODE[0]) $MODE[0]='en';
	if (!$MODE[1]) $MODE[1]=0;
	$cook = @implode(".",$MODE);
	if ($cook<>$_COOKIE['SIDUMODE']){
		$_COOKIE['SIDUMODE']=$cook;
		@setcookie('SIDUMODE',$cook,@time()+311040000);
	}
	$cook = $SIDU['cook'][$SIDU[0]];
	if ($SIDU[1]==$cook[1] && $SIDU[2]==$cook[2] && $SIDU[3]==$cook[3] && $SIDU[4]==$cook[4]) return;
	@update_sidu_cook(@array($SIDU[0],$SIDU[1],$SIDU[2],$SIDU[3],$SIDU[4]));
}
?>
