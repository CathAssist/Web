<?php
//define your token
require_once("../include/dbconn.php");
require_once("../include/define.php");
require_once("html2text.php");
define("TOKEN", "test");

$wechatObj = new wechatCallbackapiTest();
$wechatObj->responseMsg();

class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];
		echo $echoStr;
		die('');
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
      	//extract post data
		if (!empty($postStr))
		{
			$resultStr = "";
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			if($postObj->MsgType=="text")
			{
				$resultStr = $this->getTextReply($postObj);
			}
			else
			{
				$resultStr = $this->getDefaltReply($postObj);
			}
			
			//将回复插入到数据库中
			$this->insertIntoDb("",$postStr,$resultStr);
			echo $resultStr;
			exit;
        }
		else
		{
        	echo "";
        	exit;
        }
    }
		
	public function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
	
	private function getDefaltReply($postObj)
	{
		$onelodo = "";
		$result = mysql_query("select lodo from lodo L JOIN (SELECT CEIL(MAX(ID)*RAND()) AS ID FROM lodo) AS m ON L.ID >= m.ID LIMIT 1;");
		if ($row = mysql_fetch_array($result))
		{
			$onelodo = $row['lodo'];
		}
		
		$fromUsername = $postObj->FromUserName;
		$toUsername = $postObj->ToUserName;
		$time = time();
		$desc = "帮助列表：\n0、所有信息\n1、弥撒及读经\n2、日祷\n3、晨祷\n4、晚祷\n5、夜祷\n6、诵读\n7、反省\n8、礼仪\n9、圣人传记\n10、代祷本\n11、梵蒂冈中文电台\n12、常用经文\n13、推荐给朋友\n使用说明：回复数字，获取对应信息。如发送“1”可获取“弥撒及读经”。";
		if($onelodo!="")
		{
			$desc = $onelodo."\n\n".$desc;
		}
		$textTpl = '<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[news]]></MsgType>
			<ArticleCount>1</ArticleCount>
			<Articles>
			<item><Title><![CDATA[使用教程（详情点我）]]></Title><Url><![CDATA['.ROOT_WEB_URL.'wechat/help2.html]]></Url><Description><![CDATA[%s]]></Description><PicUrl><![CDATA['.ROOT_WEB_URL.'wechat/pics/logo.jpg]]></PicUrl></item>
			</Articles>
			<FuncFlag>1</FuncFlag>
			</xml>';
		return sprintf($textTpl, $fromUsername, $toUsername, $time, $desc);
	}
	
	private function getTextReply($postObj)
	{
		$modemap = array (
		'1' => 'mass',
		'2' => 'med',
		'3' => 'lod',
		'4' => 'ves',
		'5' => 'comp',
		'6' => 'let',
		'7' => 'thought',
		'8' => 'ordo',
		'9' => 'saint',
		);
		
		$fromUsername = $postObj->FromUserName;
		$toUsername = $postObj->ToUserName;
		$time = time();
		
		$ArtCount = 0;
		$Articles = "";
		$keyword = trim($postObj->Content);
		if( (strpos($keyword,"经")!==false) or (strpos($keyword,"欢喜")!==false) or (strpos($keyword,"痛苦")!==false) or (strpos($keyword,"荣福")!==false) or (strpos($keyword,"光明")!==false) or (strpos($keyword,"玫瑰")!==false) or (strpos($keyword,"颂")!==false) or (strpos($keyword,"诵")!==false) )
			$keyword = "12";
		
		if($keyword=="0")
		{
			$url = ROOT_WEB_URL."getstuff/getstuff.php?date=".gmdate("Y-m-d",time()+3600*8);
			$json = json_decode(file_get_contents($url),true);
			foreach ($modemap as $key => $value)
			{
				if(isset($json[$value]))
				{
					$Articles = $Articles.$this->getSubArticle($value,trim(convert_html_to_text($json[$value])),0,0);
					$ArtCount = $ArtCount+1;
				}
			}
			{			
				//增加代祷本的第一项
				$textTpl = '<item><Title><![CDATA[%s]]></Title><Url><![CDATA['.ROOT_WEB_URL.'pray/index.php]]></Url><Description><![CDATA[来自于代祷本]]></Description><PicUrl><![CDATA['.ROOT_WEB_URL.'wechat/pics/comp1.jpg]]></PicUrl></item>';
				
				$pray = $this->getPrays(1);
				$index = strpos($pray,"\n",30);
				$title = "代祷意向:\n";
				if($index>-1 and $index<100)
				{
					$title = $title.substr($pray,0,$index);
				}
				else
				{
					$title = $title.mb_substr($pray,0,30,"UTF-8");
				}
				$Articles = $Articles.sprintf($textTpl,$title);
				$ArtCount = $ArtCount+1;
			}
		}
		else if(isset($modemap[$keyword]))
		{
			for($i=0;$i<3;$i++)
			{
				if($i==0)
				{
					$Articles = $Articles.$this->getSubArticle($modemap[$keyword],"",1,$i);
				}
				else
				{
					$Articles = $Articles.$this->getSubArticle($modemap[$keyword],"",0,$i);
				}
				$ArtCount = $ArtCount+1;
			}
		}
		else if($keyword=="10")
		{
			$textTpl = '<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[news]]></MsgType>
				<ArticleCount>1</ArticleCount>
				<Articles>
				<item><Title><![CDATA[代祷意向]]></Title><Url><![CDATA['.ROOT_WEB_URL.'pray/index.php]]></Url><Description><![CDATA[%s]]></Description><PicUrl><![CDATA['.ROOT_WEB_URL.'wechat/pics/comp_l1.jpg]]></PicUrl></item>
				</Articles>
				<FuncFlag>1</FuncFlag>
				</xml>';
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $this->getPrays(5));
			return $resultStr;
		}
		else if($keyword=="11")
		{
			$textTpl = '<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[news]]></MsgType>
				<ArticleCount>1</ArticleCount>
				<Articles>
				<item><Title><![CDATA[梵蒂冈中文电台每日快讯]]></Title><Url><![CDATA['.ROOT_WEB_URL.'media/vaticanradio.html]]></Url><Description><![CDATA[%s]]></Description><PicUrl><![CDATA['.ROOT_WEB_URL.'wechat/pics/radiovatican.png]]></PicUrl></item>
				</Articles>
				<FuncFlag>1</FuncFlag>
				</xml>';
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "点此可进入收听页面\n温馨提示：播放音频会损耗较多流量");
			return $resultStr;
		}
		else if($keyword=="12")
		{
			$textTpl = '<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[news]]></MsgType>
				<ArticleCount>1</ArticleCount>
				<Articles>
				<item><Title><![CDATA[常用经文]]></Title><Url><![CDATA['.ROOT_WEB_URL.'prayer/index.html]]></Url><Description><![CDATA[%s]]></Description><PicUrl><![CDATA['.ROOT_WEB_URL.'wechat/pics/med_l1.jpg]]></PicUrl></item>
				</Articles>
				<FuncFlag>1</FuncFlag>
				</xml>';
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "天主教常用经文，详情请点击我");
			return $resultStr;
		}
		else if($keyword=="13")
		{
			$textTpl = '<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[news]]></MsgType>
				<ArticleCount>1</ArticleCount>
				<Articles>
				<item><Title><![CDATA[推荐给好友]]></Title><Url><![CDATA['.ROOT_WEB_URL.'wechat/help.html]]></Url><Description><![CDATA[%s]]></Description><PicUrl><![CDATA['.ROOT_WEB_URL.'wechat/pics/logo.jpg]]></PicUrl></item>
				</Articles>
				<FuncFlag>1</FuncFlag>
				</xml>';
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "详情请点击我");
			return $resultStr;
		}
		
		if($Articles!="")
		{
			$textTpl = '<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[news]]></MsgType>
				<ArticleCount>%s</ArticleCount>
				<Articles>%s
				</Articles>
				<FuncFlag>1</FuncFlag>
				</xml>';
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $ArtCount, $Articles);
		}
		else
		{
			$resultStr = $this->getDefaltReply($postObj);
		}
		return $resultStr;
	}
	
	private function getPrays($count)
	{
		$article = "";
		//先从数据库中获取
		$result = mysql_query("select name,text,createtime from pray order by id desc limit ".$count.";");
		while ($row = mysql_fetch_array($result))
		{
			if($article!="")
				$article = $article."\n\n";
			if($count==1)
			{
				$article = $row['text'];
			}
			else
			{
				$article = $article.'昵称：'.$row['name'].'  时间：'.date('m-d H:i',strtotime($row['createtime'])+3600*8)."\n".$row['text'];
			}
		}
		return $article;
	}
	
	private function getSubArticle($mode,$content,$isLarge,$offset)
	{
		$titlemap = array (
		'mass' => '弥撒及读经',
		'med' => '日祷经文',
		'lod' => '晨祷经文',
		'ves' => '晚祷经文',
		'comp' => '夜祷经文',
		'let' => '诵读',
		'thought' => '反省',
		'ordo' => '礼仪',
		'saint' => '圣人传记',
		);
		$title = "";
		$url = ROOT_WEB_URL."getstuff/stuff/".gmdate("Y-m-d",time()+3600*8+($offset*24*3600))."_".$mode.".html";
		if($content=="")
		{
			$content = convert_html_to_text(file_get_contents($url));
		}
		$picurl = ROOT_WEB_URL."wechat/pics/".$mode."1.jpg";
		if(isset($titlemap[$mode]))
			$title=$titlemap[$mode];
		$textTpl = '<item><Title><![CDATA[%s]]></Title><Url><![CDATA[%s]]></Url><Description><![CDATA[%s]]></Description><PicUrl><![CDATA[%s]]></PicUrl></item>';
		$resultStr = "";
		if($isLarge>0)
		{
			$picurl = ROOT_WEB_URL."wechat/pics/".$mode."_l1.jpg";
			$index = strpos($content,"\n",140);
			$desc = "";
			if($index>0)
			{
				$title = $title."\n".substr($content,0,$index);
				$desc = substr($content,0,$index);
			}
			else
				$desc = mb_substr($content,0,20,"UTF-8");
				
			$resultStr = sprintf($textTpl,$title,$url,$desc, $picurl);
		}
		else
		{
			$index = strpos($content,"\n",20);
			if($index>-1 and $index<100)
			{
				$title = $title."\n".substr($content,0,$index);
			}
			else
			{
				$title = $title."\n".mb_substr($content,0,30,"UTF-8");
			}
			$resultStr = sprintf($textTpl,$title,$url,mb_substr($content,0,30,"UTF-8"), $picurl);
		}
		return $resultStr;
	}
	
	private function insertIntoDb($get,$post,$result)
	{
		return;
		//检测用户名及密码是否正确
		$result = mysql_query("insert into wechat (get,post) values ('".$result."','".$post."');");
	}
}
?>