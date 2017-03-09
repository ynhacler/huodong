<?php
require_once 'getcode.php';
require_once 'common.php';
header("Content-Type: text/html;charset=utf-8"); 
require_once 'jsSDK.php';
$jssdk = new JSSDK("wx8e339c8f60f11a7f", "86f59b665cdfcd49855ba30ad063f820");
$signPackage = $jssdk->GetSignPackage();
//$zzh = new weixinController();
//$z_userinfo = json_decode($zzh->userInfo,true);
//$z_userinfo = $z_userinfo[0];

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
  <title>排行榜</title>
</head>
<link rel="stylesheet" href="/pub/dropload.css">
<style>
.dropload-up, .dropload-down{
	top:-.3rem;
}
  .rank-list li{
    float: left;
    width: 3.3rem;
    height: .7rem;
    background-color: rgb(254,248,174);
    border-radius: .1rem;
    margin-left: .45rem;
    margin-bottom: .2rem;
  }
  .list-head{
    display: inline-block;
    width: .7rem;
    height: .7rem;
    margin-left: .2rem;
    float: left;
  }
  .list-head img{
    width: 80%;
    border-radius: .3rem;
    margin-top: .1rem;
  }
  .list-name{
    font-size: .2rem;
    color: red;
    font-weight: bold;
    position: relative;
    top: -.2rem;
    width: 1.2rem;
    display: inline-block;
  }
</style>
<body class="rank-bg" style="padding-top:1.2rem;">
	<p class="rank-list">礼物排行：</p>
	<select name="ss" id="ss" class="rank-list"> 
		<?php
			require_once 'common.php';
			$re = select_DB_2("select id,title from gift;");
			foreach ($re as $key => $value) { 
				echo "<option value='{$value['id']}'>{$value['title']}</option>";
			}

		?>
	</select>

  <ul class="rank-list content-lists-main">
  <?php
		$sqlz = "select b.openid ,b.nickname ,b.headimgurl,a.praised_num from event_user a,wx_user b where b.id=a.wx_id and a.gift_id=1 order by a.praised_num desc;";
		$re = select_DB_2($sqlz);
		$ii = 1;
		foreach ($re as $key => $value) { 
			 echo "<li>";
		    echo "<a href='/zl.php?uu={$value['openid']}'>";
		    echo "<div class='list-head'>";
		    echo "<img src='{$value['headimgurl']}' alt=''>";
		    echo "</div>";
		    echo "<span class='list-name'>{$value['nickname']}</span>";
		    echo "<div class='list-fen-rank'><br>助力值<br><br>{$value['praised_num']}</div>";
		    echo "<div class='list-rank'>{$ii}</div>";
		    echo "</a>";
		    echo "</li>";
		    $ii++;
		}
	?>
  </ul>
  <div class="color-block content-block" style="top: 93%;width: 100%;margin-left: 0rem;margin-right: 0rem;position: fixed;"></div>
  <script type="text/javascript" src="/pub/dropload.min.js"></script>
	

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
</body>

</html>