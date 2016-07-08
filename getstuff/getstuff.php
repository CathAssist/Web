<?php
	require_once("../include/dbconn.php");
	require_once("../include/define.php");
	require_once("../wechat/html2text.php");
	
	
	function unicode2utf8($str)
	{
			if(!$str) return $str;
			$decode = json_decode($str);
			if($decode) return $decode;
			$str = '["' . $str . '"]';
			$decode = json_decode($str);
			if(count($decode) == 1){
					return $decode[0];
			}
			return $str;
	}
	function error()
	{
		global $isjson;
		global $errorcode;
		
		if($isjson)
			die('{"error":'.$errorcode.'}');
		else
			die('<error>'.$errorcode.'</error>');
	}
	
	function getstufferror()
	{
		global $date;
		global $isupdate;
		global $errorcode;
		if($isupdate)
		{
			mysql_query("update stuff set lastupdate=curdate() where time='".$date->format('Y-m-d')."';");
		}
		else
		{
			mysql_query("insert into stuff (time,valid,lastupdate) values ('".$date->format('Y-m-d')."',0,curdate());");
		}	
		$errorcode = 2;
		error();
	}
	
	function getstuff()
	{
		global $date;
		global $isjson;
		global $stuff_mass;
		global $stuff_med;
		global $stuff_comp;
		global $stuff_let;
		global $stuff_lod;
		global $stuff_thought;
		global $stuff_ordo;
		global $stuff_ves;
		global $stuff_saint;
				
		$mcurl = curl_init();
		curl_setopt($mcurl,CURLOPT_URL,"http://mhchina.a24.cc/api/v1/getstuff/");
		curl_setopt($mcurl, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
	//	curl_setopt($mcurl, CURLOPT_HTTPHEADER, $header);//设置HTTP头
		curl_setopt($mcurl, CURLOPT_POST, 1);//设置为POST方式
		curl_setopt($mcurl, CURLOPT_USERAGENT, "Dalvik/2.1.0 (Linux; U; Android 5.1.1; YQ601 Build/LMY47V)");
		curl_setopt($mcurl, CURLOPT_POSTFIELDS, '{"sdb":false,"to":"'.$date->format('Y-m-d').'","from":"'.$date->format('Y-m-d').'"}');//POST数据
		$response = curl_exec($mcurl);//接收返回信息
		$response = preg_replace('/<\!-.*->/i','',$response);
		
		$json = json_decode($response,true);
		if($json==null)
		{
			getstufferror();
		}
		
		{
			//获取返回的数据
			$json_date = $json[$date->format('Y-m-d')];
			if($json_date == null)
			{
				getstufferror();
			}
			
			$json_mass = $json_date['mass'];
			if($json_mass)
				$stuff_mass = zhconversion_hans($json_mass['content']);
			
			$json_med = $json_date['med'];
			if($json_med)
				$stuff_med = zhconversion_hans($json_med['content']);
			
			$json_comp = $json_date['comp'];
			if($json_comp)
				$stuff_comp = zhconversion_hans($json_comp['content']);
			
			$json_let = $json_date['let'];
			if($json_let)
				$stuff_let = zhconversion_hans($json_let['content']);

			$json_lod = $json_date['lod'];
			if($json_lod)
				$stuff_lod = zhconversion_hans($json_lod['content']);

			$json_thought = $json_date['thought'];
			if($json_thought)
				$stuff_thought = zhconversion_hans($json_thought['content']);
			
			$json_ordo = $json_date['ordo'];
			if($json_ordo)
				$stuff_ordo = zhconversion_hans($json_ordo['content']);
			
			$json_ves = $json_date['ves'];
			if($json_ves)
				$stuff_ves = zhconversion_hans($json_ves['content']);
			
			$json_saint = $json_date['saint'];
			if($json_saint)
				$stuff_saint = zhconversion_hans($json_saint['content']);
			
			insertStuff();
		}
	}
	
	function insertStuff()
	{
		global $date;
		global $isupdate;
		global $isjson;
		global $stuff_mass;
		global $stuff_med;
		global $stuff_comp;
		global $stuff_let;
		global $stuff_lod;
		global $stuff_thought;
		global $stuff_ordo;
		global $stuff_ves;
		global $stuff_saint;
		
		//插入到数据库
		if($isupdate)
		{
			mysql_query("update stuff set mass='".mysql_real_escape_string($stuff_mass)."',med='".mysql_real_escape_string($stuff_med)."',comp='".mysql_real_escape_string($stuff_comp)."',let='".mysql_real_escape_string($stuff_let)."',lod='".mysql_real_escape_string($stuff_lod)
			."',thought='".mysql_real_escape_string($stuff_thought)."',ordo='".mysql_real_escape_string($stuff_ordo)."',ves='".mysql_real_escape_string($stuff_ves)."',saint='".mysql_real_escape_string($stuff_saint)."',valid=1,lastupdate=curdate() "
			."where time='".$date->format('Y-m-d')."';");
		}
		else
		{		
			$result = mysql_query('insert into stuff (time,mass,med,comp,let,lod,thought,ordo,ves,saint,valid,lastupdate) values '.
			'("'.$date->format('Y-m-d').'","'.mysql_real_escape_string($stuff_mass).'","'.mysql_real_escape_string($stuff_med).'","'.mysql_real_escape_string($stuff_comp).'","'.mysql_real_escape_string($stuff_let).'","'.mysql_real_escape_string($stuff_lod)
			.'","'.mysql_real_escape_string($stuff_thought).'","'.mysql_real_escape_string($stuff_ordo).'","'.mysql_real_escape_string($stuff_ves).'","'.mysql_real_escape_string($stuff_saint).'",1,curdate());');
		}
		gotoend();
	}
	
	function gotoend()
	{
		global $mode;
		global $isjson;
		global $date;
		global $stuff_mass;
		global $stuff_med;
		global $stuff_comp;
		global $stuff_let;
		global $stuff_lod;
		global $stuff_thought;
		global $stuff_ordo;
		global $stuff_ves;
		global $stuff_saint;
		global $trimedUtf8;
		
		$ret["mass"] = ($stuff_mass);
		$ret["med"] = ($stuff_med);
		$ret["comp"] = ($stuff_comp);
		$ret["let"] =  ($stuff_let);
		$ret["lod"] =  ($stuff_lod);
		if($isjson==2)
		{
			$ret["thought"] =  ($stuff_thought);
		}
		else
		{
			$ret["thought"] =  '<audio id="audio" src="'.'http://media.cathassist.org/thought/mp3/'.$date->format('Y-m-d').'.mp3" controls></audio>'.($stuff_thought);
		}
		$ret["ordo"] =  ($stuff_ordo);
		$ret["ves"] =  ($stuff_ves);
		$ret["saint"] =  ($stuff_saint);
		if($isjson>0)
		{
			$ret = json_encode($ret);
			if($isjson==2)
			{
				echo $_GET['callback'].'('.$ret.')';
				die();
			}
		}
		else
		{
			$ret = '<mass>'.htmlspecialchars($stuff_mass, ENT_QUOTES).'</mass><med>'.htmlspecialchars($stuff_med, ENT_QUOTES).'</med><comp>'
			.htmlspecialchars($stuff_comp, ENT_QUOTES).'</comp><let>'.htmlspecialchars($stuff_let, ENT_QUOTES)
			.'<let><lod>'.htmlspecialchars($stuff_lod, ENT_QUOTES).'</lod><thought>'.htmlspecialchars($stuff_thought, ENT_QUOTES).'</thought><ordo>'
			.htmlspecialchars($stuff_ordo, ENT_QUOTES).'</ordo><ves>'.htmlspecialchars($stuff_ves, ENT_QUOTES).'</ves><saint>'.htmlspecialchars($stuff_saint, ENT_QUOTES).'</saint>';
		}
		
		$ret = str_replace($trimedUtf8,"",$ret);
		
		if($mode!="")
		{
			$json = json_decode($ret,true);
			$content = convert_html_to_text($json[$mode]);
			$title = str_replace(PHP_EOL, ' ', mb_substr($content,0,20,"UTF-8"));
			echo'<head><meta name="viewport" content="user-scalable=no, width=device-width"/><meta http-equiv=Content-Type content="text/html;charset=utf-8"><title>'.$title.'</title>
		<link href="/css/stuff.css" type="text/css" rel="stylesheet"></head><html><body>';
			if($mode=="lod")
			{
				$lod_all = $json[$mode];
				$index_h2 = strpos($lod_all,"</h2>",0);
				if($index_h2>4 and $index_h2<128)
				{
					$lod_first = "<p><strong>序经</strong><br><strong>领</strong>：上主，求祢开启我的口。<br><strong>答</strong>：我的口要赞美祢。</p><p><strong>对经</strong>：基督是牧者们的首领，请大家前来朝拜祂，阿肋路亚。</p><p>请大家前来，向上主欢呼，<br>向拯救我们的磐石歌舞。<br>一齐到祂面前感恩赞颂，<br>向祂歌唱圣诗，欢呼吟咏。<br><strong>对经</strong>：基督是牧者们的首领，请大家前来朝拜祂，阿肋路亚。</p><p>因为上主是崇高的天主，<br>是超越诸神的伟大君王；<br>大地深渊都在祂的手中，<br>高山峻岭都是祂的化工，<br>海洋属于祂，因为是祂所创造，<br>陆地属于祂，因为是祂所形成。<br><strong>对经</strong>：基督是牧者们的首领，请大家前来朝拜祂，阿肋路亚。</p><p>请大家前来叩首致敬，<br>向造我们的天主跪拜，<br>因为祂是我们的天主，<br>我们是祂牧养的子民，<br>是祂亲手领导的羊羣。<br><strong>对经</strong>：基督是牧者们的首领，请大家前来朝拜祂，阿肋路亚。</p><p>你们今天要听从祂的声音，<br>不要像在默黎巴那样心硬，<br>不要像在旷野中玛撒那天，<br>你们的祖先看到我的工作，<br>在那里仍然试探我，考验我。<br><strong>对经</strong>：基督是牧者们的首领，请大家前来朝拜祂，阿肋路亚。</p><p>我四十年之久厌恶那一世代，<br>我曾说：这个民族冥顽不灵，<br>他们不肯承认我的道路；<br>因此，我在盛怒之下起誓说：<br>他们绝不得进入我的安居之所。<br><strong>对经</strong>：基督是牧者们的首领，请大家前来朝拜祂，阿肋路亚。</p><p>愿光荣归于父、及子、及圣神。<br>起初如何，今日亦然，直到永远。阿们。<br><strong>对经</strong>：基督是牧者们的首领，请大家前来朝拜祂，阿肋路亚。</p>";
					$index_h2 = $index_h2+5;
					echo substr($lod_all, 0, $index_h2).$lod_first.substr($lod_all, $index_h2);
				}
				else
				{
					echo $lod_all;
				}
			}
			else if($mode=="thought")
			{
				echo $json[$mode];
			}
			else
			{
				echo $json[$mode];
			}
			
			$imgurl = ROOT_WEB_URL."wechat/pics/".$mode."1.jpg";
			$link = ROOT_WEB_URL.'getstuff/stuff/'.$_GET["date"].'_'.$mode.'.html';
			echo '</body><script type="text/javascript" language="javascript" src="/include/googleanalysis.js"></script><script type="text/javascript" language="javascript" src="http://cathassist.org/include/common.js"></script><script type="text/javascript">document.addEventListener("DOMContentLoaded", function(){SetWechatShare("'.$title.'","'.$link.'","'.$imgurl.'","'.$title.'");});</script></html>';
		}
		else
		{
			echo $ret;
		}
		die();
	}
	
	$trimedUtf8 = array(unicode2utf8("\\u0014"));
	/*
	错误码定义
	1 没有日期参数或日期参数不正确
	2 未获取到数据
	3 连接数据库失败
	*/
	//http://mhchina.a24.cc/api/v1/getstuff/
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
	header("Content-type: text/html; charset=utf-8");
	require_once("chinese_conversion/convert.php");
	$isjson = 1;
	$errorcode = 1;
	$mode="";
	if(array_key_exists("type",$_GET))
	{
		if($_GET["type"]=="xml")
			$isjson = 0;
		else if($_GET["type"]=="jsonp")
			$isjson = 2;
	}
	if(array_key_exists("mode",$_GET))
	{
		$mode = $_GET["mode"];
	}
	
	if(array_key_exists("date",$_GET)==false)
	{
		$errorcode = 1;
		error();
	}
	$date = DateTime::createFromFormat('Y-m-d',$_GET["date"]);
	if(date('Y-m-d',strtotime($_GET["date"]))!=$_GET["date"])
	{
		$errorcode = 1;
		error();
	}

	//返回的数据
	$stuff_mass = "";		//弥撒
	$stuff_med = "";		//日祷
	$stuff_comp = "";		//夜祷
	$stuff_let = "";		//诵读
	$stuff_lod = "";		//晨祷
	$stuff_thought = "";	//反省
	$stuff_ordo = "";		//礼仪
	$stuff_ves = "";		//晚祷
	$stuff_saint = "";		//圣人传记
	
	$isupdate = false;
	
	{
		//先从数据库中获取
		$result = mysql_query("select * from stuff where time='".$date->format('Y-m-d')."';");
		if(mysql_num_rows($result)>0)
		{
			$isupdate = true;
			$row = mysql_fetch_array($result);
			if(array_key_exists("force",$_GET) && $row['lastupdate']!=date('Y-m-d'))
			{
				//强制刷新
				getstuff();
			}
			else if($row['valid']>0 and strlen($row["mass"])>5)
			{
				//已经拥有数据可以直接获取
				$stuff_mass = $row["mass"];		//弥撒
				$stuff_med = $row["med"];		//日祷
				$stuff_comp = $row["comp"];		//夜祷
				$stuff_let = $row["let"];		//诵读
				$stuff_lod = $row["lod"];		//晨祷
				$stuff_thought = $row["thought"];	//反省
				$stuff_ordo = $row["ordo"];		//礼仪
				$stuff_ves = $row["ves"];		//晚祷
				$stuff_saint = $row["saint"];		//圣人传记
				
				gotoend();
			}
			else if($row['lastupdate']==date('Y-m-d'))
			{
				$errorcode = 2;
				error();
			}
			else
			{
				getstuff();
			}
		}
		else
		{
			getstuff();
		}
	}	
//	echo json_encode($retArray,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
?>