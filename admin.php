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


<table  border="1">
    <thead>
        <tr>
        <th>No.</th>
            <th>用户</th>

<th>助力值</th>
<th>等级</th>

<th>操作</th>
<th>审核状态</th>
        </tr>
    </thead>
    <tbody>


        
        <?php
        	$sql11 = "select a.openid,a.nickname,b.gift_id,b.praised_num,b.level,b.state from wx_user a,event_user b where a.id=b.wx_id  order by b.state ";
        	$re = select_DB_2($sql11);

$ii = 1;
        	foreach ($re as $key => $value) {
        		echo "<tr>";
        		echo "<td>{$ii}</td>";
            	echo "<td>{$value['nickname']}</td>";

            	echo "<td>{$value['praised_num']}</td>";
            	echo "<td>{$value['level']}</td>";
            	echo "<td><a href='/huodong/admin2.php?uu={$value['openid']}'  target='view_window'>修改</a></td>";
            	if($value['state'] == '0'){
            		echo "<td>审查中</td>";
            	}elseif($value['state'] == '1') {
            		echo "<td>审查未通过</td>";
            	}elseif($value['state'] == '2') {
            		echo "<td>审查通过,未选礼物</td>";
            	} else {
            		echo "<td>审查通过，并已经选完物品</td>";
            	}
            	
            	
            	
            	echo "</tr>";
            	$ii++;
        	}
        ?>
        
        
    </tbody>
</table>




<script>

</script>
</body>
</html>