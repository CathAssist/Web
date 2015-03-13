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

error_reporting(E_ERROR);
//$ccc = file_get_contents('http://www.chinacatholic.org/source/modules/article/packdown.php?type=txt&id=3&cid=24');
//$ccc = iconv('GBK', 'UTF-8', $ccc);
//die($ccc);


deldir('content');
mkdir('content');

$contents = file_get_contents("http://www.chinacatholic.org/source/modules/article/packshow.php?id=2&type=txtchapter");
$doc = new DOMDocument();
$doc->loadHTML($contents);
$table = $doc->getElementsByTagName('table')->item(0);
//去除Content中多余元素（table）
if(is_null($table))
{
	echo('adsf');
	return;
}


$fa = fopen('content/index.html',"w");
fwrite($fa,'<head><title>天主教法典——天主教小助手</title><meta http-equiv=Content-Type content="text/html;charset=utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"><meta name="apple-mobile-web-app-capable" content="yes"><meta name="apple-mobile-web-app-status-bar-style" content="black"><meta name="format-detection" content="telephone=no"><link href="../catechism.css" type="text/css" rel="stylesheet"><script type="text/javascript" language="javascript" src="/include/googleanalysis.js"></script></head><body><h2>天主教法典</h2>');

$c = 0;
$trInTable = $table->getElementsByTagName('tr');
foreach ($trInTable as $tr)
{
	if($tr->hasAttributes())
		continue;
	$title = $tr->getElementsByTagName('td')->item(1);
	$url = $tr->getElementsByTagName('td')->item(4);
	$href = $url->firstChild->getAttribute('href');
	++$c;
	$lstr = sprintf("%02d",$c).'.html';
	$art = file_get_contents($href);
	$art = iconv('GBK', 'UTF-8', $art);
	$art=str_replace(chr(13),'<br>',$art);$art=str_replace(chr(32),'&nbsp;',$art);
	$fs = fopen('content/'.$lstr,'w');
	fwrite($fs,'<head><title>'.$title->textContent.'——天主教小助手</title><meta http-equiv=Content-Type content="text/html;charset=utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"><meta name="apple-mobile-web-app-capable" content="yes"><meta name="apple-mobile-web-app-status-bar-style" content="black"><meta name="format-detection" content="telephone=no"><link href="../article.css" type="text/css" rel="stylesheet"></head><script type="text/javascript" language="javascript" src="/include/googleanalysis.js"></script><body><h2>'.$title->textContent.'</h2>');
	echo('<a href="content/'.$lstr.'">'.$title->textContent.'</a>'.$href.'<br/>');
	fwrite($fa,'<div class="url"><a href="'.$lstr.'" class="btn">'.$title->textContent.'</a></div>');
	
	if($c==1)
	{
		fwrite($fs,'<div class="aritle"><p>'.$art.'</p></div><div class="navp"><div class="nav"><a href="" class="btn">上一章</a></div><div class="nav"><a href="index.html" class="btn">返回主目录</a></div><div class="nav"><a href="'.sprintf("%02d",$c+1).'.html" class="btn">下一章</a></div></div></body>');
	}
	else if($c==43)
	{
		fwrite($fs,'<div class="aritle"><p>'.$art.'</p></div><div class="navp"><div class="nav"><a href="'.sprintf("%02d",$c-1).'.html" class="btn">上一章</a></div><div class="nav"><a href="index.html" class="btn">返回主目录</a></div><div class="nav"><a href="" class="btn">下一章</a></div></div></body>');
	}
	else
	{
		fwrite($fs,'<div class="aritle"><p>'.$art.'</p></div><div class="navp"><div class="nav"><a href="'.sprintf("%02d",$c-1).'.html" class="btn">上一章</a></div><div class="nav"><a href="index.html" class="btn">返回</a></div><div class="nav"><a href="'.sprintf("%02d",$c+1).'.html" class="btn">下一章</a></div></div></body>');
	}
	fclose($fs);
}

fwrite($fa,'</body>');
fclose($fa);
?>