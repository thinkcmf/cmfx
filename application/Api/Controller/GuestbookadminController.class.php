<?php
namespace Api\Controller;

use Common\Controller\AdminbaseController;

class GuestbookadminController extends AdminbaseController{
	
	protected $guestbook_model;
	
	public function _initialize() {
		parent::_initialize();
		$this->guestbook_model=D("Common/Guestbook");
	}
	
	public function index(){
		$count=$this->guestbook_model->where(array("status"=>1))->count();
		$page = $this->page($count, 20);
		$guestmsgs=$this->guestbook_model->where(array("status"=>1))->order(array("createtime"=>"DESC"))->limit($page->firstRow . ',' . $page->listRows)->select();
		$this->assign("page", $page->show('Admin'));
		$this->assign("guestmsgs",$guestmsgs);
		$this->display();
	}

	public function delete(){
		$id=I("get.id",0,'intval');
		$result=$this->guestbook_model->where(array("id"=>$id))->delete();
		if($result!==false){
			$this->success("删除成功！", U("Guestbookadmin/index"));
		}else{
			$this->error('删除失败！');
		}
	}
}