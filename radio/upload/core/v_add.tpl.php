<?php include  admin_tpl('header'); ?>
<div id="content">
    <div class="box">
    	<div class="breadcrumbs"></div>
        <div class="heading">
            <h1><img alt="" src="<?=IMG_PATH?>product.png"> <?php echo $_channelarr[$cid]['channel']; ?> > 音频上传</h1>
            <div class="buttons"><a class="button" onClick="$('form').submit();">保存</a>&nbsp;<a class="button" onclick="javascript:history.go(-1);">返回</a></div>
        </div>
        <div class="content">
        <form action="?op=<?php echo $op; ?>&cid=<?php echo $cid;?>&action=<?php echo $action; ?>" method="post" id="form"  >
        <table class="form" id="tbData" >
		<tr><td>日期：</td><td cols='4' ><input type="text" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd'})"  class="Wdate" size="15" value="<?php echo $inputtime; ?>" name="inputtime" /></td></tr>
        <tr id="tr1">
        	<td><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
        	<td>标题:</td>
            <td><input type="text" value="<?php echo $info['title']; ?>" name="info[1][title]" /></td>
            <td>音频链接:</td>
            <td><input type="text" value="<?php echo ($info['url'] == "" ? $_channelarr[$cid]['path'] : $info['url']); ?>" name="info[1][url]" /></td>
        </tr>
        </table>
        <?php if(empty($id)){ ?>
        <br/>
        <input type="hidden" value="2" id="trid" name="trid" />
        <a id="Bt_addTr" href="javascript:void(0);" class="button">添加一行</a>
        <a id="Bt_delSelect" href="javascript:void(0);" class="button">删除所选</a>
        <?php } ?>
        <a class="button" onClick="$('form').submit();">保存</a>
        <a class="button" onclick="javascript:history.go(-1);">返回</a>
        </form>
        </div>
    </div>
</div>

<script type="text/javascript">
//添加行
$("#Bt_addTr").click(function(){
	var trid = Number($('#trid').attr('value'));
	table = document.getElementById('tbData');

	var str = '<tr id="tr'+trid+'">';
	str += '<td><input type="checkbox" name="selected[]" value="'+trid+'" /></td><td>标题：</td><td><input type="text" value="" name="info['+trid+'][title]" /></td>';
	//for (var i = 1;i<=1;i++){
	str += '<td>音频链接:</td><td><input type="text" value="<?php echo $_channelarr[$cid]['path']; ?>" name="info['+trid+'][url]" /></td>';
	//str += '<td>上传文件:</td><td><input type="file" value="" name="myfile['+trid+']" /></td>';
	//}
	str += '</tr>';
	$('#tbData').append(str);
	$('#trid').attr('value',trid+1);
});

//删除所选
$('#Bt_delSelect').click(function(){
	var checkboxs = document.getElementsByName("selected[]");
	for (var i=0; i<checkboxs.length; i++) {
		if(checkboxs.length==0){
			checkboxs[i].checked = false;
			return;
		}
		if(checkboxs[i].checked==true){
			//$('#tr'+i).remove();
			var sTr = checkboxs[i].parentNode.parentNode;
			sTr.parentNode.removeChild(sTr);
			i=-1;
		}
	}			   
});

//清空所有
$('#Bt_allclear').click(function(){
	table = document.getElementById('tbData');
	var inputs = table.getElementsByTagName("input");
	for (var i=0;i<inputs.length;i++) {
		inputs[i].value="";	
	}			   
});
</script>
<?php include  admin_tpl('footer'); ?>