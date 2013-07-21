<?php
//db=0id 1db 2sch 3typ 4tab 5sort1 6sort2 7sort
@include "inc.page.php";
$SIDU['page']['nav'] = 1;
@set_cook_db();
@uppe();
@main();
@down();

function navi()
{
	global $SIDU;
	$conn = $SIDU['conn'][$SIDU[0]];
	$link = @explode(',',$_GET['id']);
	$eng = @array('my'=>'MySQL','pg'=>'Postgres','sl'=>'SQLite');
	@sidu_sort($SIDU[5],$SIDU[6],$SIDU[7],$SIDU['page']['sortObj']);
	$obj = @array("r"=>@lang(1416),"v"=>@lang(1417),"S"=>@lang(1418),"f"=>@lang(1419));
	if ($link[3]<>''){
		if ($link[3]=='r') echo "<a href='tab-new.php?id=$SIDU[0],$SIDU[1],$SIDU[2],$SIDU[3],$SIDU[4],$SIDU[5],$SIDU[6]' onclick=\"xwin(this.href,700,500);return false\" ",@html_hkey("=",@lang(1401)),">",@html_img('img/tool-add'),"</a>$SIDU[sep]";
		if ($link[3]=='r' || $link[3]=='v') echo "<a href='#' ",@html_hkey("E",@lang(1402))," onclick='dbexp(\"$SIDU[0],$SIDU[1],$SIDU[2],$SIDU[3]\",\"objs[]\")'>",@html_img('img/tool-down'),($SIDU['page']['menuText'] ? @lang(1403) : ""),"</a>";
		if ($link[3]=='r') echo " <a href='imp.php?id=$SIDU[0],$SIDU[1],$SIDU[2]' ",@html_hkey("I",@lang(1404))," onclick='xwin(this.href);return false'>",@html_img('img/tool-imp'),($SIDU['page']['menuText'] ? @lang(1405) : ""),"</a>";
		if ($link[3]=='r') echo " <a href='#' onclick=\"showHide('objTool')\" title='",@lang(1406),"'>",@html_img('img/tool-sys'),($SIDU['page']['menuText'] ? @lang(1407) : ""),"</a>
			<a href='#' onclick=\"setv('objcmd','Empty');return (confirm('",@lang(1409),"') ? dataTab.submit() : false)\" ",@html_hkey("-",@lang(1410)),">",@html_img('img/tool-flush'),($SIDU['page']['menuText'] ? @lang(1408) : ""),"</a>";
		echo " <a href='#' onclick=\"setv('objcmd','Drop'); return (confirm('",@lang(1412,$obj[$SIDU[3]]),"') ? dataTab.submit() : false)\" ",@html_hkey("X",@lang(1413)),">",@html_img('img/tool-x'),($SIDU['page']['menuText'] ? @lang(1411) : ""),"</a>
			<a href='".($conn[1]=="sl" ? "tab.php?id=$SIDU[0],$SIDU[1],0,r,sqlite_master'" : "#' onclick=\"showHide('DBseek')\"")." ",@html_hkey("F",@lang(1414)),">",@html_img('img/tool-find'),($SIDU['page']['menuText'] ? @lang(1415) : ""),"</a>$SIDU[sep]",
		@html_img("img/x$link[3]")," <a href='db.php?id=$link[0],$link[1],,,,$SIDU[5],$SIDU[6]'>$link[1]</a>",
		($link[2] ? " » <a href='db.php?id=$link[0],$link[1],$link[2],$link[3],,$SIDU[5],$SIDU[6]'>$link[2]</a>" : ""),
		($link[4]<>'' ? " » $link[4]" : "");
	}else{
		if ($conn[1]=="my") $serv['server'] = @mysql_get_server_info();
		elseif ($conn[1]=="pg") $serv = @pg_version();
		else $serv['server'] = @sqlite_libversion();
		echo @html_img("img/eng-$conn[1]")," <b>SIDU 3.0</b> for <b>{$eng[$conn[1]]}</b>
		$SIDU[sep]<a href='".($conn[1]=="sl" ? "tab.php?id=$SIDU[0],$SIDU[1],0,r,sqlite_master'" : "#' onclick=\"showHide('DBseek')\"")." ",@html_hkey("F",@lang(1414)),@html_img('img/tool-find'),"</a>
		<a href='db.php?id=$link[0],,,,,$SIDU[5],$SIDU[6]'><b>",($conn[1]=="sl" ? "SQLite" : "$conn[3]@$conn[2]"),"</b></a> v $serv[server]
		$SIDU[sep] ",@date("Y-m-d H:i:s");
	}
}
function main()
{
	global $SIDU;
	$conn = $SIDU['conn'][$SIDU[0]];
	$link = @explode(',',$_GET['id']);
	echo "<form id='dataTab' name='dataTab' action='db.php?id=$SIDU[0],$SIDU[1],$SIDU[2],$SIDU[3],$SIDU[4],$SIDU[5],$SIDU[6]' method='post'><input type='hidden' id='objcmd' name='objcmd'/>";
	@tab_tool();
	//these 4 can be merged
	if ($link[3]=='r') @main_tab($SIDU,$link,$conn);
	elseif ($link[3]=='v') @main_view($SIDU,$link,$conn);
	elseif ($link[3]=='S' && $conn[1]=='pg') @main_seq($SIDU,$link,$conn);
	elseif ($link[3]=='f' && $conn[1]=='pg') @main_func($SIDU,$link,$conn);
	else @main_db($SIDU,$link,$conn);
	echo "</form><div id='DBseek' class='blobDiv' style='display:none'><div class='box'>
",@html_img("img/tool-close.gif","Close - Fn+F","class='right' onclick=\"showHide('DBseek')\""),"
<b>",@lang(1414),":</b><br/>search table name, column name, comment, oid
<br/>Search func name, def, comment, oid<br/>-- available in next release</div></div>";
}
function main_db(&$SIDU,$link,$conn)
{
	if ($conn[6]) $dbs = @explode(";",$conn[6]);
	if ($conn[1]=="my"){
		if (!$link[1]){
			foreach ($dbs as $db) $where .= " OR a.SCHEMA_NAME LIKE '$db%'";
			if ($where) $where = " WHERE ".@substr($where,4);
		}else $where = " WHERE a.SCHEMA_NAME='$link[1]'";
		$res = @tm("SQL","SELECT a.SCHEMA_NAME,a.DEFAULT_CHARACTER_SET_NAME,\nif(b.TABLE_TYPE='VIEW','v','r'),count(b.TABLE_NAME),sum(DATA_LENGTH+INDEX_LENGTH)\nFROM information_schema.SCHEMATA a LEFT JOIN information_schema.TABLES b\non a.SCHEMA_NAME=b.TABLE_SCHEMA$where GROUP BY 1,2,3");
		while ($row = @mysql_fetch_row($res)){
			$arr[$row[0]][0][0][0][$row[2]]=$row[3];
			$arr[$row[0]][1]=$row[1];
			$arr[$row[0]][5]+=$row[4];
			$arr[$row[0]][0][0][1]=0;
		}
	}elseif ($conn[1]=='pg'){//0sch[oid sch typ num] 1enc 2oid 3owner 4ts 5size
		$owner=@sql2arr("SELECT oid,rolname FROM pg_authid",2);
		if (!$link[1]){
			foreach ($dbs as $db) $where .= " OR a.datname LIKE '$db%'";
			if ($where) $where = "\nAND (".@substr($where,4).")";
		}else $where = " AND a.datname='$link[1]'";
		$res = @tm("SQL","SELECT a.oid,a.datname,pg_encoding_to_char(a.encoding),a.datdba,c.spcname,pg_database_size(a.oid)\nFROM pg_database a,pg_tablespace c\nWHERE a.datistemplate='f' AND a.dattablespace=c.oid$where ORDER BY 2");
		while ($row = @pg_fetch_row($res)){
			@db_conn($conn,$row[1]);
			$func = @sql2arr("select pronamespace,count(*) from pg_proc group by 1",2);
			$res2 = @tm("SQL","SELECT a.oid,a.nspname,a.nspowner,b.relkind,count(b.oid)\nFROM pg_namespace a LEFT JOIN pg_class b ON a.oid=b.relnamespace\nWHERE a.nspname".($link[2] ? "='$link[2]'" : " NOT LIKE 'pg_toast%' AND a.nspname NOT LIKE 'pg_temp%'")."\nGROUP BY 1,2,3,4 ORDER BY 2");
			unset($arr2);
			while ($row2 = @pg_fetch_row($res2)){
				$arr2[$row2[0]][0][$row2[3]]=$row2[4];
				$arr2[$row2[0]][1]=$row2[1];
				$arr2[$row2[0]][2]=$owner[$row2[2]];
				$arr2[$row2[0]][0]['f']=$func[$row2[0]];
			}
			$arr[$row[1]]=@array($arr2,$row[2],$row[0],$owner[$row[3]],$row[4],$row[5]);
		}
	}else{
		foreach ($dbs as $db){
			$SIDU['dbL']=@db_conn($conn,$db);
			$stat=@stat($db);
			$arr[$db][5]=$stat['size'];
			if (@function_exists('posix_getpwuid')){
				$arr[$db][6]=@posix_getpwuid($stat['uid']);
				$arr[$db][7]=($stat['uid']==$stat['gid'] ? $arr[$db][6] : @posix_getpwuid($stat['gid']));
			}else $arr[$db][6]['name']=$arr[$db][7]['name']=@getenv('USERNAME');//windows
			$arr[$db][8]=@date("Y-m-d H:i:s",$stat['mtime']);
			$res = @tm("SQL","SELECT type,count(*) FROM sqlite_master GROUP BY 1");
			while ($row = @sqlite_fetch_array($res)){
				if ($row[0]=='table') $typ='r';
				elseif ($row[0]=='view') $typ='v';
				else $typ ='other';
				$arr[$db][0][0][0][$typ]=$row[1];
				$arr[$db][0][0][1]=0;
			}
		}
	}
	echo "<table class='grid'><tr class='th'><td>",@lang(1420),"</td><td>",@lang(1421),"</td><td>",@lang(1422),"</td>";
	if ($conn[1]=='pg') echo "<td>",@lang(1423),"</td><td>",@lang(1424),"</td><td>",@lang(1425),"</td><td>",@lang(1423),"</td><td>",@lang(1426),"</td>";
	elseif ($conn[1]=='sl') echo "<td>",@lang(1423),"</td><td>",@lang(1427),"</td>";
	echo "<td>",@lang(1428),"</td><td>",@lang(1429),"</td><td>",@lang(1430),"</td>",($conn[1]=='sl' ? "<td>".@lang(1431)."</td>" : ""),"</tr>";
	foreach ($arr as $k=>$v){
		$i=0;
		foreach ($v[0] as $k1=>$v1){
			echo "<tr>";
			if (!$i){
				echo "<td>",@html_img("img/xdb","","class='vm'")," <a href='db.php?id=$link[0],$k,,,,$SIDU[5],$SIDU[6]'",($conn[1]=='pg' ? " title='oid=$v[2]'" : ""),">$k</a></td><td>$v[1]</td><td align='right'>",@size2str($v[5]),"</td>";
				if ($conn[1]=='pg') echo "<td>$v[3]</td><td>$v[4]</td>";
			}else echo "<td colspan='5'></td>";
			if ($conn[1]=='pg') echo "<td>",@html_img("img/xsch","","class='vm'")," <a href='db.php?id=$link[0],$k,$v1[1],,,$SIDU[5],$SIDU[6]' title='oid=$k1'>$v1[1]</a></td><td>$v1[2]</td>
				<td align='right'><a href='db.php?id=$link[0],$k,$v1[1],S,,$SIDU[5],$SIDU[6]'>",($v1[0]['S']+0),"</a></td>";
			elseif ($conn[1]=='sl') echo "<td>{$v[6][name]}</td><td>{$v[7][name]}</td>";
			echo "<td align='right'><a href='db.php?id=$link[0],$k,$v1[1],r,,$SIDU[5],$SIDU[6]'>",($v1[0]['r']+0),"</a></td>
			<td align='right'><a href='db.php?id=$link[0],$k,$v1[1],v,,$SIDU[5],$SIDU[6]'>",($v1[0]['v']+0),"</a></td>
			<td align='right'><a href='db.php?id=$link[0],$k,$v1[1],f,,$SIDU[5],$SIDU[6]'>",($v1[0]['f']+0),"</a></td>";
			if ($conn[1]=='sl') echo "<td>$v[8]</td>";
			echo "</tr>";
			$i++;
		}
	}
	echo "</table><pre>\n\n";
	if (!$link[1]){
		if ($conn[1]=='pg') echo "<b>CREATE DATABASE name</b> WITH ENCODING='UTF8' OWNER=postgres TABLESPACE=pg_default;
COMMENT ON DATABASE name IS 'comm';
DROP DATABASE name;
ALTER DATABASE name RENAME TO newname;
ALTER DATABASE name OWNER TO new_owner;
ALTER DATABASE name SET TABLESPACE new_tablespace;

<b>CREATE SCHEMA name</b> AUTHORIZATION postgres;
COMMENT ON SCHEMA mysch IS 'comm';
DROP SCHEMA mysch;
ALTER SCHEMA name RENAME TO newname;
ALTER SCHEMA name OWNER TO newowner;";
		elseif ($conn[1]=='my') echo "<b>CREATE DATABASE</b> name;<br/><b>DROP DATABASE</b> name;";
	}elseif ($conn[1]=='my'){
		$res = @tm("SQL","SHOW CREATE DATABASE `$link[1]`");
		$row = @mysql_fetch_row($res);
		echo $row[1];
	}elseif ($conn[1]=='pg'){
		$db=$arr[$link[1]];
		$res = @tm("SQL","SELECT description FROM pg_shdescription WHERE objoid=$db[2]");
		$row = @pg_fetch_row($res);
		echo "CREATE DATABASE \"<b>$link[1]</b>\" WITH ENCODING='<b>$db[1]</b>' OWNER=<b>$db[3]</b> TABLESPACE=<b>$db[4]</b>;\nCOMMENT ON DATABASE \"$link[1]\" IS '<b>",@addslashes($row[0]),"</b>';";
		if ($link[2]){
			foreach ($db[0] as $sch=>$v);
			$res = @tm("SQL","SELECT obj_description('$sch','pg_namespace')");
			$row = @pg_fetch_row($res);
			echo "\n\nCREATE SCHEMA \"<b>$link[2]</b>\" AUTHORIZATION <b>$v[2]</b>;\nCOMMENT ON SCHEMA \"$link[2]\" IS '<b>",@addslashes($row[0]),"</b>';";
		}
	}
	echo "</pre>";
}
function main_tab($SIDU,$link,$conn)
{
	if ($conn[1]=="my"){
		$col = @array('Table'=>'TABLE_NAME','Engine'=>'ENGINE','RowFMT'=>'ROW_FORMAT','Auto'=>'AUTO_INCREMENT','Rows'=>'TABLE_ROWS','Avg'=>'AVG_ROW_LENGTH','Size'=>'DATA_LENGTH','Index'=>'INDEX_LENGTH','PK'=>'PK','Created'=>'CREATE_TIME','Updated'=>'UPDATE_TIME','Checked'=>'CHECK_TIME','TabColl'=>'TABLE_COLLATION','Comment'=>'TABLE_COMMENT');
		$res = @tm("SQL","SELECT TABLE_NAME,COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE\nWHERE TABLE_SCHEMA='$link[1]'".($link[4]<>'' ? " AND TABLE_NAME LIKE '$link[4]%'" : "")."\nAND CONSTRAINT_NAME='PRIMARY' ORDER BY TABLE_NAME,ORDINAL_POSITION");
		while ($row = @mysql_fetch_row($res)) $pk[$row[0]][]=$row[1];
		foreach ($pk as $tab=>$v) $PK[$tab]=@implode(",",$v);
		$res = @tm("SQL","SELECT * FROM information_schema.TABLES WHERE TABLE_SCHEMA='$link[1]'\nAND TABLE_TYPE<>'VIEW'".($link[4]<>'' ? " AND TABLE_NAME LIKE '$link[4]%'" : ""));
		while ($row = @mysql_fetch_assoc($res)){
			if ($row['TABLE_TYPE']<>'BASE TABLE'){
				$num = @mysql_fetch_row(@tm("SQL","SELECT COUNT(*) FROM `$link[1]`.`$row[TABLE_NAME]`"));
				$row['TABLE_ROWS']=$num[0];
			}
			$row['PK']=$PK[$row['TABLE_NAME']];
			foreach ($col as $k=>$v) $data[$k]=$row[$v];
			$arr[]=$data;
		}
	}elseif ($conn[1]=='pg'){
		$col = @array('OID'=>'oid','Table'=>'relname','Owner'=>'towner','TS'=>'reltablespace','Rows'=>'Rows','Avg'=>'Avg','Size'=>'size','Index'=>'ind','PK'=>'PK','Comment'=>'comm');
		@db_conn($conn,$link[1]);
		$ts=@sql2arr("SELECT oid,spcname FROM pg_tablespace",2);
		$res = @tm("SQL","SELECT b.relname,b.oid,pg_get_userbyid(b.relowner) AS towner,b.reltablespace,\npg_relation_size(b.oid) AS size,pg_total_relation_size(b.oid) AS ind,\nobj_description(b.oid,'pg_class') AS comm,b.relnamespace\nFROM pg_namespace a,pg_class b\nWHERE a.oid=b.relnamespace AND a.nspname='$link[2]' AND b.relkind='$link[3]'".($link[4]<>'' ? "\nAND b.relname LIKE '$link[4]%'" : "")." ORDER BY 1");
		while ($row = @pg_fetch_array($res)){
			$num = @pg_fetch_row(@tm("SQL","SELECT COUNT(*) FROM \"$link[2]\".\"$row[relname]\""));
			$row['Rows']=$num[0];
			$row['PK']=@get_tab_key_pg($row[1],$row['relnamespace']);
			$row['reltablespace']=$ts[$row['reltablespace']];
			$row['ind'] -= $row['size'];
			$row['Avg']=@ceil($row['size']/$row['Rows']);
			foreach ($col as $k=>$v) $data[$k]=$row[$v];
			$arr[]=$data;
		}
	}else{
		$col=@array("Table"=>"Table","Rows"=>"Rows","Definition"=>"Definition","PK"=>"PK");
		$res = @tm("SQL","SELECT name,sql FROM sqlite_master WHERE type='table'".($link[4] ? " AND name LIKE '$link[4]%'" : "")." ORDER BY name");
		while ($row = @sqlite_fetch_array($res)){
			$row2 = @sqlite_fetch_array(@tm("SQL","SELECT count(*) FROM $row[0]"));
			$arr[]=@array("Table"=>$row[0],"Rows"=>$row2[0],"Definition"=>$row[1],"PK"=>@sidu_sl_pk($row[0]));
		}
		$row2 = @sqlite_fetch_array(@tm("SQL","SELECT count(*) FROM sqlite_master"));
		$arr[]=@array("Table"=>"sqlite_master","Rows"=>$row2[0],"Definition"=>"create table sqlite_master(type text,name text,tbl_name text,rootpage int,sql text)");
	}
	@cout_obj($SIDU,$link,$arr,$col);
	echo "<br/><table class='grid'>
<tr class='th'><td>MySQL</td><td>Postgres</td><td>Range</td><td>Storage</td><td>SQLite</td></tr>
<tr><td>smallint</td><td>smallint</td><td>+-32,768</td><td>2B</td><td></td></tr>
<tr><td>int</td><td>int</td><td>+-2,147,483,647</td><td>4B</td><td>int</td></tr>
<tr><td>numeric</td><td>numeric</td><td>(x,y)</td><td>4B</td><td>real</td></tr>
<tr><td>char</td><td>char</td><td>max255?</td><td></td><td></td></tr>
<tr><td>varchar</td><td>varchar</td><td>max255?</td><td></td><td></td></tr>
<tr><td>text</td><td>text</td><td>max65535?</td><td></td><td>text</td></tr>
<tr><td>date 3B</td><td>date</td><td>4713bc 5874897ad</td><td>4B</td><td></td></tr>
<tr><td>timestamp 4B</td><td>timestamp</td><td>4713bc 294276ad</td><td>8B CURRENT_TIMESTAMP</td><td></td></tr>
<tr><td>time 3B</td><td>time</td><td>0:0:0 24:0:0</td><td>8B</td><td></td></tr>
<tr><td>blob</td><td>blob</td><td></td><td></td><td>blob</td></tr>
<tr><td>enum</td><td></td><td></td><td></td><td></td></tr>
<tr><td>auto_increment PK</td><td>serial PK</td><td></td><td></td><td>int PK</td></tr>
</table><pre>\n";
	if ($conn[1]=='pg') echo "
CREATE TABLE name(
\tid <i class='green'>serial</i> <i class='blue'>NOT NULL</i> <b>PRIMARY KEY</b>,
\tid2 <i class='green'>smallint</i> NOT NULL DEFAULT 0,
\tid4 <i class='green'>int</i> NOT NULL DEFAULT 0,
\tccy <i class='green'>char(3)</i> NOT NULL DEFAULT 'USD',
\tnotes <i class='green'>varchar(255)</i> NOT NULL DEFAULT '',
\tcreated <i class='green'>date</i> NOT NULL,
\tupdated <i class='green'>timestamp</i> NOT NULL DEFAULT <i class='blue'>now()</i>,
\tprice <i class='green'>numeric(7,2)</i> NOT NULL DEFAULT 0.00,
\ttxt <i class='green'>text</i>
)";
	elseif ($conn[1]=='my') echo "
CREATE TABLE name(
\tid <i class='green'>int(9)</i> <u>unsigned</u> <i class='blue'>NOT NULL</i> <i class='red'>auto_increment</i> <b>PRIMARY KEY</b>,
\tid2 <i class='green'>smallint(5)</i> NOT NULL DEFAULT 0,
\tccy <i class='green'>char(3)</i> <i class='blue'>binary</i> NOT NULL DEFAULT 'USD',
\tnotes <i class='green'>varchar(255)</i> NOT NULL DEFAULT '',
\tcreated <i class='green'>date</i> NOT NULL DEFAULT '0000-00-00',
\tupdated <i class='green'>timestamp</i> NOT NULL DEFAULT <i class='blue'>CURRENT_TIMESTAMP</i>,
\tprice <i class='green'>numeric(7,2)</i> NOT NULL DEFAULT 0.00,
\ttxt <i class='green'>text</i>
)";
	else echo "
CREATE TABLE name(
\tid <i class='green'>int</i> <b>PRIMARY KEY</b>,
\tccy <i class='green'>text</i>,
\tprice <i class='green'>real</i>
)";
	echo "</pre>";
}
function cout_obj($SIDU,$link,$arr,$col)
{
	$arr = @sort_arr($arr,$SIDU[5],$SIDU[6]);
	$right = @array('Rows','Avg','Size','Auto','Index');
	$slink = "db.php?id=$link[0],$link[1],$link[2],$link[3],$link[4],$SIDU[5],$SIDU[6]";
	echo "<table class='grid'><tr class='th'><td class='cbox'><input type='checkbox' onclick='checkedAll()'/></td>";
	if ($SIDU['page']['lang']<>'en') $colStr = @lang(1432);
	foreach ($col as $k=>$v){
		$align[$k] = (@in_array($k,$right) ? " align='right'" : "");
		echo "<td><a",@get_sort_css($k,$SIDU[5],$SIDU[6])," href='$slink,$k'>",($colStr[$k] ? $colStr[$k] : $k),"</a></td>";
	}
	echo "</tr>";
	$obj=($SIDU[3]=='r' ? 'Table' : ($SIDU[3]=='v' ? 'View' : ($SIDU[3]=='S' ? 'Seq' : 'Func')));
	foreach ($arr as $i=>$row){
		echo "<tr><td class='cbox'><input type='checkbox' name='objs[]' value='",@html8($row[$obj]),"'",(@in_array($row[$obj],$_POST['objs']) ? " checked='checked'" : ""),"/></td>";
		foreach ($col as $k=>$v){
			$url = "tab.php?id=$link[0],$link[1],$link[2],$link[3],".$row[$k];
			if ($k=='Table' || $k=='View') $row[$k] = "<a href='$url&#38;desc=1'>".@html_img("img/x$SIDU[3]",@lang(1433),"class='vm'")."</a> <a href='$url'>{$row[$k]}</a>";
			elseif ($k=='Size' || $k=='Index' || $k=='Avg') $row[$k]=@size2str($row[$k]);
			elseif ($k=='Definition') $row[$k] = "<input id='$k_$i' type='hidden' value='".@html8($row[$k])."'/><input type='text' style='width:200px;background:#ddc' size='1' value='".@html8(@substr($row[$k],0,100))."' onclick=\"showHide('blobDiv',1);setv('blobTxt',getv('$k_$i'))\"/>";
			echo "<td{$align[$k]}",($k=='Rows' || $k=='PK' ? " class='green'" : ($k=='Auto' && $row[$k]>2000000000 ? " class='red'" : "")),">{$row[$k]}</td>";
		}
		echo "</tr>";
	}
	echo "</table>
<div id='blobDiv' style='display:none'>
",@html_img("img/tool-close.gif","Close","onclick=\"showHide('blobDiv',-1)\""),"
<br/><textarea id='blobTxt' style='width:490px;height:295px'></textarea>
</div>";
}
function get_tab_key_pg($tab,$nsp)
{
	$pk = @pg_fetch_row(@tm("SQL","SELECT pg_get_constraintdef(oid,TRUE) FROM pg_constraint\nWHERE contype='p' AND conrelid=$tab AND connamespace=$nsp"));
	if (!$pk[0]) return;
	return @substr($pk[0],13,-1);
}
function size2str($i)
{
	if ($i<1024) $c = "grey";
	elseif ($i<1048576) $c = "green";//1m
	elseif ($i<10485760) $c = "";//10m
	elseif ($i<104857600) $c = "blue";//100m
	else $c = "red";
	if ($i<10000) $i=$i."B";
	elseif ($i<10238976) $i=@round($i/1024)."K";
	elseif ($i<10484711424) $i=@round($i/1048576)."M";
	else $i=@round($i/1073741824,1)."G";
	if (!$c) return $i;
	return "<span class='$c'>$i</span>";
}
function main_view($SIDU,$link,$conn)
{
	if ($conn[1]=="my"){
		$col = @array('View'=>'View','Rows'=>'Rows','Owner'=>'Owner','Definition'=>'Definition');
		$arr=@sql2arr("SELECT TABLE_NAME View,VIEW_DEFINITION as def,DEFINER Owner\nFROM information_schema.VIEWS WHERE TABLE_SCHEMA='$link[1]' ORDER BY 1",-1);
		foreach ($arr as $i=>$v){
			$num = @mysql_fetch_row(@tm("SQL","SELECT COUNT(*) FROM `$link[1]`.`$v[View]`"));
			$arr[$i]['Rows']=$num[0];
			$arr[$i]['Definition']=@trim(@str_replace('/* ALGORITHM=UNDEFINED */','',$v['def']));
		}
	}elseif ($conn[1]=='pg'){
		$col = @array('OID'=>'oid','View'=>'relname','Owner'=>'towner','TS'=>'reltablespace','Rows'=>'Rows','Definition'=>'def','Comment'=>'comm');
		@db_conn($conn,$link[1]);
		$ts=@sql2arr("SELECT oid,spcname FROM pg_tablespace",2);
		$res = @tm("SQL","SELECT b.relname,b.oid,pg_get_userbyid(b.relowner) AS towner,b.reltablespace,\nobj_description(b.oid,'pg_class') AS comm,pg_get_viewdef(b.oid) AS def\nFROM pg_namespace a,pg_class b WHERE a.oid=b.relnamespace\nAND a.nspname='$link[2]' AND b.relkind='$link[3]'".($link[4]<>'' ? " AND b.relname LIKE '$link[4]%'" : "")." ORDER BY 1");
		while ($row = @pg_fetch_array($res)){
			$num = @pg_fetch_row(@tm("SQL","SELECT COUNT(*) FROM \"$link[2]\".\"$row[relname]\""));
			$row['Rows']=$num[0];
			$row['reltablespace']=$ts[$row['reltablespace']];
			foreach ($col as $k=>$v) $data[$k]=$row[$v];
			$arr[]=$data;
		}
	}else{
		$col=@array("View"=>"View","Rows"=>"Rows","Definition"=>"Definition");
		$res = @tm("SQL","SELECT name,sql FROM sqlite_master WHERE type='view'".($link[4] ? " AND name LIKE '$link[4]%'" : ""));
		while ($row = @sqlite_fetch_array($res)){
			$res2 = @tm("SQL","SELECT count(*) FROM $row[0]");
			$arr[]=@array("View"=>$row[0],"Rows"=>@sqlite_num_rows($res2),"Definition"=>$row[1]);
		}
	}
	@cout_obj($SIDU,$link,$arr,$col);
	echo "<pre>\n\n<b>CREATE VIEW</b> vvv <b>AS</b>\nSELECT * FROM tab WHERE col>5</pre>";
}
function main_seq($SIDU,$link,$conn)
{
	$col = @array('OID'=>'oid','Seq'=>'relname','Owner'=>'towner','TS'=>'reltablespace','cur'=>'cur','min'=>'min','max'=>'max','inc'=>'inc','cache'=>'cache','cycle'=>'cycle','called'=>'called','min'=>'min','Comment'=>'comm');
	@db_conn($conn,$link[1]);
	$ts=@sql2arr("SELECT oid,spcname FROM pg_tablespace",2);
	$res = @tm("SQL","SELECT b.relname,b.oid,pg_get_userbyid(b.relowner) AS towner,b.reltablespace,\nobj_description(b.oid,'pg_class') AS comm\nFROM pg_namespace a,pg_class b\nWHERE a.oid=b.relnamespace AND a.nspname='$link[2]' AND b.relkind='$link[3]'".($link[4]<>'' ? " AND b.relname LIKE '$link[4]%'" : "")." ORDER BY 1");
	while ($row = @pg_fetch_array($res)){
		$row['reltablespace']=$ts[$row['reltablespace']];
		foreach ($col as $k=>$v) $data[$k]=$row[$v];
		$arr[]=$data;
	}
	@cout_obj($SIDU,$link,$arr,$col);
}
function main_func($SIDU,$link,$conn)
{
	$col = @array('OID'=>'oid','Func'=>'proname','Owner'=>'towner','return'=>'prorettype','lang'=>'prolang','Definition'=>'prosrc','Comment'=>'comm');
	@db_conn($conn,$link[1]);
	$lang=@sql2arr("SELECT oid,lanname FROM pg_language",2);
	$typ=@sql2arr("SELECT oid,typname FROM pg_type",2);
	$res = @tm("SQL","SELECT b.proname,b.oid,pg_get_userbyid(b.proowner) AS towner,b.pronamespace,\nobj_description(b.oid,'pg_proc') AS comm,b.proargtypes,b.prorettype,b.prolang,b.prosrc\nFROM pg_namespace a,pg_proc b\nWHERE a.oid=b.pronamespace AND a.nspname='$link[2]'".($link[4]<>'' ? " AND b.proname LIKE '$link[4]%'" : "")." ORDER BY 1");
	while ($row = @pg_fetch_array($res)){
		$row['prorettype']=$typ[$row['prorettype']];
		$row['prolang']=$lang[$row['prolang']];
		$para = @explode(" ",@trim($row['proargtypes']));
		unset($parr);
		foreach ($para as $v) $parr[]=$typ[$v];
		$para = @implode(",",$parr);
		if ($para) $row['proname'] .= "($para)";
		foreach ($col as $k=>$v) $data[$k]=$row[$v];
		$arr[]=$data;
	}
	@cout_obj($SIDU,$link,$arr,$col);
}
function set_cook_db()
{
	global $SIDU;
	if (!$SIDU[1]) return;
	$cook = $SIDU['cook'][$SIDU[0]];
	if ($SIDU[1]<>$cook[1]) $arr = @array($SIDU[0],$SIDU[1]);//db
	elseif ($SIDU[2]<>$cook[2]) $arr = @array($SIDU[0],$SIDU[1],$SIDU[2]);//sch
	elseif ($SIDU[3]<>$cook[3])	$arr = @array($SIDU[0],$SIDU[1],$SIDU[2],$SIDU[3]);//typ
	if (isset($arr) && $cook<>$arr) @update_sidu_cook($arr);
}
?>
