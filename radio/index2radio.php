<?php
require_once("../include/define.php");

$channel = "ai";
if(array_key_exists("channel",$_GET))
{
	$channel = $_GET["channel"];
}


$date = time()+3600*8;
if(array_key_exists("date",$_GET))
{
	$date = DateTime::createFromFormat('Y-m-d',$_GET["date"])->getTimestamp();
	if($date>(time()+3600*8))
		$date=(time()+3600*8);
}


$strDate = gmdate('Y-m-d',$date);

$newUrl = "http://www.cathassist.org/radio/radio.php#channel=".$channel."&date=".$strDate;

header("Location: ".$newUrl);
?>