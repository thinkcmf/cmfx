<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class RouteController extends AdminbaseController{
    
	protected $route_model;
	
	public function _initialize() {
		parent::_initialize();
		$this->route_model = D("Common/Route");
	}
	
	public function index(){
		$routes=$this->route_model->order("listorder asc")->select();
		sp_get_routes(true);
		$this->assign("routes",$routes);
		$this->display();
	}
	
	public function add(){
		$this->display();
	}
	
	public function add_post(){
		if(IS_POST){
			if ($this->route_model->create()!==false){
				if ($this->route_model->add()!==false) {
					$this->success("添加成功！", U("route/index"));
				} else {
					$this->error("添加失败！");
				}
			} else {
				$this->error($this->route_model->getError());
			}
		
		}
	}
	
	public function edit(){
		$id=I("get.id",0,'intval');
		$route=$this->route_model->where(array('id'=>$id))->find();
		$this->assign($route);
		$this->display();
	}
	
	public function edit_post(){
		if (IS_POST) {
			if ($this->route_model->create()!==false) {
				if ($this->route_model->save()!==false) {
					$this->success("保存成功！", U("route/index"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->route_model->getError());
			}
		}
	}
	
	/**
	 *  删除
	 */
	public function delete(){
		$id = I("get.id",0,"intval");
		if ($this->route_model->delete($id)!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}
	
	
	/**
	 *  禁用
	 */
	public function ban(){
		$id = I("get.id",0,"intval");
		$data=array();
		$data['status']=0;
		$data['id']=$id;
		if ($this->route_model->save($data)!==false) {
			$this->success("禁用成功！");
		} else {
			$this->error("禁用失败！");
		}
	}
	
	/**
	 *  启用
	 */
	public function open(){
		$id = I("get.id",0,"intval");
		$data=array();
		$data['status']=1;
		$data['id']=$id;
		if ($this->route_model->save($data)!==false) {
			$this->success("启用成功！");
		} else {
			$this->error("启用失败！");
		}
	}
	
	/**
	 * 排序
	 */
	public function listorders() {
		$status = parent::_listorders($this->route_model);
		if ($status) {
			$this->success("排序更新成功！");
		} else {
			$this->error("排序更新失败！");
		}
	}
	
	
}