<?php 

require '..'.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'common.inc.php';

$ctypearr=array(
	'1'=>'人生信仰',
	'2'=>'福音传播',
	'3'=>'信德文萃',
	'4'=>'礼仪生活',
	'5'=>'网友分享',
);
?>
<html>
<head>
	<title>信仰生活——天主教小助手</title>
	<meta http-equiv=Content-Type content="text/html;charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<link href="articles.css" type="text/css" rel="stylesheet">
</head>
<body>
<div class="topic">
<span class="current"><a href="/" alt="天主教小助手首页">首页</a> › <a href="index.php">信仰生活</a></span>
</div>
<?php foreach($ctypearr as $k=>$v){?>
<div class="content">
	<div ><h1><a href='list.php?ctype=<?=$k?>'><?=$v?></a></h1></div>
	<?php 
	$lists = $db->select('*','faithlife'," ctype='$k' ",'0,5',' id desc ');
	if(is_array($lists))foreach($lists as $v){
	?>
	<div class="link"><a href="<?=SITE_URL.$v['nurl']?>"><?=$v['title']?></a></div>
	<?php }?>
</div>
<?php }?>
</body>
<script type="text/javascript" language="javascript" src="/include/googleanalysis.js"></script>
</html>