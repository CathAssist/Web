<?php
function deldir($dir) {
  $dh=opendir($dir);
  while ($file=readdir($dh)) {
    if($file!="." && $file!="..") {
      $fullpath=$dir."/".$file;
      if(!is_dir($fullpath)) {
          unlink($fullpath);
      } else {
          deldir($fullpath);
      }
    }
  }
  closedir($dh);
  //删除当前文件夹：
  if(rmdir($dir)) {
    return true;
  } else {
    return false;
  }
}

echo('<head><title>创世纪</title><meta http-equiv=Content-Type content="text/html;charset=utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"><meta name="apple-mobile-web-app-capable" content="yes"><meta name="apple-mobile-web-app-status-bar-style" content="black"><meta name="format-detection" content="telephone=no"><link href="article.css" type="text/css" rel="stylesheet">
</head><body>');
deldir('json');
mkdir('json');

$contents = file_get_contents("bible.xml");
$meta = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
$doc = new DOMDocument();
$doc->loadHTML($meta.$contents);
$t1=0;
$c=0;
$tpath="";
$body = $doc->documentElement->getElementsByTagName('body')->item(0);
$fc = null;
$ft = null;
$fa = fopen('json/index.json',"w");
$jsonAll = null;
$jsonT = null;
$jsonC = null;
$j1 = 0;
$jt = 0;
$jc = 0;
$ctext = '';
$t1content='';
$t2content='';
foreach ($body->childNodes AS $item)
{
	if($item->nodeName=='#text')
		continue;
	$class = $item->getAttribute('class');
	if($class=='t1')
	{
		$t1++;
		$tpath='json/'.sprintf("%03d",$t1);
		mkdir($tpath);
		$t1content = $item->textContent;
		echo('<a href="'.$tpath.'/index.json">'.$doc->saveHTML($item).'</a>');
		$c=0;
		
		if($ft)
		{
			fwrite($ft,json_encode($jsonT,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
			fclose($ft);
			$jsonT = null;
			$jt=0;
		}
		$ft = fopen($tpath.'/index.json',"w");
		$jsonT['title'] = $t1content;
		$jsonT['url'] = '/bible/template/'.sprintf("%03d",$t1);
		if($t1==1)
		{
			$jsonAll[$j1]['type'] = 'a';
			$jsonAll[$j1]['text'] = '旧约圣经';
			$j1++;
		}
		else if($t1==47)
		{
			$jsonAll[$j1]['type'] = 'a';
			$jsonAll[$j1]['text'] = '新约圣经';
			$j1++;
		}
		$jsonAll[$j1]['type'] = 't1';
		$jsonAll[$j1]['url'] = '/bible/template/'.sprintf("%03d",$t1);
		$jsonAll[$j1]['text'] = $t1content;
		$j1++;
	}
	else if($class=='t2')
	{
		$nextItem = $item->nextSibling->nextSibling;
		if($nextItem->getAttribute('class')=='c')
		{
			$t2content=$item->textContent;
			echo($doc->saveHTML($item));
		}
		else
		{
			$jsonC['title'] = $item->textContent;
			echo($doc->saveHTML($item));
		}
		$jsonT['items'][$jt]['type']='b';
		$jsonT['items'][$jt]['text']=$item->textContent;
		$jt++;
	}
	else if($class=='c')
	{
		$c++;
		if($fc)
		{
			$jsonC['text'] = $ctext;
			fwrite($fc,json_encode($jsonC,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
			fclose($fc);
			$jsonC = null;
			$ctext = '';
			$jc==0;
		}
		$fc = fopen($tpath.'/'.sprintf("%03d.json",$c),"w");
		$jsonC['ptitle'] = $t1content;
		$jsonC['purl'] = '/bible/template/'.sprintf("%03d",$t1);
		$jsonC['title'] = $t1content.' '.$item->textContent;
		$jsonC['url'] = '/bible/chapter/'.sprintf("%03d",$t1).'/'.sprintf("%03d.json",$c);
		$jsonC['mp3']='/'.sprintf("%03d",$t1).'/'.sprintf("%03d.mp3",$c);
		$ctext = $ctext.'<h2>'.$t2content.'</h2>';
		$t2content='';
		
		$jsonT['items'][$jt]['type']='c';
		$jsonT['items'][$jt]['text']=$item->textContent;
		$jsonT['items'][$jt]['url']='/bible/chapter/'.sprintf("%03d",$t1).'/'.sprintf("%03d.json",$c);
		$jt++;
		echo($doc->saveHTML($item));
		$ctext = $ctext.'<h3>'.$t1content.' '.$item->textContent.'</h3>';
	}
	else if($class=='t3')
	{
		$ctext = $ctext.$doc->saveHTML($item);
		echo($doc->saveHTML($item));
	}
	else if($class=='s')
	{
		$stext = '<p class="s">'.$item->getAttribute('value').'&nbsp;&nbsp;'.$item->textContent.'</p>';
		$ctext = $ctext.$stext;
	}
}
$jsonC['text'] = $ctext;
fwrite($fc,json_encode($jsonC,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
fclose($fc);

fwrite($ft,json_encode($jsonT,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
fclose($ft);

fwrite($fa,json_encode($jsonAll,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
fclose($fa);
echo('</body>');
?>