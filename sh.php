<?php
require_once 'getcode.php';
require_once 'jsSDK.php';
$jssdk = new JSSDK("wxe682f756fa360517", "dc50e76b1812252c27f8d436846caa1f");
$signPackage = $jssdk->GetSignPackage();
$zzh = new weixinController();
?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="format-detection"content="telephone=no, email=no" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes" /><!-- 删除苹果默认的工具栏和菜单栏 -->
	<meta name="apple-mobile-web-app-status-bar-style" content="black" /><!-- 设置苹果工具栏颜色 -->
	<meta name="format-detection" content="telphone=no, email=no" /><!-- 忽略页面中的数字识别为电话，忽略email识别 -->
	<link rel="stylesheet" href="/huodong/pub/style.css">
	<script type="text/javascript" src="/huodong/pub/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="/huodong/pub/rem.js"></script>
  <title>审核信息</title>
  <style>
	.time-title{
		margin-top: 2.5rem;
		height: .4rem;
		font-size: .3rem;
		font-weight: bold;
		text-align: center;
		letter-spacing: .1rem;
		color: red;
	}
	.timer{
		text-align: center;
		font-size: .2rem;
	}
	.timer font{
		font-size: .3rem;
		color: red;
	}
	.userinfo{
		display: inline-block;
		width: 3rem;
		margin-left: .55rem;
		margin-top: .3rem;
		border-radius: .1rem;
		height: 1rem;
		background-color: rgb(218,185,107);
	}
	.list-head{
		display: inline-block;
		width: .7rem;
		height: .7rem;
		margin-left: .2rem;
		float: left;
	}
	.list-head img{
		border-radius: .3rem;
		margin-top: .1rem;
	}
	.userinfo .txt{
		font-size: .15rem;
		margin-top: .2rem;
		margin-left: 1rem;
	}
	.line-txt{
		font-size: .2rem;
		text-align: center;
	}
	.line{
		width: 1rem;
    border: 1px solid #000000;
    display: inline-block;
	}
	.list li{
		height: .5rem;
		width: .5rem;
		float: left;
		padding: 0px;
		margin-left: .1rem;
	}
	.list li img{
		border-radius: .3rem;
	}
	.ul-li{
		padding-left: 0.3rem;
		background-image: url(/huodong/pub/home-bg2.png);
		display: inline-block;
		width: 3.3rem;
		margin-left: .3rem;
		margin-top: .1rem;
		background-size: 3.6rem;
	}
</style>
</head>
<body class="check-bg" onload="Load('/huodong/index.php')">
<div id="ShowDiv"></div>
	<p class="time-title">
		<?php
		if($_GET['s'] == "loading"){
			//echo "还在审核中，请等待通知！";
			//sleep(2);
			//redirect("/huodong/index.php");
		}
		if($_GET['s'] == "NO"){
			//echo "您的资料信息审核未通过！感谢你的参与，谢谢！";
			//redirect("/huodong/index.php");
		}
		?>

	</p>


<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
<script>
wx.config({
    debug: false, 
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: '<?php echo $signPackage["timestamp"];?>',
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: ['hideMenuItems'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
});
wx.ready(function(){

  wx.hideMenuItems({
    menuList: ["menuItem:copyUrl",//复制链接
                     "menuItem:originPage",//原网页
                     "menuItem:readMode",//阅读模式
                     "menuItem:openWithQQBrowser",//在QQ浏览器中打开
                     "menuItem:openWithSafari"//在Safari中打开
                   ] // 要隐藏的菜单项
  });
	
});
</script>
<script language="javascript">

var secs = 3; //倒计时的秒数 
var URL ;
function Load(url){
//window.location.reload();
//self.opener.location.reload();
parent.location.reload();
}

</script>
</body>
</html>