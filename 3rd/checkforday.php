<?php
	require_once("../include/wbto.php");
	require_once("../include/define.php");
	require_once("../wechat/html2text.php");
	
	echo('<h1>获取每日弥撒、日课</h1>');
	echo(file_get_contents(ROOT_WEB_URL.'getstuff/checkstuff.php'));
	
	{
		$dtstr = gmdate("Y-m-d",time()+3600*8);
		$dtnow = intval((time()+3600*8)/(3600*24))*3600*24;
		$url = ROOT_WEB_URL."getstuff/getstuff.php?date=".$dtstr;
		$json = json_decode(file_get_contents($url),true);
		if(isset($json['lod']))
		{
			//6点发送晨祷信息
			add2weibolist('#晨祷# '.mb_substr(convert_html_to_text($json['lod']),0,40,"UTF-8").'…… '.ROOT_WEB_URL.'getstuff/stuff/'.$dtstr.'_lod.html',$dtnow+3600*6);
		}
		if(isset($json['mass']))
		{
			//7点发送弥撒信息
			add2weibolist('#弥撒# '.mb_substr(convert_html_to_text($json['mass']),0,40,"UTF-8").'…… '.ROOT_WEB_URL.'getstuff/stuff/'.$dtstr.'_mass.html',$dtnow+3600*7);
		}
		if(isset($json['thought']))
		{
			//8点发送反省（读经）信息
			add2weibolist('#读经及反省# '.mb_substr(convert_html_to_text($json['thought']),0,60,"UTF-8").'…… '.ROOT_WEB_URL.'getstuff/stuff/'.$dtstr.'_thought.html',$dtnow+3600*8);
		}
		if(isset($json['ves']))
		{
			//18点发送晚祷信息
			add2weibolist('#晚祷# '.mb_substr(convert_html_to_text($json['ves']),0,40,"UTF-8").'…… '.ROOT_WEB_URL.'getstuff/stuff/'.$dtstr.'_ves.html',$dtnow+3600*18);
		}
		if(isset($json['comp']))
		{
			//18点发送晚祷信息
			add2weibolist('#夜祷# '.mb_substr(convert_html_to_text($json['comp']),0,40,"UTF-8").'…… '.ROOT_WEB_URL.'getstuff/stuff/'.$dtstr.'_comp.html',$dtnow+3600*21);
		}
		if(isset($json['saint']))
		{
			//12点发送圣人传记
			add2weibolist('#圣人传记# '.mb_substr(convert_html_to_text($json['saint']),0,60,"UTF-8").'…… '.ROOT_WEB_URL.'getstuff/stuff/'.$dtstr.'_saint.html',$dtnow+3600*12);
		}
	}
?>