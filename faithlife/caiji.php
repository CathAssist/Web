<?php 
require '..'.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'common.inc.php';
include WX_PATH.'include'.DIRECTORY_SEPARATOR.'collection.class.php';

/*
曾经使用的方式
$urls=array(
	'1'=>'http://www.chinacatholic.org/index.php?m=content&c=rss&rssid=16',//人生信仰
	'2'=>'http://www.chinacatholic.org/index.php?m=content&c=rss&rssid=27',//福音传播
	'3'=>'http://www.chinacatholic.org/index.php?m=content&c=rss&rssid=33',//信德文萃
	'4'=>'http://www.chinacatholic.org/index.php?m=content&c=rss&rssid=39',//礼仪生活
	'5'=>'http://www.chinacatholic.org/index.php?m=content&c=rss&rssid=44',//网友分享
);
$cjconfig=array('sourcecharset'=>'utf-8','sourcetype'=>4);
*/

$urls=array(
	101=>'http://www.chinacatholic.org/category/17.html',//人生信仰
	102=>'http://www.chinacatholic.org/category/18.html',//人生信仰
	103=>'http://www.chinacatholic.org/category/19.html',//人生信仰
	104=>'http://www.chinacatholic.org/category/20.html',//人生信仰
	105=>'http://www.chinacatholic.org/category/21.html',//人生信仰
	106=>'http://www.chinacatholic.org/category/22.html',//人生信仰
	107=>'http://www.chinacatholic.org/category/23.html',//人生信仰
	108=>'http://www.chinacatholic.org/category/24.html',//人生信仰
	109=>'http://www.chinacatholic.org/category/25.html',//人生信仰
	110=>'http://www.chinacatholic.org/category/26.html',//人生信仰

	201=>'http://www.chinacatholic.org/category/28.html',//福音传播
	202=>'http://www.chinacatholic.org/category/29.html',//福音传播
	203=>'http://www.chinacatholic.org/category/30.html',//福音传播
	204=>'http://www.chinacatholic.org/category/31.html',//福音传播
	205=>'http://www.chinacatholic.org/category/32.html',//福音传播

	301=>'http://www.chinacatholic.org/category/34.html',//信德文萃
	302=>'http://www.chinacatholic.org/category/35.html',//信德文萃
	303=>'http://www.chinacatholic.org/category/36.html',//信德文萃
	304=>'http://www.chinacatholic.org/category/37.html',//信德文萃
	305=>'http://www.chinacatholic.org/category/38.html',//信德文萃

	401=>'http://www.chinacatholic.org/category/40.html',//礼仪生活
	402=>'http://www.chinacatholic.org/category/41.html',//礼仪生活
	403=>'http://www.chinacatholic.org/category/42.html',//礼仪生活
	404=>'http://www.chinacatholic.org/category/43.html',//礼仪生活

	501=>'http://www.chinacatholic.org/category/45.html',//网友分享
	502=>'http://www.chinacatholic.org/category/46.html',//网友分享
	503=>'http://www.chinacatholic.org/category/47.html',//网友分享
);
$cjconfig=array('sourcecharset'=>'utf-8','sourcetype'=>5);

$ctypearr=array(
'1'=>'人生信仰',
'2'=>'福音传播',
'3'=>'信德文萃',
'4'=>'礼仪生活',
'5'=>'网友分享',
);

function get_content($url){
	
	if(empty($url)) return false;
	$data=array();

	$html = @file_get_contents($url);
	
	$html = explode('<p class="text-center padding_10">', $html);
	if(is_array($html)) $html1 = explode('</p>', $html[1]);
	$str=$html1[0];
	$strarr=explode('|',$str);
	
	$data['inputtime'] = trim($strarr[0]);
	if(strlen($data['inputtime'])>30)
	{
		$realtime = explode('style="float:right">打印</a>',$strarr[0]);
		$data['inputtime'] = trim($realtime[1]);
	}
	
	//$authorarr = explode("：",$strarr[1]);
	//$data['author'] = trim($authorarr[1]);
	//$comeformarr =explode("：",$strarr[2]);
	//$data['comefrom']=trim($comeformarr[1]);


	$html =explode('div class="xindecontent">',$html[1]);
	//print_r($html[1]);exit;
	if(is_array($html)) $html = explode('</div>', $html[1]);
	$data['content']=str_replace('src="/uploadfile/','src="http://www.chinacatholic.org/uploadfile/',$html[0]);
	//移出margin-left属性
//	$data['content']=str_replace('margin-left: 240px;', '', $data['content']);
//	$data['content'] = preg_replace('/(<p.+?)style=".+?"(>.+?)/i', "$1$2", $data['content']);
	$data['content'] = preg_replace('/margin-left.*[1,10]px;/', '', $data['content']);

	//print_r($html[0]);exit;
//	echo($data['content']);
	return $data;
}


@set_time_limit(600);
foreach($urls as $k=>$url_list){
	$url = collection::get_url_lists($url_list, $cjconfig);
	//var_dump($url );exit;
	if (is_array($url) && !empty($url)){
		foreach ($url as $v) {
			//if (empty($v['url']) || empty($v['title']) || (strpos($v['url'],'www.chinacatholic.org')<1)) continue;
			if (empty($v['url']) || empty($v['title']) || (strpos($v['url'],'www.chinacatholic.org')<1))
			{
				echo('<b>invalid url:'.$v['url'].'</b><br/>');
				continue;
			}
			//$v = new_addslashes($v);
			$v['url'] = str_replace('/index/id','',$v['url']);
			$v['title'] = strip_tags($v['title']);
			$md5 = md5($v['url']);
			if ( !$db->get_one('id','faithlife'," md5url='$md5' ") )
			{
				$cinfo = get_content($v['url']);//获取发布时间、作者、来源、内容

				//生成静态页面
				ob_start();
				include WX_PATH.'faithlife'.DIRECTORY_SEPARATOR.'show.tpl.php';

				$file='faithlife'.DIRECTORY_SEPARATOR.date('Y').DIRECTORY_SEPARATOR.date('m').DIRECTORY_SEPARATOR.md5($v['url']).'.html';
				$nurl=WX_PATH.$file;
				createhtml($nurl);
				
				$addinfo = array('md5url'=>$md5, 'ctype'=>(int)($k/100), 'furl'=>$v['url'], 'title'=>$v['title'], 'nurl'=>$file, 'inputtime'=>$cinfo['inputtime'], 'picurl'=>$v['pic']);
				$addinfo = new_addslashes($addinfo);

				$db->insert($addinfo,'faithlife');
				echo('new '.$file.'</br>');
			}
		}
	}

}
?>