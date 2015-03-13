<?php
	$nowtime = time()-(time()%(3600*24));
	$updateStr = date(DATE_ATOM,$nowtime);
	echo('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');
	echo('<url><loc>http://www.cathassist.org/</loc><lastmod>'.$updateStr.'</lastmod><changefreq>always</changefreq><priority>1.0</priority></url>');
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
		foreach ($modemap as $key => $value)
		{
			echo('<url><loc>http://www.cathassist.org/getstuff/stuff/'.$datestr.'_'.$value.'.html</loc><lastmod>'.$updateStr.'</lastmod><changefreq>monthly</changefreq><priority>0.6</priority></url>');
		}
		
		$index++;
	}
	while($index<3);
	echo('<url><loc>http://www.cathassist.org/pray/index.php</loc><lastmod>'.$updateStr.'</lastmod><changefreq>always</changefreq><priority>0.8</priority></url>');
	echo("</urlset>");
?>