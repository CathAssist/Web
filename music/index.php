<?php
	require_once("../include/dbconn.php");
	require_once("../include/define.php");
	require_once("../users/user.class.php");
	
	$id = -1;
	if(isset($_GET['id']))
	{
		$id = (int)$_GET['id'];
	}
	
	$web_title = '教会音乐——天主教小助手';
	echo('<html><head><title>'.$web_title.'</title><meta http-equiv=Content-Type content="text/html;charset=utf-8"><meta name="viewport" content="user-scalable=no, width=device-width" /><link rel="stylesheet" type="text/css" href="index.css"/></head><body><center><h2>歌手名称</h2>');
	
	$result = mysql_query('select * from singer;');
	while($row = mysql_fetch_array($result))
	{
		echo('<div class="singer"><a href="singer.php?id='.$row['id'].'">'.$row['name'].'</a></div>');
	}
	echo('</center></body></html>');
 ?>
