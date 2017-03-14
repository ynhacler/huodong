<?php
require_once 'getcode.php';
require_once 'common.php';

header("Content-Type: text/html;charset=utf-8"); 
require_once 'jsSDK.php';
$jssdk = new JSSDK("wxe682f756fa360517", "dc50e76b1812252c27f8d436846caa1f");
$signPackage = $jssdk->GetSignPackage();
$zzh = new weixinController();

//判断是否参加过
$z_userinfo = $zzh->userInfo;
$openid = $z_userinfo['openid'];
//麦克提交后跳转，先插入event_user再跳转
  if(isset($_GET["g"])){
      $u_file_name = '-'; 
      $u_id = $z_userinfo['id'];
      $u_name = '1';
      $u_phone = '1';
      $u_content = '1';
      $u_gift_id= '0';//$_GET["g"];

      //是否重复提交
      $openid = $z_userinfo['openid'];
      $sql12 = "select count(*) as bb from event_user a,wx_user b where a.wx_id=b.id and b.openid='{$openid}';";
      //echo $sql12;
      $re12 = select_DB_2($sql12);
      if($re12[0]["bb"] >= '1'){
        redirect("/huodong/zl.php?uu={$openid}");
      }

      insert_DB("INSERT INTO event_user (wx_id,user_name,phone,content,img_url,gift_id,if_maike) VALUES ('{$u_id}','{$u_name}','{$u_phone}','{$u_content}','{$u_file_name}','{$u_gift_id}','1');");
 
      redirect("/huodong/sh.php?s=OK");
  }
  

$sql12 = "select state from event_user a,wx_user b where a.wx_id=b.id and b.openid='{$openid}';";
//echo $sql12;
$re12 = select_DB_2($sql12);

//已经填写了麦克
if(!empty($re12)){
  //$maike = $re12[0]["if_maike"];
  $state = $re12[0]["state"];

  switch ($state) {
    case '0'://审查中
      redirect("/huodong/sh.php?s=loading");
      break;
      case '1'://审查失败
      redirect("/huodong/sh.php?s=NO");
      break;
      case '2'://审查通过
      redirect("/huodong/xw.php?s=PASS");
      break;
      case '3'://已经选完物品
      redirect("/huodong/zl.php?uu={$openid}");
      break;
    
    default:
      # code...
      break;
  }
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
  <script charset="UTF-8" defer>(function(h){function n(a){return null===a?null:a.scrollHeight>a.clientHeight?a:n(a.parentNode)}function t(b){if(b.data){var f=JSON.parse(b.data);!f.height||p||q||(d.style.height=+f.height+"px");if(f.getter){b={};var f=[].concat(f.getter),k,h=f.length,m,c,g,e;for(k=0;k<h;k++){m=k;c=f[k]||{};c.n&&(m=c.n);g=null;try{switch(c.t){case "window":e=window;break;case "scrollParent":e=n(a)||window;break;default:e=a}if(c.e)if("rect"===c.v){g={};var l=e.getBoundingClientRect();g={top:l.top,left:l.left,width:l.width,height:l.height}}else g=e[c.v].apply(e,[].concat(c.e))||!0;else c.s?(e[c.v]=c.s,g=!0):g=e[c.v]||!1}catch(u){}b[m]=g}b.innerState=!p&&!q;a.contentWindow.postMessage(JSON.stringify({queryRes:b}),"*")}}}for(var r=h.document,b=r.documentElement;b.childNodes.length&&1==b.lastChild.nodeType;)b=b.lastChild;var d=b.parentNode,a=r.createElement("iframe");d.style.overflowY="auto";d.style.overflowX="hidden";var p=d.style.height&&"auto"!==d.style.height,q="absolute"===d.style.position||window.getComputedStyle&&"absolute"===window.getComputedStyle(d,null).getPropertyValue("position")||d.currentStyle&&"absolute"===d.currentStyle.position;h.addEventListener&&h.addEventListener("message",t,!1);a.src="http://travelingbook.mikecrm.com/dcR6h6l";a.id="mkindcR6h6l";a.onload=function(){a.contentWindow.postMessage(JSON.stringify({cif:1}),"*")};a.frameBorder=0;a.scrolling="no";a.style.display="block";a.style.minWidth="100%";a.style.width="100px";a.style.height="100%";a.style.border="none";a.style.overflow="auto";d.insertBefore(a,b)})(window);</script>
</body>
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
</html>