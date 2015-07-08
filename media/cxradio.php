<?php
require_once("../include/define.php");

$date = time()+3600*8;
if(array_key_exists("date",$_GET))
{
	$date = DateTime::createFromFormat('Y-m-d',$_GET["date"])->getTimestamp();
	if($date>(time()+3600*8))
		$date=(time()+3600*8);
}
$strDate = gmdate('Y-m-d',$date);

header("Location: http://www.cathassist.org/radio/radio.php#channel=cx&date=".$strDate);
?>