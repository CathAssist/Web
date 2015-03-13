//baidu
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?ba70a76114ed3d7071ccc0b18a16bc3c";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();

//google
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-29392184-2', 'cathassist.org');
ga('send', 'pageview');

//淘宝广告代码
window.onload = function(){
	var div = document.createElement("div");
	div.style.margin = "20px auto";
	var tb = document.createElement("a");
	tb.href = "http://taobao.cathassist.org/";
	tb.innerHTML = "小助手淘宝店";
	tb.target="_blank";
	tb.style.textAlign = "center";
	tb.style.display = "block"
	div.appendChild(tb);
	var s = document.getElementsByTagName("body")[0];
	s.appendChild(div);
	
	//Snow in Christmas
	document.body.style.backgroundColor = "rgb(233,233,233)";
	var jsSnow = document.createElement("script");
	jsSnow.src = "http://www.cathassist.org/js/snowstorm.js";
	document.body.appendChild(jsSnow);
};