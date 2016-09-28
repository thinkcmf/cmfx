<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class LinkController extends AdminbaseController{
	
	protected $link_model;
	protected $targets=array("_blank"=>"新标签页打开","_self"=>"本窗口打开");
	
	public function _initialize() {
		parent::_initialize();
		$this->link_model = D("Common/Links");
	}
	
	public function index(){
		$links=$this->link_model->order(array("listorder"=>"ASC"))->select();
		$this->assign("links",$links);
		$this->display();
	}
	
	public function add(){
		$this->assign("targets",$this->targets);
		$this->display();
	}
	
	public function add_post(){
		if(IS_POST){
			if ($this->link_model->create()!==false) {
				if ($this->link_model->add()!==false) {
					$this->success("添加成功！", U("link/index"));
				} else {
					$this->error("添加失败！");
				}
			} else {
				$this->error($this->link_model->getError());
			}
		
		}
	}
	
	public function edit(){
		$id=I("get.id",0,'intval');
		$link=$this->link_model->where(array('link_id'=>$id))->find();
		$this->assign($link);
		$this->assign("targets",$this->targets);
		$this->display();
	}
	
	public function edit_post(){
		if (IS_POST) {
			if ($this->link_model->create()!==false) {
				if ($this->link_model->save()!==false) {
					$this->success("保存成功！");
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->link_model->getError());
			}
		}
	}
	
	/**
	 * 排序
	 */
	public function listorders() {
		$status = parent::_listorders($this->link_model);
		if ($status) {
			$this->success("排序更新成功！");
		} else {
			$this->error("排序更新失败！");
		}
	}
	
	/**
	 * 删除
	 */
	public function delete(){
		if(isset($_POST['ids'])){
			
		}else{
			$id = I("get.id",0,'intval');
			if ($this->link_model->delete($id)!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
	
	}
	
	/**
	 * 显示/隐藏
	 */
	public function toggle(){
		if(isset($_POST['ids']) && $_GET["display"]){
			$ids = I('post.ids/a');
			if ($this->link_model->where(array('link_id'=>array('in',$ids)))->save(array('link_status'=>1))!==false) {
				$this->success("显示成功！");
			} else {
				$this->error("显示失败！");
			}
		}
		
		if(isset($_POST['ids']) && $_GET["hide"]){
			$ids = I('post.ids/a');
			if ($this->link_model->where(array('link_id'=>array('in',$ids)))->save(array('link_status'=>1))!==false) {
				$this->success("隐藏成功！");
			} else {
				$this->error("隐藏失败！");
			}
		}
	}
	
	
}