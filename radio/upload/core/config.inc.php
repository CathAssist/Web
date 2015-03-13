<?php

@error_reporting(E_ERROR | E_WARNING | E_PARSE);

define('SYS_TIME', time());
//定义网站根路径
//define('WEB_root','/');
//js 路径
define('JS_PATH','./statics/js/');
//css 路径
define('CSS_PATH','./statics/css/');
//img 路径
define('IMG_PATH','./statics/images/');

//当前访问的主机名
define('SITE_URL', (isset($_SERVER['HTTP_HOST']) ? 'http://'.$_SERVER['HTTP_HOST'].'/' : '/'));

//基本配置
define('IN_WEB', true);
define('CHARSET', 'utf-8'); //网站字符集
define('TIMEZONE', 'Etc/GMT-8'); //网站时区（只对php 5.1以上版本有效），Etc/GMT-8 实际表示的是 GMT+8
define('GZIP', 0); //是否Gzip压缩后输出



//后台用户 键为用户名、值为密码
$_adminarr = array('admin'=>'admin','test'=>'test','test1'=>'test1');

//频道 
$_channelarr=array(
'vacn'=>array('id'=>'vacn','channel'=>'梵蒂冈广播'),
'cxradio'=>array('id'=>'cxradio','channel'=>'晨星电台'),
);

//后台用户及频道  键为用户名、值为用户所管理的频道
$_admin_channelarr =array(
'admin'=>array('vacn','cxradio'),
'test'=>array('vacn'),
'test1'=>array('cxradio'),

);

//允许上传文件类型
$_filetype=array('.mp3','.jpg');




?>
