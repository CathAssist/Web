<?php
	require_once("../include/dbconn.php");
	require_once("../include/define.php");
	session_start();
	/*
	错误码定义
	1 没有日期参数或日期参数不正确
	2 未获取到数据
	3 连接数据库失败
	*/
	//http://mhchina.a24.cc/api/v1/getstuff/
	header("Content-type: text/html; charset=utf-8");
	
	if(!isset($_SESSION['isadmin']))
	{
		die("请先登录！");	
	}
	if($_SESSION['isadmin']!='1')
	{
		echo $_SESSION['isadmin'];
		die("非管理员帐户！");
	}
	if(!isset($_POST['lodo']))
	{
		die("未获取到圣经金句！");
	}
	
	$lodo = "";
	if(isset($_POST['lodo']))
	{
		$lodo = $_POST['lodo'];
	}
	if($lodo=="")
	{
		die("未获取到圣经金句！");
	}
	
	$listLodo = explode("\n",$lodo);
	
	foreach($listLodo as $arr)
	{
		$arr = trim($arr);
		//插入到数据库
		mysql_query("insert into lodo(lodo) values('".mysql_real_escape_string($arr)."')");
	}
	
	exit("更新成功");
?>