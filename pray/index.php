<?php
require_once("../include/dbconn.php");
require_once("../include/define.php");
require_once("../users/user.class.php");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
?>
<head>
	<title>代祷本——天主教小助手</title>
	<meta http-equiv=Content-Type content="text/html;charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<script type="text/javascript" language="javascript" src="/js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript">
	function postText()
	{
		var nick = $("#input_name").val();
		var text = $("#input_text").val();
		var cap = $("#input_cap").val();
		
		if(text == '在此输入你的代祷意向，然后点击提交')
		{
			alert("请输入代祷意向");
			return;
		}
		
		
		$.post("./update.php",{'name':nick,'text':text,'cap':cap},function(msg){
				if(msg==""){
					window.location.reload();
					window.scrollTo();
				}else{
					alert(msg);
				}
			});
	}
	<?php
if(User::isLogin())
{
	echo('function delPray(id)
{
$.post("./pray.php",{"mode":"del","id":id},function(msg){
		parent.location.reload();
	});
}');
}
?>
	$(function(){
		//算术验证
		$("#getcode_math").click(function(){
			$(this).attr("src",'/include/captcha/code_math.php?' + Math.random());
		});
	});
	</script>
	<style type="text/css">
	.css_div_class
	{
		margin: 10px 0 0 0;
		padding: 10px;
		border: 0;
		border: 1px dotted #785;
		background: #f5f5f5;
		font-family: "Courier New",monospace;
		font-size: 12px;
	}
	div p
	{
		text-indent: 0em;
		font-family: "Microsoft JhengHei",SimSun,monospace;
		color:#000000;
		font-size: 15px;
		font-weight:bold;
	}
	div span
	{
		color:#999;
	}
	
	div.tips p
	{
		color:#990000;
		margin: auto;
	}
	
	input{}
	
	.css_btn_class {
		font-size:16px;
		font-family:Arial;
		font-weight:normal;
		-moz-border-radius:8px;
		-webkit-border-radius:8px;
		border-radius:8px;
		border:1px solid #dcdcdc;
		padding:9px 18px;
		text-decoration:none;
		background:-webkit-gradient( linear, left top, left bottom, color-stop(5%, #ededed), color-stop(100%, #dfdfdf) );
		background:-moz-linear-gradient( center top, #ededed 5%, #dfdfdf 100% );
		background:-ms-linear-gradient( top, #ededed 5%, #dfdfdf 100% );
		filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#dfdfdf');
		background-color:#ededed;
		color:#777777;
		display:inline-block;
		text-shadow:1px 1px 0px #ffffff;
		-webkit-box-shadow:inset 1px 1px 0px 0px #ffffff;
		-moz-box-shadow:inset 1px 1px 0px 0px #ffffff;
		box-shadow:inset 1px 1px 0px 0px #ffffff;
	}.css_btn_class:hover {
		background:-webkit-gradient( linear, left top, left bottom, color-stop(5%, #dfdfdf), color-stop(100%, #ededed) );
		background:-moz-linear-gradient( center top, #dfdfdf 5%, #ededed 100% );
		background:-ms-linear-gradient( top, #dfdfdf 5%, #ededed 100% );
		filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#dfdfdf', endColorstr='#ededed');
		background-color:#dfdfdf;
	}.css_btn_class:active {
		position:relative;
		top:1px;
	}
	</style>
</head>
<html>
<body>
<div class="tips">
	<p>望大家的代祷意向符合以下要求：</p>
	<p>&nbsp;&nbsp;1、请不要在意向中使用任何人的姓名；</p>
	<p>&nbsp;&nbsp;2、请不要在意向中诅咒别人</p>
</div>
<?php
	//先从数据库中获取
//	mysql_query("delete from pray where createtime>(utc_timestamp()-3600);");
	$result = mysql_query("select id,name,text,createtime from pray order by id desc limit 20;");
	if(User::isLogin())
	{
		while ($row = mysql_fetch_array($result))
		{
			echo('<div class="css_div_class"><span  style="width:100%">[<a href="#" onclick="delPray('.$row['id'].')">删除</a>]昵称：'.$row['name'].'  时间：'.date('Y-m-d H:i',strtotime($row['createtime'])+3600*8).'</span><p>'.nl2br($row['text']).'</p></div>');
		}
	}
	else
	{
		while ($row = mysql_fetch_array($result))
		{
			echo('<div class="css_div_class"><span  style="width:100%">昵称：'.$row['name'].'  时间：'.date('Y-m-d H:i',strtotime($row['createtime'])+3600*8).'</span><p>'.nl2br($row['text']).'</p></div>');
		}
	}
?>
<hr/>
<p><strong>提交你的代祷意向：</strong></p>
<form action="update.php" method="post">
	<label for="input_name" style="width:100px;">昵称：</label><input name="name" id="input_name" type="text" value="<?php
		if(isset($_SESSION['name']))
		{echo $_SESSION['name'];}else{ echo '匿名';}
		?>"></input>
	<br/>
	<p>代祷内容：</p>
	<textarea id="input_text" name="text" style="width:100%; height:80px" placeholder="在此输入你的代祷意向，然后点击提交"></textarea><br/>
	</p>
	<p>验证码：<input name="cap" id="input_cap" type="text" maxlength="4" /><img src="/include/captcha/code_math.php" id="getcode_math" title="看不清，点击换一张" align="absmiddle"/></p>
	<div align="center"><a href="#" class="css_btn_class" onclick="postText()">提交</a></div>
</form>
</body>
<script type="text/javascript" language="javascript" src="/include/googleanalysis.js"></script>
<?php require_once("../include/define.php"); echo(getWechatShareScript(ROOT_WEB_URL.'pray/index.php','代祷本——天主教小助手',ROOT_WEB_URL.'pray/icon.png'));?>
</html>