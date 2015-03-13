<?php
require_once("../include/dbconn.php");
require_once("../include/define.php");

$refto = "http://cathassist.org";
if(array_key_exists("sub",$_GET) && array_key_exists("id",$_GET))
{
	$sub = $_GET["sub"];
	$id = $_GET["id"];
	
	if($sub == "faithlife")
	{
		$result = mysql_query("select nurl from faithlife where id=".$id.";");
		$row = mysql_fetch_array($result);
		$refto = "http://cathassist.org/".$row['nurl'];
	}
	else if($sub == "vaticanacn")
	{
		$result = mysql_query("select local from vaticanacn where id=".$id.";");
		$row = mysql_fetch_array($result);
		$refto = "http://cathassist.org/vaticanacn/".$row['local'];
	}
	else if($sub == "articles")
	{
		$refto = "http://cathassist.org/articles/articles/".$id.".html";
	}
}

header("Location: ".$refto);
echo("跳转到：".$refto);
?>