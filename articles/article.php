<?php
	require_once("../include/dbconn.php");
	require_once("../include/define.php");
	require_once("../users/user.class.php");
	if(!User::isLogin())
	{
		die("非法访问！");
	}
	if(!isset($_POST['mode']))
	{
		die("未找到参数1！");
	}
	
	function writeArticle($id,$title,$author,$content,$topic,$src)
	{
		$filestr = "./articles/".$id.".html";
		$link = ROOT_WEB_URL.'articles/'.$filestr;
		$topicName = "未知";
		$result = mysql_query('select id,name from article_topic where id='.$topic.'');
		while ($row = mysql_fetch_array($result))
		{
			$topicName = $row['name'];
		}
		
		$fp = fopen($filestr,"w");
		if(!$fp)
		{
			return false;
		}
		else
		{
			fwrite($fp,'<html><head><title>'.$title.'</title><meta http-equiv=Content-Type content="text/html;charset=utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"><meta name="apple-mobile-web-app-capable" content="yes"><meta name="apple-mobile-web-app-status-bar-style" content="black"><meta name="format-detection" content="telephone=no"><link href="../articles.css" type="text/css" rel="stylesheet"></head><body><div class="topic"><span class="current"><a href="/">首页</a> › <a href="../index.php">小助手推荐</a> › <a href="../index.php?topic='.$topic.'">'.$topicName.'</a></span><h1 class="topic-title">'.$title.'</h1></div><div class="content">'.$content.'</div>');
			if(!empty($src))
			{
				fwrite($fp,'<br/><a class="src" href="'.$src.'">>>>原始文章</a>');
			}
			fwrite($fp,'</body><script type="text/javascript" language="javascript" src="/include/googleanalysis.js"></script><script type="text/javascript" language="javascript" src="http://cathassist.org/include/common.js"></script><script type="text/javascript">document.addEventListener("DOMContentLoaded", function(){SetWechatShare("'.$title.'","'.$link.'","http://cathassist.org/logo.jpg","'.$title.'");})</script></html>');
		}
		fclose($fp);
		return true;
	}
	
	$mode = $_POST['mode'];
	if($mode=='del')
	{
		if(!isset($_POST['id']))
		{
			die("未找到参数2！");
		}
		$id=htmlspecialchars($_POST['id']);
		mysql_query('delete from articles where id='.$id);
		echo("已删除指定文章！");
	}
	else if($mode='add')
	{
		$title = "";			//标题
		$author = "匿名";		//作者
		$content = "";			//内容
		$topic = "-1";			//分类号
		$srcurl = "";			//原始链接
		$user = User::getName();	//当前登录的帐号
		
		if(isset($_POST['title']))
			$title = $_POST['title'];
		if(isset($_POST['author']))
			$author = $_POST['author'];
		if(isset($_POST['content']))
			$content = $_POST['content'];
		if(isset($_POST['topic']))
			$topic = $_POST['topic'];
		if(isset($_POST['src']))
			$srcurl = $_POST['src'];
		
		mysql_query('insert into articles(title,content,author,topic,src,user) values("'.mysql_real_escape_string($title).'","'.mysql_real_escape_string($title).'","'.mysql_real_escape_string($title).'",'.$topic.',"'.mysql_real_escape_string($srcurl).'","'.$user.'")');
		$newID = mysql_insert_id();
		writeArticle($newID,$title,$author,$content,$topic,$srcurl);
		echo("文章提交成功！");
	}
?>