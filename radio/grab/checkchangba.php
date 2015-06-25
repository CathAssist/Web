<?php
	/*
		抓取唱吧上的音频
		调用方式:
			//生命之言个人主页- 唱吧
			file_get_contents("http://media.cathassist.org/radio/checkchangba.php?ukey=smzy&uid=64844778");
			//诚者天之道个人主页- 唱吧
			file_get_contents("http://media.cathassist.org/radio/checkchangba.php?ukey=cztzd&uid=80430124");
			//合一祈祷个人主页- 唱吧
			file_get_contents("http://media.cathassist.org/radio/checkchangba.php?ukey=hyqd&uid=85110488");
	*/

	chdir(dirname(__FILE__));
	set_time_limit(60*50);
	
	function curl_download($remote, $local) {
		$cp = curl_init($remote);
		$fp = fopen($local, "w");
		curl_setopt($cp, CURLOPT_FILE, $fp);
		curl_setopt($cp, CURLOPT_HEADER, 0);
		curl_setopt($cp, CURLOPT_FOLLOWLOCATION, true);

		curl_exec($cp);
		curl_close($cp);
		fclose($fp);
		
		clearstatcache(true,$local);
	}
	
	$uid = 'invalid-id';
	$ukey = "invalid-key";
	
	if(isset($_GET['uid']) and isset($_GET['ukey']))
	{
		$uid = $_GET['uid'];
		$ukey = $_GET['ukey'];
	}
	
	if(file_exists("./".$ukey) == false)
	{
		die("invalid ukey.");
	}
	
	chdir("./".$ukey);
	echo("change current dir to ".getcwd()."<br/>");
	
	echo("get the uid:".$uid."<br/>");
//	die();
	$page = 0;
	
	$allObjs = array();
	
	$strDate = date("Y-m-d", time());
	$strUrl = 'http://media.cathassist.org/radio/'.$ukey.'/mp3/'.$strDate.'/';
	$strJson = './data/'.$strDate.'.txt';
	$lpath = "./mp3/".$strDate.'/';
	
	if(file_exists($lpath) == false)
		mkdir($lpath);
	
	if(file_exists($strJson))
	{
		$jtmp = json_decode(file_get_contents($strJson));
		if(count($jtmp)>0)
		{
			$allObjs = $jtmp;
		}
	}
	
	$hadNew = 0;
	
	for($page=0; $page<1; $page++)
	{
		$lurl = 'http://changba.com/member/personcenter/loadmore.php?'.'pageNum='.$page.'&userid='.$uid;
		$jlist = file_get_contents($lurl);
		$lobj = json_decode($jlist);
		if(count($lobj) < 1)
		{
			break;
		}
		
		foreach($lobj as $obj)
		{
			$wid = $obj->workid;
			$wtitle = $obj->songname;
			$lfile = $lpath.$wid.'.mp3';
			$rfile = 'http://aliuwmp3.changba.com/userdata/userwork/'.$wid.'.mp3';
			$mp3url = $strUrl.$wid.'.mp3';
			
			$tmNow = time();
			$isContinue = true;
			for($ddd=0; $ddd<20; $ddd++)
			{
				$fff = "./mp3/".date("Y-m-d", $tmNow).'/'.$wid.'.mp3';
				if(file_exists($fff))
				{
					if(filesize($fff)>1024*3)
					{
						$isContinue = false;
						break;
					}
				}
				
				$tmNow = $tmNow - 3600*24;
			}
			
			if($isContinue == false)
			{
				break;
			}
			
			
			if(file_exists($lfile))
			{
				if(filesize($lfile)>1024*3)
				{
					continue;
				}
				else
				{
					unlink($lfile);
				}
			}
			
			//download mp3 to local
			{
				curl_download($rfile,$lfile);
				echo('name:'.$wtitle.'&nbsp;&nbsp;url:'.$rfile."<br/>");
			}
			
			//open page and get mp3 file url
			if(file_exists($lfile)==false or filesize($lfile)<1024*3)
			{
				$ourl = 'http://changba.com/member/personcenter/loadplayurl.php?wid='.$wid;
				$rurl = file_get_contents($ourl);
				$rtext = file_get_contents($rurl);
				$startIndex = strpos($rtext,'var a="');
				if($startIndex > 0)
				{
					$endIndex = strpos($rtext,'.mp3"',$startIndex);
					if($endIndex > 0)
					{
						$rfile = substr($rtext,$startIndex + 7,$endIndex-$startIndex-3);
						
						//get real url from regexp
						$regex='/userwork\/([abc])(\d+)\/(\w+)\/(\w+)\.mp3/';
						$matches = array();
						if(preg_match($regex, $rfile, $matches)){
							if(count($matches)>4)
							{
								$e=base_convert($matches[2],8,10);
								$f=base_convert($matches[3],16,10)/$e/$e;
								$g=base_convert($matches[4],16,10)/$e/$e;
								
								if($matches[1]=='a' and $g%1e3 == $f)
								{
									$rfile = "http://a".$e."mp3.changba.com/userdata/userwork/".$f."/".$g.".mp3";
								}
							}
						}
						
						//download twice
						{
							curl_download($rfile,$lfile);
							echo('name:'.$obj->songname.'&nbsp;&nbsp;url:'.$rfile."<br/>");
						}
					}
				}
			}
			
			if(file_exists($lfile))
			{
				if(filesize($lfile)>1024*3)
				{
					$jsObj = array();
					$jsObj['title'] = $wtitle;
					$jsObj['url'] = $mp3url;
					array_push($allObjs,$jsObj);
					$hadNew = 1;
				}
				else
				{
					unlink($lfile);
				}
			}
		}
		
		if($hadNew>0)
		{
			file_put_contents($strJson,json_encode($allObjs));
			//refresh
			file_get_contents("http://www.cathassist.org/radio/getradio.php?channel=".$ukey.'&date='.$strDate.'&r=1');
		}
//		print_r($lobj);
	}
?>