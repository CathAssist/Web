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


deldir('json');
mkdir('json');

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

$jsonAll = null;
$jA = 0;

$fa = fopen('json/index.json',"w");

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
	$lstr = sprintf("%02d",$c).'.json';
	$art = file_get_contents($href);
	$art = iconv('GBK', 'UTF-8', $art);
	$art=str_replace(chr(13),'<br>',$art);$art=str_replace(chr(32),'&nbsp;',$art);
	$jsonS = null;
	$js = 0;
	$fs = fopen('json/'.$lstr,'w');
	$jsonS['title'] = $title->textContent;
	$jsonS['purl'] = '/catechism';
	$jsonS['ptitle'] = '天主教教理';
	$jsonS['text'] = $art;
	echo('<a href="content/'.$lstr.'">'.$title->textContent.'</a>'.$href.'<br/>');
	
	if($c==1)
	{
		$jsonAll[$jA]['type'] = 'a';
		$jsonAll[$jA]['url'] = '';
		$jsonAll[$jA]['text'] = '前言';
		$jA++;
	}
	else if($c==3)
	{
		$jsonAll[$jA]['type'] = 'a';
		$jsonAll[$jA]['url'] = '';
		$jsonAll[$jA]['text'] = '卷一 信仰的宣认';
		$jA++;
	}
	else if($c==11)
	{
		$jsonAll[$jA]['type'] = 'a';
		$jsonAll[$jA]['url'] = '';
		$jsonAll[$jA]['text'] = '卷二 基督奥迹的庆典';
		$jA++;
	}
	else if($c==20)
	{
		$jsonAll[$jA]['type'] = 'a';
		$jsonAll[$jA]['url'] = '';
		$jsonAll[$jA]['text'] = '卷三 在基督内的生活';
		$jA++;
	}
	else if($c==31)
	{
		$jsonAll[$jA]['type'] = 'a';
		$jsonAll[$jA]['url'] = '';
		$jsonAll[$jA]['text'] = '卷四 基督徒的祈祷';
		$jA++;
	}
	
	$jsonAll[$jA]['type'] = 't1';
	$jsonAll[$jA]['url'] = '/catechism/chapter/'.$lstr;
	$jsonAll[$jA]['text'] = $title->textContent;
	$jA++;
	
	fwrite($fs,json_encode($jsonS,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
	fclose($fs);
}

fwrite($fa,json_encode($jsonAll,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
echo("asdfasdfasdf");
fclose($fa);
?>