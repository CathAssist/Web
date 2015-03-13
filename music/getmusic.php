<?php
	require_once("../include/dbconn.php");
	require_once("../include/define.php");

	$ret = null;
	
	$result = mysql_query("select * from song L JOIN (SELECT CEIL(MAX(ID)*RAND()) AS ID FROM song) AS m ON L.ID >= m.ID LIMIT 1;");
	if ($row = mysql_fetch_array($result))
	{
		$songid = $row['id'];
		$result = mysql_query('select song.name as name,song.src as mp3,song.alume as aid,song.singer as sid,alume.name as aname,alume.pic as pic,singer.name as sname from song,alume,singer where song.alume=alume.id and song.singer=singer.id and song.id='.$row['id'].';');
		if($row = mysql_fetch_array($result))
		{
			$ret['mp3'] = $row['mp3'];
			$ret['name'] = $row['name'];
			$ret['singer'] = $row['sname'];
			$ret['alume'] = $row['aname'];
			$ret['pic'] = $row['pic'];
			$ret['aid'] = $row['aid'];
			$ret['sid'] = $row['sid'];
			$ret['id'] = $songid;
		}
	}
	
	echo $_GET['callback'].'('.json_encode($ret).')';
?>