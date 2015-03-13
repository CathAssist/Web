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
	$pic = "";
	$singer = "";
	$sid = "";
	$result = mysql_query('select alume.name as aname,alume.pic as pic,singer.name as sname,singer.id as sid from alume,singer where alume.singer=singer.id and alume.id='.$id.';');
	if($row = mysql_fetch_array($result))
	{
		$name = $row['aname'];
		$pic = $row['pic'];
		$singer = $row['sname'];
		$sid = $row['sid'];
		$web_title = $name.'——天主教小助手';
	}
	else
	{
		header("Location: index.php");
		exit();
	}
	echo('<html><head><title>'.$web_title.'</title><meta http-equiv=Content-Type content="text/html;charset=utf-8"><meta name="viewport" content="user-scalable=no, width=device-width" /><link rel="stylesheet" type="text/css" href="alume.css"/>');
	if(User::isAdmin())
	{echo('<script type="text/javascript">function delAlume(id)
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
		xmlhttp.send("mode=dela&id="+id);
		}</script>');
	}
	echo('</head><body><center>');
	echo('<div class="alume"><h3>'.$name.'</h3></div><img src="'.$pic.'"/><br/><br/><br/>');
	echo('<div class="singer">歌手：<a href="singer.php?id='.$sid.'">'.$singer.'</a></div><br/>');
	
	$result = mysql_query('select * from song where alume='.$id.';');
	while($row = mysql_fetch_array($result))
	{
		echo('<div class="song"><a href="music.php?id='.$row['id'].'">'.$row['name'].'</a></div>');
	}
	if(User::isAdmin()){echo('<a style="display:block;clear:left;" href="#" onclick="delAlume('.$id.')">删除</a>');}
	echo('</center></body><script type="text/javascript" language="javascript" src="/include/googleanalysis.js"></script>'.getWechatShareScript(ROOT_WEB_URL.'music/alume.php?id='.$id,$web_title,$pic).'</html>');
 ?>
