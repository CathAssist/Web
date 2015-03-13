<html>
<head>
	<title>普世教会——天主教小助手</title>
	<meta http-equiv=Content-Type content="text/html;charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<link href="articles.css" type="text/css" rel="stylesheet">
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
		$result = mysql_query('select id,local,name from vatican_topic where id='.$topic.';');		
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
		echo(' › <a href="index.php">普世教会</a></span><h1 class="topic-title">普世教会之『'.$topicname.'』</h1></div><div class="content">');
	}
	else
	{
		echo(' › <a href="index.php">普世教会</a></span><h1 class="topic-title">普世教会</h1></div><div class="content">');
	}
	
	
	$fromid = 0;
	if(isset($_GET['from']))
	{
		$fromid = (int)$_GET['from'];
		if($fromid<0)
			$fromid = 0;
	}
	
	$sql = 'select id,title,local,src from vaticanacn order by id desc limit '.$fromid.',10;';
	if($topic>-1)
	{
		$sql = 'select id,title,local,src from vaticanacn where cate='.$topic.' order by id desc limit '.$fromid.',10;';
	}
	
	$result = mysql_query($sql);
	$resultcount = mysql_num_rows($result);
	while ($row = mysql_fetch_array($result))
	{
		echo('<div class="link"><a href="'.$row[local].'">'.$row[title].'</a></div>');
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
	echo('</span></div>');
?>
</body>
<script type="text/javascript" language="javascript" src="/include/googleanalysis.js"></script>
</html>