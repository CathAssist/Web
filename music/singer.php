<?php
	require_once("../include/dbconn.php");
	require_once("../include/define.php");
	require_once("../users/user.class.php");
	
	$id = -1;
	if(isset($_GET['id']))
	{
		$id = (int)$_GET['id'];
	}
	
	$web_title = "";
	$name = "";
	$result = mysql_query('select * from singer where id='.$id.';');
	if($row = mysql_fetch_array($result))
	{
		$name = $row['name'];
		$web_title = $name.'——天主教小助手';
	}
	else
	{
		header("Location: index.php");
		exit();
	}
	echo('<html><head><title>'.$web_title.'</title><meta http-equiv=Content-Type content="text/html;charset=utf-8"><meta name="viewport" content="user-scalable=no, width=device-width" /><link rel="stylesheet" type="text/css" href="singer.css"/>');
	if(User::isAdmin())
	{echo('<script type="text/javascript">function delSinger(id)
		{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange=function()
		{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			window.location.href="index.php";
			return;
		}
		}
		xmlhttp.open("POST","./music_op.php",true);
		xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlhttp.send("mode=delsinger&id="+id);
		}</script>');
	}
	echo('</head><body><center>');
	echo('<div class="singer"><h3>'.$name.'</h3></div><br/>');
	
	$pic = "";
	$result = mysql_query('select * from alume where singer='.$id.';');
	while($row = mysql_fetch_array($result))
	{
		if(empty($pic))
			$pic = $row['pic'];
		echo('<a href="alume.php?id='.$row['id'].'"><div class="alume"><span>'.$row['name'].'</span><img src="'.$row['pic'].'"/></div></a>');
	}
	if(User::isAdmin()){echo('<br/><br/><br/><a style="display:block;clear:left;" href="#" onclick="delSinger('.$id.')">删除</a>');}
	echo('</center></body><script type="text/javascript" language="javascript" src="/include/googleanalysis.js"></script>'.getWechatShareScript(ROOT_WEB_URL.'music/singer.php?id='.$id,$web_title,$pic).'</html>');
 ?>
