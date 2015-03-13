<?php
/**
 *  index.php 入口
 */
 //根目录
define('WEB_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);

include WEB_PATH.'/core/config.inc.php';
include WEB_PATH.'/core/global.func.php';

if(function_exists('date_default_timezone_set')) date_default_timezone_set(TIMEZONE);

header('Content-type: text/html; charset='.CHARSET);


if(GZIP && function_exists('ob_gzhandler'))
{
	ob_start('ob_gzhandler');
}
else
{
	ob_start();
}

if(!get_magic_quotes_gpc()) {
	$_POST = new_addslashes($_POST);
	$_GET = new_addslashes($_GET);
	$_REQUEST = new_addslashes($_REQUEST);
	$_COOKIE = new_addslashes($_COOKIE);
}

session_start();


if(isset($_SESSION['admin']) && $_SESSION['admin']==1){
	$op = isset($_GET['op']) && trim($_GET['op']) ? trim($_GET['op']) : 'index';
}else{
	$op = 'login';
}


if (!preg_match('/([^a-z_]+)/i',$op) && file_exists(WEB_PATH.'core/'.$op.'.php')) {
	include WEB_PATH.'core/'.$op.'.php';
} else {
	exit(' file does not exist');
}

?>