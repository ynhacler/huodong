<?php
require_once 'getcode.php';
require_once 'common.php';

header("Content-Type: text/html;charset=utf-8"); 
require_once 'jsSDK.php';
$jssdk = new JSSDK("wx8e339c8f60f11a7f", "86f59b665cdfcd49855ba30ad063f820");
$signPackage = $jssdk->GetSignPackage();
$zzh = new weixinController();

//判断是否参加过
$z_userinfo = json_decode($zzh->userInfo,true);
$z_userinfo = $z_userinfo[0];

$openid = $z_userinfo['openid'];
$sql12 = "select count(*) as bb from event_user a,wx_user b where a.wx_id=b.id and b.openid='{$openid}';";
//echo $sql12;
$re12 = select_DB_2($sql12);
if($re12[0]["bb"] >= '1'){
	redirect("/zl.php?uu={$openid}");
}
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
	<link rel="stylesheet" href="/pub/style.css">
	<script type="text/javascript" src="/pub/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="/pub/rem.js"></script>
  <title>参加活动</title>
  <style>
  .lin-txt{
    font-size: .18rem;
    padding-left: .2rem;
    height: .3rem;
    padding-top: .2rem;
    padding-bottom: .2rem;
  }
  .wit-bg{
    background-color: #fff;
  }
  .lin-ipt{
    display: inline-block;
    height: 100%;
    width: 70%;
    font-size: .18rem;
    position: relative;
    top: -.02rem;
    color: #268c9f;
  }
  .botm-border{
    border-bottom:1px solid #efefef;
  }
  .line{
    margin-left: .28rem;
    width: 3.5rem;
    border-bottom:1px solid #d1d1d1;
  }
  .sub{
    height: 1rem;
    padding: .2rem;
    text-align: center;
  }
  .sub-btn{
    background-color: rgb(255, 0, 0);
    color: #fff;
    font-size: .2rem;
    width: 3.5rem;
    height: .6rem;
    border-radius: .03rem;
  }
  input{
    line-height: normal; /* for non-ie */
  }
  .t1{
    font-size: .15rem;
    line-height: .3rem;
    text-indent: .3rem;
  }
</style>
</head>
<body style="background-color:rgb(242,242,242);">
  <div style="height:100%;margin-top:.1rem;">
	<form action="/zl.php?uu=<?php echo $openid?>&state=123" method="post" enctype="multipart/form-data">
	<p class="lin-txt" style="color:#8b8b8b; padding-bottom: 0rem;">个人信息</p>
	<p class="lin-txt wit-bg">
      <span>姓名：</span>
      <input class="lin-ipt botm-border" type="text" name="user_name" placeholder="请填写您的姓名" value="">
    </p>
    <div class="line"></div>

    <p class="lin-txt wit-bg">
      <span>手机：</span>
      <input class="lin-ipt" type="phone" name="user_phone" placeholder="请填写您的的手机号码" value="">
    </p>
    <div class="line"></div>

    <p class="lin-txt wit-bg">
	<span>文字描述:</span>
	<textarea rows="4" cols="50" name="user_content">
	请在此处输入文本...</textarea></p>
    <div class="line"></div>

    <p class="lin-txt wit-bg">
	<span>上传图片:</span>
	<input  class="lin-ipt" name="user_imgfile" type="file" />
	</p>
    <div class="line"></div>

    <p class="lin-txt wit-bg">
	<span>礼物选项:</span>
	<select name="user_gift_id" >
		<?php
			require_once 'common.php';
			$re = select_DB_2("select id,title from gift;");
			foreach ($re as $key => $value) { 
				echo "<option value='{$value['id']}'>{$value['title']}</option>";
			}

		?>
	</select> 
	</p>
    <div class="line"></div>

	<br><br>
	<p class="sub">
      <button class="sub-btn" type="submit">开始助力</button>
    </p>
	</form> 
	</div>
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
<script>
wx.config({
    debug: false, 
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: '<?php echo $signPackage["timestamp"];?>',
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: ['onMenuShareAppMessage','onMenuShareTimeline','hideMenuItems'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
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
    // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。
	wx.onMenuShareAppMessage({
	    title: '大派送！', // 分享标题
	    desc: '送豪礼！', // 分享描述
	    link: 'http://www.uhit.me/index.php', // 分享链接
	    imgUrl: 'http://www.uhit.me/pub/share.png', // 分享图标
	    type: '', // 分享类型,music、video或link，不填默认为link
	    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
	    success: function () {
	        // 用户确认分享后执行的回调函数
	        // $.get('/szrsgg/index.php?m=Home&c=User&a=share',function(data){
	        // 	if(data.status == 0){
	        // 		location.reload();
	        // 	}
	        // },'json');
	    },
	    cancel: function () {
	        // 用户取消分享后执行的回调函数
	    }
	});
	wx.onMenuShareTimeline({
	    title: '大派送！', // 分享标题
	    link: 'http://www.uhit.me/index.php', // 分享链接
	    imgUrl: 'http://www.uhit.me/pub/share.png', // 分享图标
	    success: function () {
	        // 用户确认分享后执行的回调函数
	        // $.get('/szrsgg/index.php?m=Home&c=User&a=share',function(data){
	        // 	if(data.status == 0){
	        // 		location.reload();
	        // 	}
	        // },'json');
	    },
	    cancel: function () {
	        // 用户取消分享后执行的回调函数
	    }
	});
});
</script>
</html>