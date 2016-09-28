<?php

/**
 * 会员中心
 */
namespace User\Controller;
use Common\Controller\MemberbaseController;
class CenterController extends MemberbaseController {
	
	function _initialize(){
		parent::_initialize();
	}
    //会员中心
	public function index() {
		$this->assign($this->user);
    	$this->display(':center');
    }
}
