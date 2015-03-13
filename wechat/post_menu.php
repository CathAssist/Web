<?php
header("Content-type: text/html; charset=utf-8");
define("ACCESS_TOKEN", "PKsEe-0C2uryPQd6IxliDGPH-puOjLeKP2Z6UH1zr7CxtN9FGUt82nNK8s5UgP3yMv3HmTHWCQt2UsCw6G-fdhPu3yjvDRfKB5hnSOypkjQ");

//创建菜单
function createMenu($data){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".ACCESS_TOKEN);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$tmpInfo = curl_exec($ch);
	if (curl_errno($ch)) {
	  return curl_error($ch);
	}
	curl_close($ch);
	return $tmpInfo;
}
//获取菜单
function getMenu(){
	return file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".ACCESS_TOKEN);
}
//删除菜单
function deleteMenu(){
	return file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".ACCESS_TOKEN);
}


$data = file_get_contents('menu.json');


echo createMenu($data);
echo getMenu();
//echo deleteMenu();
?>