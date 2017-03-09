<?php
require_once 'getcode.php';
require_once 'common.php';
header("Content-Type: text/html;charset=utf-8"); 

//检查uu是否存在
if(is_array($_GET)&&count($_GET)>0)//先判断是否通过get传值了
{
    if(!isset($_GET["uu"]))//是否存在"id"的参数
    {
        redirect("/index.php");
    }
}else{
	redirect("/index.php");
}

$zzh = new weixinController('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
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
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>助力页面</title>
</head>
<body>
倒计时：8天8时33分11秒<br/>
<?php
echo "<img src='{$z_userinfo['headimgurl']}' />";
echo "{$z_userinfo['nickname']}<br/>";

$openid = $z_userinfo['openid'];
$lizhisql = "select praised_num from event_user a,wx_user b where a.wx_id=b.id and b.openid='{$openid}';";
$re123 = select_DB_2($lizhisql);
echo "力值：{$re123[0]["praised_num"]}<br/>";

$paiming = 999;//select_DB_2("123");
echo "排名：{$paiming}";
?>
<br/>
==================<br/>
	<a href="http://www.uhit.me/zhuta.php?uu=<?php echo $_SERVER['QUERY_STRING'];?>&uud=<?php echo $openid;?>">助它一臂之力</a><br/>
	<a href="http://www.uhit.me/wd.php">奖品设置</a><br/>
	<a href="http://www.uhit.me/cy.php">我也要参与</a><br/>
	<a href="##">分享</a>

</body>

</html>