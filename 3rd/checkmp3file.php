<?php
	require_once("../include/define.php");
	require_once("../include/dbconn.php");
	require_once("../include/bcs/bcs.class.php");
	set_time_limit(60*15);		//设置超时为15分钟
	error_reporting(E_ERROR | E_PARSE);
	$bcs = new BaiduBCS ();
		
	function curl_download($remote, $local) {
		$cp = curl_init($remote);
		$fp = fopen($local, "w");

		curl_setopt($cp, CURLOPT_FILE, $fp);
		curl_setopt($cp, CURLOPT_HEADER, 0);

		curl_exec($cp);
		curl_close($cp);
		fclose($fp);
	}
	
	function upload2bcsbyurl($remote, $url)
	{
		global $bcs;
		$name = basename($url);
		$local = './objs/'.time().'_'.$name;
		curl_download($url,$local);
		$response = $bcs->create_object(BCS_BUCKET,$remote,$local);
		unlink($local);
		echo('upload "'.$remote.'" to bcs!'.'<br/>');
	}
	
	function url_exists($urlpath)
	{
		$h = get_headers($urlpath);
		if(strpos($h[0],'OK')>-1)
		{
			return true;
		}
		return false;
	}
	
	
//	die(urlencode('：'));
	
/*	$result = mysql_query("select * from song where id=113;");
	while ($row = mysql_fetch_array($result))
	{
		die($row['mp3']);
	}*/
	//获取mp3地址
	$result = mysql_query("select * from song where id>89;");
	while ($row = mysql_fetch_array($result))
	{
		$mp3 = $row['mp3'];
		$id = $row['id'];
		$local = '/var/www/cathassist/music/mp3/'.$id.'.mp3';
		$mp3 = str_replace('（','%EF%BC%88',$mp3);
		$mp3 = str_replace('）','%EF%BC%89',$mp3);
		$mp3 = str_replace('，','%EF%BC%8C',$mp3);
		$mp3 = str_replace('！','%EF%BC%81',$mp3);
		$mp3 = str_replace('’','%E2%80%99',$mp3);
		$mp3 = str_replace('：','%EF%BC%9A',$mp3);
//		echo(url_exists($mp3).'<br/>');
//		continue;
		if(!file_exists($local))
		{
//			curl_download($mp3,$local);
//			$response = $bcs->create_object(BCS_BUCKET,'/music/mp3/'.$id.'.mp3',$local);
			if(!url_exists($mp3))
			{
				die($id.' '.$mp3);
			}
			echo($id."\t");
//			unlink($local);
		}
	}
?>