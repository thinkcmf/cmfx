<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class StorageController extends AdminbaseController{
	
	public function _initialize() {
		parent::_initialize();
	}
	
	// 文件存储设置
	public function index(){
		$this->assign(sp_get_cmf_settings('storage'));
		$this->display();
	}
	
	// 文件存储设置提交
	public function setting_post(){
		if(IS_POST){
			
			$support_storages=array("Local","Qiniu");
			$type=I('post.type');
			$post=I('post.');
			if(in_array($type, $support_storages)){
				$result=sp_set_cmf_setting(array('storage'=>$post));
				if($result!==false){
				    unset($post[$type]['setting']);
					sp_set_dynamic_config(array("FILE_UPLOAD_TYPE"=>$type,"UPLOAD_TYPE_CONFIG"=>$post[$type]));
					$this->success("设置成功！");
				}else{
					$this->error("设置出错！");
				}
			}else{
				$this->error("文件存储类型不存在！");
			}
		
		}
	}
	
	
	
}