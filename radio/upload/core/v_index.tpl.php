<?php include  admin_tpl('header'); ?>
<div id="content">
    <div class="box">
    	<div class="breadcrumbs"></div>
        <div class="heading">
            <h1><img alt="" src="<?=IMG_PATH?>product.png"><?php echo $_channelarr[$cid]['channel']; ?></h1>
            <div class="buttons"><a class="button" href="?op=v&cid=<?php echo $cid;?>&action=add">添加</a>&nbsp;<a class="button" onClick="$('form').submit();">删除</a></div>
        </div>
        <div class="content">
		<!--搜索表单-->
		<form action="?" name="form" method="get" id="myform">
		<input type="hidden" value="v"  name="op" />
		<input type="hidden" value="<?=$cid?>"  name="cid" />
		<table class="form">
        <tr>
            <!--<td class="center" width="200">标题：<input type="text" name="keywords" size="15" value="<?php echo $keywords; ?>" /></td>-->
            <td class="center" width="200" >日期：<input type="text" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" name="nowtime" class="Wdate" size="15" value="<?php echo $nowtime; ?>"/></td>
            <td class="center"><input type="submit" name="dosearch" class="button" value="查询"></td>
        </tr>
        </table>
        </form>
		
		<!--列表-->
        <form action="?op=v&action=del" method="post" id="form" >
		<input type="hidden" value="<?=$cid?>"  name="cid" />
		
		<input type="hidden" value="<?=$nowtime?>"  name="nowtime" />
        <table class="list">
        <tr>
        	<th width="1" style="text-align: center;"><input type="checkbox" onClick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
            <th class="center" width="50">序号</th>
            <th class="center" width="300">标题</th>
            <th class="center" width="400" >链接</th>
            <th class="center" width="200">日期</th>
        </tr>
        <?php if(!empty($infos)){ ?>
			<?php foreach($infos as $k=>$vo){ ?>
			<tr>
				<td class="center"><input type="checkbox" name="ids[]" value="<?php echo $k+1; ?>" /></td>
				<td class="center"><?php echo $k+1; ?></td>
				<td class="center"><?php echo $vo['title']; ?></td>
				<td class="center"><?php echo $vo['url']; ?></td>
				<td class="center"><?php echo $nowtime; ?></td>
			</tr>
			<?php } ?>
        <?php }else{ ?>
        <tr>
        	<td class="center" colspan="6">无记录</td>
        </tr>
        <?php } ?>
        </table>
        </form>
        </div>
    </div>
</div>
<?php include  admin_tpl('footer'); ?>