<?php
require_once("../include/dbconn.php");
require_once("../include/weibo/config.php");
require_once("../include/weibo/saetv2.ex.class.php");

//用于发送微博到微博通
function send2wbto($content)
{
	$c = new SaeTClientV2( WB_AKEY , WB_SKEY , file_get_contents("../include/weibo/token.txt") );
	$ret = $c->update( $content );	//发送微博
	if ( isset($ret['error_code']) && $ret['error_code'] > 0 ) {
		echo "<p>发送失败，错误：{$ret['error_code']}:{$ret['error']}</p>";
	} else {
		echo "<p>发送成功</p>";
	}
}

function add2weibolist($content,$t=0)
{
	if($t==0)
	{
		$t = time()+3600*8;
	}
	mysql_query('insert into weibolist(text,time) values ("'.mysql_real_escape_string($content).'","'.gmdate("Y-m-d H:i:s",$t).'");');
}

function check2updateweibo()
{
	$result = mysql_query('select id,text,time from weibolist where time<"'.gmdate("Y-m-d H:i:s",time()+3600*8).'";');
	while ($row = mysql_fetch_array($result))
	{
		send2wbto($row['text']);
		echo('send to wbto "'.$row['text'].'"'.'&nbsp;&nbsp;time:'.$row['time']);
		mysql_query('delete from weibolist where id='.$row['id'].';');
	}
}
//how to use
//send2wbto('欢迎大家关注天主教小助手的微博');
?>