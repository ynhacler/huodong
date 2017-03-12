<?php
require_once 'getcode.php';
require_once 'common.php';
?>

<!DOCTYPE html>
<html>
	<head>
	<meta charset="utf-8">
	<script type="text/javascript" src="/huodong/pub/jquery-1.7.2.min.js"></script>
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.js"></script>

<!-- Latest compiled and minified Locales -->
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/locale/bootstrap-table-zh-CN.min.js"></script>
  <title>管理页面</title>
</head>
<body>
    <?php
        $id = $_GET['uu'];
        	$sql11 = "select * from wx_user a,event_user b where a.id=b.wx_id and a.openid='{$id}'";
        	//echo $sql11;
        	$re = select_DB_2($sql11);
        	//var_dump($re);
        	//exit;
        	$z= $re[0];

        ?>
        <p>用户名: <?php echo "{$z['nickname']}";?></p>
      <form action="admin3.php" method="POST">
  <p>ID:<input type="text" name="uu" readonly="true" value="<?php echo $id; ?>"/></p>
  <p>等级: 
<select name="level">
  <option value ="1">1</option>
  <option value ="2">2</option>
  <option value="3">3</option>
  <option value="4">4</option>
</select>

  </p>
  <p>审核状态:
<select name="state">
  <option value ="0">审查中</option>
  <option value ="1">审查未通过</option>
  <option value="2">审查通过,未选礼物</option>
  <option value="3">通过并已经选完物品</option>
</select>

  </p>
  <input type="submit" value="OK" />
</form>  



<script>

</script>
</body>
</html>