<html>
<head>
<title>管理员登录——天主教小助手</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<script type="text/javascript">
function onlogin()
{
	var user = username.value;
	var pass = password.value;
	
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			alert(xmlhttp.responseText);
			parent.location.reload();
			return;
		}
	}
	xmlhttp.open("POST","./login.php",true);
	xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	xmlhttp.send("submit=1&username="+user+"&password="+pass+"");
}
function onlogout()
{
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			parent.location.reload();
			return;
		}
	}
	xmlhttp.open("POST","./logout.php",true);
	xmlhttp.send();
}

</script>
</head>
<body>
<center>
<?php
require_once("../include/dbconn.php");
require_once("../include/define.php");
require_once("./user.class.php");
if(User::isLogin())
{
	echo "欢迎你，".User::getName();
	echo '<input type="button" name="btnLogin" value=" 注 销 " onclick="onlogout()"/>';
	echo '<h2><a href="/articles/publish.php">发表『主内分享』文章</a></h2>';
}
else
{
	echo('<div>
<label for="username" style="display:inline-block;width:80px;">用户名:</label>
<input id="username" name="username" type="text"/></br>
<label for="password" style="display:inline-block;width:80px;">密码:</label>
<input id="password" name="password" type="password"/></br>
<input type="button" name="btnLogin" value="  确 定  " onclick="onlogin()"/>
</div>');
}
?>
</center>
</body>