<?php
//general func
function swap(&$a,&$b){$c=$a;$a=$b;$b=$c;}
function html8($str){return @htmlspecialchars($str,ENT_QUOTES,"UTF-8");}
function html_js($str){return "<script type='text/javascript'>$str</script>";}
function html_img($url,$t=null,$style=null,$w,$h,$b=0,$alt="")
{
	if (!@strpos($url,".")) $url .= ".png";
	return "<img src='$url'".($w ? " width='$w'" : "").($h ? " height='$h'" : "")." border='$b'".($t ? " title='$t'" : "")." alt='$alt'".($style ? " $style" : "")."/>";
}
function html_hkey($key,$title)
{
	if ($key==",") $str = "&lt;";
	elseif ($key==".") $str = "&gt;";
	elseif ($key=="=") $str = "+";
	else $str = $key;
	return "accesskey='$key' title='$title - Fn+$str'";
}
/*textar name	val	x			 y		style	-
	select name	val	size	 sql	style	arr |select: sql+arr: arr=[0,1]
	radio	 name	val	sepa	 sql	-			arr |select: !sql arr:arr[k->v,...]
	cbox	 name	val	sepa	 sql	-			arr |cbox|radio: sql or arr
	text	 name	val	size	 max	style	-
	pass	 name	val	size	 max	style	-
	submit name	val	size	 -		style	-
	hidden name	val	-			 -		-			-	*/
function html_form($type,$name,$val=null,$size=null,$sql=null,$style=null,$arr=null)
{
	if ($val && !@is_array($val)) $val = @html8($val);
	$style = ($style ? " $style" : "");
	$style_arr = @explode("style='",$style,2);
	if ($type=="textarea"){
		if ($size || $sql){
			if ($size) $str = "width:$size"."px;";
			if ($sql) $str .= "height:$sql"."px;";
			if ($style_arr[1]) $style = $style_arr[0]."style='$str".$style_arr[1];
			else $style = " style='$str'".$style;
		}
		return "<textarea name='$name'$style>$val</textarea>";
	}
	if ($size && $type<>"radio" && $type<>"checkbox"){
		if ($style_arr[1]) $style = $style_arr[0]."style='width:$size"."px;$style_arr[1]";
		else $style = " style='width:$size"."px'$style";
	}
	$style = "name='$name'$style";
	if ($sql && ($type=="select" || $type=="radio" || $type=="checkbox")) $res = @mysql_query($sql);
	if ($type=="select"){
		$str = "<select size='1' $style>";
		if (isset($res)){
			if (isset($arr[0]))
				$str .= "<option value='$arr[0]'".(@in_array($arr[0],$val) || $arr[0]==$val ? " selected='selected'" : "").">$arr[1]</option>";
			while ($row = @mysql_fetch_row($res))
				$str .= "<option value='$row[0]'".(@in_array($row[0],$val) || $row[0]==$val ? " selected='selected'" : "").">$row[1]</option>";
		}else{
			foreach ($arr as $k=>$v)
				$str .= "<option value='$k'".(@in_array($k,$val) || $k==$val ? " selected='selected'" : "").">$v</option>";
		}
		$str .= "</select>";
		return $str;
	}
	if ($type=="radio" || $type=="checkbox"){
		if (!$size) $size = " ";//sepa
		if (isset($res)){
			while ($row = @mysql_fetch_row($res))
				$str .= "<input type='$type' $style value='$row[0]'".($row[0]==$val || @in_array($row[0],$val) ? " checked='checked'" : "")." /> $row[1]$size";
		}else{
			foreach ($arr as $k=>$v)
				$str .= "<input type='$type' $style value='$k'".($k==$val || @in_array($k,$val) ? " checked='checked'" : "")." /> $v$size";
		}
		return @substr($str,0,0-@strlen($size));
	}
	return "<input type='$type' $style value='$val'".(@is_numeric($sql) ? " maxlength='$sql'" : "")." />";
}
function strip($val,$trim=0,$tag=0,$slash=0)//tag==1,0 or <p><b>
{
	if (@is_array($val)){
		foreach ($val as $k=>$v) $val[$k] = @strip($v,$trim,$tag,$slash);
	}else{
		if ($tag)	$val = @strip_tags($val,($tag==1 ? "" : $tag));
		if ($slash) $val = @stripslashes($val);
		if ($trim) $val = @trim($val);
	}
	return $val;
}
//sidu only func
function initSIDU()
{
	global $SIDU;
	//0lang.1gridMode.2pgSize.3tree.4sortObj.5sortData.6menuTextSQL.7menuText.8his.9hisErr.10hisSQL.11hisData.12dataEasy(pg).13oid(pg).14slconn
	$cook = @explode('.',$_COOKIE['SIDUMODE']);
	$MODE['lang']=($cook[0] ? $cook[0] : 'en');//defa lang=en
	if ($_POST['opt']['lang']) $MODE['lang']=$_POST['opt']['lang'];
	elseif ($_POST['conn']['lang']) $MODE['lang']=$_POST['conn']['lang'];
	$MODE['gridMode']=$cook[1];
	$MODE['pgSize']=$cook[2];
	$cook[3]=@substr($cook[3],0,1);
	$MODE['tree']=($cook[3]=='_' || ($cook[3]>-1 && $cook[3]<10) ? $cook[3] : '_');//defa tree=_
	$MODE['sortObj']=($cook[4]==2 ? 2 : 1);//defa sortObj=1
	$MODE['sortData']=($cook[5]==1 ? 1 : 2);//defa sortData=2
	$MODE['menuTextSQL']=($cook[6]=='' || $cook[6] ? 1 : 0);//0off 1on in sql window--defa=1
	$MODE['menuText']=$cook[7];//0off 1on in data window--defa=0
	$MODE['his']=($cook[8]=='' || $cook[8] ? 1 : 0);//0off 1log his--defa=1
	$MODE['hisErr']=($cook[9]=='' || !$cook[9] ? 0 : 1);//0off 1log his err--defa=0
	$MODE['hisSQL']=($cook[10]=='' || !$cook[10] ? 0 : 1);//0off 1log his in SQL window--defa=0
	$MODE['hisData']=($cook[11]=='' || $cook[11] ? 1 : 0);//0off 1log original 5 rows of data fefore upd|del--defa=1
	$MODE['dataEasy']=($cook[12]=='' || $cook[12] ? 1 : 0);//0off 1on pg int char varchar handy--defa=1
	$MODE['oid']=($cook[13]=='' || $cook[13] ? 1 : 0);//0off 1on pg show oid--defa=1
	$SIDU['page']=$MODE;
	$SIDU['eng']=$SIDU['conn'][$SIDU[0]][1];
	$SIDU['sep']=@html_img('img/dot','','',2,16);
}
function siduMD5($str)
{
	$ip = ($_SERVER['HTTP_X_REMOTE_ADDR'] ? $_SERVER['HTTP_X_REMOTE_ADDR'] : $_SERVER['REMOTE_ADDR']);
	return $str.@substr(@md5($str.@SIDU_PK().$ip),0,8);
}
function check_global_ip_access()
{
	$ip = @explode(";",@SIDU_IP());
	if (!$ip[0]) return;//ok--no firewall
	foreach ($ip as $IP){
		if(@ereg($IP,$_SERVER['REMOTE_ADDR'])) return;//ok
	}
	exit(@lang(101,$_SERVER['REMOTE_ADDR'])."<br/><br/>".@lang(102,"inc.page.php")."<br/>".@lang(103,"sidu.sf.net"));
}
function close_sidu_conn($id)
{
	global $SIDU;
	unset($SIDU['conn'][$id]);
	if (empty($SIDU['conn'])){
		@setcookie(@siduMD5('SIDUCONN'),'',-1);
		@setcookie('SIDUCONN','',-1);
		return;
	}
	unset($SIDU['cook'][$id]);
	foreach ($SIDU['cook'] as $v) $cook[] = @implode(".",$v);
	@setcookie('SIDUSQL',@enc65(@implode('@',$cook),1));
	foreach ($SIDU['conn'] as $k=>$v) $conn[] = @implode("#",$v);
	@setcookie(@siduMD5('SIDUCONN'),@enc65(@implode('@',$conn),1));
	return $k;
}
function get_sidu_conn(){
	$conn = @explode("@",@dec65($_COOKIE[@siduMD5('SIDUCONN')],1));
	foreach ($conn as $v){
		$arr = @explode("#",$v);//0id 1eng[my|pg] 2host 3user 4pass 5port 6dbs 7save 8charset
		if ($arr[0]<>'') $res[$arr[0]] = $arr;
	}
	return $res;
}
function get_sidu_cook()
{
	global $SIDU;
	$cook = @explode('@',@dec65($_COOKIE['SIDUSQL'],1));
	foreach ($cook as $v){
		$arr = @explode(".",$v);//0id 1db 2sch 3typ 4tab
		if ($arr[0]<>'') $res[$arr[0]]=$arr;
	}
	return $res;
}
function update_sidu_cook($arr)
{
	global $SIDU;
	$SIDU['cook'][$SIDU[0]] = $arr;
	foreach ($SIDU['cook'] as $v) $res[] = @implode(".",$v);
	@setcookie('SIDUSQL',@enc65(@implode('@',$res),1));
}
function check_sidu_conn($SIDU)
{
	if ($SIDU[0] && isset($SIDU['conn'][$SIDU[0]])) return;//ok
	if (@substr($_SERVER['SCRIPT_NAME'],-8)<>'conn.php'){
		echo @html_js("top.location='./conn.php'");
		exit;//no connection
	}
}
function db_conn($conn,$db)
{
	$pass = @dec65($conn[4],1);
	if ($conn[1]=="my"){
		$res = @mysql_connect($conn[2].(($conn[5] && $conn[5]<>3306) ? ":$conn[5]" : ""),$conn[3],$pass);
//bug---charset still not work :(
		if ($conn[8] && $conn[8]<>'latin1') @mysql_set_charset($conn[8]); 
		if ($db) $res = @mysql_select_db($db);
		return $res;
	}elseif ($conn[1]=="pg"){
		$pg = "host=$conn[2] user=$conn[3]";
		if ($pass) $pg .=" password=".@str_replace('"','\"',$pass);
		if ($conn[5] && $conn[5]<>5432) $pg .= " port=$conn[5]";
		if ($db) $pg .= " dbname=$db";
		return @pg_connect($pg);
	}elseif ($conn[1]=="sl"){
		if ($db) return @sqlite_open($db,0666);
		$db = @explode(";",$conn[6],2);
		return @sqlite_open($db[0],0666);
	}
}
function lang($id,$arr)
{
	global $LANG;
	if (!isset($arr)) return $LANG[$id];
	if (!@is_array($arr)) return @str_replace('%0%',$arr,$LANG[$id]);
	foreach ($arr as $k=>$v) $tr[] = '%'.$k.'%';
	return @str_replace($tr,$arr,$LANG[$id]);
}
function tm($cmd,$tab,$col,$val,$where)
{
	global $SIDU;
	if ($cmd=="SQL"){
		@tm_use_db($col);
		$sql = @trim($tab);//sql
		return @tm_his($sql);
	}elseif ($cmd=="SQLS"){
		@tm_use_db($col);
		foreach ($tab as $sql) $res=@tm("SQL",$sql);
		return $res;
	}
	foreach ($val as $k=>$v) $val[$k] = @addslashes($v);
	if ($cmd=="insert" || $cmd=="replace"){
		foreach ($col as $i=>$v) $CV .= ",".(@strtoupper($val[$i])==='NULL' ? "NULL" : ($val[$i]=='now()' ? $val[$i] : "'{$val[$i]}'"));
		$sql = "$cmd INTO $tab(".@implode(",",$col).")\nVALUES(".@substr($CV,1).")";
	}elseif ($cmd=="delete") $sql = "DELETE FROM $tab\n$where";
	elseif ($cmd=="update"){
		foreach ($col as $i=>$v) $CV .= ",{$col[$i]}=".(@strtoupper($val[$i])==='NULL' ? "NULL" : ($val[$i]=='now()' ? $val[$i] : "'{$val[$i]}'"));
		$sql = "UPDATE $tab\nSET ".@substr($CV,1)."\n$where";
	}
	if (($cmd=="update" || $cmd=="delete") && $where && $SIDU['page']['hisData']){
		$sqlL = "SELECT * FROM $tab $where LIMIT 5";
		if ($SIDU['eng']=="my"){
			$res = @mysql_query($sqlL);
			while ($row=@mysql_fetch_row($res)) @tm_his_log('D',$row);
		}elseif ($SIDU['eng']=="pg"){
			$res = @pg_query($sqlL);
			while ($row=@pg_fetch_row($res)) @tm_his_log('D',$row);
		}else{
			$res = @sqlite_query($SIDU['dbL'],$sqlL);
			while ($row=@sqlite_fetch_array($res,SQLITE_NUM)) @tm_his_log('D',$row);
		}
	}
	return @tm_his($sql);
}
function tm_use_db($db,$sch)
{
	global $SIDU;
	$db = @trim($db);
	if (!$db) return;
	$conn = $SIDU['conn'][$SIDU[0]];
	if ($conn[1]=="my") @tm_his("USE `$db`");
	elseif ($conn[1]=="pg"){
		@db_conn($conn,$db);
		if ($sch) @pg_query("SET search_path to \"$sch\"");
	}else $SIDU['dbL']=@sqlite_open($db,0666);
}
function tm_his($sql)
{
	global $SIDU;
	$time_start = @microtime(true);
	if ($SIDU['eng']=="my") $res = @mysql_query($sql);
	elseif ($SIDU['eng']=="pg") $res = @pg_query($sql);
	else $res = @sqlite_query($SIDU['dbL'],$sql);
	if (!$SIDU['page']['his']) return $res;
	$time_end = @microtime(true);
	$time = @round(($time_end - $time_start) * 1000);
	if ($SIDU['page']['hisErr']) $err = @sidu_err(1);
	@tm_his_log('B',$sql,$time,$err);
	return $res;
}
function tm_his_log($typ,$log,$time,$err)
{
	//[id]date-time ms Back|Sql|Err|Data logs
	global $SIDU;
	$id = $SIDU[0];
	$ts = @date("Y-m-d H:i:s");
	$his = &$_SESSION['siduhis'][$id];
	if ($typ=='D' && $SIDU['page']['hisData']) $his[] = "$ts D 0 ".@implode("»",$log);
	else{
		if (($typ=='B' && $SIDU['page']['his']) || ($typ=='S' && $SIDU['page']['hisSQL'])) $his[] = "$ts $typ $time $log";
		if ($err && $SIDU['page']['hisErr']) $his[] = "$ts E 0 $err";
	}
}
function sidu_err($errStr)
{
	global $SIDU;
	if ($SIDU['eng']=='pg') return @pg_last_error();
	$err = ($SIDU['eng']=='my' ? @mysql_errno() : @sqlite_last_error($SIDU['dbL']));
	if (!$errStr || !$err) return $err;
	if ($SIDU['eng']=='my')	return "$err ".@mysql_error();
	return @sqlite_error_string($err);
}
function sidu_sort(&$s1,&$s2,&$sort,$mode)
{
	if (!$sort) return;
	if ($mode==1){//1 sort | 2 sort
		$s1 = $sort.($s1=="$sort desc" ? "" : " desc");
		$s2=$sort = "";
		return;
	}
	if (!$s1) $s1 = "$sort desc";
	elseif ($s1=="$sort desc") $s1 = $sort;
	elseif ($s1==$sort){$s1=$s2;$s2="";}
	elseif (!$s2) $s2 = "$sort desc";
	elseif ($s2=="$sort desc") $s2 = $sort;
	elseif ($s2==$sort) $s2 = "";
	else{$s1=$s2;$s2="$sort desc";}
	$sort="";
return;//the following sort is by defaut:asc
	if (!$sort) return;
	if ($mode==1){//1 sort | 2 sort
		$s1 = $sort.($s1==$sort ? " desc" : "");
		$s2=$sort = "";
		return;
	}
	if (!$s1) $s1 = $sort;
	elseif ($s1==$sort) $s1 .= " desc";
	elseif ($s1=="$sort desc"){$s1=$s2;$s2="";}
	elseif (!$s2) $s2 = $sort;
	elseif ($s2==$sort) $s2 .= " desc";
	elseif ($s2=="$sort desc") $s2 = "";
	else{$s1=$s2;$s2=$sort;}
	$sort="";
}
function get_sort_css($name,$sort1,$sort2)
{
	if ($name==$sort1) return " class='sort1'";
	if ("$name desc"==$sort1) return " class='sort1d'";
	if (!$sort2) return "";
	if ($name==$sort2) return " class='sort2'";
	if ("$name desc"==$sort2) return " class='sort2d'";
}
function sort_arr($arr,$s1=null,$s2=null)
{
	if (!$s1) return $arr;
	if (@substr($s1,-5)==" desc"){
		$s1 = @substr($s1,0,-5); $desc1 = 1;
	}
	if ($s2 && @substr($s2,-5)==" desc"){
		$s2 = @substr($s2,0,-5); $desc2 = 1;
	}
	foreach ($arr as $k=>$v){
		$a1[$k] = $v[$s1];
		if ($s2) $a2[$k] = $v[$s2];
	}
	if ($s2) @array_multisort($a1,($desc1 ? SORT_DESC : SORT_ASC),$a2,($desc2 ? SORT_DESC : SORT_ASC),$arr);
	else @array_multisort($a1,($desc1 ? SORT_DESC : SORT_ASC),$arr);
	return $arr;
}
function table2tabs($str,$tree)
{
	if ($tree=='_'){
		if (@substr($str,0,1)==$tree) $tabs = @substr($str,0,@strpos(@substr($str,1),$tree)+1);
		else $tabs = @substr($str,0,@strpos($str,$tree));
	}elseif ($tree) $tabs = @substr($str,0,$tree);
	if ($tabs==$str) $tabs = "";
	return $tabs;
}
function sql2arr($sql,$pk)
{
	global $SIDU;
	$res = @tm("SQL",$sql);
	if ($SIDU['eng']=='pg'){
		if ($pk==1){
			while ($row=@pg_fetch_row($res)) $arr[]=$row[0];
		}elseif ($pk==2){
			while ($row=@pg_fetch_row($res)) $arr[$row[0]]=$row[1];
		}elseif ($pk<>-1){
			while ($row=@pg_fetch_assoc($res)) $arr[$row[$pk]]=$row;
		}else{
			while ($row=@pg_fetch_array($res)) $arr[]=$row;
		}
	}elseif ($SIDU['eng']=='my'){
		if ($pk==1){
			while ($row=@mysql_fetch_row($res)) $arr[]=$row[0];
		}elseif ($pk==2){
			while ($row=@mysql_fetch_row($res)) $arr[$row[0]]=$row[1];
		}elseif ($pk<>-1){
			while ($row=@mysql_fetch_assoc($res)) $arr[$row[$pk]]=$row;
		}else{
			while ($row=@mysql_fetch_array($res)) $arr[]=$row;
		}
	}else{
		if ($pk==1){
			while ($row=@sqlite_fetch_array($res)) $arr[]=$row[0];
		}elseif ($pk==2){
			while ($row=@sqlite_fetch_array($res)) $arr[$row[0]]=$row[1];
		}elseif ($pk<>-1){
			while ($row=@sqlite_fetch_array($res,SQLITE_ASSOC)) $arr[$row[$pk]]=$row;
		}else{
			while ($row=@sqlite_fetch_array($res)) $arr[]=$row;
		}
	}
	return $arr;
}
function goodname($str)
{//not need always add ` and \"?
	global $SIDU;
	if ($SIDU['eng']=='sl') return $str;
	if ($SIDU['eng']=='my') return "`$str`";
	else return "\"$str\"";
}
function init_col_width(&$SIDU)
{
	foreach ($SIDU['col'] as $i=>$v){//init grid size
		$px = @ceil($_POST['g'][$i]);
		if (!$px || $px<-1 || $_POST['showCol']=="$i"){
			@init_tab_grid($SIDU['data'],$i,$px);
			if ($px<60) $px=60;
			if ($px==60){
				$len=@strlen($v[0])*10;
				if ($len>$px) $px=$len;
				if ($px>110) $px=110;
			}
		}
		$SIDU['g'][$i] = $px;
	}
}
function init_tab_grid($data,$j,&$px)
{
	foreach ($data as $row){
		$grid = @strlen($row[$j])*10;
		if ($grid>$px) $px=$grid;
		if ($px>300){
			$px=300;
			return;
		}
	}
}
function init_pg_col_align($data,&$col)
{//this method sucks and not good
	foreach ($data as $row){
		foreach ($col as $j=>$v){
			if (!is_null($row[$j])) $col[$j][8]=(!@is_numeric($row[$j]) || $col[$j][8]=='c' ? 'c' : 'i');
		}
	}
}
function is_blob($col,$data)//data is not in use...
{//this method is not 100% correct--mostly affected is update|delete with no pk
	global $SIDU;
	if ($SIDU['eng']=='sl' && $SIDU['g'][$col[9]-1]<300) return 0;
	if ($col[1]=='text' || $col[1]=='longtext' || $col[1]=='blob') return 1;
}
function get_sql_col($res,$eng)
{
	if ($eng=='my'){
		$num = @mysql_num_fields($res);
		for ($i=0;$i<$num;$i++) $col[$i] = @array(@mysql_field_name($res,$i),@mysql_field_type($res,$i));
	}elseif ($eng=='pg'){
		$num = @pg_num_fields($res);
		for ($i=0;$i<$num;$i++) $col[$i] = @array(@pg_field_name($res,$i),@pg_field_type($res,$i));
	}else{
		$num = @sqlite_num_fields($res);
		for ($i=0;$i<$num;$i++) $col[$i] = @array(@sqlite_field_name($res,$i));
	}
	return $col;
}
function get_sql_data($res,$eng)
{
	if ($eng=='my'){
		while ($row = @mysql_fetch_row($res)) $arr[] = $row;
	}elseif ($eng=='pg'){
		while ($row = @pg_fetch_row($res)) $arr[] = $row;
	}else{
		while ($row = @sqlite_fetch_array($res,SQLITE_NUM)) $arr[] = $row;
	}
	return $arr;
}
function cout_data($SIDU,$link,$conn,$sql)
{
	if ($_POST['cmd']=='data_save' || $_POST['cmd']=='data_del') @save_data($SIDU,$conn[1],$_POST['cmd']);
	$url = (!$sql ? "tab" : "sql");
	echo "<form id='dataTab' name='dataTab' action='$url.php?id=$link[0],$link[1],$link[2],$link[3],$link[4],$SIDU[5],$SIDU[6]' method='post'>";
	if (!$sql){
		echo "<p style='padding:3px'>where ",@html_form("text","f[sql]",$SIDU['f']['sql'],300)," <img src='img/tool-run.png' onclick=\"submitForm('cmd','p1')\" class='vm'/> eg col='abc'</p>";
		foreach ($SIDU['g'] as $j=>$gSize){
			if ($gSize==-1) $hidden .= "<a href='#' onclick=\"submitForm('showCol',$j)\">{$SIDU[col][$j][0]}</a> ";
		}
		if ($hidden) echo "<p>",@lang(104),": $hidden</p>";
	}
	if (isset($SIDU['pk'])) $pk=$SIDU['pk'];
	foreach ($SIDU['col'] as $j=>$v){
		$disp[$j] = ($SIDU['g'][$j]==-1 ? " style='display:none'" : "");
		$title = "$v[0] ".@str_replace("'","",$v[1]);
		$color = '';
		if (@in_array($j,$SIDU['pk'])){
			$title = "PK $title";
			$color = '06c';
		}
		if ($v[5]=='auto_increment' || $v[1]=='serial' || $v[1]=='bigserial') $color = 'c00';
		$colH .= "<td class='td$j' title='$title'{$disp[$j]}><div class='gridH' id='gH$j'".(!$SIDU['gridMode'] ? " style='width:{$SIDU[g][$j]}px'" : "").">";
		if (!$sql) $colH .= "<a".@get_sort_css($v[0],$SIDU[5],$SIDU[6])." href='#' onclick=\"submitForm('sidu7','$v[0]')\">"
			.($color ? "<span style='color:#$color'>$v[0]</span>" : $v[0])."</a>";
		else $colH .= "<a>$v[0]</a>";
		$colH .= "</div></td>";
		$jsStr .= "xHRD.init('gH$j',10);";
		$filter .= "<td class='td$j'{$disp[$j]}><input type='text' size='1' id='f$j' name='f[$j]' value='".@html8($SIDU['f'][$j])."'/></td>";
		$grid .= "<td class='td$j'{$disp[$j]}><input type='text' size='1' name='g[$j]' id='g$j' value='".$SIDU['g'][$j]."'/></td>";
		if (!$sql) $gridShow .= "<td class='td$j'{$disp[$j]}><img src='img/tool-sys.png' title='".@lang(124)."' onclick=\"submitForm('sidu7','del:$v[0]')\"/> <a href='#' onclick=\"submitForm('hideCol',$j)\">".@html_img('img/tool-x')."</a></td>";
		if ($v[3]=='CURRENT_TIMESTAMP' || $v[3]=='now()') $v[3]="'+his.getFullYear()+'-'+(parseInt(his.getMonth())+1)+'-'+his.getDate()+' '+his.getHours()+':'+his.getMinutes()+':'+his.getSeconds()+'";
		elseif (@substr($v[3],0,9)=="nextval('") $v[3]='';
		else $v[3] = @html8($v[3] ? $v[3] : ($v[2]=='YES' || $v[2]=='f' ? 'NULL' : ''));
		$align = ($SIDU['col'][$j][8]=='i' ? ' style="text-align:right"' : '');
		$id = 'data_new\'+id+\'_'.$j;
		$is_blob = (@is_blob($SIDU['col'][$j]) ? ' onclick="editBlob(\\\''.$id.'\\\')"' : '');
		$jsColNew .= '<td class="blue td'.$j.'"'.@str_replace("'","\"",$disp[$j]).'>';
		if ($is_blob) $jsColNew .= '<input type="hidden" name="'.$id.'" id="'.$id.'" value="'.$v[3].'"/><input type="text" value="'.$v[3].'" size="1" id="blob'.$id.'"'.$is_blob.' style="background:#ddc"/></td>';
		else $jsColNew .= '<input type="text" size="1" name="'.$id.'" id="'.$id.'" value="'.$v[3].'"'.$align.$is_blob.' onchange="document.dataTab.cbox_data_new\'+id+\'.checked=\\\'checked\\\'"/></td>';
		if (!isset($SIDU['pk'])) $pk[]=$j;//no pk table with blob col will be slow here
	}
	echo "\n<table class='grid' id='dataTable'>";
	if (!$sql) echo "\n<tr id='trhide' title='",@lang(105),"' style='opacity:0.6",($SIDU['gridShow'] ? "" : ";display:none"),"'><td class='cbox'></td>$gridShow</tr>";
	echo "\n<tr class='th'><td class='cbox'><input type='checkbox' onclick='checkedAll()'/></td>$colH</tr>";
	if (!$sql) echo "\n<tr id='trgrid' title='",@lang(106),"'",($SIDU['gridShow'] ? "" : " style='display:none'")," class='grey'><td class='cbox'></td>$grid</tr>
		<tr class='gridf' title='",@lang(107)," eg: =12'><td class='cbox'><a href='tab.php?id=$SIDU[0],$SIDU[1],$SIDU[2],$SIDU[3],$SIDU[4]' title='",@lang(108),"'>",@html_img("img/tool-find"),"</a></td>$filter</tr>";
	foreach ($SIDU['data'] as $i=>$row){
		echo "\n<tr id='tr_$i'><td class='cbox'><input type='checkbox' name='cbox_data_$i'/></td>";
		foreach ($row as $j=>$v){
			$align = ($SIDU['col'][$j][8]=='i' ? " style='text-align:right'" : "");
			if (is_null($v)){
				$v='NULL';
				$classNull = " null";
			}else $classNull = "";
			$v8 = @html8($v);
			$id = "data_$i"."_$j";
			$is_blob = (@is_blob($SIDU['col'][$j]) ? " onclick=\"editBlob('$id')\"" : "");
			echo "<td class='td$j$classNull'{$disp[$j]}$align>";
			if ($SIDU['gridMode']){
				if ($is_blob || $sql) echo @nl2br($v8);
				else{
					$v8str = ($v8==='NULL' ? "IS NULL" : "=\\'".@strtr($v8,@array("&#039;"=>"\&#039;\&#039;","\\"=>"\\\\\\\\"))."\\'");
					echo "<a href='#' onclick=\"setv('f$j','$v8str');submitForm('cmd','p1')\">".@nl2br($v8)."</a>";
				}
			}else{
				if ($is_blob) echo "<input type='hidden' name='$id' id='$id' value='$v8'/><input type='text' value='".@substr($v8,0,30)."' size='1' id='blob$id'$is_blob style='background:#ddc",($classNull ? ";color:#888;font-style:italic" : ""),"'/>";
				else echo "<input type='text' size='1'",($classNull ? " class='null'" : "")," name='$id' id='$id' value='$v8' onchange=\"document.dataTab.cbox_data_$i.checked='checked'\"$align/>";
			}
			if (!$sql && @in_array($j,$pk)) echo "<input type='hidden' name='pkV[$i][$j]' value='$v8'/>";
			echo "</td>";
		}
		echo "</tr>";
	}
	echo "\n</table>";
	$arrH = @array('cmd','sidu7','sidu8','sidu9','showCol','hideCol');
	foreach ($arrH as $v) echo @html_form("hidden",$v);
	echo "<input type='hidden' id='gridShow' name='gridShow' value='$SIDU[gridShow]'/><input type='hidden' id='gridMode' name='gridMode' value='$SIDU[gridMode]'/>";
	echo "</form>
<div id='blobDiv' style='display:none;width:99%;max-width:700px'>
<input type='button' value='",@lang(109),"' onclick='editBlobSave()'/><input type='button' value='",@lang(110),"' onclick=\"showHide('blobDiv',-1)\"/><input type='hidden' id='blobTxtID'/>
<br/><textarea id='blobTxt' style='width:99%;height:280px'></textarea>
</div>
<iframe name='hiddenfr' src='#' style='width:600px;height:200px;display:none'></iframe>
<script type='text/javascript'>
window.onload = function(){".$jsStr."}";
	if (!$sql) echo "
function addRow(){
	var his = new Date();
	var id = his.getHours()+his.getMinutes()+his.getSeconds();
	var row = document.getElementById('dataTable').insertRow(4);
	row.innerHTML='<td class=\"cbox\"><input type=\"checkbox\" name=\"cbox_data_new'+id+'\"></td>$jsColNew';
}";
	echo "</script>";
}
function tab_tool()
{
	global $SIDU;
	$obj = @array("r"=>@lang(111),"v"=>@lang(112),"S"=>@lang(113),"f"=>@lang(114));
	if (!$obj[$SIDU[3]]) return;
	$objcmd = ($_POST['objcmd'] ? $_POST['objcmd'] : $_GET['objcmd']);
	$url = @substr($_SERVER['SCRIPT_NAME'],-8);
	if ($url=='/tab.php'){
		echo "<form action='tab.php?id=$SIDU[0],$SIDU[1],$SIDU[2],$SIDU[3],$SIDU[4],$SIDU[5],$SIDU[6]' method='post'>",@html_form("hidden","objs[0]",$SIDU[4]);
		$objs[0]=$SIDU[4];
	}else $objs = $_POST['objs'];
	$txt = ($url=='/tab.php' ? "{$obj[$SIDU[3]]}: $SIDU[4]" : $obj[$SIDU[3]]);
	echo "<div id='objTool' style='margin:5px",($objcmd ? "" : ";display:none"),"'><div class='box'>",
		@html_img("img/tool-close.gif",@lang(115),"class='right' onclick=\"showHide('objTool')\""),
		"<p class='dot'>",@html_form("submit","objcmd","Drop",76,"","onclick=\"return confirm('".@lang(117,$txt)."?')\"");
	if ($SIDU[3]=='r'){
		echo @html_form("submit","objcmd","Empty",77,"","onclick=\"return confirm('".@lang(119,$txt)."?')\"");
		if ($SIDU['eng']<>'sl') echo @html_form("submit","objcmd","Analyze",76);
		if ($SIDU['eng']=='my') echo @html_form("submit","objcmd","Check",73),@html_form("submit","objcmd","Optimize",84),@html_form("submit","objcmd","Repair",73),@html_form("submit","objcmd","Change Engine to MyISAM",230,"","onclick=\"return confirm('".@lang(116,@array("MyISAM",$txt))."?')\""),@html_form("submit","objcmd","Change Engine to InnoDB",230,"","onclick=\"return confirm('".@lang(116,@array("InnoDB",$txt))."?')\"");
		else echo @html_form("submit","objcmd","Vacuum");
		if ($SIDU['eng']=='pg') echo @html_form("submit","objcmd","Re-index"),@html_form("submit","objcmd","Truncate Cascade",154,"","onclick=\"return confirm('".@lang(118,$txt)."?')\"");
	}
	if ($SIDU['eng']=='pg') echo @html_form("submit","objcmd","Drop Cascade",154,"","onclick=\"return confirm('".@lang(120,$txt)."?')\"");
	echo "</p>";
	if ($objcmd){
		if (!$objs[0]) echo "<br/><p class='err'>",@lang(121,$obj[$SIDU[3]]),"</p>";
		else @tab_tool_save($SIDU,$objcmd,$objs);
	}
	echo "</div></div>";
	if ($url=='/tab.php') echo "</form>";
}
function tab_tool_save($SIDU,$cmd,$objs)
{//note: do not translate cmd to other lang
	$obj = @array("r"=>"TABLE","v"=>"VIEW","S"=>"SEQUENCE","f"=>"FUNCTION");
	$obj = $obj[$SIDU[3]];
	if (@in_array($cmd,@array("Drop","Analyze","Check","Optimize","Repair"))) $CMD = @strtoupper($cmd)." $obj";
	elseif ($cmd=='Empty') $CMD = ($SIDU['eng']=='sl' ? "DELETE FROM" : "TRUNCATE TABLE");
	elseif ($cmd=='Vacuum') $CMD = "VACUUM";
	elseif ($cmd=='Re-index') $CMD = "REINDEX TABLE";
	elseif ($cmd=='Drop Cascade'){
		$CMD = "DROP $obj"; $CMD2 = " CASCADE";
	}elseif ($cmd=='Truncate Cascade'){
		$CMD = "TRUNCATE $obj"; $CMD2 = " CASCADE";
	}
	if ($cmd=='Analyze' && $SIDU['eng']=='pg') $CMD = 'ANALYZE';
	if (@substr($cmd,0,17)=="Change Engine to "){
		$engTo = @substr($cmd,17);
		$res = @tm("SQL","SHOW TABLE STATUS FROM `$SIDU[1]`");
		while ($row = @mysql_fetch_array($res)){
			if ($row['Engine']<>$engTo) $tabs[]=$row['Name'];
		}
		foreach ($objs as $v){
			if (@in_array($v,$tabs)) @tab_tool_run_sql("ALTER TABLE `$SIDU[1]`.`$v` ENGINE = $engTo;");
		}
	}elseif ($CMD=="DROP FUNCTION"){
		foreach ($objs as $v) @tab_tool_run_sql("$CMD $v$CMD2;");
	}else{
		foreach ($objs as $v) @tab_tool_run_sql("$CMD ".@goodname($v)."$CMD2;");
	}
}
function tab_tool_run_sql($sql)
{
	@tm("SQL",$sql);
	echo "<br/>$sql";
	$err = @sidu_err(1);
	if ($err) echo "<br/><span class='red'>$err</span>";
	else echo "<br/><span class='green'>OK</span>";
}
function sidu_sl_pk($tab)
{
	$res = @tm("SQL","pragma table_info($tab)");
	while ($row=@sqlite_fetch_array($res)){
		if ($row['pk']) $pk[]=$row['name'];
	}
	return @implode(',',$pk);
}
//sidu page maker
function uppe()
{
	global $SIDU;
	$url = @array_pop(@explode("/",$_SERVER['SCRIPT_NAME']));
	if ($url=='db.php'){
		$title = ($SIDU[4]<>'' ? "$SIDU[4] « ": "").($SIDU[2] ? "$SIDU[2] « " : "").$SIDU[1];
		$ico = ($SIDU[4] || $SIDU[3] ? "z$SIDU[3]" : ($SIDU[2] ? "xsch" : "xdb"));
	}elseif ($url=='tab.php'){
		$title = $SIDU[4].($SIDU[2] ? " « $SIDU[2]" : "")." « $SIDU[1]";
		$ico = "x$SIDU[3]";
	}else $ico = "sidu";
	if (!$title) $title = "SIDU 3 Database Web GUI: MySQL + Postgres + SQLite";
	echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
<link rel='shortcut icon' href='img/$ico.png'/>
<title>$title</title>
<link rel='stylesheet' media='all' type='text/css' href='css.css'/>";
	if ($SIDU['page']['xJS']) echo "
<script language='javascript' type='text/javascript' src='x.js'></script>
<script language='javascript' type='text/javascript' src='xenabledrag.js'></script>";
	echo "
<script language='javascript' type='text/javascript' src='css.js'></script>
</head>
<body>";
	if ($SIDU['page']['nav']){
		echo "<div class='nav1'>";
		if ($SIDU['page']['nav']=='defa') echo @html_img('img/sidu')," <b>SIDU 3.2</b> Database Web GUI ",@html_img('img/tool-web')," <b>sidu.sf.net</b>";
		else @navi();
		echo "</div><div class='nav2'>&nbsp;</div>";
	}
}
function down()
{
	echo "</body></html>";
}
function navi_obj($arr,$is_db)
{//db.php not use this func yet
	global $SIDU;
	if (!$arr[1]) return;
	echo $SIDU['sep'];
	if ($arr[4]<>'' && !$is_db) echo "<a href='tab.php?id=$arr[0],$arr[1],$arr[2],$arr[3],$arr[4]&#38;desc=1' title='",@lang(122),"' onclick=\"xwin(this.href,720);return false\">",@html_img("img/x$arr[3]"),"</a> ";
	elseif ($arr[4]<>'') echo @html_img("img/x$arr[3]")," ";
	else echo @html_img('img/xdb')," ";
	echo "<a href='db.php?id=$arr[0],$arr[1]'>$arr[1]</a>";
	if ($arr[2]<>''){
		if ($arr[2]) echo " » <a href='db.php?id=$arr[0],$arr[1],$arr[2]",($arr[3] ? ",$arr[3]" : ""),"'>$arr[2]</a>";
		if ($arr[4]<>''){
			$tabs=@table2tabs($arr[4],$SIDU['page']['tree']);
			if ($tabs<>'') echo " » <a href='db.php?id=$arr[0],$arr[1],$arr[2],$arr[3],$tabs'>$tabs</a>";
			echo " » <a href='tab.php?id=$arr[0],$arr[1],$arr[2],$arr[3],$arr[4]' title='",@lang(123),"'>$arr[4]</a>";
		}
	}
}
?>
