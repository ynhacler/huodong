<?php
require_once 'getwx.php';
require_once 'common.php';

    class weixinController{
        public $userInfo;
        public $wxId;
 
        public function __construct($re_url = "http://www.uhit.me/index.php"){

            //只要用户一访问此模块，就登录授权，获取用户信息
            $this->userInfo = $this->getWxUserInfo($re_url);
        }
     
 
        /**
         * 确保当前用户是在微信中打开，并且获取用户信息
         *
         * @param string $url 获取到微信授权临时票据（code）回调页面的URL
         */
        private function getWxUserInfo($url) {
            $wxModel = new WxModel();
            //微信标记（自己创建的）
            if(!empty($_COOKIE['wxSign'])){
                //缓存中有信息
                $wxSign = $_COOKIE['wxSign'];
                //先看看本地cookie里是否存在微信唯一标记，
                //假如存在，可以通过$wxSign到DB里取出微信个人信息（因为在第一次取到微信个人信息，我会将其保存一份到DB服务器里缓存着）
                if (!empty($wxSign)) {
                    //如果存在，则从DB里取出缓存了的数据
                    $userInfo = select_DB("select * from wx_user where wxSign = '{$wxSign}';");
                    var_dump($userInfo['openid']);
                    exit;
                    if (!empty($userInfo)) {
                        //获取用户的openid
                        $this->wxId = $userInfo['openid'];
                        //将其存在cookie里
                        setcookie('wxId', $this->wxId, time() + 60*60*24*7);
                        return $userInfo;
                    }
                }
            }

            if(empty($_GET['code'])){
                redirect($wxModel->getOAuthUrl($url));
            }
            
 
            //获取授权临时票据（code）
            $code = $_GET['code'];

            /***************这里开始第二步：通过code获取access_token****************/
            $result = $wxModel->getOauthAccessToken($code);


            //如果发生错误
            if (isset($result['errcode'])) {
                return array('msg'=>'授权失败,请联系客服','result'=>$result);
            }
 
            //到这一步就说明已经取到了access_token
            $this->wxId = $result['openid'];
            $accessToken = $result['access_token'];
            $openId = $result['openid'];
 
            //将openid和accesstoken存入cookie中
            setcookie('wx_id', $this->wxId, time() + 60*60*24*7);
            setcookie('access_token', $accessToken);
 
            /*******************这里开始第三步：通过access_token调用接口，取出用户信息***********************/
            $userInfo = $wxModel->getUserInfo($openId, $accessToken);
 
            //自定义微信唯一标识符
            $wxSign =substr(md5($this->wxId.'k2a5dd'), 8, 16);
            //将其存到cookie里
            setcookie('wxSign', $wxSign, time() + 60*60*24*7);
            //将个人信息缓存到DB里，先检查是否存在了
            $sql234 = "select count(*) as bb from wx_user where openid='{$openId}';";
            $re125 = select_DB_2($sql234);
            if($re125[0]["bb"] == '1'){
                return $userInfo;
            }

            insert_DB("INSERT INTO `wx_user` (`wxSign`,`openid`,`nickname`,`headimgurl`) VALUES ('{$wxSign}', '{$userInfo['openid']}', '{$userInfo["nickname"]}', '{$userInfo["headimgurl"]}');");

            return $userInfo;
        }

    }
?>