<?php 
/**
 * 返回经addslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_addslashes($string){
	if(!is_array($string)) return addslashes($string);
	foreach($string as $key => $val) $string[$key] = new_addslashes($val);
	return $string;
}


function admin_tpl($file = 'index')
{
	return WEB_PATH.'/core/'.$file.'.tpl.php';
}

//建立目录函数
function DoMkdir($savepath){
	$returnpath="";
	$r=explode("/",$savepath);
	$count=count($r);
	for($i=0;$i<$count;$i++){
		if($i>0){
			$returnpath.="/".$r[$i];
		}
		else{
			$returnpath.=$r[$i];
		}
		$createpath=WEB_PATH.$returnpath;
		
		//不存在则建立
		if(!file_exists($createpath))
		{
			$mk=@mkdir($createpath,0777);
			@chmod($createpath,0777);
			if(empty($mk)) exit('CreatePathFail');
		}
		
	}
	return $returnpath;
}



function showmessage($msg, $url_forward = 'goback', $ms = 1250, $direct = 0)
{
	
    if($direct && $url_forward && $url_forward!='goback')
    {
        ob_clean();
        header("location:$url_forward");
        exit("<script>self.location='$url_forward';</script>");
    }
	include admin_tpl('showmessage');
	exit;
}

?>