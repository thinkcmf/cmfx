<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class SettingController extends AdminbaseController{
	
	
	protected $options_obj;
	
	function _initialize() {
		parent::_initialize();
		$this->options_obj = D("Common/Options");
	}
	
	function site(){
		$option=$this->options_obj->where("option_name='site_options'")->find();
		$cmf_settings=$this->options_obj->where("option_name='cmf_settings'")->getField("option_value");
		$tpls=scandir(C("SP_TMPL_PATH"));
		$noneed=array(".","..",".svn");
		$tpls=array_diff($tpls, $noneed);
		$this->assign("templates",$tpls);
		
		$adminstyles=scandir(SPSTATIC."simpleboot/themes");
		$adminstyles=array_diff($adminstyles, $noneed);
		$this->assign("adminstyles",$adminstyles);
		if($option){
			$this->assign((array)json_decode($option['option_value']));
			$this->assign("option_id",$option['option_id']);
		}
		
		$this->assign("cmf_settings",json_decode($cmf_settings,true));
		
		
		$this->display();
	}
	
	function site_post(){
		if (IS_POST) {
			if(isset($_POST['option_id'])){
				$data['option_id']=intval($_POST['option_id']);
			}
			
			$configs["SP_DEFAULT_THEME"]=$_POST['options']['site_tpl'];
			$configs["DEFAULT_THEME"]=$_POST['options']['site_tpl'];
			$configs["SP_ADMIN_STYLE"]=$_POST['options']['site_adminstyle'];
			$configs["URL_MODEL"]=$_POST['options']['urlmode'];
			$configs["URL_HTML_SUFFIX"]=$_POST['options']['html_suffix'];
			$configs["UCENTER_ENABLED"]=empty($_POST['options']['ucenter_enabled'])?0:1;
			$configs["COMMENT_NEED_CHECK"]=empty($_POST['options']['comment_need_check'])?0:1;
				
			sp_set_dynamic_config($configs);//sae use same function
				
			$data['option_name']="site_options";
			$data['option_value']=json_encode($_POST['options']);
			if($this->options_obj->where("option_name='site_options'")->find()){
				$r=$this->options_obj->where("option_name='site_options'")->save($data);
			}else{
				$r=$this->options_obj->add($data);
			}
			
			$cmf_settings['option_name']="cmf_settings";
			$banned_usernames=preg_replace("/[^0-9A-Za-z_\x{4e00}-\x{9fa5}-]/u", ",", $_POST['cmf_settings']['banned_usernames']);
			$_POST['cmf_settings']['banned_usernames']=$banned_usernames;
			$cmf_settings['option_value']=json_encode($_POST['cmf_settings']);

			F("cmf_settings",null);
			if($this->options_obj->where("option_name='cmf_settings'")->find()){
				$this->options_obj->where("option_name='cmf_settings'")->save($cmf_settings);
			}else{
				$r=$this->options_obj->add($cmf_settings);
			}
			
			
			
			
			if ($r!==false) {
				$this->success("保存成功！");
			} else {
				$this->error("保存失败！");
			}
			
		}
	}
	
	
	function password(){
		$this->display();
	}
	
	function password_post(){
		if (IS_POST) {
			if(empty($_POST['old_password'])){
				$this->error("原始密码不能为空！");
			}
			if(empty($_POST['password'])){
				$this->error("新密码不能为空！");
			}
			$user_obj = D("Common/Users");
			$uid=get_current_admin_id();
			$admin=$user_obj->where(array("id"=>$uid))->find();
			$old_password=$_POST['old_password'];
			$password=$_POST['password'];
			if(sp_password($old_password)==$admin['user_pass']){
				if($_POST['password']==$_POST['repassword']){
					if($admin['user_pass']==sp_password($password)){
						$this->error("新密码不能和原始密码相同！");
					}else{
						$data['user_pass']=sp_password($password);
						$data['id']=$uid;
						$r=$user_obj->save($data);
						if ($r!==false) {
							$this->success("修改成功！");
						} else {
							$this->error("修改失败！");
						}
					}
				}else{
					$this->error("密码输入不一致！");
				}
	
			}else{
				$this->error("原始密码不正确！");
			}
		}
	}
	
	//清除缓存
	function clearcache(){
			
		sp_clear_cache();
		$this->display();
	}
	
	
	
	
}