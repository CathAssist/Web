<?php
//公共入口
define('IN_WX', true);
define('WX_PATH', substr(dirname(__FILE__), 0, -8).DIRECTORY_SEPARATOR);//网站真实路径
define('SITE_URL','http://www.cathassist.org/');//站点访问路径

define('ERRORLOG', false);
define('GZIP', true);

ERRORLOG ? set_error_handler('my_error_handler') : error_reporting(E_ERROR | E_WARNING | E_PARSE);
//设置本地时差
function_exists('date_default_timezone_set') && date_default_timezone_set('Etc/GMT-8');

define('CHARSET' ,'utf-8');
//输出页面字符集
header('Content-type: text/html; charset='.CHARSET);

if(GZIP && function_exists('ob_gzhandler'))
{
	ob_start('ob_gzhandler');
}
else
{
	ob_start();
}

require WX_PATH.'include'.DIRECTORY_SEPARATOR.'define.php';
require WX_PATH.'include'.DIRECTORY_SEPARATOR.'global.func.php';//加载公共方法
require WX_PATH.'include'.DIRECTORY_SEPARATOR.'db_factory.class.php';//加载数据库工厂类

//实现化数据库类
$db = db_factory::get_instance()->get_database('default');

if(!get_magic_quotes_gpc()) {
	$_POST = new_addslashes($_POST);
	$_GET = new_addslashes($_GET);
	$_REQUEST = new_addslashes($_REQUEST);
	$_COOKIE = new_addslashes($_COOKIE);
}

?>
