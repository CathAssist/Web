<html>
<head>
	<title>小助手推荐——天主教小助手</title>
	<meta http-equiv=Content-Type content="text/html;charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<link href="articles.css" type="text/css" rel="stylesheet">
<?php
	require_once("../users/user.class.php");
	if(User::isLogin())
	{
		echo('<script type="text/javascript">function delArticle(id)
{
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			parent.location.reload();
			return;
		}
	}
	xmlhttp.open("POST","./article.php",true);
	xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	xmlhttp.send("mode=del&id="+id);
}</script>');
	}
?>
</head>
<body>
<?php
	require_once("../include/dbconn.php");
	require_once("../include/define.php");
	
	$topic = -1;
	$topiclocal = "";
	$topicname = "";
	if(isset($_GET['topic']))
	{
		$topic = (int)$_GET['topic'];
	}
	
	if($topic>-1)
	{
		$result = mysql_query('select id,name from article_topic where id='.$topic.';');		
		$topic=-1;
		while ($row = mysql_fetch_array($result))
		{
			$topic = $row['id'];
			$topiclocal = $row['local'];
			$topicname = $row['name'];
		}
	}
	
	echo('<div class="topic"><span class="current"><a href="/" alt="天主教小助手首页">首页</a>');
	if($topic>-1)
	{
		echo(' › <a href="index.php">小助手推荐</a></span><h1 class="topic-title">'.$topicname.'</h1></div><div class="content">');
	}
	else
	{
		echo(' › <a href="index.php">小助手推荐</a></span></div><div class="content">');
	}
	
	
	$fromid = 0;
	if(isset($_GET['from']))
	{
		$fromid = (int)$_GET['from'];
		if($fromid<0)
			$fromid = 0;
	}
	
	$sql = 'select id,title from articles order by id desc limit '.$fromid.',10;';
	if($topic>-1)
	{
		$sql = 'select id,title from articles where topic='.$topic.' order by id desc limit '.$fromid.',10;';
	}
	
	$result = mysql_query($sql);
	$resultcount = mysql_num_rows($result);
	if(User::isLogin())
	{
		while ($row = mysql_fetch_array($result))
		{
			echo('<a href="./articles/'.$row['id'].'.html">'.$row[title].'</a><input type="button" value="删除" onclick="delArticle('.$row['id'].')"/><br/>');
		}
	}
	else
	{
		while ($row = mysql_fetch_array($result))
		{
			echo('<a href="./articles/'.$row['id'].'.html">'.$row[title].'</a><br/>');
		}
	}
	
	
	echo('<span class="pages">');
	if($fromid>0)
	{
		echo('<a href="index.php?topic='.$topic.'&from='.($fromid-10).'">上一页</a>');
	}
	if($resultcount>9)
	{
		echo('<a href="index.php?topic='.$topic.'&from='.($fromid+10).'">下一页</a>');
	}
	echo('</span>');
	echo $_POST['page']
?>
</body>
<script type="text/javascript" language="javascript" src="/include/googleanalysis.js"></script>
</html>