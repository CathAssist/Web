<?php
/**
 * 本文件是為第三方應用預留的. 本插件中不會載入和使用這個文件.
 * 
 * 通過include本文件, 你可以使用中文繁簡轉換函數zhconversion($str, $variant)
 * 如果$_GET['doconversion']或$_POST['doconversion'])有設置, 本文件將獲取$_REQUEST['data']并把其轉換為$_REQUEST['variant']語言后輸出.
 *
 * 你不應該也不需要在Wordpress程序, 插件/主題 或 任何已經包含wp-config.php文件的php程序中包含本文件
 *
 * 本插件目录下convert.html是一个简单的在线繁简转换工具, 使用了本php文件. 当作是本插件的bonus吧 ^_^
 */

global $zh2Hans;
require_once( dirname(__FILE__) . '/ZhConversion.php');

global $wpcc_langs;
$wpcc_langs = array(
	'zh-hans' => array('zhconversion_hans', 'zh2Hans', '简体中文'),
);

if( empty($nochineseconversion) && empty($GLOBALS['nochineseconversion']) ) {
if( ( isset($_GET['dochineseconversion']) || isset($_POST['dochineseconversion']) ) &&
	isset($_REQUEST['data']) )
{	$wpcc_data = get_magic_quotes_gpc() ? stripslashes($_REQUEST['data']) : $_REQUEST['data'];
	$wpcc_variant = str_replace('_', '-', strtolower(trim($_REQUEST['variant'])));
	if( !empty($wpcc_variant) && in_array($wpcc_variant, array('zh-hans')) )
		echo zhconversion($wpcc_data, $wpcc_variant);
	else echo $wpcc_data;
	die();
}
}
function zhconversion_hans($str) {
	global $zh2Hans;
	return strtr($str, $zh2Hans);
}
