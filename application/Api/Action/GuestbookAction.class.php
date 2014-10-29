<?php
namespace Api\Action;
use Common\Action\AppframeAction;
class GuestbookAction extends AppframeAction{
	
	protected $guestbook_model;
	
	function _initialize() {
		parent::_initialize();
		$this->guestbook_model=D("Guestbook");
	}
	
	function index(){
		
	}
	
	function addmsg(){
		if($_SESSION['_verify_']['verify']!=I("post.verify")){
			$this->error("验证码错误！");
		}
		
		if (IS_POST) {
			if ($this->guestbook_model->create()) {
				$result=$this->guestbook_model->add();
				if ($result!==false) {
					$_SESSION['_verify_']['verify']="";
					$this->success("留言成功！");
				} else {
					$this->error("留言失败！");
				}
			} else {
				$this->error($this->guestbook_model->getError());
			}
		}
		
	}
}