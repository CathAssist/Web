<?php
session_start();
header("Content-type: text/html; charset=utf-8");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>提交圣经金句</title>
<script type="text/javascript">
function onupdate()
{
	var xmlhttp = new XMLHttpRequest();
	var btnUpdate = document.getElementById("btnUpdate");
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			alert(xmlhttp.responseText);
			btnUpdate.disabled = false;
			return;
		}
	}
	
	btnUpdate.disabled = true;
	xmlhttp.open("POST","../lodo/update.php",true);
	xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	var params = "&lodo="+document.getElementById("input_lodo").value;
	xmlhttp.send(params);
}
</script>
</head>
<?php
//登录
if(!isset($_SESSION['userid'])){
    die('请先登录！！！');
}
else
{
	$username = $_SESSION['name'];
	echo "欢迎你，".$username;
echo '<input type="button" name="btnUpdate" id="btnUpdate" value=" 更 新 " onclick="onupdate()"/>';
}
?>
<table style="width: 100%; height: 100%;">
    <tr>
        <td class="style1">圣经金句:</td>
        <td><textarea id="input_lodo" cols="50" rows="15"></textarea></td>
    </tr>
</table>
