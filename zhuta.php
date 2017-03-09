<?php
require_once 'getcode.php';
require_once 'common.php';
header("Content-Type: text/html;charset=utf-8"); 

if(is_array($_GET) && count($_GET)>0 && isset($_GET["uu"]) && isset($_GET["uud"]))//先判断是否通过get传值了
{
    $praised = $_GET["uu"];//被投
    $praise = $_GET["uud"];//投票的

    $aa = array();

    //1、验证所有uid是否合法
    $er1 = "select count(*) as aa from wx_user where openid='{$praised}';";
	$re11 = select_DB_2($er1);
	$er2 = "select count(*) as aa from wx_user where openid='{$praise}';";
	$re22 = select_DB_2($er2);
	
	if($re22[0]["aa"] != 1 || $re11[0]["aa"] != 1){
		$aa['msg'] = "ER2";
		echo json_encode($aa);
		exit;
	}

    //2、是否已经投过
    $er3 = "select count(*) as aa from praise_table where praised_uid='{$praised}' and praise_uid='{$praise}';";
	$re33 = select_DB_2($er3);
	if($re33[0]["aa"] == 1){
 	   $aa['msg'] = "您已经投过了！";
		echo json_encode($aa);
		exit;
	}

    //3、记录并修改praised_num
    insert_DB("insert into praise_table(praised_uid,praise_uid) values ('{$praised}','{$praise}');");
    insert_DB("update event_user set praised_num=praised_num+1 where wx_id='{$praised}';");
    $aa['msg'] = "助力成功！！！";
		echo json_encode($aa);
		exit;

}else{
	$aa['msg'] = "ER2";
		echo json_encode($aa);
		exit;
}
?>