<?php
require_once 'getcode.php';
require_once 'common.php';
$id = $_POST['uu'];
$level = $_POST['level'];
$state = $_POST['state'];

    	$bbsql = "update event_user set state='{$state}',level='{$level}' where wx_id in(select id from wx_user where openid='{$id}');";
    	//echo $bbsql;
    	//exit;
    	insert_DB($bbsql);
echo "审核成功！";
?>