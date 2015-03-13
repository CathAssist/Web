<?php
	require_once("../include/define.php");
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
	
	{
		//更新梵蒂冈广播
		$rsscontent = file_get_contents("http://media01.vatiradio.va/podmaker/podcaster.aspx?c=cinese");
		$rss = simplexml_load_string($rsscontent);
		$channel = $rss->channel;
		$i = 0;
		while($i<2)
		{
			$item = $channel->item[$i];
			$enclosure = $item->enclosure;
			$link = $enclosure['url'];
			$name = date("Y-m-d", strtotime($item->pubDate)+3600*5);
			$remote = '/vaticanradio/cn/mp3/'.$name.'.mp3';
			if(!$bcs->is_object_exist(BCS_BUCKET,$remote)){
				upload2bcsbyurl($remote,$link);
			}
			$i++;
		}
	}
	
	{
		//更新“圣言及反思”音频
		$tmNow = time();
		$base = intval(date('H',$tmNow))%12;
		$i = 0;
		while($i<2)
		{
			$tmNow = time()+($base+$i*12)*3600*24;
			$strDate = date('Y-m-d',$tmNow);
			$link = 'http://apps.thomasluk.idv.hk/apps/themes/read_bible/'.date('Ymd',$tmNow).'p.mp3';
			$remote = '/thought/mp3/'.date('Y-m-d',$tmNow).'.mp3';
			if(url_exists($link))
			{
				if(!$bcs->is_object_exist(BCS_BUCKET,$remote))
				{
					upload2bcsbyurl($remote,$link);
				}
			}

			$i++;
		}
	}
	
	echo('<br/><h2>Done!!!</h2>');
?>