<?php
	/*
	"love","爱德与关怀"
	"cath","教会"
	"social","文化与社会"
	"vantican","梵蒂冈文献"
	"meeting","主教会议"
	"one","大公合一运动"
	"family","家庭"
	"youth","青年"
	"peace","正义与和平"
	"politics","政治"
	"talk","宗教与对话"
	"science","科学与伦理"
	"peter","教宗与圣座"
	"spirit","灵修生活"
	"other","其他"*/
	
	require_once("../include/dbconn.php");
	require_once("../include/define.php");
	function get_inner_html($node)
	{
		$innerHTML= '';
		$children = $node->childNodes;
		foreach ($children as $child)
		{
			$innerHTML .= $child->ownerDocument->saveXML( $child );
		}

		return $innerHTML;
	}
	
	function getArticle($link,$title,$filestr)
	{
		$contents = file_get_contents($link);
		$doc = new DOMDocument();
		$doc->loadHTML($contents);
		$content2 = $doc->getElementById('content2');
		//去除Content中多余元素（table）
		if(is_null($content2))
			return;
		$tablesInContent = $content2->getElementsByTagName('table');
		foreach ($tablesInContent as $t)
		{
			$content2->removeChild($t);
		}
		
		$imgurl = "";		
		$imgsInContent = $content2->getElementsByTagName('img');
		foreach ($imgsInContent as $img)
		{
			$imgurl = $img->getAttribute('src');
			break;
		}
		
		$table = $content2->parentNode->parentNode;
		$aTopic = $table->childNodes->item(2);
		$topicName = trim($aTopic->childNodes->item(0)->textContent);
		$topicLocal = "other";
		$topicID = 0;
		$result = mysql_query('select id,local,name from vatican_topic where name="'.$topicName.'"');
		while ($row = mysql_fetch_array($result))
		{
			$topicLocal = $row['local'];
			$topicID = (int)$row['id'];
		}
		
		$fp = fopen($filestr,"w");
		if(!$fp)
		{
			echo($filestr);
			return;
		}
		else
		{
			$content='<html><head><title>'.$title.'</title><meta http-equiv=Content-Type content="text/html;charset=utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"><meta name="apple-mobile-web-app-capable" content="yes"><meta name="apple-mobile-web-app-status-bar-style" content="black"><meta name="format-detection" content="telephone=no"><link href="../articles.css" type="text/css" rel="stylesheet"></head><body><div class="topic"><span class="current"><a href="/">首页</a> › <a href="../index.php">普世教会</a> › <a href="../index.php?topic='.$topicID.'">'.$topicName.'</a></span><h1 class="topic-title">'.$title.'</h1></div><div class="content">'.get_inner_html($content2).'</div><br/><br/><a class="src" href="'.$link.'">>>>原始文章</a></body><script type="text/javascript" language="javascript" src="/include/googleanalysis.js"></script><script type="text/javascript" language="javascript" src="http://cathassist.org/include/common.js"></script><script type="text/javascript">document.addEventListener("DOMContentLoaded", function(){SetWechatShare("'.$title.'","'.ROOT_WEB_URL.'vaticanacn/'.$filestr.'","'.$imgurl.'","'.$title.'");});</script></html>';
			fwrite($fp,$content);
			$result = mysql_query('insert into vaticanacn (title,src,local,time,cate,picurl) values '.'("'.mysql_real_escape_string($title).'","'.mysql_real_escape_string($link).'","'.mysql_real_escape_string($filestr).'",curdate(),'.$topicID.',"'.mysql_real_escape_string($imgurl).'");');
		}
		fclose($fp);
	}
	
//	getArticle("http://zh.radiovaticana.va/articolo.asp?c=727740","title",'articles/123.html');
//	return;
	libxml_use_internal_errors(true);
	$rssurl = "http://zh.radiovaticana.va/rssarticoli.asp";
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
			getArticle($item->link,$ititle,$filestr);
			echo('<a href="'.ROOT_WEB_URL.'vaticanacn/'.$filestr.'">'.$item->title.'</a></br><br/>');
		}
	}
?>