<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=CHARSET?>" />
<title>跳转提示</title>
<link href="<?=CSS_PATH?>style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
*{ padding: 0; margin: 0; }
body{ background: #fff; font-family: '微软雅黑'; color: #333; font-size: 16px; }
.system-message{ width:500px;height:100px; margin:auto;border:6px solid #80BDCB;text-align:center; position:relative;top:50px;}
.system-message legend{font-size:24px;font-weight:bold;color:#335B64;margin:auto;width:100px;}
.system-message h1{ font-size: 100px; font-weight: normal; line-height: 120px; margin-bottom: 12px; }
.system-message .jump{ padding-right:10px;height:25px;line-height:25px;font-size:14px;position:absolute;bottom:0px;left:0px;background-color:#e6e6e1 ; display:block;width:490px;text-align:right;}
.system-message .jump a{ color: #333;}
.system-message .success,.system-message .error{ line-height: 1.8em; font-size: 15px }
.system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:''}
</style>
</head>
<body>
<div id="container">
    <div id="header">
        <div class="div1">
            <div class="div2">后台管理</div>
        </div>
    </div>
	<div id="content">
  		<div class="box">
        <div class="content" style="min-height: 150px; overflow: hidden; border:0px;">
        <fieldset class="system-message">
            <legend>提示信息</legend>
            <div style="text-align:left;padding-left:10px;height:75px;width:490px;  ">
                <p class="success"><?=$msg?></p>
            </div>
            <p class="jump">
                 <?php if($url_forward == "goback"){?>
				  <a href="javascript:history.go(-1);" >[ 点这里返回上一页 ]</a>
				  <?php  }elseif($url_forward){?>
				  <a href="<?=$url_forward?>">如果您的浏览器没有自动跳转，请点击这里</a>
				  <script>setTimeout("redirect('<?=$url_forward?>');", <?=$ms?>);</script>
				  <?php } ?>
            </p>
        </fieldset>
        </div>
        </div>
    </div>
</div>


<script type="text/javascript">
function redirect(href){
	location.href = href;
}
</script>
</body>
</html>
