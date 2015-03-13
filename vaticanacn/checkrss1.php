<?php
	/*
	梵蒂冈电台在2014-09-09进行了改版，此页面用于抓取改版后的内容，仅使用RSS进行抓取
	对应数据库中的id为 2425 以后的
	*/
	
	require_once("../include/dbconn.php");
	require_once("../include/define.php");
	
	libxml_use_internal_errors(true);
	$rssurl = "http://zh.radiovaticana.va/feed/rss-articoli-standard";
	$rsscontent = file_get_contents($rssurl);
	$rss = simplexml_load_string($rsscontent);
	$channel = $rss->channel;
	for($i=(count($channel->item)-1);$i>=0;$i--)
	{
		$item = $channel->item[$i];
		$filestr = 'articles/'.md5($item->link).'.html';
		$result = mysql_query('select id from vaticanacn where local="'.$filestr.'";');
		if(mysql_num_rows($result)<1)
		{
			$ititle = trim($item->title);
			$itcontent = $item->description;
			$itcontent = str_ireplace('Thumbnail.jpg','Articolo.jpg',$itcontent);
			$ittopicID = 32;
			$ittopicName = "其他";
			$itlink = $item->link;
			$itimgurl = "";
			{
				//获取imgurl
				$itbegin = strpos($itcontent,'http://media',0);
				if($itbegin>0)
				{
					$itend = strpos($itcontent,'Articolo.jpg',$itbegin);
					if($itend>0)
					{
						$itimgurl = substr($itcontent,$itbegin,$itend-$itbegin+12);
					}
				}
			}
			
			$fp = fopen($filestr,"w");
			if(!$fp)
			{
				echo($filestr);
				return;
			}
			else
			{
				$content='<html><head><title>'.$ititle.'</title><meta http-equiv=Content-Type content="text/html;charset=utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"><meta name="apple-mobile-web-app-capable" content="yes"><meta name="apple-mobile-web-app-status-bar-style" content="black"><meta name="format-detection" content="telephone=no"><link href="../articles.css" type="text/css" rel="stylesheet"></head><body><div class="topic"><span class="current"><a href="/">首页</a> › <a href="../index.php">普世教会</a> › <a href="../index.php?topic='.$ittopicID.'">'.$ittopicName.'</a></span><h1 class="topic-title">'.$ititle.'</h1></div><div class="content">'.$itcontent.'</div><br/><br/><a class="src" href="'.$itlink.'">>>>原始文章</a></body><script type="text/javascript" language="javascript" src="/include/googleanalysis.js"></script><script type="text/javascript" language="javascript" src="/include/common.js"></script><script type="text/javascript">document.addEventListener("DOMContentLoaded", function(){SetWechatShare("'.$ititle.'","'.ROOT_WEB_URL.'vaticanacn/'.$filestr.'","'.$itimgurl.'","'.$ititle.'");});</script></html>';
				fwrite($fp,$content);
				$result = mysql_query('insert into vaticanacn (title,src,local,time,cate,picurl) values '.'("'.mysql_real_escape_string($ititle).'","'.mysql_real_escape_string($itlink).'","'.mysql_real_escape_string($filestr).'",curdate(),'.$ittopicID.',"'.mysql_real_escape_string($itimgurl).'");');
			}
			fclose($fp);
			echo('<a href="'.ROOT_WEB_URL.'vaticanacn/'.$filestr.'">'.$ititle.'</a></br><br/>');
		}
	}
?>