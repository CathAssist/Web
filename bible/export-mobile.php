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
deldir('mobile');
mkdir('mobile');

$contents = file_get_contents("bible.xml");
$meta = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
$doc = new DOMDocument();
$doc->loadHTML($meta.$contents);
$t1=0;
$c=0;
$tpath="";
$lastcpath="";
$body = $doc->documentElement->getElementsByTagName('body')->item(0);
$fc = null;
$ft = null;
$fa = fopen('mobile/index.html',"w");
fwrite($fa,'<head><title>思高版圣经——天主教小助手</title><meta http-equiv=Content-Type content="text/html;charset=utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"><meta name="apple-mobile-web-app-capable" content="yes"><meta name="apple-mobile-web-app-status-bar-style" content="black"><meta name="format-detection" content="telephone=no"><link href="bible.css" type="text/css" rel="stylesheet"></head><body><h2>思高版圣经</h2>');
$t1content='';
$t2content='';
foreach ($body->childNodes AS $item)
{
	if($item->nodeName=='#text')
		continue;
	$class = $item->getAttribute('class');
	if($class=='t1')
	{
		if($lastcpath!="")
		{
			$lastcpath='../'.sprintf("%03d",$t1).'/'.sprintf("%03d.html",$c-1);
		}
		$t1++;
		$tpath='mobile/'.sprintf("%03d",$t1);
		mkdir($tpath);
		$t1content = $item->textContent;
		echo('<a href="'.$tpath.'/index.html">'.$doc->saveHTML($item).'</a>');
		$c=0;
		
		if($ft)
		{
			fwrite($ft,'</div></body>');
			fclose($ft);
		}
		$ft = fopen($tpath.'/index.html',"w");
		fwrite($ft,'<head><title>'.$t1content.'</title><meta http-equiv=Content-Type content="text/html;charset=utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"><meta name="apple-mobile-web-app-capable" content="yes"><meta name="apple-mobile-web-app-status-bar-style" content="black"><meta name="format-detection" content="telephone=no"><link href="../template.css" type="text/css" rel="stylesheet"></script></head><body>');
		fwrite($ft,'<h2>'.$t1content.'</h2><div class="group">');
		fwrite($fa,'<div class="url"><a href="'.sprintf("%03d",$t1).'/index.html" class="btn">'.$t1content.'</a></div>');
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
			fwrite($fc,'<h2>'.$item->textContent.'</h2>');
			echo($doc->saveHTML($item));
		}
		fwrite($ft,'</div><h3>'.$item->textContent.'</h3><div class="group">');
	}
	else if($class=='c')
	{
		if($c>1)
		{
			$lastcpath='../'.sprintf("%03d",$t1).'/'.sprintf("%03d.html",$c-1);
		}
		$c++;
		if($fc)
		{
			fwrite($fc,'<div class="navp"><div class="nav"><a href="'.$lastcpath.'" class="btn">上一章</a></div><div class="nav"><a href="index.html" class="btn">返回</a></div><div class="nav"><a href="../'.sprintf("%03d",$t1).'/'.sprintf("%03d.html",$c).'" class="btn">下一章</a></div></div></body>');
			fclose($fc);
		}
		$fc = fopen($tpath.'/'.sprintf("%03d.html",$c),"w");
		fwrite($fc,'<head><title>'.$t1content.' '.$item->textContent.'</title><meta http-equiv=Content-Type content="text/html;charset=utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"><meta name="apple-mobile-web-app-capable" content="yes"><meta name="apple-mobile-web-app-status-bar-style" content="black"><meta name="format-detection" content="telephone=no"><link href="../chapter.css" type="text/css" rel="stylesheet"></head><body>');
		fwrite($fc,'<h2>'.$t2content.'</h2>');
		$t2content='';
		fwrite($ft,'<div class="url"><a href="'.sprintf("%03d.html",$c).'" class="btn">'.$item->textContent.'</a></div>');
		echo($doc->saveHTML($item));
		fwrite($fc,'<h3>'.$t1content.' '.$item->textContent.'</h3>');
		fwrite($fc,'<audio src="http://media.cathassist.org/bible/mp3/'.sprintf("%03d",$t1).'/'.sprintf("%03d",$c).'.mp3" controls></audio>');
	}
	else if($class=='t3')
	{
		fwrite($fc,$doc->saveHTML($item));
		echo($doc->saveHTML($item));
	}
	else if($class=='s')
	{
		fwrite($fc,$doc->saveHTML($item));
	}
}
fwrite($fc,'<div class="navp"><div class="nav"><a href="'.'../'.sprintf("%03d",$t1).'/'.sprintf("%03d.html",$c-1).'" class="btn">上一章</a></div><div class="nav"><a href="index.html" class="btn">返回</a></div><div class="nav"><a href="" class="btn">下一章</a></div></div></body>');
fwrite($ft,'</div></body>');
fwrite($fa,'</body>');
fclose($fc);
fclose($ft);
fclose($fa);
echo('</body>');
?>