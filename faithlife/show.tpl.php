<?php defined('IN_WX') or exit('Access Denied'); ?>
<html>
<head>
<title><?=$v['title']?>_信仰生活_天主教小助手</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<link href="<?=SITE_URL?>faithlife/articles.css" type="text/css" rel="stylesheet">
</head>
<body>
<div class="topic">
<span class="current"><a href="<?=SITE_URL?>">首页</a> › <a href="<?=SITE_URL?>faithlife/index.php">信仰生活</a> › <a href="<?=SITE_URL?>faithlife/list.php?ctype=<?=(int)($k/100)?>"><?=$ctypearr[(int)($k/100)]?></a></span>
<h1 class="topic-title"><?=$v['title']?></h1>
</div>
<div class="content"><?=$cinfo['content']?></div>
<br/><br/><a class="src" href="<?=$v['url']?>">>>>原始文章</a>
</body>
<script type="text/javascript" language="javascript" src="/include/googleanalysis.js"></script>
<script type="text/javascript" language="javascript" src="/include/common.js"></script>
<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function(){SetWechatShare("<?=$v['title']?>",window.location.href,"<?=$v['pic']?>","<?=$v['title']?>");});
</script>
</html>