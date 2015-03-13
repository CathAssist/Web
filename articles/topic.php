<?php
	require_once("../include/dbconn.php");
	require_once("../include/define.php");
	require_once("../users/user.class.php");
	if(!User::isLogin())
	{
		die("非法访问！");
	}
	if(!isset($_POST['mode']))
	{
		die("未找到参数1！");
	}
	
	function getTopics()
	{
		$ret = array();
		$result = mysql_query("select id,name from article_topic;");
		while ($row = mysql_fetch_array($result))
		{
			$ret[] = array(
			  'id' => $row['id'],
			  'name' => $row['name']
		   );
		}
		
		return json_encode($ret);
	}
	
	$mode = $_POST['mode'];
	if($mode=='get')
	{
		echo(getTopics());
	}
	else if($mode='add')
	{
		if(!isset($_POST['topic']))
		{
			die("未找到参数2！");
		}
		if(empty($_POST['topic']))
		{
			die("要插入的类别为空！");
		}
		$topic=htmlspecialchars($_POST['topic']);
		mysql_query('insert into article_topic(name) values("'.$topic.'");');
	}
?>