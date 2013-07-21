<?php
@session_start();
@extract($_POST); @extract($_GET);
@include_once "inc.func.php";
@check_global_ip_access();//exit if ip blocked
@include_once "inc.func.enc65.php";
//db =0id 1db 2sch 3typ 4tab 5sort1 6sort2 7sort
//tab=0id 1db 2sch 3typ 4tab 5sort1 6sort2 7sort 8fm 9to ; fm|to ; f[0..x|sql] ; g[size|show][0..x]
//sql=id,sql,hide,ttl_sql,ttl_err,ttl_time,Rows
//cookie:--better put into session. but sf.net does not support session?
//conn[txt]--please read conn.php
//MODE=0lang.1gridMode.2pgSize.3tree.4sortObj.5sortData.6menuTextSQL.7menuText.8his.9hisErr.10hisSQL.11hisData.12dataEasy(pg).13oid(pg)
//CONN=0id.1eng.2host.3user.4enc(pass).5port.6dbs.7penc.8charset
//SQL =0id.1db.2sch.3typ.4tab@id.db.sch.typ.tab@...
$SIDU = @explode(",",$_GET['id']);
$SIDU['conn'] = @get_sidu_conn();
$SIDU['cook'] = @get_sidu_cook();//SIDUSQL
@check_sidu_conn($SIDU);//exit if no conn
$SIDU['dbL'] = @db_conn($SIDU['conn'][$SIDU[0]],$SIDU[1]);
@initSIDU();
@include_once "lang/en.php";
if ($SIDU['page']['lang']<>'en') @include_once "lang/{$SIDU[page][lang]}.php";

//SIDU Product Key
//Please change to any chars except double quotation {"} at any length
//example: return "my very long and secure sidu global key - hehe:";
function SIDU_PK(){return "2345-A3BCD-X5Y9";}
//SIDU Global Firewall eg "^127.0.0.1$;^192.168"
function SIDU_IP(){return "";}
?>
