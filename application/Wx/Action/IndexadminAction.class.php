<?php

/**
 * 微信公众平台管理
 */
namespace Wx\Action;
use Common\Action\AdminbaseAction;
class IndexadminAction extends AdminbaseAction {
	
    function index(){
    	$config = M("WxConfig");
    	$proto = is_ssl() ? 'https://':'http://';
    	$rst['url'] = $proto.$_SERVER['HTTP_HOST'];
    	$this->assign('rst', $rst);
    	$this->display();
    }
    
    function index_post(){
    	if(!empty($_POST)){
    		wx_val($_POST);
    		$this->success('更新成功！');
    	}
    }
}
?>
