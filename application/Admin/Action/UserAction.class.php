<?php
namespace Admin\Action;
use Common\Action\AdminbaseAction;
class UserAction extends AdminbaseAction{
	protected $users_obj,$role_obj;
	
	function _initialize() {
		parent::_initialize();
		$this->users_obj = D("Users");
		$this->role_obj = D("Role");
	}
	function index(){
		$users=$this->users_obj->where(array("user_type"=>1))->select();
		$roles_src=$this->role_obj->select();
		$roles=array();
		foreach ($roles_src as $r){
			$roleid=$r['id'];
			$roles["$roleid"]=$r;
		}
		$this->assign("roles",$roles);
		$this->assign("users",$users);
		$this->display();
	}
	
	
	function add(){
		$roles=$this->role_obj->where("status=1")->select();
		$this->assign("roles",$roles);
		$this->display();
	}
	
	function add_post(){
		if(IS_POST){
			if ($this->users_obj->create()) {
				if ($this->users_obj->add()!==false) {
					$this->success("添加成功！", U("user/index"));
				} else {
					$this->error("添加失败！");
				}
			} else {
				$this->error($this->users_obj->getError());
			}
		}
	}
	
	
	function edit(){
		$id= intval(I("get.id"));
		$roles=$this->role_obj->where("status=1")->select();
		$this->assign("roles",$roles);
			
		$user=$this->users_obj->where(array("id"=>$id))->find();
		$this->assign($user);
		$this->display();
	}
	
	function edit_post(){
		if (IS_POST) {
			if(empty($_POST['user_pass'])){
				unset($_POST['user_pass']);
			}
			if ($this->users_obj->create()) {
				$result=$this->users_obj->save();
				if ($result!==false) {
					$this->success("保存成功！");
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->users_obj->getError());
			}
		}
	}
	
	/**
	 *  删除
	 */
	function delete(){
		$id = intval(I("get.id"));
		if($id==1){
			$this->error("最高管理员不能删除！");
		}
		
		if ($this->users_obj->where("id=$id")->delete()!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}
	
	
	function userinfo(){
		$id=get_current_admin_id();
		$user=$this->users_obj->where(array("id"=>$id))->find();
		$this->assign($user);
		$this->display();
	}
	
	function userinfo_post(){
		if (IS_POST) {
			if ($this->users_obj->create()) {
				if ($this->users_obj->save()!==false) {
					$this->success("保存成功！");
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->users_obj->getError());
			}
		}
	}
	
	
	
	
	
}