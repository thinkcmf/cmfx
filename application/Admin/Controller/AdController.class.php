<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class AdController extends AdminbaseController{
	protected $ad_obj;
	
	function _initialize() {
		parent::_initialize();
		$this->ad_obj = D("Common/Ad");
	}
	function index(){
		$ads=$this->ad_obj->select();
		$this->assign("ads",$ads);
		$this->display();
	}
	
	
	function add(){
		$this->display();
	}
	function add_post(){
		if(IS_POST){
			if ($this->ad_obj->create()){
				if ($this->ad_obj->add()!==false) {
					$this->success("添加成功！", U("ad/index"));
				} else {
					$this->error("添加失败！");
				}
			} else {
				$this->error($this->ad_obj->getError());
			}
		
		}
	}
	
	
	function edit(){
		$id=I("get.id");
		$ad=$this->ad_obj->where("ad_id=$id")->find();
		$this->assign($ad);
		$this->display();
	}
	
	function edit_post(){
		if (IS_POST) {
			if ($this->ad_obj->create()) {
				if ($this->ad_obj->save()!==false) {
					$this->success("保存成功！", U("ad/index"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->ad_obj->getError());
			}
		}
	}
	
	/**
	 *  删除
	 */
	function delete(){
		$id = I("get.id",0,"intval");
		$data['ad_id']=$id;
		if ($this->ad_obj->delete()!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}
	
	function toggle(){
		if(isset($_POST['ids']) && $_GET["display"]){
			$ids = implode(",", $_POST['ids']);
			$data['status']=1;
			if ($this->ad_obj->where("ad_id in ($ids)")->save($data)!==false) {
				$this->success("显示成功！");
			} else {
				$this->error("显示失败！");
			}
		}
		if(isset($_POST['ids']) && $_GET["hide"]){
			$ids = implode(",", $_POST['ids']);
			$data['status']=0;
			if ($this->ad_obj->where("ad_id in ($ids)")->save($data)!==false) {
				$this->success("隐藏成功！");
			} else {
				$this->error("隐藏失败！");
			}
		}
	}
	
	
	
	
	
}