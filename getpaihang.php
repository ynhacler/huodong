<?php
require_once 'getcode.php';
require_once 'common.php';
header("Content-Type: text/html;charset=utf-8"); 

if(is_array($_GET) && count($_GET)>0 && isset($_GET["next"]) && isset($_GET["jiang"]))//先判断是否通过get传值了
{
	$next_num = int($_GET["next"]);
	$jiang = $_GET["jiang"];

	$sqlz = "select b.openid ,b.nickname ,b.headimgurl,a.praised_num from event_user a,wx_user b where b.id=a.wx_id and a.gift_id='{$jiang}' order by a.praised_num desc limit {$next_num}-1,5;";
	$re = select_DB_2($sqlz);
	foreach ($re as $key => $value) {
	}
	var_dump($re);
	exit;
}else{
	echo 'error';
}

?>
