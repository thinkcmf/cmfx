<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class NavcatController extends AdminbaseController {
	
	protected $navcat_model;
	
	public function _initialize() {
		parent::_initialize();
		$this->navcat_model =D("Common/NavCat");
	}
	
	// 导航分类显示
	public function index() {
		$cats=$this->navcat_model->select();
		$this->assign("navcats",$cats);
		$this->display();
	}
	
	// 导航分类添加
	public function add() {
		$this->display();
	}
	
	// 导航分类添加提交
	public function add_post() {
		if (IS_POST) {
			if(empty($_POST['active'])){
				$_POST['active']=0;
			}else{
				$this->navcat_model->where("active=1")->save(array("active"=>0));
			}
			if ($this->navcat_model->create()!==false) {
				if ($this->navcat_model->add()!==false) {
					$this->success("添加成功！", U("navcat/index"));
				} else {
					$this->error("添加失败！");
				}
			} else {
				$this->error($this->navcat_model->getError());
			}
		}
	}
	
	// 导航分类编辑
	public function edit(){
		$id= I("get.id",0,'intval');
		$navcat=$this->navcat_model->where(array('navcid'=>$id))->find();
		$this->assign($navcat);
		$this->display();
	}
	
	// 导航分类编辑提交
	public function edit_post(){
		if (IS_POST) {
			if(empty($_POST['active'])){
				$_POST['active']=0;
			}else{
				$this->navcat_model->where("active=1")->save(array("active"=>0));
			}
			if ($this->navcat_model->create() !== false) {
				if ($this->navcat_model->save() !== false) {
					$this->success("保存成功！", U("navcat/index"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->navcat_model->getError());
			}
		}
	}
	
	// 删除导航分类
	public function delete(){
		$id = I("get.id",0,'intval');
		if ($this->navcat_model->where(array('navcid'=>$id))->delete()!==false) {
			$nav_obj=D("Common/Nav");
			$nav_obj->where(array('cid'=>$id))->delete();
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}
	
}