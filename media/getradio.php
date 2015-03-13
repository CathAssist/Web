<?php
	require_once("../include/define.php");
	$xmlDoc = new DOMDocument();
	$xmlDoc->load("../wechat/vaticanradio.xml");
	$root = $xmlDoc->documentElement;
	
	$ret = null;
	$ret['mp3'] = '';
	$ret['url'] = $root->getElementsByTagName('MusicUrl')->item(0)->textContent;
	$ret['title'] = '中文广播('.$root->getElementsByTagName('Description')->item(0)->textContent.')';
	$ret['desc'] = '<br/><br/><br/><br/><br/>因软件有bug，你所使用的版本不再支持，强烈建议你更新至最新版本，下载地址:
		<a href="javascript:openLinkInExternal(\'http://app.cathassist.org/desc.html\');" data-ignore="true" target="_blank">http://app.cathassist.org/desc.html</a>';
	
	echo $_GET['callback'].'('.json_encode($ret).')';
?>