<?php
require_once 'getcode.php';
require_once 'common.php';
header("Content-Type: text/html;charset=utf-8"); 
$zzh = new weixinController();
//$z_userinfo = json_decode($zzh->userInfo,true);
//$z_userinfo = $z_userinfo[0];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>排名页面</title>
</head>
<body>
	<p>礼物排行：</p>
	<select name=""> 
		<?php
			require_once 'common.php';
			$re = select_DB_2("select id,title from gift;");
			foreach ($re as $key => $value) { 
				echo "<option value='{$value['id']}'>{$value['title']}</option>";
			}

		?>
	</select>
	<br/>
	<?php
		$sqlz = "select b.openid ,b.nickname ,b.headimgurl,a.praised_num from event_user a,wx_user b where b.id=a.wx_id and a.gift_id=1 order by a.praised_num desc;";
		$re = select_DB_2($sqlz);
		$ii = 1;
		foreach ($re as $key => $value) { 
			echo "<table border="1">";
			echo "<tr>";
			  echo "<td><img src='{$value['headimgurl']}' /></td>";
			  echo "<td>{$value['nickname']}</td>";
			  echo "<td>{$value['praised_num']}</td>";
			  echo "<td>排名：{$ii}</td>";
			echo "</tr>";
			echo "</table>";
			echo "<br/>";
			$ii++;
		}
	?>
	
</body>

</html>