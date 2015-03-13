<?php
defined('IN_WEB') or exit('No permission resources.'); 


$_GET['action']=empty($_POST['action'])?$_GET['action']:$_POST['action'];
if(isset($_GET['action']) && !empty($_GET['action']) ){
	$action = $_GET['action'];
}else{
	$action='index';
}

$_GET['cid']=empty($_POST['cid'])?$_GET['cid']:$_POST['cid'];
if(isset($_GET['cid']) && !empty($_GET['cid']) ){
	$cid = $_GET['cid'];
}else{
	showmessage('频道参数丢失。');
}

if(!empty($_SESSION['AdminName'])){
	if( !in_array($cid,$_admin_channelarr[$_SESSION['AdminName']]) ){
		showmessage('没有频道权限');
	}
}

switch($action)
{
	case 'index':
		
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$infos=array();
		$nowtime='';
		if(!empty($_GET['nowtime'])){
			$nowtime=$_GET['nowtime'];
		}else{
			$nowtime=date('Y-m-d');
		}

		$savepath = 'data/'.$cid.'/'.$nowtime.'/'; //文件保存路径
		$jsonfile = WEB_PATH.$savepath.$nowtime.'.txt';
		if(file_exists($jsonfile)){
			$jsonstr = file_get_contents($jsonfile);
			$infos = json_decode($jsonstr,TRUE);
		}
		
		$total = count($infos);
		include admin_tpl('v_index');
		
		break;

	case 'add':

		if(isset($_POST['trid']) && !empty($_POST['trid']))
	    {	
			$jsonarr = array();
			$data = $_POST['info'];

			if(empty($_POST['inputtime']))showmessage('请选择日期');

			$savepath = 'data/'.$cid.'/'.$_POST['inputtime'].'/'; //文件保存路径
			$savepath = DoMkdir($savepath);

			foreach($data as $k=>$v){

				$filename=$newfile='';//记录文件名
				
				//检查是否有上传文件
				if( isset($_FILES) && $_FILES['myfile'][error][$k]==UPLOAD_ERR_OK ){//上传成功
					 
					if($_FILES["myfile"]["tmp_name"][$k]){
						$tmp_name = $_FILES["myfile"]["tmp_name"][$k];
						$name = $_FILES["myfile"]["name"][$k];
						
						$filer=explode(".",$name);
						$count=count($filer)-1;
						$filetype = strtolower($filer[$count]);

						$filename = str_replace('.'.$filetype,'',$name);

						$newfile  = $savepath.$name;
						
						if(in_array('.'.$filetype,$_filetype) ){
							@move_uploaded_file($tmp_name, WEB_PATH.$newfile);
						}else{
							showmessage('文件上传格式不正确');
						}
					}

				}

				$a=array();
				$a['title'] = trim($v['title'])?trim($v['title']):(empty($filename)?'':$filename);
				$a['url']   = trim($v['url'])?trim($v['url']):(empty($newfile)?'':$newfile);
				//$a['filename']=empty($$name)?'':$name;
				$jsonarr[]=$a;
			
			}
			if(!empty($jsonarr)){
				
				$jsonfile = WEB_PATH.$savepath.$_POST['inputtime'].'.txt';

				//以标题作为重复判断依据
				if(file_exists($jsonfile)){
					$oldjson = file_get_contents($jsonfile);
					$oldjsonarr = json_decode($oldjson,TRUE);
					//新旧数据合并
					foreach($oldjsonarr as $jk=>$jv){
	
						foreach($jsonarr as $njv){
							if($jv['title']==$njv['title']){
								unset($oldjsonarr[$jk]);
							}
						}
						
					}

					$jsonarr = array_merge ($oldjsonarr,$jsonarr);
				
				}
				$jsonstr = json_encode($jsonarr);
				@file_put_contents($jsonfile,$jsonstr);
			}
			
			//更新web服务器上的数据
			$url = 'http://www.cathassist.org/radio/getradio.php?channel=ai&r=1&date='.$_POST['inputtime'];
			file_get_contents($url);

			showmessage('操作成功','?op=v&cid='.$cid);
			
		}
		else
	    {	
			$inputtime=date('Y-m-d');
			include admin_tpl('v_add');
		}
		break;


	case 'del':
		
		if(empty($_POST['nowtime']))showmessage('日期参数丢失');
		if(empty($_POST['ids']))showmessage('请选择要删除的数据');

		$nowtime = $_POST['nowtime'];

		$savepath = 'data/'.$cid.'/'.$nowtime.'/'; //文件保存路径
		$jsonfile = WEB_PATH.$savepath.$nowtime.'.txt';
		if(file_exists($jsonfile)){
			$jsonstr = file_get_contents($jsonfile);
			$infos = json_decode($jsonstr,TRUE);
		}
		

		if(empty($infos) )showmessage('数据不存在');
		
		foreach($_POST['ids'] as $v){
			if($v>0){
				$i=$v-1;
				if(!empty($infos[$i])){

					if(!empty($infos[$i]['filename']) && file_exists(WEB_PATH.$savepath.$infos[$i]['filename'])){
						//unlink(WEB_PATH.$savepath.$infos[$i]['filename']);//删除原文件
					}
					unset($infos[$i]);
				}
			}
		}
		
		$jsonarr = array_values($infos);
		$jsonstr = json_encode($jsonarr);
		@file_put_contents($jsonfile,$jsonstr);

		//更新web服务器上的数据
//		echo(file_get_contents($jsonfile));
		file_get_contents('http://www.cathassist.org/radio/getradio.php?channel=ai&r=1&date='.$nowtime);

		showmessage('删除成功');

		break;



}
?>