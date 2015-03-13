<?php
	function cn_urlencode($url)
	{
		$pregstr   =  "/[\x{4e00}-\x{9fa5}]+/u" ; //UTF-8中文正则
		if (preg_match_all( $pregstr , $url , $matchArray ))
		{ //匹配中文，返回数组
			foreach ( $matchArray [0]  as   $key => $val )
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
	
	//查询url是否存在
	function url_exists($urlpath)
	{
		$h = get_headers($urlpath);
		if(strpos($h[0],'OK')>-1 || strpos($h[0],'302')>-1)
		{
			return true;
		}
		return false;
	}
	
	
	function append2All($c,$j)
	{
		$ja = json_decode(file_get_contents("list"),true);
		
		if(array_key_exists($c,$ja))
		{
			$curDate = DateTime::createFromFormat('Y-m-d',$ja[$c]['date'])->getTimestamp();
			$newDate = DateTime::createFromFormat('Y-m-d',$j['date'])->getTimestamp();
			if($curDate<$newDate)
			{
				$ja[$c] = $j;
				file_put_contents("list",json_encode($ja));
			}
		}
		else
		{
			$ja[$c] = $j;
			file_put_contents("list",json_encode($ja));
		}
	}
	
	$date = time()+8*3600;
	$strDate = date('Y-m-d',$date);
	//晨星生命之音
	{
		$cxfile = './cx/'.$strDate;
		$cxjson = null;
		if(!file_exists($cxfile))
		{
			$cxdate = date("Y-n-j", $date);
			$cxradio = 'http://211.149.237.175/playlist/'.$cxdate.'.txt';
			if(url_exists($cxradio))
			{
				$cxlist = explode("\n",file_get_contents($cxradio));		//或是url list
				$cnpreg = "/[\x{4e00}-\x{9fa5}]+/u";
				$cxjson["title"] = "晨星生命之音";
				$cxjson["date"] = $strDate;
				$cxjson["logo"] = "http://www.cathassist.org/radio/logos/cx.png";
				$i = 0;
				foreach($cxlist as $v)
				{
					$v = iconv("GB2312", "UTF-8//IGNORE",trim($v));
					$title = "";
					preg_match_all($cnpreg, substr($v,strrpos($v,'/')), $title);
					$title=implode("", $title[0]);
					$cxjson['items'][$i] = array('title'=>$title,'src'=>cn_urlencode($v));
					++$i;
				}
				file_put_contents($cxfile,json_encode($cxjson));
				append2All("cx",$cxjson);
			}
			else
			{
				echo("Can't update cxradio...<br/>");
			}
		}
	}
	
	//梵蒂冈中文广播
	{
		$vafile = './vacn/'.$strDate;
		$vajson = null;
		if(!file_exists($vafile))
		{
			$vajson["title"] = "梵蒂冈中文广播";
			$vajson["date"] = $strDate;
			$vajson["logo"] = "http://www.cathassist.org/radio/logos/vacn.jpg";
			$itemsrc = "http://media.cathassist.org/vaticanradio/cn/mp3/".$strDate.".mp3";
			if(url_exists($itemsrc))
			{
				$title = "梵蒂冈中文广播";
				$vajson['items'][0] = array('title'=>$title,'src'=>$itemsrc);
				file_put_contents($vafile,json_encode($vajson));
				append2All("vacn",$vajson);
			}
			else
			{
				echo("Can't update vacn...<br/>");
			}
		}
	}
	
	//每日福音
	{
		$gosfile = './gos/'.$strDate;
		$gosjson = null;
		if(!file_exists($gosfile))
		{
			$gosjson["title"] = "每日福音";
			$gosjson["date"] = $strDate;
			$gosjson["logo"] = "http://www.cathassist.org/radio/logos/gos.jpg";
			$itemsrc = "http://media.cathassist.org/thought/mp3/".$strDate.".mp3";
			if(url_exists($itemsrc))
			{
				$title = "每日福音";
				$gosjson['items'][0] = array('title'=>$title,'src'=>$itemsrc);
				file_put_contents($gosfile,json_encode($gosjson));
				append2All("gos",$gosjson);
			}
			else
			{
				echo("Cant't update gos...<br/>");
			}
		}
	}
?>