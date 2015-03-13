<?php
require_once("../getstuff/chinese_conversion/convert.php");
function getHexStr($i)
{
	$fname = dechex($i);
	if(strlen($fname)<2)
	{
		$fname = '0'.$fname;
	}
	return $fname;
}
function mergecitem($item,$t1,$c,$sc)
{
	$czip = 'D:\Projects\zhunei-wechat\web\bible\b\\'.getHexStr($t1).'\\'.getHexStr($c).'.zip';
	echo($czip.'<br/>');
	
	$curitem = $item;
	$lastsitem = null;
	$zip = new ZipArchive();
	if ($zip->open($czip))
	{
		$i = 0;
		while(true)
		{
			$fname = dechex($i);
			if(strlen($fname)<2)
			{
				$fname = '0'.$fname;
			}
			
			$contents = '';
			$fp = $zip->getStream($fname);
			if(!$fp) break;
			
			while (!feof($fp))
			{
				$contents .= fread($fp, 2);
			}
			fclose($fp);
			
			$sitem = null;
			while(true)
			{
				$curitem = $curitem->nextSibling->nextSibling;
				$class = $curitem->getAttribute('class');
				if($class == 'c')
				{
					break;
				}
				else if($class == 's')
				{
					$sitem = $curitem;
					break;
				}
			}
			
			if($sitem)
			{
				$sitem->removeChild($sitem->firstChild);
				$sitem->appendChild(new DOMText(zhconversion_hans($contents)));
				$sitem->setAttribute('value',$i+1);
				$lastsitem = $sitem;
			}
			else
			{
				$sitem = $lastsitem->parentNode->insertBefore(new DOMElement('p'),$lastsitem->nextSibling->nextSibling);
				$sitem->setAttribute('class','s');
				$sitem->setAttribute('value',$i+1);
				$sitem->appendChild(new DOMText(zhconversion_hans($contents)));
			}
//			echo(($i+1).'&nbsp;&nbsp;'.zhconversion_hans($contents).'&nbsp;'.$sitem->textContent.'<br/>');
			$i++;
		}
	}
	
//	die();
}

$zipdir = 'D:\Projects\zhunei-wechat\web\bible\b';

$contents = file_get_contents("bible.xml");
$meta = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
$doc = new DOMDocument();
$doc->loadHTML($meta.$contents);
$t1=0;
$c=0;
$s=0;

$ct = 0;
$t1t = 0;
$st = 0;
$lastcitem = null;
$body = $doc->documentElement->getElementsByTagName('body')->item(0);
foreach ($body->childNodes AS $item)
{
	if($item->nodeName=='#text')
		continue;
	$class = $item->getAttribute('class');
	if($class=='t1')
	{
		$t1content = $item->textContent;
		if($c!=$ct)
		{
			die('error:'.$t1.'ct:'.$ct.'c'.$c);
		}
		if($t1>0)
		{
			mergecitem($lastcitem,$t1t-1,$c-1,$s);
			echo($t1.'&nbsp;'.$c.'&nbsp;'.$s.'<br/>');
		}
		$t1++;
		$c = 0;
		$ct = 0;
//		$s = 0;
//		$st = 0;
		
		if($t1==1)
		{
			//'旧约圣经'
		}
		else if($t1==47)
		{
			//'新约圣经'
		}
		
		
		$t1dir = $zipdir.'\\'.getHexStr($t1t);
		while(!file_exists($t1dir))
		{
			$t1t++;
			if($t1t>100)
			{
				die('error!!!');
			}
			$t1dir = $zipdir.'\\'.getHexStr($t1t);
		}
		while(true)
		{
			$czip = $t1dir.'\\'.getHexStr($ct).'.zip';
//			echo($czip.'<br/>');
			if(file_exists($czip))
			{
				$ct++;
			}
			else break;
		}
		$t1t++;
	}
	else if($class=='t2')
	{
	}
	else if($class=='c')
	{
		if($c>0)
		{
			mergecitem($lastcitem,$t1t-1,$c-1,$s);
			echo($t1.'&nbsp;'.$c.'&nbsp;'.$s.'<br/>');
		}
		$lastcitem = $item;
		$c++;
		$s = 0;
		$st = 0;
	}
	else if($class=='t3')
	{
	}
	else if($class=='s')
	{
		$s++;
	}
}

mergecitem($lastcitem,$t1t-1,$c-1,$s);
echo($t1.'&nbsp;'.$c.'&nbsp;'.$s.'<br/>');

$fn = fopen('D:\\bible.xml',"w");
fwrite($fn,$doc->saveHTML($body));
fclose($fn);
die();
?>