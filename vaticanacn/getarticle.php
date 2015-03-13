<?php
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
	
	
	function getList($from,$count)
	{
		$sql = 'select id,title,picurl,cate,time from vaticanacn where id<'.$from.' order by id desc limit '.$count.';';
		if($from<0)
		{
			$sql = 'select id,title,picurl,cate,time from vaticanacn order by id desc limit '.$count.';';
		}
		$result = mysql_query($sql);
		$i = 0;
		while ($row = mysql_fetch_array($result))
		{
			$ret[$i] = array('id'=>$row['id'],'title'=>$row['title'],'pic'=>$row['picurl'],'cate'=>$row['cate'],'time'=>$row['time']);
			$i++;
		}
		return $ret;
	}
	function getItem($id)
	{
		$sql = 'select id,title,picurl,local,cate,time from vaticanacn where id='.$id.';';
		$result = mysql_query($sql);
		if ($row = mysql_fetch_array($result))
		{
			$contents = file_get_contents('./'.$row['local']);
			$begin = strpos($contents,'<div class="content">')+21;
			$end = strpos($contents,'</div><br/><br/><a class="src"',$begin);
			$content = substr($contents,$begin,$end-$begin);
			$ret = array('id'=>$row['id'],'title'=>$row['title'],'pic'=>$row['picurl'],'cate'=>$row['cate'],'time'=>$row['time'],'content'=>$content);
		}
		return $ret;
	}
	
	if(array_key_exists("type",$_GET))
	{
		if($_GET["type"]!="jsonp")
		{
			die();
		}
	}
	
	if(array_key_exists("mode",$_GET))
	{
		if($_GET["mode"]=="list")
		{
		//http://cathassist.org/vaticanacn/getarticle.php?type=jsonp&mode=list&callback=10&from=479&count=200
			$from = -1;
			$count = 10;
			if(array_key_exists("from",$_GET))
			{
				$from = (int)($_GET['from']);
			}
			if(array_key_exists("count",$_GET))
			{
				$count = (int)($_GET['count']);
			}
			echo $_GET['callback'].'('.json_encode(getList($from,$count)).')';
		}
		else if($_GET["mode"]=="item")
		{
			if(array_key_exists("id",$_GET))
			{
				$id = $_GET["id"];
				echo $_GET['callback'].'('.json_encode(getItem($id)).')';
			}
			else
			{
				echo $_GET['callback'].'()';
			}
		}
	}
?>