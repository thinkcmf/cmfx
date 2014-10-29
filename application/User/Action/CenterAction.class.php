<?php

/**
 * 会员中心
 */
namespace User\Action;
use Common\Action\MemberbaseAction;
class CenterAction extends MemberbaseAction {
	
	protected $users_model;
	function _initialize(){
		parent::_initialize();
		$this->users_model=D("Users");
	}
    //会员中心
	public function index() {
		$userid=sp_get_current_userid();
		$user=$this->users_model->where(array("id"=>$userid))->find();
		$this->assign($user);
    	$this->display(':center');
    }
}
?>
