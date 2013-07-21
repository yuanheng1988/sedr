<?php
@include "inc.page.php";
@main($conn,$cmd);

function main($conn,$cmd)
{
	if ($cmd=='quit'){
		@setcookie(@siduMD5('SIDUCONN'),'',-1);
		@setcookie('SIDUSQL','',-1);
	}elseif ($cmd=='close'){
		$goto=@close_sidu_conn($_GET['id']);
		if ($goto) return @header("Location: ./?id=$goto");
	}elseif ($cmd==@lang(1101)){
		if (@substr($_FILES['fconn']['type'],0,4)=='text' && $_FILES['fconn']['size'] && !$_FILES['fconn']['error'])
			$err = @init_conn($conn,@file_get_contents($_FILES['fconn']['tmp_name']),$arrConn,$cmd);
		else $err = @lang(1102,1);
	}elseif ($cmd==@lang(1103) || $cmd==@lang(1104)){
		$err = @test_conn($conn);
		if ($cmd==@lang(1104) && !$err) return @main_conn($conn);
		elseif (!$err) $err = "OK";
	}
	if ($cmd<>"Open" && $conn['txt']) $err .= @init_conn($conn,@dec65($conn['txt'],1),$arrConn,$cmd);
	elseif ((!$cmd || $cmd=='quit' || $cmd=='close') && !$conn['txt'] && $_COOKIE[@siduMD5('SIDUconnS')]) $err .= @init_conn($conn,@dec65($_COOKIE[@siduMD5('SIDUconnS')],1),$arrConn,@lang(1101));
	@uppe();
	@main_form($conn,$arrConn,$err,$cmd);
	@down();
}
function main_form($conn,$arrConn,$err,$cmd)
{
	global $SIDU;
	if (!$conn['lang']) $conn['lang']=$SIDU['page']['lang'];
	if (!$conn['lang']) $conn['lang']='en';
	if (!$conn['host']) $conn['host']='localhost';
	if ($conn['eng']<>'pg' && $conn['eng']<>'sl') $conn['eng']='my';
	if (!$conn['user']) $conn['user']=($conn['eng']=='my' ? 'root' : 'postgres');
	elseif (!$cmd){
		if ($conn['user']=='root' && $conn['eng']=='pg') $conn['user']='postgres';
		elseif ($conn['user']=='postgres' && $conn['eng']=='my') $conn['user']='root';
	}
//	$arr_lang = @array("cn"=>"中文","de"=>"Deutsch","en"=>"English","es"=>"Espanol","fr"=>"Francais","it"=>"Italiano");
	$arr_lang = @array("en"=>"English","cn"=>"中文");
	echo "<form name='connf' action='conn.php' method='post' enctype='multipart/form-data'>
<div align='center' class='web'><table class='box'><tr><td align='left'>
<div class='right'>",@html_form("select","conn[lang]",$conn['lang'],0,"","onchange='connf.submit()'",$arr_lang),"</div>
<p class='clear dot b'><br/>",@html_img("img/tool-pc","","class='vm'")," ",@lang(1105),":</p>";
	if ($err=='OK') echo "<p class='ok'>",@lang(1106),"</p>";
	elseif ($err) echo "<p class='err' style='width:400px'>$err</p>";
	echo "<table>";
	if ($conn['txt']) echo "<tr><td>",@lang(1107),"</td><td>",@html_form("select","conn[id]",$conn['id'],304,"","onchange='connf.submit()'",$arrConn),"</td></tr>";
	echo "<tr><td>",@lang(1108),"</td><td>";
	$arr_eng = @array("my"=>"MySQL","pg"=>"Postgres","sl"=>"SQLite");
	if ($conn['txt'] && $conn['id']) echo "<b class='blue'>{$arr_eng[$conn[eng]]}</b>",@html_form("hidden","conn[eng]",$conn['eng']);
	else echo @html_form("radio","conn[eng]",$conn['eng']," &nbsp; ","","onclick='connf.submit()'",$arr_eng);
	echo "</td></tr>";
	if ($conn['eng']<>'sl') echo "<tr><td>",@lang(1109)," <b class='red'>*</b></td><td>",@html_form("text","conn[host]",$conn['host'],300),"</td></tr>
<tr><td>",@lang(1110)," <b class='red'>*</b></td><td>",@html_form("text","conn[user]",$conn['user'],300),"</td></tr>
<tr><td>",@lang(1111),"</td><td>",@html_form("password","conn[pass]",$conn['pass'],300),"</td></tr>
<tr><td>",@lang(1112),"</td><td>",@html_form("text","conn[port]",$conn['port'],60)," &nbsp; <input type='checkbox' name='conn[penc]' value='1'",($conn['penc'] ? " checked='checked'" : ""),"/> ",@lang(1113),"</td></tr>";
	echo "<tr><td>",@lang(1114),($conn['eng']=='sl' ? " <b class='red'>*</b>" : ""),"</td><td>",@html_form("text","conn[dbs]",$conn['dbs'],300),"</td></tr>
<tr><td></td><td>eg: db1<b class='red'>;</b> db2<br/>",($conn['eng']=='sl' ? @lang(1115) : @lang(1116)),"</td></tr>";
	if ($conn['eng']<>'pg') echo "<tr><td>",@lang(1117),"</td><td>",@html_form("select","conn[char]",$conn['char'],304,"","",@get_conn_char()),"</td></tr>";
	echo "</table><p align='center' class='dot'><br/>",@html_form("submit","cmd",@lang(1104))," ",@html_form("submit","cmd",@lang(1103))," <input type='button' value='",@lang(1118),"' onclick=\"showHide('savedconn')\"/><br/>&nbsp;</p>
<div id='savedconn'",($cmd==@lang(1119) ? "" : " style='display:none'"),">
<p>",@lang(1120),":<br/><input type='file' name='fconn'/>",@html_form("submit","cmd",@lang(1101)),"</p>
<p class='dot'>",@lang(1121),": <a href='sidu-conn.txt' target='_blank'>sidu-conn.txt</a><br/>&nbsp;</p>
<p>",@html_img("img/tool-sys","","class='vm'")," ",@html_form("password","pstr","",170),@html_form("submit","cmd",@lang(1119)),"</p>
<p>",@lang(1122),":<br/><span style='font-size:12px' class='grey'>",($cmd==@lang(1119) ? @enc65($_POST['pstr']) : ""),"</span></p>
</div>",@html_form("hidden","conn[txt]",$conn['txt']),"</td></tr></table></div></form>";
}
function init_conn_cut($txt,&$opt,$opara)
{
	$CUT1 = "SIDU Saved Preference # do not change this line";
	$CUT2 = "--------------------- # do not change this line";
	$arr = @explode($CUT1,@trim($txt),2);
	$arr[0]=@trim($arr[0]);//conn
	$conf=@trim($arr[1]);//configure
	if (!$arr[0]) return @lang(1123,2);
	$arr0 = @explode($CUT2,$arr[0]);
	$paras = @array('eng','host','user','pass','penc','port','dbs','char');
	foreach ($arr0 as $v){
		$arr1 = @explode("\n",$v);
		unset($arr3);
		foreach ($arr1 as $line){
			$line = @trim($line);
			if ($line){
				$arr2 = @explode("=",$line,2);
				$arr2[0]=@trim($arr2[0]);
				if (@in_array($arr2[0],$paras)) $arr3[$arr2[0]]=@trim($arr2[1]);
			}
		}
		if ($arr3['eng']=='my' || $arr3['eng']=='pg' || $arr3['eng']=='sl') $res[]=$arr3;
	}
	if (!$opt[0]) return $res;
	//cut opt
	unset($opt[0]);
	$res = $arr[0];
	$arr = @explode("\n",$conf);
	foreach ($arr as $line){
		$line = @trim($line);
		$arr2 = @explode("=",$line,2);
		$arr2[0]=@trim($arr2[0]);
		if (@in_array($arr2[0],$opara)){
			$arr2[1]=@trim($arr2[1]);
			$arr3=@explode("#",$arr2[1],2);
			$arr4=@explode("/",@trim($arr3[0]),2);
			$arr3=@explode(";",@trim($arr4[0]),2);
			$arr2[1]=@trim($arr3[0]);
			$opt[$arr2[0]]=$arr2[1];
		}
	}
	return $res;
}
function init_conn(&$conn,$txt,&$arrConn,$cmd)
{
	$res = @init_conn_cut($txt);
	if (!$res) return @lang(1124,3);
	$arrConn[0] = @lang(1125);
	foreach ($res as $v) $arrConn[]=($v['eng']=='sl' ? "SQLite" : @strtoupper($v['eng']).": $v[user]@$v[host]");
	$hold=$conn;
	$id = ($cmd==@lang(1101) ? 1 : $conn['id']);
	$conn=$res[$id-1];
	$conn['id']=$id;
	$conn['txt']=@enc65($txt,1);
	if ($id<1) $conn['eng']=$hold['eng'];
	if ($cmd==@lang(1103)){
		$conn['pass']=$hold['pass'];
		$conn['penc']=$hold['penc'];
	}
	$conn['lang']=$hold['lang'];
}
function test_conn(&$conn)
{
	$conn = @strip($conn,1,0,1);
	$conn['port']=@ceil($conn['port']);
	if ($conn['port']<1) $conn['port']='';
	if ($conn['penc']) $conn['penc']=1;
	$conn['dbs']=@strtr($conn['dbs'],@array(" "=>"",","=>";","%%"=>"%","%%%"=>"%","%%%%"=>"%"));//if you keep play with % i do not care
	$dbs = @explode(";",$conn['dbs']);
	foreach ($dbs as $v){
		$v = @trim($v);
		if ($v && $v<>'%' && $v<>'%%' && $v<>'%%%' && $v<>'%%%%') $db[]=$v;
	}
	$conn['dbs']=@implode(";",$db);
	$pass = ($conn['penc'] ? @dec65($conn['pass']) : $conn['pass']);
	if ($conn['eng']=="my"){
		$res = @mysql_connect($conn['host'].($conn['port'] && $conn['port']<>"3306" ? ":$conn[port]" : ""),$conn['user'],$pass);
		if (!$res) $err = @mysql_error();
	}elseif ($conn['eng']=='pg'){
		$res = @pg_connect("host=$conn[host]".($db[0] ? " dbname=$db[0]" : "")." user=$conn[user]".($pass<>'' ? " password=$pass" : "").($conn['port'] && $conn['port']<>"5432" ? " port=$conn[port]" : ""));
		if (!$res) $err = @lang(1126);
	}elseif (!$conn['dbs']) $err = @lang(1127);
	else{
		foreach ($db as $v){
			@sqlite_open($v,0666,$err0);
			if ($err0) $err .= "$err0<br/>";
		}
	}
	return $err;
}
function main_conn($conn)
{
	global $SIDU;
	$cook = @explode('@',@dec65($_COOKIE[@siduMD5('SIDUCONN')],1));
	foreach ($cook as $v){
		$arr = @explode('#',$v,2);
		if ($id<$arr[0]) $id=$arr[0];
	}
	$id++;
	//0id 1eng[my|pg] 2host 3user 4pass 5port 6dbs 7save 8charset
	$cook[$id] = "$id#$conn[eng]#$conn[host]#$conn[user]#".@enc65($conn['pass'],1)."#$conn[port]#$conn[dbs]#$conn[save]#$conn[char]";
	@setcookie(@siduMD5('SIDUCONN'),@enc65(@implode('@',$cook),1));
	$mood=@explode(".",$_COOKIE['SIDUMODE']);
	if ($conn['txt']){//save conn in cookie
		$opt[0]=1;
		$opara = @array('lang','gridMode','pgSize','tree','sortObj','sortData','menuTextSQL','menuText','his','hisErr','hisSQL','hisData','dataEasy','oid');
		$res = @init_conn_cut(@dec65($conn['txt'],1),$opt,$opara);
		@setcookie(@siduMD5('SIDUconnS'),@enc65($res,1));
		foreach ($opara as $i=>$k) $mood[$i]=(isset($opt[$k]) ? $opt[$k] : "{$mood[$i]}");
		$mood[0]=$conn['lang'];
		@setcookie('SIDUMODE',@implode(".",$mood),@time()+311040000);
	}elseif ($mood[0]<>$conn['lang']){
		$mood[0]=$conn['lang'];
		@setcookie('SIDUMODE',@implode(".",$mood),@time()+311040000);
	}
	@header("Location: ./?id=$id");
}
function get_conn_char()
{
	return @array(0=>@lang(1128),
'armscii8'=>'armscii8: ARMSCII-8 Armenian',
'ascii'=>'ascii: US ASCII',
'big5'=>'big5: Big5 繁体中文',
'binary'=>'binary: Binary pseudo charset',
'cp1250'=>'cp1250: Windows Central European',
'cp1251'=>'cp1251: Windows Cyrillic',
'cp1256'=>'cp1256: Windows Arabic',
'cp1257'=>'cp1257: Windows Baltic',
'cp850'=>'cp850: DOS West European',
'cp852'=>'cp852: DOS Central European',
'cp866'=>'cp866: DOS Russian',
'cp932'=>'cp932: SJIS for Windows Japanese',
'dec8'=>'dec8: DEC West European',
'eucjpms'=>'eucjpms: UJIS for Windows Japanese',
'euckr'=>'euckr: EUC-KR Korean',
'gb2312'=>'gb2312: GB2312 简体中文',
'gbk'=>'gbk: GBK 简体中文',
'geostd8'=>'geostd8: GEOSTD8 Georgian',
'greek'=>'greek: ISO 8859-7 Greek',
'hebrew'=>'hebrew: ISO 8859-8 Hebrew',
'hp8'=>'hp8: HP West European',
'keybcs2'=>'keybcs2: DOS Kamenicky Czech-Slovak',
'koi8r'=>'koi8r: KOI8-R Relcom Russian',
'koi8u'=>'koi8u: KOI8-U Ukrainian',
'latin1'=>'latin1: cp1252 West European',
'latin2'=>'latin2: ISO 8859-2 Central European',
'latin5'=>'latin5: ISO 8859-9 Turkish',
'latin7'=>'latin7: ISO 8859-13 Baltic',
'macce'=>'macce: Mac Central European',
'macroman'=>'macroman: Mac West European',
'sjis'=>'sjis: Shift-JIS Japanese',
'swe7'=>'swe7: 7bit Swedish',
'tis620'=>'tis620: TIS620 Thai',
'ucs2'=>'ucs2: UCS-2 Unicode',
'ujis'=>'ujis: EUC-JP Japanese',
'utf8'=>'utf8: UTF-8 Unicode');
}
?>
