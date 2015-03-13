<?php
	require_once("../include/define.php");

	function url_exists($urlpath)
	{
		$h = get_headers($urlpath);
		if(strpos($h[0],'OK')>-1)
		{
			return true;
		}
		return false;
	}
	
	function getAudio($rss,&$link,$abstime=0)
	{
		$rsscontent = file_get_contents($rss);
		$rss = simplexml_load_string($rsscontent);
		$channel = $rss->channel;
		if(count($channel->item)>0)
		{
			$item = $channel->item[0];
			$enclosure = $item->enclosure;
			$link = $enclosure['url'];
			if($abstime>0)
			{
				$strDate = date("Y-m-d",strtotime($item->pubDate)+$abstime);
				$remote = 'http://media.cathassist.org/vaticanradio/cn/mp3/'.$strDate.'.mp3';
				if(url_exists($remote))
				{
					$link = $remote;
				}
			}
			return date("Y-m-d", strtotime($item->pubDate)+$abstime);
		}
	}
	
	$cnday = "http://media.vaticanradiowebcast.org/mp3_od/cinese_1.mp3";
	$cndaytitle = "中文广播";
	$enmorning = "http://media.vaticanradiowebcast.org/mp3_od/rg_inglese_1_1.mp3";
	$enmtitle = "English News(morning)";
	$enevening = "http://media.vaticanradiowebcast.org/mp3_od/rg_inglese_2_1.mp3";
	$enetitle = "English News(evening)";
	$cnmass = "http://media.vaticanradiowebcast.org/mp3_od/messa_cinese_1.mp3";
	$cnmasstitle = "主日弥撒";
	$cndaystr = "";
	
	{
		$cndaystr = getAudio("http://media01.vatiradio.va/podmaker/podcaster.aspx?c=cinese",$cnday,3600*14);
		$cndaytitle = '中文广播('.$cndaystr.')';
		$enmtitle = 'English News('.getAudio("http://media01.vatiradio.va/podmaker/podcaster.aspx?c=rg_inglese_1",$enmorning).' morning)';
		$enetitle = 'English News('.getAudio("http://media01.vatiradio.va/podmaker/podcaster.aspx?c=rg_inglese_2",$enevening).' evening)';
		$cnmasstitle = '主日弥撒('.getAudio("http://media01.vatiradio.va/podmaker/podcaster.aspx?c=messa_cinese",$cnmass).')';
	}
	
	$fp = fopen("vaticanradio.html","w");
	fwrite($fp,'<html><head><title>梵蒂冈广播电台——天主教小助手</title><meta http-equiv=Content-Type content="text/html;charset=utf-8"><meta name="viewport" content="user-scalable=no, width=device-width" /><link rel="stylesheet" type="text/css" href="vaticanradiowebcast_cn.css"/></head><body><div style="float:right"><a href="http://mp.weixin.qq.com/mp/appmsg/show?__biz=MzA5MzAwNjcxMQ==&appmsgid=10000002&itemidx=1&sign=e5eb309ec3013bbc30d7d3b5d339d499#wechat_redirect" class="btn" style="color:rgb(48, 135, 253);">关注我们</a></div><center style="clear:right;"><h2>梵蒂冈广播电台</h2><h3>'.$cndaytitle.'</h3>
	<audio src="'.$cnday.'" controls></audio><h3>'.$enmtitle.'</h3>
	<audio src="'.$enmorning.'" controls></audio>
	<h3>'.$enetitle.'</h3>
	<audio src="'.$enevening.'" controls></audio>
	<h3>'.$cnmasstitle.'</h3>
	<audio src="'.$cnmass.'" controls></audio>
	<br/><h3><b>提示：播放音频会损耗较多流量</b></h3></div></center></body><script type="text/javascript" language="javascript" src="/include/googleanalysis.js"></script>');
	fwrite($fp,'<script type="text/javascript" language="javascript" src="http://cathassist.org/include/common.js"></script><script type="text/javascript">document.addEventListener("DOMContentLoaded", function(){SetWechatShare("梵蒂冈广播电台","'.ROOT_WEB_URL.'media/vaticanradiowebcast_cn.html'.'","'.ROOT_WEB_URL.'media/icon.jpg'.'","梵蒂冈广播电台");});</script>');
	fwrite($fp,'</html>');
	fclose($fp);
	
	$fpv = fopen("../wechat/vaticanradio.xml","w");
	fwrite($fpv,'<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[music]]></MsgType>
		 <Music>
		 <Title><![CDATA[梵蒂冈中文广播]]></Title>
		 <Description><![CDATA['.$cndaystr.']]></Description>
		 <MusicUrl><![CDATA['.$cnday.']]></MusicUrl>
		 <HQMusicUrl><![CDATA['.$cnday.']]></HQMusicUrl>
		 </Music>
		 </xml>');
	fclose($fpv);
	echo("check vaticanradio(".date("Y-m-d",time()).")");
?>