<?php
require_once("../include/dbconn.php");
require_once("../include/define.php");
require_once("../users/user.class.php");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>update</title>
<script type="text/javascript" src="/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="nicEdit/nicEdit.js"></script>
<script type="text/javascript">
bkLib.onDomLoaded(function(){
		new nicEditor({buttonList : ['save','bold','italic','underline','left','center','right','justify','ol','ul','fontSize','fontFamily','fontFormat','indent','outdent','image','upload','link','unlink','forecolor','bgcolor','html','xhtml']}).panelInstance('content');
		refreshTopics();
	}
);

//对特殊字符串进行转义
function getinputvalue(obj)
{
	var post_Str = obj.replace(/\+/g, "%2B");//"+"转义    
	var post_Str= post_Str.replace(/\&/g, "%26");//"&"  
	var post_Str= post_Str.replace(/\#/g, "%23");//"#"
	return post_Str;
}

//退出登录
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
	xmlhttp.open("POST","../users/logout.php",true);
	xmlhttp.send();
}

//提交新的类别
function insertTopic()
{
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			refreshTopics();
			return;
		}
	}
	xmlhttp.open("POST","./topic.php",true);
	xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	xmlhttp.send("mode=add&topic="+document.getElementById('insert_topic').value);
}

//刷新所有类别
function refreshTopics()
{
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			var topicCombo = document.getElementById('topics');
			topicCombo.options.length=0;
			
			var opts = eval(xmlhttp.response);
			for(var i=0; i<opts.length; i++)
			{
				topicCombo.options.add(new Option(opts[i].name,opts[i].id,false,true));
			}
			return;
		}
	}
	xmlhttp.open("POST","./topic.php",true);
	xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	
	xmlhttp.send("mode=get");
}

//提交文章
function uploadArticle()
{
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			alert(xmlhttp.response);
			$("#btnInsertArticle")[0].disabled = false;
			
			return;
		}
	}
	
	$("#btnInsertArticle")[0].disabled = true;
	var ct = new nicEditors.findEditor('content');
	
	xmlhttp.open("POST","./article.php",true);
	xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	var params = "mode=add&title="+getinputvalue($('#title')[0].value);
	params+="&author="+getinputvalue($('#author')[0].value);
	params+="&content="+getinputvalue(ct.getContent());
	params+="&topic="+getinputvalue($('#topics').val());
	params+="&src="+getinputvalue($('#src')[0].value);
	xmlhttp.send(params);
}
</script>
<link href="article.css" type="text/css" rel="stylesheet">
</head>
<body>
<?php
//判断是否登录
if(!User::isLogin())
{
	die();
}

echo "欢迎你，".User::getName();
echo '<input type="button" name="btnLogin" value=" 注 销 " onclick="onlogout()"/>';
?>
<div class="article">
	<h3>标题：</h3>
	<input type="text" id="title" class="title"/>
	<h3>作者：</h3>
	<input type="text" id="author" class="author"/>
	<textarea id="content" style="width:90%; height:50%;">
	</textarea>
	<h3>分类：</h3>
	<select id="topics">
	</select>
	<input type="text" id="insert_topic"/>
	<input type="button" id="btnInsertTopic" value="插入分类" onclick="insertTopic()"/>
	<br/>
	<h3>原始链接：</h3>
	<input type="text" id="src" class="src"/>
	<input type="button" id="btnInsertArticle" class="btnSubmit" value="提交文章" onclick="uploadArticle()">
</div>
</body>