<?php
session_start();
class User
{
	public static function isLogin()
	{
		return isset($_SESSION['userid']);
	}
	public static function getUserName()
	{
		return $_SESSION['username'];
	}
	public static function getName()
	{
		return $_SESSION['name'];
	}
	public static function isAdmin()
	{
		if(User::isLogin())
		{
			return ($_SESSION['isadmin'] == '1');
		}
		return false;
	}
}
?>