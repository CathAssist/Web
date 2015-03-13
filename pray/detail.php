<?php
require_once("../include/define.php");

$id = (int)$_GET['id'];
if($id<1)
{
	header("Location: ".ROOT_WEB_URL."pray/index.php");
	die();
}

require_once("../include/dbconn.php");
header("Content-type: text/html; charset=utf-8");
?>
<head>
	<title>代祷意向详情</title>
	<meta http-equiv=Content-Type content="text/html;charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
</head>
<html>
<?php
	global $id;
	//先从数据库中获取
	$result = mysql_query("select id,name,text,createtime from pray where id=".$id.";");
	while ($row = mysql_fetch_array($result))
	{
		echo('<h2>['.$row['id'].']昵称：'.$row['name'].'  时间：'.date('Y-m-d H:i',strtotime($row['createtime'])+3600*8).'</h2><h1>'.nl2br($row['text']).'</h1>');
	}
?>
<script type="text/javascript" language="javascript" src="/include/googleanalysis.js"></script>
</html>