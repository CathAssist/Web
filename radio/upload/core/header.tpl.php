<?php 
defined('IN_WEB') or exit('No permission resources.'); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=CHARSET?>" />
<title>电台小助手后台管理</title>
<link href="<?=CSS_PATH?>style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=JS_PATH?>jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<?=JS_PATH?>superfish/js/superfish.js"></script>
<script type="text/javascript" src="<?=JS_PATH?>jqPagination/jquery.jqpagination.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=JS_PATH?>My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    // Confirm Delete
    $('#form').submit(function(){
		
        if ($(this).attr('action').indexOf('del',1) != -1) {
            if (!confirm('删除后您将不能恢复，请确定要删除吗？')) {
                return false;
            }
        }
    });

	$("tbody tr").hover(function(){
        $(this).css({background:"#EEF8F9"});
    	},function(){
        $(this).css({background:"#fff"});
    });
});
</script>
</head>
<body>
<div id="container">
	<div id="header">
        <div class="div1">
            <div class="div2">电台小助手后台管理</div>
            <?php $adminName = $_SESSION['AdminName']; ?>
            <div class="div3"><img src="<?=IMG_PATH?>lock.png" style="position: relative; top: 3px;">&nbsp;您已登录：<span><?php echo $adminName; ?>&nbsp;&nbsp;<a href='?op=login&action=logout' ><font color='white'>安全退出</font></a></span></div>
        </div>
    </div>
    <div id="mainmenu">
    <ul style="display: block;" class="left sf-js-enabled">
        <li id="dashboard" <?php if(empty($cid)) echo 'class="selected"'; ?> ><a class="top" href="./">后台首页</a></li>
		<?php foreach($_admin_channelarr[$adminName] as $v){?>
		<li id="dashboard" <?php if(!empty($cid)&& $cid==$v) echo 'class="selected"'; ?>><a class="top" href="?op=v&cid=<?php echo $v; ?>"><?php echo $_channelarr[$v]['channel']; ?></a></li>
		<?php }?>
    </ul>
    </div>