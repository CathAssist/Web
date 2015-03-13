<?php
	set_time_limit(60*15);		//设置超时为15分钟
	error_reporting(E_ERROR | E_PARSE);
	
	function curl_download($remote, $local) {
		$cp = curl_init($remote);
		$fp = fopen($local, "w");
		curl_setopt($cp, CURLOPT_FILE, $fp);
		curl_setopt($cp, CURLOPT_HEADER, 0);

		curl_exec($cp);
		curl_close($cp);
		fclose($fp);
	}
	
	function cn_urlencode($url)
	{
		$pregstr   =  "/[\x{4e00}-\x{9fa5}]+/u" ; //UTF-8中文正则
		if (preg_match_all( $pregstr , $url , $matchArray ))
		{ //匹配中文，返回数组
			foreach($matchArray[0] as $key=>$val)
			{
				$url = str_replace ( $val , urlencode( $val ),  $url ); //将转译替换中文
			}
			if ( strpos ( $url , ' ' ))
			{ //若存在空格
				$url = str_replace ( ' ' , '%20' , $url );
			}
		}
		return $url;
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
		echo("<h2>Vatican Radio</h2>");
		$rsscontent = file_get_contents("http://media01.vatiradio.va/podmaker/podcaster.aspx?c=cinese");
		$rss = simplexml_load_string($rsscontent);
		$channel = $rss->channel;
		$i = 0;
		while($i<2)
		{
			$item = $channel->item[$i];
			$enclosure = $item->enclosure;
			$link = $enclosure['url'];
			
			echo("check link:".$link."<br/>");
			$name = date("Y-m-d", strtotime($item->pubDate)+3600*14);
			$lmp3 = './vaticanradio/cn/mp3/'.$name.'.mp3';
			if(!file_exists($lmp3))
			{
				echo("file:".$lmp3." not exists<br/>");
				$bname = basename($link);
				$local = './objs/'.time().'_'.$bname;
				curl_download($link,$local);
				echo("download to local:".$local."<br/>");
				if(!rename($local,$lmp3))
				{
					echo("rename false<br/>");
				}
			}
			$i++;
		}
	}
	
	{
		echo("<h2>Thought</h2>");
		//更新“圣言及反思”音频
		$tmNow = time();
		$base = intval(date('H',$tmNow))%12;
		$i = 0;
		while($i<2)
		{
			$tmNow = time()+($base+$i*12)*3600*24;
			
			$strDate = date('Y-m-d',$tmNow);
			$link = 'http://apps.thomasluk.idv.hk/apps/themes/read_bible/'.date('Ymd',$tmNow).'p.mp3';
			$lmp3 = './thought/mp3/'.date('Y-m-d',$tmNow).'.mp3';
			
			if(!file_exists($lmp3))
			{
				$cxdate = date("Y-n-j", $tmNow);
				$cxradio = 'http://radio.cxsm.org/playlist/'.$cxdate.'.txt';
				$cxlist = explode("\n",file_get_contents($cxradio));		//或是url list
				if(count($cxlist)>1)
				{
					$link = cn_urlencode(trim($cxlist[1]));
					echo("Use cxradio mp3<br/>");
				}
				
				echo("check link:".$link."<br/>");
				
				if(url_exists($link))
				{
					$bname = basename($link);
					echo("base name:".$bname.'<br/>');
					$local = './objs/'.time().'_'.$bname;
					curl_download($link,$local);
					
					echo("download from:".$link."<br/>");
					if(!rename($local,$lmp3))
					{
						echo("rename false<br/>");
					}
				}
			}

			$i++;
		}
	}
	
	echo('<br/><h2>Done!!!</h2>');
?>