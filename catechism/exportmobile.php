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


deldir('mobile');
mkdir('mobile');

$contents = file_get_contents("http://www.chinacatholic.org/source/modules/article/packshow.php?id=3&type=txtchapter");
$doc = new DOMDocument();
$doc->loadHTML($contents);
$table = $doc->getElementsByTagName('table')->item(0);
//去除Content中多余元素（table）
if(is_null($table))
{
	echo('adsf');
	return;
}


$fa = fopen('mobile/index.html',"w");
fwrite($fa,'<head><title>天主教教理——天主教小助手</title><meta http-equiv=Content-Type content="text/html;charset=utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"><meta name="apple-mobile-web-app-capable" content="yes"><meta name="apple-mobile-web-app-status-bar-style" content="black"><meta name="format-detection" content="telephone=no"><link href="catechism.css" type="text/css" rel="stylesheet"></head><body><h2>天主教教理</h2>');

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
	$fs = fopen('mobile/'.$lstr,'w');
	fwrite($fs,'<head><title>'.$title->textContent.'——天主教小助手</title><meta http-equiv=Content-Type content="text/html;charset=utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"><meta name="apple-mobile-web-app-capable" content="yes"><meta name="apple-mobile-web-app-status-bar-style" content="black"><meta name="format-detection" content="telephone=no"><link href="article.css" type="text/css" rel="stylesheet"></head><body><h2>'.$title->textContent.'</h2>');
	echo('<a href="mobile/'.$lstr.'">'.$title->textContent.'</a>'.$href.'<br/>');
	fwrite($fa,'<div class="url"><a href="'.$lstr.'" class="btn">'.$title->textContent.'</a></div>');
	fwrite($fs,'<div class="aritle"><p>'.$art.'</p></div></body>');
	fclose($fs);
}

fwrite($fa,'</body>');
fclose($fa);
?>