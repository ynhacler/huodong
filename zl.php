<?php
require_once 'getcode.php';
require_once 'common.php';
header("Content-Type: text/html;charset=utf-8"); 
require_once 'jsSDK.php';


//检查uu是否存在
if(is_array($_GET)&&count($_GET)>0)//先判断是否通过get传值了
{
    if(!isset($_GET["uu"]))//是否存在"id"的参数
    {
        redirect("/index.php");
    }
}else{
	redirect("/index.php");
	//////
}

$jssdk = new JSSDK("wx8e339c8f60f11a7f", "86f59b665cdfcd49855ba30ad063f820");
$signPackage = $jssdk->GetSignPackage();

$zzh = new weixinController('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
//echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$z_userinfo = json_decode($zzh->userInfo,true);
$z_userinfo = $z_userinfo[0];

if(!isGet()){
	if(!empty($_FILES['user_imgfile']['tmp_name'])){
		//==========图片上传====================
		$file = $_FILES["user_imgfile"];//得到传输的数据
		//得到文件名称
		$name = $file['name'];
		$type = strtolower(substr($name,strrpos($name,'.')+1)); //得到文件类型，并且都转化成小写
		$allow_type = array('jpg','jpeg','gif','png'); //定义允许上传的类型
		//判断文件类型是否被允许上传
		if(!in_array($type, $allow_type)){
		  //如果不被允许，则直接停止程序运行
		  return ;
		}
		//判断是否是通过HTTP POST上传的
		if(!is_uploaded_file($file['tmp_name'])){
		  //如果不是通过HTTP POST上传的
		  return ;
		}
		$upload_path = "/data/"; //上传文件的存放路径
		//开始移动文件到相应的文件夹
		$u_file_name = create_password(9).".".$type;
		$file_path = $upload_path.$u_file_name;
		if(move_uploaded_file($file['tmp_name'],$file_path)){
		  echo "Successfully!";
		}else{
		  echo "Failed!";
		}

		//===========================================
	}else{
		$u_file_name = 'no_upload_img';
	}
	
	  $u_id = $z_userinfo['id'];
	  $u_name = $_POST['user_name'];
	  $u_phone = $_POST['user_phone'];
	  $u_content = $_POST['user_content'];
	  $u_gift_id= $_POST['user_gift_id'];

	  insert_DB("INSERT INTO event_user (wx_id,user_name,phone,content,img_url,gift_id) VALUES ('{$u_id}','{$u_name}','{$u_phone}','{$u_content}','{$u_file_name}','{$u_gift_id}');");
	//-------------------------------------------
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
  <title>助力页面</title>
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
		background-image: url(/pub/home-bg2.png);
		display: inline-block;
		width: 3.3rem;
		margin-left: .3rem;
		margin-top: .1rem;
		background-size: 3.6rem;
	}
</style>
</head>
<body class="home-bg">
<p class="time-title">倒计时</p>
<p class="timer">
	<font id="day"></font>
	<span>天</span>
	<font id="hour"></font>
	<span>时</span>
	<font id="minute"></font>
	<span>分</span>
	<font id="second"></font>
	<span>秒</span>
</p>

<div class="userinfo">
	<div class="list-head">
		<img src="<?php echo $z_userinfo['headimgurl'];?>" alt="">
	</div>
	<p class="txt" style="font-weight:bold;"><?php echo "{$z_userinfo['nickname']}"; ?></p>
	<p class="txt">排名：第709位</p>
	<?php
$openid = $z_userinfo['openid'];
$lizhisql = "select praised_num from event_user a,wx_user b where a.wx_id=b.id and b.openid='{$openid}';";
$re123 = select_DB_2($lizhisql);
//$paiming = 999;//select_DB_2("123");
//echo "排名：{$paiming}";
?>
  <div class="list-fen"><br>助力值<br><br><font style="font-size:.2rem;font-weight:bold;"><?php echo "{$re123[0]["praised_num"]}";?></font></div>
</div>


	<p style="text-align:center;">
		<a id="help" href="javascript:;" onclick="help()"><button class="btn-help"></button></a><!--助力-->
		<a href="javascript:;" onclick="alert('点击右上角分享')"><button class="btn-find"></button></a><!--分享-->
	</p>

<p style="text-align:center;">
	<a href="/cy.php"><button class="btn-back"></button></a><!--我也要参与-->
			<a href="/wd.php"><button class="btn-reward"></button></a><!--奖品设置-->
</p>


<p class="line-txt">
	<span class="line"></span>
	<?php echo "{$z_userinfo['nickname']}"; ?>的助力团
	<span class="line"></span>
</p>
<div class="ul-li">
	<ul class="list">
	<?php
		$sqldd = "select headimgurl from wx_user a,praise_table b where b.praised_uid='{$z_userinfo['openid']}' and b.praise_uid=a.openid;";
		$re = select_DB_2($sqldd);
		foreach ($re as $key => $value) { 
			echo "<li>";
					echo "<a href='#'>";
						echo "<img src='{$value['headimgurl']}' alt=''>";
					echo "</a>";
			echo "</li>";
		}
	?>
	</ul>
</div>

<script>
var helping = false;
function help(){
	if(!helping){
		helping = true;
		var url = "/zhuta.php?uu=<?php echo $_GET['uu'];?>&uud=<?php echo $openid;?>";
		$.get(url,function(data){
			alert(data.msg);
			helping = false;
			location.reload();
		},'json');
	}
}
function GetRTime(){
	var EndTime= new Date("2017/04/16 16:15:16");
	var NowTime = new Date();
	var t =EndTime.getTime() - NowTime.getTime();
	var d=0;
	var h=0;
	var m=0;
	var s=0;
	if(t>=0){
		d=Math.floor(t/1000/60/60/24);
		h=Math.floor(t/1000/60/60%24);
		m=Math.floor(t/1000/60%60);
		s=Math.floor(t/1000%60);
	}

	$('#day').html(d);
	$('#hour').html(h);
	$('#minute').html(m);
	$('#second').html(s);
}
setInterval(GetRTime,0);
</script>
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