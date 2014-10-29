<?php
namespace Api\Action;
use Common\Action\AdminbaseAction;
class GuestbookadminAction extends AdminbaseAction{
	
	protected $guestbook_model;
	
	function _initialize() {
		parent::_initialize();
		$this->guestbook_model=D("Guestbook");
	}
	
	function index(){
		$count=$this->guestbook_model->where(array("status"=>1))->count();
		$page = $this->page($count, 20);
		$guestmsgs=$this->guestbook_model->where(array("status"=>1))->order(array("createtime"=>"DESC"))->limit($page->firstRow . ',' . $page->listRows)->select();
		$this->assign("Page", $page->show('Admin'));
		$this->assign("guestmsgs",$guestmsgs);
		$this->display();
	}

	function delete(){
		$id=intval(I("get.id"));
		$result=$this->guestbook_model->where(array("id"=>$id))->delete();
		if($result!==false){
			$this->success("删除成功！", U("Guestbookadmin/index"));
		}else{
			$this->error('删除失败！');
		}
	}
}