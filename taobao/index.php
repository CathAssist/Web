<?php
require_once("../include/define.php");
if(is_weixin())
{
	echo(file_get_contents('recommend.html'));
}
else
{
	header("Location: http://shop114244839.taobao.com/");
}
?>