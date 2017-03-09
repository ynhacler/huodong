<?php
require_once 'getcode.php';
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
	<link rel="stylesheet" href="/pub/style.css">
	<script type="text/javascript" src="/pub/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="/pub/rem.js"></script>
  <title>首页</title>
</head>
<body>
	<body class="index-bg">
	<div style="    position: absolute;    top: 2.5rem;    left: .5rem;">
		<a href="/wd.php">礼物设置<button class="btn-send1"></button></a>
		<a href="/hd.php">活动规则<button class="btn-send2"></button></a>
		<a href="/cy.php">我要参与<button class="btn-send3"></button></a>
		<a href="/pm.php">助力排行<button class="btn-send4"></button></a>
	</div>
</body>
</html>