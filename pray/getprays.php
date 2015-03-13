<?php
require_once("../include/dbconn.php");
require_once("../include/define.php");

if(array_key_exists("type",$_GET))
{
	if($_GET["type"]!="jsonp")
	{
		die();
	}
}

$ret = null;
//先从数据库中获取
$result = mysql_query("select id,name,text,createtime from pray order by id desc limit 20;");
$i = 0;
while ($row = mysql_fetch_array($result))
{
	$ret[$i] = array('id'=>$row['id'],'name'=>$row['name'],'text'=>nl2br($row['text']),'time'=>$row['createtime']);
	$i++;
}

echo $_GET['callback'].'('.json_encode($ret).')';
?>