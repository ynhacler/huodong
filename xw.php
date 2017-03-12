<?php
require_once 'getcode.php';
require_once 'jsSDK.php';
$jssdk = new JSSDK("wxe682f756fa360517", "dc50e76b1812252c27f8d436846caa1f");
$signPackage = $jssdk->GetSignPackage();
$zzh = new weixinController();
$z_userinfo = $zzh->userInfo;
$openid = $z_userinfo['openid'];

if(isset($_GET["ss"]))//是否存在"ss"的参数，说明在提交礼物
    {
    	//检查是否提交过
    	$sqlbb = "select state from event_user where wx_id in(select id from wx_user where openid='{$openid}');";
    	$re12a = select_DB_2($sqlbb);
    	if($re12a[0]['state'] == '3'){
    		redirect("/huodong/zl.php?uu={$openid}");
    	}

    	$bbsql = "update event_user set gift_id='{$_GET['ss']}' where wx_id in(select id from wx_user where openid='{$openid}');";
    	insert_DB($bbsql);
    	$bbsql = "update event_user set state='3' where wx_id in(select id from wx_user where openid='{$openid}');";
    	insert_DB($bbsql);
        redirect("/huodong/zl.php?uu={$openid}");
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
	<link rel="stylesheet" href="/huodong/pub/style.css">
	<script type="text/javascript" src="/huodong/pub/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="/huodong/pub/rem.js"></script>
  <title>选择你的礼物</title>
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
  .btn{border-style:none;
    padding:8px 30px;
    line-height:24px;
    color:#fff;
    font:16px "Microsoft YaHei", Verdana, Geneva, sans-serif;
    cursor:pointer;
    border:1px #ae7d0a solid;
    -webkit-box-shadow:inset 0px 0px 1px #fff;
    -moz-box-shadow:inset 0px 0px 1px #fff;
    box-shadow:inset 0px 0px 1px #fff;/*内发光效果*/
    -webkit-border-radius:4px;
    -moz-border-radius:4px;
    border-radius:4px;/*边框圆角*/
    text-shadow:1px 1px 0px #b67f01;/*字体阴影效果*/
    background-color:#feb100;
    background-image: -webkit-gradient(linear, 0 0%, 0 100%, from(#feb100), to(#e8a201));
    background-image: -webkit-linear-gradient(top, #feb100 0%, #e8a201 100%);
    background-image: -moz-linear-gradient(top, #feb100 0%, #e8a201 100%);
    background-image: -ms-linear-gradient(top, #feb100 0%, #e8a201 100%);
    background-image: -o-linear-gradient(top, #feb100 0%, #e8a201 100%);
    background-image: linear-gradient(top, #feb100 0%, #e8a201 100%);/*颜色渐变效果*/} 
</style>
</head>
<body class="index-bg">
<?php
$sql12 = "select level from event_user a,wx_user b where a.wx_id=b.id and b.openid='{$openid}';";
//echo $sql12;
$re12 = select_DB_2($sql12);
$gg = $re12[0]['level'];
/*
<div style="height:100%;margin-top:.1rem;">
<p class="lin-txt wit-bg">
<span>你的级别：<?php echo "{$gg}"; ?></span>
</p>
<div class="line"></div>
*/
?>
<div style="height:100%;margin-top:2.5rem;">
<!--
<p class="lin-txt">
<a href="https://weidian.com/?userid=1172856672&wfr=wx"><button class="btn-send3"></button></a>
</p>-->
<form action="/huodong/xw.php" method="get" >
<p class="lin-txt">
	<span>选择礼物：</span>
	<select name="ss" id="ss" class="lin-ipt"> 
		<?php
			require_once 'common.php';
			$re = select_DB_2("select id,title from gift where grade='{$gg}';");
			foreach ($re as $key => $value) { 
				echo "<option value='{$value['id']}'>{$value['title']}</option>";
			}
		?>
	</select>
	</p>

	<p class="lin-txt">
	<span>是否确认：</span>
	<label><input id="if_yes" type="radio" value="yes" />是</label> 
<label><input id="if_no" type="radio" value="no" checked="true" />否</label>
</p>
<p class="sub">
<input type="submit" id="oksub" value="提交" class="btn" />
</p>
</form>
  </div>

<script type="text/javascript">
    $(function(){
    	$('#oksub').attr('disabled',"true");//添加disabled属性 
        $("#if_yes").click(function () {
                if ($(this).attr("checked")) {
                    $('#oksub').removeAttr("disabled"); //移除disabled属性 
                    $("#if_no").attr("checked",false);
                }
            });

        $("#if_no").click(function () {
                if ($(this).attr("checked")) {
                    $('#oksub').attr('disabled',"true");//添加disabled属性 
                    $("#if_yes").attr("checked",false);
                }
            });
    });
</script>

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

</body>
</html>