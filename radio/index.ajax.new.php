<html>
<head>
	<title>天主教小助手网络电台</title>
	<meta http-equiv=Content-Type content="text/html;charset=utf-8">
	<meta name="viewport" content="user-scalable=no, width=device-width, minimal-ui" />
	<link rel="stylesheet" type="text/css" href="/js/jPlayer/skin/cd/cd.new.css"/>
	<style type="text/css">
		.title-bar select{
			padding: 5px;
			font-size: 20px;
			height: 100%;
			-webkit-appearance: none;
			border: 0 none;
			background: transparent;
		}
	</style>
	<script type="text/javascript" src="http://cathassist.org/js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="http://cathassist.org/js/jPlayer/js/jquery.jplayer.min.js"></script>
	<script type="text/javascript" src="http://cathassist.org/js/jPlayer/js/jplayer.playlist.min.js"></script>
	<script src="http://cathassist.org/js/snap.min.js"></script>
	<script type="text/javascript" language="javascript" src="http://cathassist.org/include/googleanalysis.js"></script>
	<script type="text/javascript" language="javascript" src="http://cathassist.org/include/common.js"></script>
    <script>
    //<![CDATA[
	Date.prototype.Format = function (fmt) { //author: meizz 
		var o = {
			"M+": this.getMonth() + 1, //月份 
			"d+": this.getDate(), //日 
			"h+": this.getHours(), //小时 
			"m+": this.getMinutes(), //分 
			"s+": this.getSeconds(), //秒 
			"q+": Math.floor((this.getMonth() + 3) / 3), //季度 
			"S": this.getMilliseconds() //毫秒 
		};
		if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
		for (var k in o)
		if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
		return fmt;
	}

	function getQueryString(name) {
		var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
		var r = window.location.search.substr(1).match(reg);
		if (r != null) return unescape(r[2]); return null;
    }

	function updateHref()
	{
        return false;
//		var state = "index.php?channel="+channel+"&date="+curDate.Format("yyyy-MM-dd");
//		var loc = "http://www.cathassist.org/radio/"+state;
//		window.location.href = loc;
////		window.history.pushState([],0,state);
	}

	function getRadio(_d)
	{
		console.log("getradio...");
		ppl.pause();
		$.get("getradio.php?channel="+channel+"&date="+_d.Format("yyyy-MM-dd"), function(data)
		{
			data = jQuery.parseJSON(data);
			var list = new Object();
			$.each( data['items'], function( key, val )
			{
				list[key] = new Object();
				list[key]['title'] = val['title'];
				list[key]['mp3'] = val['src'];
			});
            $(".jp-playlist").hide();
			ppl.setPlaylist(list);
            if(typeof(list[1])=="object"){
                $(".jp-playlist").show();
            }
			$("#jptitle").html(data['title']);
			$("#jpimg").attr("src",data["logo"]);
			curDate = new Date(data['date']);
			$("#dateBtn").val(curDate.Format("yyyy-MM-dd"));
			document.title = data['title'];

			SetWechatShare(data['title'],window.location.href,data['logo'],data['title']);
		});
	}
	
    $(document).ready(function()
	{
		ppl = new jPlayerPlaylist({
			jPlayer: "#jquery_jplayer_1",
			cssSelectorAncestor: "#jp_container_1"
		},[], {
			playlistOptions: {
				autoPlay: true,
				enableRemoveControls: true
			},
			supplied: "mp3",
			play: function(){
				console.log("jplayer play...");
				var current = ppl.playlist[ppl.current];

				$('.title h2').text(current.title);
				//$('.author').text(current.artist);
				$('.disc img').addClass('cycling').removeClass('paused')
				$('.mag-head').removeClass('off-off')
			},
			pause: function(){
				$('.disc img').addClass('paused')
				$('.mag-head').addClass('off-off')
			},
			ended: function(){
				$('.disc img').removeClass('paused cycling')
			},
			swfPath: "http://cathassist.org/js/jPlayer/js",
			supplied: "oga, mp3",
			wmode: "window",
			smoothPlayBar: true,
			keyEnabled: true
		});
        $('.open-right').click(function(){
            $(".jp-playlist").toggle();
        });
		
		//channel changed
//		$("#jpchannel").change(function()
//		{
//			channel = $("#jpchannel").val();
//			getRadio(curDate);
//			updateHref();
//		});
        $("#cp-channel-list li").click(function(){
            $(this).addClass("active").siblings().removeClass("active")
            channel = $(this).attr("data-channel");
            getRadio(curDate);
            updateHref();
        })
		
		//prev day
		$("#prevBtn").click(function()
		{
			curDate.setDate(curDate.getDate()-1);
			getRadio(curDate);
			updateHref();
		});
		//next day
		$("#nextBtn").click(function()
		{
			curDate.setDate(curDate.getDate()+1);
			getRadio(curDate);
			updateHref();
		});
		
		//set date;
		$("#dateBtn").blur(function(event)
		{
			curDate = new Date($("#dateBtn").val());
			getRadio(curDate);
			updateHref();
		});
		
		var argC = getQueryString("channel");

		var argD = getQueryString("date");
		if(argD != null && argD != "")
		{
			curDate = new Date(argD);
		}
		channel =argC;
		getRadio(curDate);
    });
	
	var ppl = null;
	var curDate = new Date();
	var channel = "cx";
    //]]>
    </script>
</head>

<body>
    <div id="jquery_jplayer_1" class="jp-jplayer"></div>
    <div id="jp_container_1" class="jp-audio">
        <div class="jp-type-playlist">
            <div class="jp-gui jp-interface">
                <div class="title-bar">
                    <a href="#" class="open-right"></a>
                    <span class="wrap">
                        <b id="jptitle">晨星生命之音</b>
                    </span>
					<!--<h2 id="jptitle">天主教小助手网络电台</h2>-->
                </div>
                <div class="cp-buffer-holder">
                    <!-- .cp-gt50 only needed when buffer is > than 50% -->
                    <div class="cp-buffer-1"></div>
                    <div class="cp-buffer-2"></div>
                </div>
                <div class="cp-progress-holder">
                    <!-- .cp-gt50 only needed when progress is > than 50% -->
                    <div class="cp-progress-1"></div>
                    <div class="cp-progress-2"></div>
                </div>
                <div class="cp-circle-control"></div>
                <div class="cp-channel-list" id="cp-channel-list">
                    <ul >
                        <li data-channel="cx">晨星生命之音</li>
                        <li data-channel="ai">福音i广播</li>
                        <li data-channel="vacn">梵蒂冈广播</li>
                        <li data-channel="gos">每日福音</li>
                    </ul>
                </div>

                <div class="cp-showbox">
                    <div class="control cp-control">
                        <div class="control-con">
                            <div class="prev jp-previous"></div>
                            <span class="opt need-active play jp-play"><i></i>播放</span>
                            <span class="opt need-active play jp-pause"><i></i>暂停</span>
                            <div class="next jp-next"></div>
                        </div>
                    </div>
                    <div class="jp-progress">
                        <div class="jp-seek-bar">
                            <div class="jp-play-bar"></div>
                        </div>
                    </div>
                </div>
                <div class="date-control">
                    <button id="prevBtn">上一天</button>
                    <input id="dateBtn" type="date" value="2014/03/08" autocomplete="off"/>
                    <button id="nextBtn">下一天</button>
                </div>
            </div>
            <div class="jp-playlist snap-drawers">
                <div class="snap-drawer snap-drawer-right">
                    <ul>
                        <li>电台小助手</li>
                    </ul>
                </div>
            </div>
            <div class="jp-no-solution">
                <span>Update Required</span>
                To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
            </div>
        </div>
    </div>
</body>
</html>