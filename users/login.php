<?php
require_once("../include/dbconn.php");
require_once("../include/define.php");
session_start();

header("Content-type: text/html; charset=utf-8");
//登录
if(!isset($_POST['submit'])){
    exit('非法访问!');
}

$username = checkSqlArg($_POST['username']);
$password = checkSqlArg($_POST['password']);

//检测用户名及密码是否正确
$result = mysql_query("select * from users where username='".$username."' and password='".$password."' limit 1;");
if($result = mysql_fetch_array($result)){
	//登录成功
	$_SESSION['username'] = $username;
	$_SESSION['userid'] = $result['id'];
	$_SESSION['name'] = $result['name'];
	$_SESSION['isadmin'] = $result['isadmin'];
	exit('登录成功!');
}
exit('登录失败!');
?>