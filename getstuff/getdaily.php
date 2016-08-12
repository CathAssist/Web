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
		global $errorcode;
		die('{"error":'.$errorcode.'}');
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
		curl_setopt($mcurl, CURLOPT_POSTFIELDS, '{"from":"'.$date->format('Y-m-d').'","to":"'.$date->format('Y-m-d').'","sdb":false}');//POST数据
		$response = curl_exec($mcurl);//接收返回信息
		
		$response = preg_replace('/<\!(.*?)-->/i','',$response);
		
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

		$daily = array();
		//以数组形式返回
		{
			//弥撒及读经
			$jItem['key'] = "mass";
			$jItem['title'] = "弥撒及读经";
			$jItem['content'] = $stuff_mass;
			array_push($daily,$jItem);
		}
		{
			//诵读
			$jItem['key'] = "let";
			$jItem['title'] = "诵读";
			$jItem['content'] = $stuff_let;
			array_push($daily,$jItem);
		}
		{
			//晨祷
			$jItem['key'] = "lod";
			$jItem['title'] = "晨祷";
			$jItem['content'] = $stuff_lod;
			array_push($daily,$jItem);
		}
		{
			//日祷
			$jItem['key'] = "med";
			$jItem['title'] = "日祷";
			$jItem['content'] = $stuff_med;
			array_push($daily,$jItem);
		}
		{
			//晚祷
			$jItem['key'] = "ves";
			$jItem['title'] = "晚祷";
			$jItem['content'] = $stuff_ves;
			array_push($daily,$jItem);
		}
		{
			//夜祷
			$jItem['key'] = "comp";
			$jItem['title'] = "夜祷";
			$jItem['content'] = $stuff_comp;
			array_push($daily,$jItem);
		}
		{
			//反省
			$jItem['key'] = "thought";
			$jItem['title'] = "反省";
			$jItem['content'] = $stuff_thought;
			$jItem['audio'] = 'http://media.cathassist.org/thought/mp3/'.$date->format('Y-m-d').'.mp3';
			array_push($daily,$jItem);
		}
		{
			//礼仪
			$jItem['key'] = "ordo";
			$jItem['title'] = "礼仪";
			$jItem['content'] = $stuff_ordo;
			array_push($daily,$jItem);
		}
		{
			//圣人传记
			$jItem['key'] = "saint";
			$jItem['title'] = "圣人传记";
			$jItem['content'] = $stuff_saint;
			array_push($daily,$jItem);
		}

	
		for ($i = 0; $i<count($daily); $i++) {
			$daily[$i]['link'] = ROOT_WEB_URL.'getstuff/getstuff.php?date='.$date->format('Y-m-d').'&mode='.$daily[$i]['key'];
			$iDesc = mb_substr(convert_html_to_text($daily[$i]['content']),0,30,"UTF-8");
			$daily[$i]['desc'] = str_replace("\n", ' ', $iDesc);
		}

		//返回的日课数据
		$jDaily = array();
		$jDaily['date'] = $date->format('Y-m-d');
		$jDaily['verse'] = '你们求，必要给你们；你们找，必要找著；你们敲，必要给你们开';
		$jDaily['section'] = '47:7:7';
		$jDaily['link'] = ROOT_WEB_URL.'getstuff/stuff/'.$date->format('Y-m-d').'.html';
		$jDaily['items'] = $daily;

		$ret = json_encode($jDaily,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

		$ret = str_replace($trimedUtf8,"",$ret);
		
		echo $ret;
		exit();
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
	header("Content-type: application/json; charset=utf-8");

	require_once("chinese_conversion/convert.php");
	$errorcode = 1;
	$mode="";
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
			if($row['valid']>0 and strlen($row["mass"])>5)
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