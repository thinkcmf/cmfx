<?php
namespace Common\Controller;
use Common\Controller\HomeBaseController;
class MemberbaseController extends HomeBaseController{
	
	function _initialize() {
		parent::_initialize();
		
		$this->check_login();
		$this->check_user();
		
	}
	
}