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
	
	
	$date = time()+3600*8;
	if(array_key_exists("date",$_GET))
	{
		$date = DateTime::createFromFormat('Y-m-d',$_GET["date"])->getTimestamp();
		if($date>(time()+3600*8))
			$date=(time()+3600*8);
	}
	$strDate = gmdate('Y-m-d',$date);
	$cxfile = './cxradio/'.$strDate;
	$cxjson = null;
	if(!file_exists($cxfile))
	{
		$cxdate = gmdate("Y-n-j", $date);
		$cxradio = 'http://radio.cxsm.org/playlist/'.$cxdate.'.txt';
		$cxlist = explode("\n",file_get_contents($cxradio));		//或是url list
		$cnpreg = "/[\x{4e00}-\x{9fa5}]+/u";
		$i = 0;
		foreach($cxlist as $v)
		{
			$v = iconv("GB2312", "UTF-8//IGNORE",trim($v));
			$title = "";
			preg_match_all($cnpreg, substr($v,strrpos($v,'/')), $title);
			$title=implode("", $title[0]);
			$cxjson[$i] = array('title'=>$title,'src'=>cn_urlencode($v));
			++$i;
		}
		file_put_contents($cxfile,json_encode($cxjson));
	}
	else
	{
		$cxjson = json_decode(file_get_contents($cxfile),true);
	}
	echo('<html>
<head>
<title>晨星生命之音网络电台——天主教小助手</title>
<meta http-equiv=Content-Type content="text/html;charset=utf-8">
<meta name="viewport" content="user-scalable=no, width=device-width" />
<link rel="stylesheet" type="text/css" href="vaticanradiowebcast_cn.css"/>
<link rel="stylesheet" type="text/css" href="/js/jPlayer/skin/pink.flag/jplayer.pink.flag.css"/>
<script type="text/javascript" src="/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="/js/jPlayer/js/jquery.jplayer.min.js"></script>
<script type="text/javascript" src="/js/jPlayer/js/jplayer.playlist.min.js"></script>
<style>
body{background-color:#3A2A45}
.pages{
	display:block;
	text-align:center;
	margin-top:10px;
}

.pages a{
	font-size:20px;
	line-height:25px;
	text-decoration:none;
	padding-left:5px;
	padding-right:5px;
}
</style>
<script type="text/javascript">
function initMedia()
{
	//cn
	var cnList = new jPlayerPlaylist({
		jPlayer: "#jquery_jplayer_cn",
		cssSelectorAncestor: "#jp_container_cn"
	}, [');
		foreach($cxjson as $cxu)
		{
			echo('{title:"'.$cxu['title'].'",artist:"晨星电台",mp3:"'.$cxu['src'].'"},');
		}
	echo('],
	{
		playlistOptions:
		{
			autoPlay: true,
			enableRemoveControls: true
		},
		supplied: "mp3",
		wmode: "window",
		smoothPlayBar: true,
		keyEnabled: true
	});
}
$(document).ready(function(){
	initMedia();
	SetWechatShare("晨星生命之音网络电台('.$strDate.')——天主教小助手","http://www.cathassist.org/media/cxradio.php","http://www.cxsm.org/Image/home/logo.png","天主教小助手整理");
});
</script>
</head>
<body>
<center><h2>晨星生命之音网络电台</h2><h3>'.$strDate.'</h3></center>
<div class="player">
	<div id="jquery_jplayer_cn" class="jp-jplayer"></div>
	<div id="jp_container_cn" class="jp-audio">
		<div class="jp-type-playlist">
			<div class="jp-gui jp-interface">
				<ul class="jp-controls">
					<li><a href="javascript:;" class="jp-previous" tabindex="1">previous</a></li>
					<li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
					<li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
					<li><a href="javascript:;" class="jp-next" tabindex="1">next</a></li>
					<li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
					<li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
					<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
					<li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
				</ul>
				<div class="jp-progress">
					<div class="jp-seek-bar">
						<div class="jp-play-bar"></div>
					</div>
				</div>
				<div class="jp-volume-bar">
					<div class="jp-volume-bar-value"></div>
				</div>
				<div class="jp-time-holder">
					<div class="jp-current-time"></div>
					<div class="jp-duration"></div>
				</div>
				<ul class="jp-toggles">
					<li><a href="javascript:;" class="jp-shuffle" tabindex="1" title="shuffle">shuffle</a></li>
					<li><a href="javascript:;" class="jp-shuffle-off" tabindex="1" title="shuffle off">shuffle off</a></li>
					<li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>
					<li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>
				</ul>
			</div>
			<div class="jp-playlist">
				<ul>
					<li>梵蒂冈中文广播</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<span class="pages"><a href="cxradio.php?date='.gmdate('Y-m-d',$date-3600*24).'">上一日</a><a href="cxradio.php?date='.gmdate('Y-m-d',$date+3600*24).'">下一日</a></span>

<br/><center>版权归<a href="http://www.cxsm.org/" target="_blank">晨星生命之音网络广播</a>所有</center>
<center>
<a href="http://mp.weixin.qq.com/mp/appmsg/show?__biz=MzA5MzAwNjcxMQ==&appmsgid=10000002&itemidx=1&sign=e5eb309ec3013bbc30d7d3b5d339d499#wechat_redirect" target="_blank">关注天主教小助手微信</a></center>
</body>
<script type="text/javascript" language="javascript" src="/include/googleanalysis.js"></script>
<script type="text/javascript" language="javascript" src="/include/common.js"></script>
</html>');
?>