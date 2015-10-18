<?php
	require_once("../include/dbconn.php");

	$ret = array();

	$channel = "";
	if(array_key_exists('channel', $_GET))
	{
		$channel = $_GET['channel'];
	}

	$singerid = 237;
	if($channel == "")
	{
		//get channel list
		$results = mysql_query("select * from alume where singer=".$singerid);

		$index = 0;
		while ($row = mysql_fetch_array($results))
		{
			$ret[$index]['key'] = $row['id'];
			$ret[$index]['name'] = $row['name'];
			$ret[$index]['date'] = $row['pubdate'];
			$ret[$index]['logo'] = $row['pic'];
			$ret[$index]['desc'] = $row['slogen'];

			$index++;
		}
	}
	else
	{
		//get channel items
		$startid = -1;
		$count = 20;
		$order = 0;
		if(array_key_exists('start', $_GET))
			$startid = $_GET['start'];
		if(array_key_exists('count', $_GET))
			$count = $_GET['count'];
		if(array_key_exists('order', $_GET))
			$order = $_GET['order'];

		$sqlstr = "select * from song where alume=".$channel." order by id desc limit ".$count.";";
		if($startid>-1)
		{
			if($order>0)
			{
				//正序
				$sqlstr = "select * from song where alume=".$channel." and id>".$startid." order by id asc limit ".$count.";";
			}
			else
			{
				//逆序
				$sqlstr = "select * from song where alume=".$channel." and id<".$startid." order by id desc limit ".$count.";";
			}
		}
		else if($order>0)
		{
			$sqlstr = "select * from song where alume=".$channel." order by id asc limit ".$count.";";
		}

		$results = mysql_query($sqlstr);

		$index = 0;
		while ($row = mysql_fetch_array($results))
		{
			$ret[$index]['key'] = $row['id'];
			$ret[$index]['name'] = $row['name'];
			$ret[$index]['date'] = $row['pubdate'];
			$ret[$index]['src'] = $row['src'];

			$index++;
		}	
	}

	header('Content-type: application/json;text/html;charset=utf-8;');
	echo(json_encode($ret));
?>