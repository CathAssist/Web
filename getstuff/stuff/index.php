<?php
require_once("../../include/define.php");
$datestr = gmdate("Y-m-d",time()+3600*8+$index*3600*24);
header('Location: '.ROOT_WEB_URL.'getstuff/stuff/'.$datestr.'.html');
exit;
?>    
