<?php
@include_once "inc.page.php";
@uppe();
@main($txt,$cmd);
@down();
	
function main($txt,$cmd)
{
	global $SIDU;
	$eng=$SIDU['eng'];
	$txt = @strip($txt,1,0,1);
	if ($txt && $cmd) $err = @save_data($SIDU,$txt,$eng);
	if (!$txt) $txt = "CREATE TABLE ".($eng=='my' ? "`$SIDU[1]`.`table_name`" : ($eng=='pg' ? "\"$SIDU[2]\".\"table_name\"" : "table_name"))
		."(\ncolname int".($eng=='my' ? "(8)" : "").($eng=='sl' ? "" : " NOT NULL DEFAULT 0")." PRIMARY KEY,\n\n)";
	echo "<div class='web'><p class='b dot'>",@lang(4101)," <span class='red'>",($eng=='my' ? $SIDU[1] : "$SIDU[1].$SIDU[2]"),"</span></p>";
	if ($err) echo "<p class='err'>$err</p>";
	echo "<form action='tab-new.php?id=$SIDU[0],$SIDU[1],$SIDU[2],$SIDU[3],$SIDU[4],$SIDU[5],$SIDU[6]' method='post' name='myform'>
	<table><tr><td valign='top'>".@html_form("textarea","txt",$txt,420,320,"spellcheck='false' class='box'")
	."<br /><br />".@html_form("submit","cmd",@lang(4102))."</td><td valign='top' style='padding-left:10px'>";
	$str = "9|0|smallint|smallint(5)
0|1|32768|smallint(5) unsigned NOT NULL DEFAULT 0
1|0|int|int(9)
0|1|2,147,483,647|int(9) unsigned NOT NULL DEFAULT 0
1|0|numeric|numeric(7,2)
0|1|(7,2)|numeric(7,2) unsigned NOT NULL DEFAULT 0.00
2|0|char|char(255)
0|1|255|char(255) NOT NULL DEFAULT \'\'
0|0|binary|char(255) binary NOT NULL DEFAULT \'\'
1|0|varchar|varchar(255)
0|1|255|varchar(255) NOT NULL DEFAULT \'\'
0|0|binary|varchar(255) binary NOT NULL DEFAULT \'\'
1|0|text|text
0|1|65535|text NOT NULL DEFAULT \'\'
2|0|date|date
0|1|YYYY-MM-DD|date NOT NULL DEFAULT \'0000-00-00\'
1|0|timestamp|timestamp
0|1|YmdHis|timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\'
0|0|now|timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
2|0|auto|auto_increment
0|1|!null|NOT NULL
0|0|PK|NOT NULL auto_increment PRIMARY KEY
1|0|PK|PRIMARY KEY
0|1|PK(a)|PRIMARY KEY (col1,col2)
0|0|UK|UNIQUE uk (col1,col2)
0|1|idx|INDEX idx (col1,col2)
2|0|MyISAM|ENGINE = MyISAM
0|1|InnoDB|ENGINE = InnoDB";
	if ($eng=='pg') $str = @strtr($str,@array("smallint(5)"=>"smallint","int(9)"=>"int"," DEFAULT \'0000-00-00\'"=>""," DEFAULT \'0000-00-00 00:00:00\'"=>"","CURRENT_TIMESTAMP"=>"now()","auto|auto_increment"=>"serial|serial","NOT NULL auto_increment"=>"serial NOT NULL","0|0|binary|char(255) binary NOT NULL DEFAULT \'\'"=>"","0|0|binary|varchar(255) binary NOT NULL DEFAULT \'\'"=>"","MyISAM|ENGINE = MyISAM"=>"With OID|WITH (OIDS=TRUE)","0|1|InnoDB|ENGINE = InnoDB"=>""," unsigned"=>"","PRIMARY KEY ("=>"CONSTRAINT pk PRIMARY KEY (","UNIQUE uk ("=>"CONSTRAINT uk UNIQUE (","idx|INDEX idx (col1,col2)"=>"FK|CONSTRAINT fk FOREINGN KEY (col) REFERENCES tab(pk) MATCH SIMPLE\\n\\tON UPDATE NO ACTION ON DELETE NO ACTION"));
	elseif ($eng=='sl') $str = "9|0|int|int,\\n
0|1|PK|int PRIMARY KEY
1|0|text|text,\\n
1|0|real|real,\\n";
	$arr = @explode("\n",$str);
	foreach ($arr as $v) @main_add_txt(@trim($v));
	if ($eng=='my') @main_add_txt("2|0|enum(Y,N)|enum(\'Y\',\'N\') NOT NULL DEFAULT \'Y\',\\n");
	echo "</td></tr></table></form></div>";
}
function main_add_txt($str)
{
	if (!$str) return;
	$arr = @explode("|",$str,4);
	if ($arr[0]=="0") echo " ";
	elseif ($arr[0]=="1") echo "<br/>";
	elseif ($arr[0]=="2") echo "<br/><br/>";
	echo "<a href='#'".($arr[1] ? " class='red'" : "")." onclick=\"replaceTxt(' $arr[3]".($arr[0]=="0" ? ",\\n" : "")."',document.myform.txt)\" title=\"".@stripslashes(@str_replace(",\\n","",$arr[3]))."\">$arr[2]</a>";
}
function save_data($SIDU,$txt,$eng)
{
	$txt = @trim($txt);
	if (@substr($txt,-1)==')'){
		$txt = @trim(@substr($txt,0,-1));
		if (@substr($txt,-1)==',') $txt = @substr($txt,0,-1);
		$txt .= ')';
	}
	$res = @tm("SQL",$txt);
	$err = @sidu_err(1);
	if ($err) return $err;
	echo @html_js("Goto('menu','menu.php?id=$SIDU[0]',1);Goto('main','db.php?id=$SIDU[0],$SIDU[1],$SIDU[2],$SIDU[3],$SIDU[4],$SIDU[5],$SIDU[6]',1);self.close()");
	exit;
}
?>
