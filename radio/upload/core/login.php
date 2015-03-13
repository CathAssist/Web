<?php
defined('IN_WEB') or exit('No permission resources.'); 

if( !empty($_POST['username']) && !empty($_POST['password']) ){
	$username = $_POST['username'];
	$password = $_POST['password'];
	if(isset($_adminarr[$username]) && $_adminarr[$username]==$password){
		$_SESSION['admin']=1;
		$_SESSION['AdminName']=$username;
		//登录成功
		header("Location: index.php"); 
		//确保重定向后，后续代码不会被执行 
		exit;
	}
}
if(!empty($_GET['action']) && $_GET['action']=='logout'){
	$_SESSION['admin']=0;
	showmessage('退出成功');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=CHARSET?>" />
<link href="<?=CSS_PATH?>style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=JS_PATH?>jquery-1.4.2.min.js"></script>
<title>电台小助手</title>
</head>

<body>
<div id="container">
	<div id="header">
        <div class="div1">
            <div class="div2">登录</div>
        </div>
    </div>
	<div id="content">
  		<div class="box" style="width: 400px; min-height: 300px; margin-top: 40px; margin-left: auto; margin-right: auto;">
        <div class="heading">
        	<h1><img src="<?=IMG_PATH?>lockscreen.png" alt="" /> 请输入您的登录信息</h1>
        </div>
        <div class="content" style="min-height: 150px; overflow: hidden;">
        <form action="?op=login" method="post" id="form">
        <table style="width: 100%;">
            <tr>
                <td style="text-align: center;" rowspan="4"><img src="<?=IMG_PATH?>login.png" alt="" /></td>
            </tr>
            <tr>
                <td>用户名：<br />
                <input type="text" name="username" value="" style="margin-top: 4px;" />
                <br />
                <br />
                登录密码：<br />
                <input type="password" name="password" value="" style="margin-top: 4px;" />
                <br /></td>
            </tr>
            <tr>
            	<td>&nbsp;</td>
            </tr>
            <tr>
            	<td style="text-align: right;"><a onclick="$('#form').submit();" class="button">登录</a></td>
            </tr>
        </table>
        </form>
        </div>
        </div>
    </div>
</div>
</body>

<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#form').submit();
	}
});
//--></script> 
</html>