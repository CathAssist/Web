<?php
function checkSqlArg($str)
{
	return mysql_real_escape_string(strip_tags($str));
}

$conn = mysql_pconnect("localhost","cathassist","cathassist");
if(!$conn)
{
	$errorcode = 3;
	die("connect db error!");
}

mysql_select_db("cathassist",$conn);
?>
