<?php
require_once "jssdk/jssdk.php";

if(array_key_exists("type",$_GET))
{
	if($_GET["type"]=="json")
	{
		$jssdk = new JSSDK("", "");
		$signPackage = $jssdk->GetSignPackage(urldecode($_GET["url"]));

		$ret = json_encode($signPackage);
		//echo $_GET['callback'].'('.$ret.')';
		echo $ret;
	}
}
?>
