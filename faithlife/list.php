<?php 
require '..'.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'common.inc.php';

$ctypearr=array(
	'1'=>'人生信仰',
	'2'=>'福音传播',
	'3'=>'信德文萃',
	'4'=>'礼仪生活',
);

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$ctype = isset($_GET['ctype']) ? intval($_GET['ctype']) : 1;

$where = " ctype='$ctype' ";

$pagesize=10;//每页条数
$offset = $pagesize*($page-1);
$limit = "$offset,$pagesize";

$allnum = $db->get_one( "COUNT(*) AS num",'faithlife',$where);//总条数
$pages = ceil($allnum['num'] / $pagesize);//总页数

$lists = $db->select('*','faithlife',$where,$limit,' inputtime desc ');
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
<h1 class="topic-title"><?=$ctypearr[$ctype]?></h1>
</div>
<div class="content">
<?php if(is_array($lists))foreach($lists as $v){?>
<div class="link"><a href="<?=SITE_URL.$v['nurl']?>"><?=$v['title']?></a></div>
<?php }?>

<?php 
	if($allnum['num']>$pagesize){
		if($page==1){
			echo "<span class='pages'><a href='?ctype=".$ctype."&page=".($page+1)."'>下一页</a></span>";
		}elseif($pages==$page){
			echo "<span class='pages'><a href='?ctype=".$ctype."&page=".($page-1)."'>上一页</a></span>";
		}else{
			echo "<span class='pages'><a href='?ctype=".$ctype."&page=".($page-1)."'>上一页</a><a href='?ctype=".$ctype."&page=".($page+1)."'>下一页</a></span>";
			
		}
	}
?>
</div>
</body>
<script type="text/javascript" language="javascript" src="/include/googleanalysis.js"></script>
</html>