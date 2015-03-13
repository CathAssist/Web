<?php
	require_once("../include/dbconn.php");
	require_once("../include/define.php");
	require_once("../users/user.class.php");
	/*
	错误码定义
	1 没有日期参数或日期参数不正确
	2 未获取到数据
	3 连接数据库失败
	*/
	//http://mhchina.a24.cc/api/v1/getstuff/
	header("Content-type: text/html; charset=utf-8");
	
	if(!User::isAdmin())
	{
		die();
	}
	
	if(!isset($_POST['mode']))
	{
		die("未找到参数1！");
	}
	$mode = $_POST['mode'];
	if($mode=='dels')
	{
		if(!isset($_POST['id']))
		{
			die("未找到参数2！");
		}
		$id=htmlspecialchars($_POST['id']);
		$result = mysql_query("delete from song where id=".$id.";");
		if(mysql_affected_rows()>0)
		{
			die('已删除');
		}
	}
	else if($mode=='dela')
	{
		if(!isset($_POST['id']))
		{
			die("未找到参数2！");
		}
		$id=htmlspecialchars($_POST['id']);
		$result = mysql_query("delete from alume where id=".$id.";");
		if(mysql_affected_rows()>0)
		{
			mysql_query("delete from song where alume=".$id.";");
			die('已删除');
		}
	}
	else if($mode=='delsinger')
	{
		if(!isset($_POST['id']))
		{
			die("未找到参数2！");
		}
		$id=htmlspecialchars($_POST['id']);
		$result = mysql_query("delete from singer where id=".$id.";");
		if(mysql_affected_rows()>0)
		{
			mysql_query("delete from alume where singer=".$id.";");
			mysql_query("delete from song where singer=".$id.";");
			die('已删除');
		}		
	}
?>