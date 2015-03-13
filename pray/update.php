<?php
	require_once("../include/dbconn.php");
	require_once("../include/define.php");
	require_once("../include/wbto.php");
	
	session_start();
	/*
	错误码定义
	1 没有日期参数或日期参数不正确
	2 未获取到数据
	3 连接数据库失败
	*/
	//http://mhchina.a24.cc/api/v1/getstuff/
	header("Content-type: text/html; charset=utf-8");
?>
<?php
	function gotoend()
	{
		global $error;
		echo $error;
		die();
	}
	
	if(isset($_POST["text"]))
	{
		$text = checkSqlArg(trim($_POST["text"]));
	}
	if(strlen($text)<20)
	{
		$error= "请输入祈祷意向!";
		gotoend();
	}
	
	if (!isset($_SESSION['pray_time']))
	{
		$_SESSION['pray_time'] = 0;
	}
	$cur_time = date(time());
	$last_time = $_SESSION['pray_time'];
	if(abs($cur_time-$last_time) < 3600)
	{
		//10分钟只能提交一次祈祷意向
		$error = '请不要频繁提交祈祷意向，'.(3600-abs($cur_time-$last_time)).'秒后再试，主佑！';
		gotoend();
	}
	else
	{
		$_SESSION['pray_time'] = $cur_time;
	}
	
	if(!isset($_POST['cap']))
	{
		die('验证码错误！');
	}
	else
	{
		$cap = trim($_POST['cap']);
		if($cap!=$_SESSION["helloweba_math"])
		{
			die('验证码错误！');
		}
	}
	
	if(isset($_POST['name']))
	{
		$name=checkSqlArg(trim($_POST['name']));
		$_SESSION['name'] = $name;
	}

	if( (strpos($name, "张译公") !== false) || (strpos($text, "张译公") !== false) )
	{
		die("");
	}
	
	//先从数据库中获取
	$result = mysql_query("insert into pray (name,text,createtime) values ('".$name."','".$text."',utc_timestamp());");
	if(mysql_affected_rows()<1)
	{
		$error = "添加祈祷意向失败，请稍后重试...";
		gotoend();
	}
	
	//send to wbto
	//add2weibolist('#彼此代祷# '.$name.'：'.$text);
?>