<?php
require_once 'getcode.php';
require_once 'common.php';

header("Content-Type: text/html;charset=utf-8"); 
$zzh = new weixinController();

//判断是否参加过
$z_userinfo = json_decode($zzh->userInfo,true);
$z_userinfo = $z_userinfo[0];

$openid = $z_userinfo['openid'];
$sql12 = "select count(*) as bb from event_user a,wx_user b where a.wx_id=b.id and b.openid='{$openid}';";
//echo $sql12;
$re12 = select_DB_2($sql12);
if($re12[0]["bb"] == '1'){
	redirect("/zl.php?uu={$openid}");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>参加活动</title>
</head>
<body>
	<form action="/zl.php?uu=<?php echo $openid?>&state=123" method="post" enctype="multipart/form-data">
	姓名:<br>
	<input type="text" name="user_name" value="Mickey">
	<br>
	手机号:<br>
	<input type="text" name="user_phone" value="Mouse">
	<br>文字描述:<br>
	<textarea rows="4" cols="50" name="user_content">
	请在此处输入文本...</textarea>
	<br>上传图片:<br>
	<input name="user_imgfile" type="file" />
	<br>礼物选项:<br>
	<select name="user_gift_id" >
		<?php
			require_once 'common.php';
			$re = select_DB_2("select id,title from gift;");
			foreach ($re as $key => $value) { 
				echo "<option value='{$value['id']}'>{$value['title']}</option>";
			}

		?>
	</select> 
	<br><br>
	<input type="submit" value="开始助力">
	</form> 
</body>

</html>