<?php
	require_once("../include/define.php");
	
	$modemap = array (
		'弥撒及读经' => 'mass',
		'日祷' => 'med',
		'晨祷' => 'lod',
		'晚祷' => 'ves',
		'夜祷' => 'comp',
		'诵读' => 'let',
		'反省' => 'thought',
		'礼仪' => 'ordo',
		'圣人传记' => 'saint',
		);
	$index = 0;
	do
	{
		$datestr = gmdate("Y-m-d",time()+3600*8+$index*3600*24);

		//update stuff force
		file_get_contents("http://www.xiaozhushou.org/api.php?op=get_daily&date=".$datestr."&r=1");
		echo("http://www.xiaozhushou.org/api.php?op=get_daily&date=".$datestr."&r=1"."<br/>");
		
		$stuffstr = 'stuff/'.$datestr.'.html';
		$fs=null;
		if(!file_exists($stuffstr))
		{
			$fs = fopen($stuffstr,"w");
			if(!$fs)
			{
				echo "System Error";
				die();
			}
			fwrite($fs,'<head><meta http-equiv=Content-Type content="text/html;charset=utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"><meta name="apple-mobile-web-app-capable" content="yes"><meta name="apple-mobile-web-app-status-bar-style" content="black"><meta name="format-detection" content="telephone=no"><link rel="stylesheet" type="text/css" href="../stuff.css" /><title>日课及读经('.$datestr.')</title></head><body><h2>日课及读经('.$datestr.')</h2><div class="group">');
		}
		foreach ($modemap as $key => $value)
		{
			$filestr = 'stuff/'.$datestr.'_'.$value.'.html';
			if($fs!=null)
			{
				fwrite($fs,'<div class="url"><a href="'.ROOT_WEB_URL.'getstuff/'.$filestr.'" class="btn">'.$key.'</a></div>');
			}
			if(!file_exists($filestr))
			{
				$fp = fopen($filestr,"w");
				if(!$fp)
				{
					echo "System Error";
					die();
				}
				else
				{
					$url = ROOT_WEB_URL."getstuff/getstuff.php?date=".$datestr."&mode=".$value;
					$contents = file_get_contents($url); 
					fwrite($fp,$contents);
					fclose($fp);
					echo("generate file '".$filestr."'<br/>");
				}
			}
		}
		if($fs!=null)
		{
			fwrite($fs,'</div></body>');
			fclose($fs);
		}
		
		$index++;
	}
	while($index<5);
	
	//检查梵蒂冈广播电台
	file_get_contents(ROOT_WEB_URL."vaticanacn/checkrss.php");
	
	echo("<br/><br/><h1>done</h1>");
	echo('<script type="text/javascript" language="javascript" src="/include/googleanalysis.js"></script>');
?>