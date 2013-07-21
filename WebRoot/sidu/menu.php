<?php
@include "inc.page.php";
@uppe();
@main();
@down();

function main()
{
	global $SIDU;
	$conn = $SIDU['conn'][$SIDU[0]];
	$res = @explode(";",@trim($conn[6]));
	foreach ($res as $v){
		$v = @trim($v);
		if ($v) $dbs[] = ($SIDU['eng']=='sl' ? $v : "$v%");
	}
	if (!isset($dbs)) $dbs[] = "%";
	$conn[6] = $dbs;
	if ($conn[1]=="my") $arr = @menu_my($conn,$SIDU['page']['tree']);
	elseif ($conn[1]=="pg") $arr = @menu_pg($conn,$SIDU['page']['tree']);
	else $arr = @menu_sl($conn,$SIDU['page']['tree']);
	@menu_tree_cout($arr,$conn);
}
function menu_my($conn,$tree)
{
	foreach ($conn[6] as $db){
		$res = @tm("SQL","SHOW DATABASES".($db<>'%' ? " LIKE '$db'" : ""));
		while ($row = @mysql_fetch_row($res)){
			$arr[$row[0]][0]['r'] = array();//table
			$arr[$row[0]][0]['r'] = array();//table
			if ($conn[6][0]<>'%') $where[] = "TABLE_SCHEMA='$row[0]'";
		}
	}
	if (!isset($arr)) return;
	if (isset($where)) $where = " WHERE ".@implode(' OR ',$where);;
	$res = @tm("SQL","SELECT TABLE_SCHEMA,TABLE_NAME,TABLE_TYPE FROM information_schema.TABLES$where");
	while ($row = @mysql_fetch_row($res)){
		if ($row[2]<>'VIEW') @menu_tree_init($arr[$row[0]][0]['r'],$row[1],$tree);
		else @menu_tree_init($arr[$row[0]][0]['v'],$row[1],$tree);
	}
	return $arr;
}
function menu_pg($conn,$tree)
{
	if ($conn[6][0]<>'%'){
		foreach ($conn[6] as $db) $where .= " OR datname LIKE '$db'";
		$where = "\nAND (".@substr($where,4).")";
	}
	$res = @tm("SQL","SELECT datname FROM pg_database WHERE datistemplate=false$where ORDER BY 1");
	while ($row = @pg_fetch_row($res)) $dbs[] = $row[0];
	if (!$dbs[0] && $conn[6][0]<>'%'){
		foreach ($conn[6] as $db) $dbs[] = @substr($db,0,-1);
	}
	foreach ($dbs as $db){
		@db_conn($conn,$db);
		$res2 = @tm("SQL","SELECT oid,nspname FROM pg_namespace\nWHERE nspname NOT LIKE 'pg_toast%' AND nspname NOT LIKE 'pg_temp%' ORDER BY 2");
		while ($row2 = @pg_fetch_row($res2)){
			$res3 = @tm("SQL","SELECT substr(proname,1,2),count(*) FROM pg_proc\nWHERE pronamespace=$row2[0] GROUP BY 1 ORDER BY 1");
			$arr[$db][$row2[1]]['f'] = array();//func
			while ($row3 = @pg_fetch_row($res3)) $arr[$db][$row2[1]]['f'][$row3[0]] = $row3[1];
			$res3 = @tm("SQL","SELECT count(*) FROM pg_class WHERE relnamespace=$row2[0] AND relkind='S'");
			$row3 = @pg_fetch_row($res3);
			$arr[$db][$row2[1]]['S'] = $row3[0];//Seq
			$arr[$db][$row2[1]]['r'] = array();//table
			$arr[$db][$row2[1]]['v'] = array();//view
			$res3 = @tm("SQL","SELECT relname,relkind FROM pg_class\nWHERE relnamespace=$row2[0] AND (relkind='r' OR relkind='v') ORDER BY 1");
			while ($row3 = @pg_fetch_row($res3)) @menu_tree_init($arr[$db][$row2[1]][$row3[1]],$row3[0],$tree);
		}
	}
	return $arr;
	//operator temp parser dict domain conversion aggregate -- not available at the moment
}
function menu_sl($conn,$tree)
{
	global $SIDU;
	foreach ($conn[6] as $db){
		$SIDU['dbL']=@db_conn($conn,$db);
		$arr[$db][0]['r']=array();
		$res = @tm("SQL","SELECT type,name FROM sqlite_master ORDER BY 2");
		while ($row = @sqlite_fetch_array($res,SQLITE_NUM)){
			if ($row[0]=='table') @menu_tree_init($arr[$db][0]['r'],$row[1],$tree);
			elseif ($row[0]=='view') @menu_tree_init($arr[$db][0]['v'],$row[1],$tree);
		}
		@menu_tree_init($arr[$db][0]['r'],"sqlite_master",$tree);
	}
	return $arr;
}
function menu_tree_init(&$arr,$str,$tree)
{
	$tabs=@table2tabs($str,$tree);
	if ($tabs=='') $tabs = $str;
	$arr[$tabs][] = $str;
}
function menu_tree_cout($arr,$conn)
{
	echo "<div id='menu'>\n<div><a id='menuRoot' title='",@lang(2401),"' onclick='showAll()'>",@html_img("img/eng-$conn[1]"),"</a> <a href='db.php?id=$conn[0]' target='main'>",($conn[1]=="sl" ? "SQLite" : "$conn[3]@$conn[2]"),"</a></div>";
	$arr_typ = @array('r'=>@lang(2404),'v'=>@lang(2405),'f'=>@lang(2406),'p'=>@lang(2407),'t'=>@lang(2408),'S'=>@lang(2409));
	$x=0;
	$numX=count($arr);
	foreach ($arr as $db=>$arr2){
		$x++;
		$allNode[] = $x;
		$is_lastX=($x==$numX ? 'last' : '');
		$strX = @html_img("img/trline$is_lastX.gif");
		$numY=count($arr2);
		echo "\n<div>",@html_img("img/tr$is_lastX.gif","","id='t$x' onclick='showHideTree($x)'"),@html_img("img/xdb","DB: $db"),
			" <a href='db.php?id=$conn[0],$db' target='main'>$db</a>",($numY && $conn[1]=='pg' ? " ($numY)" : ""),"</div>\n<div id='p$x' style='display:none'>";
		$y=0;
		foreach ($arr2 as $sch=>$arr3){
			$y++;
			$is_lastY=($y==$numY ? 'last' : '');
			$numZ=count($arr3);
			if ($conn[1]=='pg'){
				$allNode[] = "$x.$y";
				$strY = @html_img("img/trline$is_lastY.gif");
				echo "\n\t<div>$strX",@html_img("img/tr$is_lastY.gif","","id='t$x.$y' onclick='showHideTree($x.$y)'"),@html_img("img/xsch","Sch: $sch"),
					" <a href='db.php?id=$conn[0],$db,$sch' target='main'>$sch</a></div>\n\t<div id='p$x.$y' style='display:none'>";
			}
			$z=0;
			foreach ($arr3 as $typ=>$arr4){
				$z++;
				$is_lastZ=($z==$numZ ? 'last' : '');
				$strZ = @html_img("img/trline$is_lastZ.gif");
				$numZZ=($typ=='S' ? 0 : count($arr4));
				if ($numZZ) $allNode[] = "$x.$y.$z";
				if ($typ=='S') $numZZsum=$arr4;
				else{
					$numZZsum=0;
					foreach ($arr4 as $arr5) $numZZsum += ($typ=='f' ? $arr5 : count($arr5));
				}
				echo "\n\t\t<div>$strX$strY",@html_img("img/tr".($numZZ ? "" : "join")."$is_lastZ.gif","",($numZZ ? "id='t$x.$y.$z' onclick=\"showHideTree('$x.$y.$z')\"" : "")),
					@html_img("img/z$typ")," <a href='db.php?id=$conn[0],$db,$sch,$typ' target='main'>{$arr_typ[$typ]}</a>",($numZZsum ? " ($numZZsum)" : ""),"</div>\n\t\t<div id='p$x.$y.$z' style='display:none'>";
				$zz=0;
				foreach ($arr4 as $tab=>$arr5){
					$zz++;
					$is_lastZZ=($zz==$numZZ ? 'last' : '');
					$strZZ = @html_img("img/trline$is_lastZZ.gif");
					$numZY=count($arr5);
					if ($typ=='f') echo "\n\t\t\t<div>$strX$strY$strZ",@html_img("img/trjoin$is_lastZZ.gif"),@html_img("img/xf")," <a href='db.php?id=$conn[0],$db,$sch,$typ,$tab' target='main'>$tab</a>",($arr5 ? " ($arr5)" : ""),"</div>";
					elseif ($numZY>1 && $numZZ>1){
						$allNode[] = "$x.$y.$z.$zz";
						echo "\n\t\t\t<div>$strX$strY$strZ",@html_img("img/tr$is_lastZZ.gif","","id='t$x.$y.$z.$zz' onclick=\"showHideTree('$x.$y.$z.$zz')\""),@html_img("img/xf.gif"),
							" <a href='db.php?id=$conn[0],$db,$sch,$typ,$tab' target='main'>$tab</a>",($numZY ? " ($numZY)" : ""),"</div>\n\t\t\t<div id='p$x.$y.$z.$zz' style='display:none'>";
						$zy=0;
						foreach ($arr5 as $v){
							$zy++;
							$is_lastZY=($zy==$numZY ? 'last' : '');
							echo "\n\t\t\t\t<div>$strX$strY$strZ$strZZ",@html_img("img/trjoin$is_lastZY.gif")
								,"<a href='tab.php?id=$conn[0],$db,$sch,$typ,$v&#38;desc=1' title='",@lang(2403),"' target='main'>",@html_img("img/x$typ"),"</a> <a href='tab.php?id=$conn[0],$db,$sch,$typ,$v' title='$v' target='main'>$v</a></div>";
						}
						echo "\n\t\t\t</div><!--p$x.$y.$z.$zz-->";
					}else{
						$zy=0;
						foreach ($arr5 as $v){
							$zy++;
							$is_lastZY=($zy==$numZY && $zz==$numZZ ? 'last' : '');
							echo "\n\t\t\t<div>$strX$strY$strZ",@html_img("img/trjoin$is_lastZY.gif")
								,"<a href='tab.php?id=$conn[0],$db,$sch,$typ,$v&#38;desc=1' title='",@lang(2403),"' target='main'>",@html_img("img/x$typ"),"</a> <a href='tab.php?id=$conn[0],$db,$sch,$typ,$v' title='$v' target='main'>$v</a></div>";
						}
					}
				}
				echo "\n\t\t</div><!--p$x.$y.$z-->";
			}
			if ($conn[1]=='pg') echo "\n\t</div><!--p$x.$y-->";
		}
		echo "\n</div><!--p$x-->";
	}
	echo "\n</div><!--menu-->\n<script type='text/javascript'>\nfunction showAll(){
	var mode=document.getElementById('menuRoot').title;
	document.getElementById('menuRoot').title=(mode=='",@lang(2401),"' ? '",@lang(2402),"' : '",@lang(2401),"');
	var node=new Array('",@implode("','",$allNode),"');
	for(i=0;i<node.length;i++) showHideTree(node[i],(mode=='",@lang(2401),"' ? 1 : -1));\n}\n</script>";
}
?>
