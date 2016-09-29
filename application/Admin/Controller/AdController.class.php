<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class AdController extends AdminbaseController{
    
	protected $ad_model;
	
	public function _initialize() {
		parent::_initialize();
		$this->ad_model = D("Common/Ad");
	}
	
	public function index(){
		$ads=$this->ad_model->select();
		$this->assign("ads",$ads);
		$this->display();
	}
	
	public function add(){
		$this->display();
	}
	
	public function add_post(){
		if(IS_POST){
			if ($this->ad_model->create()!==false){
				if ($this->ad_model->add()!==false) {
					$this->success(L('ADD_SUCCESS'), U("ad/index"));
				} else {
					$this->error(L('ADD_FAILED'));
				}
			} else {
				$this->error($this->ad_model->getError());
			}
		
		}
	}
	
	public function edit(){
		$id=I("get.id",0,'intval');
		$ad=$this->ad_model->where(array('ad_id'=>$id))->find();
		$this->assign($ad);
		$this->display();
	}
	
	public function edit_post(){
		if (IS_POST) {
			if ($this->ad_model->create()!==false) {
				if ($this->ad_model->save()!==false) {
					$this->success("保存成功！", U("ad/index"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->ad_model->getError());
			}
		}
	}
	
	/**
	 *  删除
	 */
	public function delete(){
		$id = I("get.id",0,"intval");
		if ($this->ad_model->delete($id)!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}
	
	public function toggle(){
		if(!empty($_POST['ids']) && isset($_GET["display"])){
			$ids = I('post.ids/a');
			if ($this->ad_model->where(array('ad_id'=>array('in',$ids)))->save(array('status'=>1))!==false) {
				$this->success("显示成功！");
			} else {
				$this->error("显示失败！");
			}
		}
		
		if(isset($_POST['ids']) && isset($_GET["hide"])){
			$ids = I('post.ids/a');
			if ($this->ad_model->where(array('ad_id'=>array('in',$ids)))->save(array('status'=>0))!==false) {
				$this->success("隐藏成功！");
			} else {
				$this->error("隐藏失败！");
			}
		}
	}
	
}