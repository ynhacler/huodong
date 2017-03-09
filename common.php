<?php



function valid_user(){
	if (valid_access_token()){
		//没有access_token就跳转到验证页面
		$redirect_url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8e339c8f60f11a7f&redirect_uri=http%3a%2f%2fwww.uhit.me%2findex.php&response_type=code&scope=snsapi_userinfo&state=123&connect_redirect=1#wechat_redirect";

		if (isset($redirect_url)) 
		{ 
			Header("HTTP/1.1 303 See Other"); 
			Header("Location: $redirect_url"); 
			exit;
		} 
	}
}

function redirect($url){
    Header("HTTP/1.1 303 See Other"); 
    Header("Location: $url"); 
    exit;
}

function valid_access_token(){
	//1、cookies是否为空
	if (!($_COOKIE['ACCESS_TOKEN'])){
		return true;
	}

	//2、access_token是否失效
	$access_token = $_COOKIE['ACCESS_TOKEN'];
	$open_id = $_COOKIE['OPEN_ID'];

	$url = "https://api.weixin.qq.com/sns/auth?access_token=$access_token&openid=$open_id";

	$error = NULL;
	$response = curl_request_json($error,$url);

	//验证是否有效，非0就是无效
	if($response["errcode"] != 0){
		return true;
	}
	return false;
}

function curl_request_json(&$error, $url, $param = array(), $method = 'GET', $timeout = 10, $exOptions = null) {
        $error = false;
        $responseText = curl_request_text($error, $url, $param, $method, $timeout, $exOptions);

        //var_dump($responseText);
        $response = null;
        //var_dump($error == false);
        //var_dump($responseText > 0);
        if ($error == false && strlen($responseText) > 0) {
            $response = json_decode($responseText, true);

            //var_dump($response);
            if ($response == null) {
                $error = array('errorCode'=>-1, 'errorMessage'=>'json decode fail', 'responseText'=>$responseText);
                //将错误信息记录日志文件里
                $logText = "json decode fail : $url";
                if (!empty($param)) {
                    $logText .= ", param=".json_encode($param);
                }
                $logText .= ", responseText=$responseText";
                file_put_contents("/data/error.log", $logText);
            }
        }
        return $response;
}

function curl_request_text(&$error, $url, $param = array(), $method = 'GET', $timeout = 15, $exOptions = NULL) {
        //判断是否开启了curl扩展
        if (!function_exists('curl_init')) exit('please open this curl extension');
 
        //将请求方法变大写
        $method = strtoupper($method);
 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        if (isset($_SERVER['HTTP_USER_AGENT'])) curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        if (isset($_SERVER['HTTP_REFERER'])) curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_REFERER']);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        switch ($method) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                if (!empty($param)) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, (is_array($param)) ? http_build_query($param) : $param);
                }
                break;
             
            case 'GET':
            case 'DELETE':
                if ($method == 'DELETE') {
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                }
                if (!empty($param)) {
                    $url = $url.(strpos($url, '?') ? '&' : '?').(is_array($param) ? http_build_query($param) : $param);
                }
                break;
        }
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        //设置额外配置
        if (!empty($exOptions)) {
            foreach ($exOptions as $k => $v) {
                curl_setopt($ch, $k, $v);
            }
        }
        $response = curl_exec($ch);
 
        $error = false;
        //看是否有报错
        $errorCode = curl_errno($ch);
        if ($errorCode) {
            $errorMessage = curl_error($ch);
            $error = array('errorCode'=>$errorCode, 'errorMessage'=>$errorMessage);
            //将报错写入日志文件里
            $logText = "$method $url: [$errorCode]$errorMessage";
            if (!empty($param)) $logText .= ",$param".json_encode($param);
            file_put_contents('/data/error.log', $logText);
        }
 
        curl_close($ch);
 
        return $response;
    }

    function insert_DB($sql){
        $db_ip = '127.0.0.1';
            $db_port = 3306;
            $db_user = 'root';
            $db_passwd = '1q2w3e';
        $conn=mysql_connect($db_ip,$db_user,$db_passwd) or die("error connecting") ; //连接数据库
        mysql_select_db('huodong',$conn);

        mysql_query("set names 'utf8'",$conn); //数据库输出编码
        mysql_query("set character set 'utf8'",$conn);//读库 
        
        mysql_query($sql,$conn);

        mysql_close($conn);
    }

    function select_DB($sql){
        $db_ip = '127.0.0.1';
            $db_port = 3306;
            $db_user = 'root';
            $db_passwd = '1q2w3e';
        $conn=mysql_connect($db_ip,$db_user,$db_passwd) or die("error connecting") ; //连接数据库
        mysql_select_db('huodong',$conn);
        mysql_query("set names 'utf8'",$conn); //数据库输出编码
        mysql_query("set character set 'utf8'",$conn);//读库 
        
        $result = mysql_query($sql,$conn);

        $results = array();
        while ($row = mysql_fetch_assoc($result)) {
            $results[] = $row;
        }

        mysql_close($conn);

        return json_encode($results,true);
    }

    function select_DB_2($sql){
        $db_ip = '127.0.0.1';
            $db_port = 3306;
            $db_user = 'root';
            $db_passwd = '1q2w3e';
        $conn=mysql_connect($db_ip,$db_user,$db_passwd) or die("error connecting") ; //连接数据库
        mysql_select_db('huodong',$conn);
        mysql_query("set names 'utf8'",$conn); //数据库输出编码
        mysql_query("set character set 'utf8'",$conn);//读库 
        
        $result = mysql_query($sql,$conn);

        $results = array();
        while ($row = mysql_fetch_assoc($result)) {
            $results[] = $row;
        }

        mysql_close($conn);

        return $results;
    }

    //随机字符串
    function create_password($pw_length = 6){ 
        $randpwd = ""; 
        for ($i = 0; $i < $pw_length; $i++) 
        { 
            $randpwd .= chr(mt_rand(33, 126)); 
        } 
        $randpwd = substr(md5($randpwd.'k2a5dd'), 8, 16);
        return $randpwd; 
    } 

    function  isGet(){
        return $_SERVER['REQUEST_METHOD'] == 'GET' ? true : false;
    }
?>