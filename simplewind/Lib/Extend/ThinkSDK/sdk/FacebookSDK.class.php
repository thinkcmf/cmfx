<?php
// +----------------------------------------------------------------------
// | TOPThink [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Co <imdevmsg@gmail.com> <https://www.devmsg.com>
// +----------------------------------------------------------------------
// | FacebookSDK.class.php 2016-09-05
// +----------------------------------------------------------------------

class FacebookSDK extends ThinkOauth{
	/**
	* 获取requestCode的api接口
	* @var string
	*/
    protected $GetRequestCodeURL = 'https://www.facebook.com/dialog/oauth';
    /**
     * 获取access_token的api接口
     * @var string
     */
    protected $GetAccessTokenURL = 'https://graph.facebook.com/oauth/access_token';
    /**
     * 获取request_code的额外参数 URL查询字符串格式
     * @var srting
     */
    protected $Authorize = 'scope=email';
    /**
     * API根路径
     * @var string
     */
    protected $ApiBase = 'https://graph.facebook.com/';
    /**
     * 组装接口调用参数 并调用接口
     * @param  string $api    微博API
     * @param  string $param  调用API的额外参数
     * @param  string $method HTTP请求方法 默认为GET
     * @param  bool   $multi
     * @return array
     */
    public function call($api, $param = '', $method = 'GET', $multi = false){
        /*  facebook 调用公共参数 */
        $params = array('access_token'=>$this->Token['access_token']);
        $header = array();
        $data = $this->http($this->url($api), $this->param($params, $param), $method, $header);
        return json_decode($data, true);
    }
    
    /**
     * 解析access_token方法请求后的返回值
     * @param string $result 获取access_token的方法的返回值
     * @param mix    $extend
     * @return array
     */
    protected function parseToken($result, $extend){
        parse_str($result, $data);
        if(is_array($data) && $data['access_token'] && $data['expires']){
            $this->Token = $data;
            $data['openid'] = $this->openid();
            return $data;
        }else{
            throw new Exception("获取 facebook ACCESS_TOKEN出错：未知错误");
        }
    }
    
    /**
     * 获取当前授权应用的openid
     * @return string
     */
    public function openid(){
        if(isset($this->Token['openid']))
            return $this->Token['openid'];
        
        $data = $this->call('me');
        if(!empty($data['id']))
            return $data['id'];
        else
            throw new Exception('没有获取到 facebook 用户ID！');
    }
}