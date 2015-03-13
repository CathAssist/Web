<?php
	$channel = "";
	if(array_key_exists("channel",$_GET))
	{
		$channel = $_GET["channel"];
	}

	$fName = "about".$channel.".html";
	if(file_exists($fName))
	{
		echo(file_get_contents($fName));
	}
	else
	{
		header("Location: http://www.cathassist.org/");
	}
?>