<?php
require_once 'common.php';

    class WxModel{
        public $appId;
        public $appSecret;
        public $token;
        public $redis;
        public $redis_IP;
        public $redis_port;
        
 
        public function __construct() {
            //审核通过的移动应用所给的AppID和AppSecret
            $this->appId = 'wx8e339c8f60f11a7f';
            $this->appSecret = '86f59b665cdfcd49855ba30ad063f820';
            $this->token = '00000000';

            $this->redis_IP = '127.0.0.1';
            $this->redis_port = 6379;
        }
 
 
        /**
         * 获取微信授权url
         * @param string 授权后跳转的URL
         * @param bool 是否只获取openid，true时，不会弹出授权页面，但只能获取用户的openid，而false时，弹出授权页面，可以通过openid获取用户信息
         *   
        */
        public function getOAuthUrl($redirectUrl, $openIdOnly = '', $state = "123") {
            $redirectUrl = urlencode($redirectUrl);
            $scope = $openIdOnly ? 'snsapi_base' : 'snsapi_userinfo';
            $oAuthUrl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->appId}&redirect_uri={$redirectUrl}&response_type=code&scope=$scope&state=$state#wechat_redirect";
            //echo $oAuthUrl;
            return $oAuthUrl;
        }
 
 
        /**
        * 获取access_token
        */
        public function getoAuthAccessToken($authCode) {

            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->appId}&secret={$this->appSecret}&code={$authCode}&grant_type=authorization_code";

            $re  = file_get_contents($url);
            $jj = json_decode($re,true);
            return $jj;
        }
 
        /**
        * 获取用户信息  
        */
        public function getUserInfo($openId, $accessToken) {
            $url = 'https://api.weixin.qq.com/sns/userinfo';
            //获取用户微信账号信息
            $userInfo = $this->callApi("$url?access_token=$accessToken&openid=$openId&lang=zh-CN");
 
            if (in_array('errcode', $userInfo) && $userInfo['errcode']) {
                return array('msg'=>'获取用户信息失败，请联系客服', $userInfo);
            }
 
            $userInfo['wx_id'] = $openId;
 
            return $userInfo;
        }
 
        /**
         * 发起Api请求，并获取返回结果
         * @param string 请求URL
         * @param mixed 请求参数 (array|string)
         * @param string 请求类型 (GET|POST)
         * @return array        
         */
        public function callApi($apiUrl, $param = array(), $method = 'GET') {
            $result = curl_request_json($error, $apiUrl, $param, $method);
            //假如返回的数组有错误码,或者变量$error也有值
            if (!empty($result['errcode'])) {
                $errorCode = $result['errcode'];
                $errorMsg = $result['errmsg'];
            } else if ($error != false) {
                $errorCode = $error['errorCode'];
                $errorMsg = $error['errorMessage'];
            }
 
            if (isset($errorCode)) {
                //将其插入日志文件
                file_put_contents("/data/error.log", "callApi:url=$apiUrl,error=[$errorCode]$errorMsg");
 
                if ($errorCode === 40001) {
                    //尝试更正access_token后重试
                    try {
                        $pos = strpos(strtolower($url), 'access_token=');
                        if ($pos !==false ) {
                            $pos += strlen('access_token=');
                            $pos2 = strpos($apiUrl, '&' ,$pos);
                            $accessTokened = substr($apiUrl, $pos, $pos2 === false ? null : ($pos2 - $pos));
                            return $this->callApi(str_replace($accessTokened, $this->_getApiToken(true), $apiUrl), $param, $method);
                        }
                    }catch (WeixinException $e) { 
 
                    }
                }
                //这里抛出异常，具有的就不详说了
                throw new WeixinException($errorMessage, $errorCode);
            }
            return $result;
        }
 
        /**
        * 获取微信 api 的 access_token 。 不同于 OAuth 中的 access_token ，参见  http://mp.weixin.qq.com/wiki/index.php?title=%E8%8E%B7%E5%8F%96access_token
        *
        * @param bool 是否强制刷新 accessToken
        */
        private function _getApiToken($forceRefresh = false) {
            //先查看一下redis里是否已经缓存过access_token
            $this->redis = new Redis();
            $this->redis->connect($this->redis_IP, $this->redis_port);
            $accessToken = $this->redis->get('Weixin:AccessToken');
            if($forceRefresh || empty($accessToken)) {
                $result = $this->callApi("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appId}&secret={$this->appSecret}");
                $accessToken = $result['access_token'];
                $expire = max(1, intval($result['expires_in']) - 60);
                //将access_token缓存到redis里去
                $this->redis->set('Weixin:AccessToken', $accessToken );
                $this->redis->setTimeout('Weixin:AccessToken', $expire);
            }
            return $accessToken;
        }
    }
?>