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
	
	
	//基础频道类
	class BaseChannel
	{
		public static function append2All($c,$j)
		{
			$ja = json_decode(file_get_contents("list"),true);
			
			if(array_key_exists($c,$ja))
			{
				$curDate = DateTime::createFromFormat('Y-m-d',$ja[$c]['date'])->getTimestamp();
				$newDate = DateTime::createFromFormat('Y-m-d',$j['date'])->getTimestamp();
				if($curDate<=$newDate)
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
		
		public static function createChannel($c)
		{
			if($c=="cx")
			{
				return new CxChannel();
			}
			else if($c=="ai")
			{
				return new AiChannel();
			}
			else if($c=="ls")
			{
				return new LSChannel();
			}
			else if($c=="vacn")
			{
				return new VacnChannel();
			}
			else if($c=="gos")
			{
				return new GosChannel();
			}
			else if($c=="smzy")
			{
				return new SmzyChannel();
			}
			else if($c=="hyqd")
			{
				return new HyqdChannel();
			}
			else if($c=="cztzd")
			{
				return new CztzdChannel();
			}
			else if($c=="jimmy-lx")
			{
				return new JimmyLxChannel();
			}
			else
			{
				return new BaseChannel();
			}
		}
		public function getRadio($date)
		{
			$all = json_decode(file_get_contents("list"),true);
			$all["cx"]["desc"] = "我们因爱而相聚";
			$all["ls"]["desc"] = "天主教每日圣经诵读";
			$all["vacn"]["desc"] = "每天半小时来自宗座的声音";
			$all["ai"]["desc"] = "来自8090的声音";
			$all["gos"]["desc"] = "一起聆听主的教诲";
			$all["jimmy-lx"]["desc"] = "听Jimmy神父谈灵修";
			$all["smzy"]["desc"] = "听贾神父讲道";

			return $all;
		}
	}

	class CXZShouChannel extends BaseChannel
	{
		public function getInfo() {
			$jRet =  array();
			$jRet['key'] = 'ai';
			$jRet['albumId'] = 60;
			$jRet['title'] = '福音i广播';
			$jRet['logo'] = 'http://www.cathassist.org/radio/logos/ai.png';
			$jRet['desc'] = '来自8090的声音';
			$jRet['item'] = 'i的签到簿';
			return $jRet;
		}

		public function getRadio($date) {
			$cInfo = $this->getInfo();
			
			$ukey = $cInfo['key'];


			$strDate = gmdate('Y-m-d',$date);
			$jUrl = "http://www.xiaozhushou.org/api.php?op=get_music&m=radio_list&album_id=".$cInfo['albumId']."&pubdate=".$strDate;
			$aiContent = file_get_contents($jUrl);
			$jData = json_decode($aiContent,true);

			$jRet = null;
			$jRet["title"] = $cInfo['title'];
			$jRet["date"] = $jData['date'];
			$jRet["logo"] = $cInfo['logo'];
			//$jRet["desc"] = $cInfo['desc'];
			$jRet['items'] = array();

			if(count($jData["items"]) > 0) {
				foreach ($jData["items"] as $item) {
					if(isset($cInfo['item'])) {
						$itemNew['title'] = $cInfo['item'];
					}
					else {
						$itemNew['title'] = $item['title'];
					}
					$itemNew['src'] = $item['src'];

					array_push($jRet['items'],$itemNew);
				}
				
				BaseChannel::append2All($ukey,$jRet);
			}
			else
			{
				return null;
			}

			return $jRet;
		}
	}
	
	//从唱吧获取的电台基类
	class CCChannel extends BaseChannel
	{
		public function getInfo()
		{
			$ret = array();
			$ret['key'] = 'smzy';
			$ret['title'] = '生命之言';
			$ret['logo'] = 'http://www.cathassist.org/radio/logos/smzy.png';
			$ret['desc'] = '听神父分享圣经';
			return $ret;
		}
		public function getBeginDate()
		{
			return mktime(8, 0, 0, 7, 24, 2014);
		}
	
		public function getRadio($date)
		{
			global $refresh;
			$cInfo = $this->getInfo();
			
			$ukey = $cInfo['key'];
			$dtBegin = $this->getBeginDate();
			
			if($date<$dtBegin)
			{
				$date = $dtBegin;
			}
			$strDate = gmdate('Y-m-d',$date);
			$ccfile = './'.$ukey.'/'.$strDate;
			$ccjson = null;
			if($refresh)
			{
				$ccradio = 'http://media.cathassist.org/radio/'.$ukey.'/data/'.$strDate.'.txt';
				$cccontent = file_get_contents($ccradio);		//或是url list
				$ccjson["title"] = $cInfo['title'];
				$ccjson["date"] = $strDate;
				$ccjson["logo"] = $cInfo['logo'];
				$ccjson["desc"] = $cInfo['desc'];
				$i = 0;
				$items = json_decode($cccontent,true);
				foreach($items as $item)
				{
					$ccjson['items'][$i] = array('title'=>$item['title'],'src'=>$item['url']);
					$i++;
				}
				if($i<1)
				{
					return null;
				}
				file_put_contents($ccfile,json_encode($ccjson));
				BaseChannel::append2All($ukey,$ccjson);
			}
			else if(!file_exists($ccfile))
			{
				return null;
			}
			else
			{
				$ccjson = json_decode(file_get_contents($ccfile),true);
			}
			return $ccjson;
		}
	}
	class SmzyChannel extends CXZShouChannel
	{
		public function getInfo() {
			$jRet =  array();
			$jRet['key'] = 'smzy';
			$jRet['albumId'] = 61;
			$jRet['title'] = '生命之言';
			$jRet['logo'] = 'http://www.cathassist.org/radio/logos/smzy.png';
			$jRet['desc'] = '听贾神父讲道';
			return $jRet;
		}
	}
	class HyqdChannel extends CCChannel
	{
		public function getInfo()
		{
			$ret = array();
			$ret['key'] = 'hyqd';
			$ret['title'] = '合一祈祷';
			$ret['logo'] = 'http://www.cathassist.org/radio/logos/hyqd.jpg';
			$ret['desc'] = '一起听神父讲道';
			return $ret;
		}
		
		public function getBeginDate()
		{
			return mktime(8, 0, 0, 5, 2, 2014);
		}
	}
	class CztzdChannel extends CCChannel
	{
		public function getInfo()
		{
			$ret = array();
			$ret['key'] = 'cztzd';
			$ret['title'] = '诚者天之道';
			$ret['logo'] = 'http://www.cathassist.org/radio/logos/cztzd.jpg';
			$ret['desc'] = '一起听神父讲道';
			return $ret;
		}
		
		public function getBeginDate()
		{
			return mktime(8, 0, 0, 3, 27, 2014);
		}
	}
	class JimmyLxChannel extends CXZShouChannel
	{
		public function getInfo() {
			$jRet =  array();
			$jRet['key'] = 'jimmy-lx';
			$jRet['albumId'] = 63;
			$jRet['title'] = 'Jimmy神父谈信仰生活';
			$jRet['logo'] = 'http://www.cathassist.org/radio/logos/jimmy-lx.jpg';
			$jRet['desc'] = '听Jimmy神父谈灵修';
			return $jRet;
		}
	}
	
	//晨星生命之音
	class CxChannel extends BaseChannel
	{
		function getRadio($date)
		{
			global $refresh;
			$strDate = gmdate('Y-m-d',$date);
			$cxfile = './cx/'.$strDate;
			$cxjson = null;
			if(!file_exists($cxfile) or $refresh)
			{
				$cxdate = gmdate("Y-n-j", $date);
				$cxradio = 'http://211.149.237.175//playlist/'.$cxdate.'.txt';
				$cxlist = explode("\n",file_get_contents($cxradio));		//或是url list
				$cnpreg = "/[\x{4e00}-\x{9fa5}]+/u";
				$cxjson["title"] = "晨星生命之音";
				$cxjson["date"] = $strDate;
				$cxjson["logo"] = "http://www.cathassist.org/radio/logos/cx.png";
				$cxjson["desc"] = "我们因爱而相聚";
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
				if($i<1)
				{
					return null;
				}
				file_put_contents($cxfile,json_encode($cxjson));
				BaseChannel::append2All("cx",$cxjson);
			}
			else
			{
				$cxjson = json_decode(file_get_contents($cxfile),true);
			}

			unset($cxjson["desc"]);
			return $cxjson;
		}
	}
	
	//福音爱广播
	class AiChannel extends CXZShouChannel
	{
		public function getInfo() {
			$jRet =  array();
			$jRet['key'] = 'ai';
			$jRet['albumId'] = 60;
			$jRet['title'] = '福音i广播';
			$jRet['logo'] = 'http://www.cathassist.org/radio/logos/ai.png';
			$jRet['desc'] = '来自8090的声音';
			$jRet['item'] = 'i的签到簿';
			return $jRet;
		}
	}

	//Lauda Sion
	class LSChannel extends CXZShouChannel
	{
		public function getInfo() {
			$jRet =  array();
			$jRet['key'] = 'ls';
			$jRet['albumId'] = 66;
			$jRet['title'] = 'Lauda Sion';
			$jRet['logo'] = 'http://www.cathassist.org/radio/logos/LaudaSion.jpg';
			$jRet['desc'] = '天主教每日圣经诵读';
			$jRet['item'] = '读经与诵读节选';
			return $jRet;
		}
	}


	//梵蒂冈中文广播
	class VacnChannel extends CXZShouChannel
	{
		public function getInfo() {
			$jRet =  array();
			$jRet['key'] = 'vacn';
			$jRet['albumId'] = 50;
			$jRet['title'] = '梵蒂冈中文广播';
			$jRet['logo'] = 'http://www.cathassist.org/radio/logos/vacn.jpg';
			$jRet['desc'] = '每天半小时来自宗座的声音';
			$jRet['item'] = '梵蒂冈中文广播';
			return $jRet;
		}
	}
	
	//每日福音
	class GosChannel extends BaseChannel
	{
		function getRadio($date)
		{
			global $refresh;
			
			$strDate = gmdate('Y-m-d',$date);
			$gosfile = './gos/'.$strDate;
			$gosjson = null;
			if(!file_exists($gosfile) or $refresh)
			{
				$gosjson["title"] = "每日福音";
				$gosjson["date"] = $strDate;
				$gosjson["logo"] = "http://www.cathassist.org/radio/logos/gos.jpg";
				$gosjson["desc"] = "一起聆听主的教诲";
				$itemsrc = "http://media.cathassist.org/thought/mp3/".$strDate.".mp3";
				if(url_exists($itemsrc))
				{
					$title = "每日福音";
					$gosjson['items'][0] = array('title'=>$title,'src'=>$itemsrc);
					file_put_contents($gosfile,json_encode($gosjson));
					BaseChannel::append2All("gos",$gosjson);
				}
				else
				{
					return null;
				}
			}
			else
			{
				$gosjson = json_decode(file_get_contents($gosfile),true);
			}
			return $gosjson;
		}
	}
	
	date_default_timezone_set("UTC");	
	$channel = "";
	if(array_key_exists("channel",$_GET))
	{
		$channel = $_GET["channel"];
	}
	
	$date = time()+3600*8;
	if(array_key_exists("date",$_GET))
	{
		$argDate = DateTime::createFromFormat('Y-m-d',$_GET["date"]);
		if($argDate == false)
		{
			$date = time();
		}
		else
		{
			$date = $argDate->getTimestamp();
		}

		$date = $date+3600*8;
		if($date>time()+3600*8)
		{
			$date=(time()+3600*8);
		}
	}

	$refresh = false;
	if(array_key_exists("r",$_GET))
	{
		if($_GET["r"] == "1" or $_GET["r"]=="true")
		{
			$refresh = true;
		}
	}
	
	$cc = BaseChannel::createChannel($channel);
	$ret = $cc->getRadio($date);
	$count = 0;
	while($ret==null && $count<10)
	{
		//查询历史十天内的数据
		$date = $date-3600*24;
		$ret = $cc->getRadio($date);
		$count++;
	}
	if($ret==null)
	{
		die("Something is wrong about this channel.");
	}
	
	header('Content-type: application/json;text/html;charset=utf-8;');
	echo(json_encode($ret));
?>
