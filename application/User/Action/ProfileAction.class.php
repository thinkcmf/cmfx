<?php

/**
 * 会员中心
 */
namespace User\Action;
use Common\Action\MemberbaseAction;
class ProfileAction extends MemberbaseAction {
	
	protected $users_model;
	function _initialize(){
		parent::_initialize();
		$this->users_model=D("Users");
	}
	
    //编辑用户资料
	public function edit() {
		$userid=sp_get_current_userid();
		$user=$this->users_model->where(array("id"=>$userid))->find();
		$this->assign($user);
    	$this->display();
    }
    
    public function edit_post() {
    	if(IS_POST){
    		$userid=sp_get_current_userid();
    		$_POST['id']=$userid;
    		if ($this->users_model->create()) {
				if ($this->users_model->save()!==false) {
					$user=$this->users_model->find($userid);
					sp_update_current_user($user);
					$this->success("保存成功！",U("user/profile/edit"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->users_model->getError());
			}
    	}
    	
    }
    
    public function password() {
    	$userid=sp_get_current_userid();
    	$user=$this->users_model->where(array("id"=>$userid))->find();
    	$this->assign($user);
    	$this->display();
    }
    
    public function password_post() {
    	if (IS_POST) {
    		if(empty($_POST['old_password'])){
    			$this->error("原始密码不能为空！");
    		}
    		if(empty($_POST['password'])){
    			$this->error("新密码不能为空！");
    		}
    		$uid=sp_get_current_userid();
    		$admin=$this->users_model->where("id=$uid")->find();
    		$old_password=$_POST['old_password'];
    		$password=$_POST['password'];
    		if(sp_password($old_password)==$admin['user_pass']){
    			if($_POST['password']==$_POST['repassword']){
    				if($admin['user_pass']==sp_password($password)){
    					$this->error("新密码不能和原始密码相同！");
    				}else{
    					$data['user_pass']=sp_password($password);
    					$data['id']=$uid;
    					$r=$this->users_model->save($data);
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
    
    
    function bang(){
    	$oauth_user_model=M("OauthUser");
    	$uid=sp_get_current_userid();
    	$oauths=$oauth_user_model->where(array("uid"=>$uid))->select();
    	$new_oauths=array();
    	foreach ($oauths as $oa){
    		$new_oauths[strtolower($oa['from'])]=$oa;
    	}
    	$this->assign("oauths",$new_oauths);
    	$this->display();
    }
    
    function avatar(){
    	$userid=sp_get_current_userid();
		$user=$this->users_model->where(array("id"=>$userid))->find();
		$this->assign($user);
    	$this->display();
    }
    
    function avatar_upload(){
    	$config=array(
    			'rootPath' => './'.C("UPLOADPATH"),
    			'savePath' => './avatar/',
    			'maxSize' => 512000,//500K
    			'saveName'   =>    array('uniqid',''),
    			'exts'       =>    array('jpg', 'png', 'jpeg'),
    			'autoSub'    =>    false,
    	);
    	$upload = new \Think\Upload($config);//
    	$info=$upload->upload();
    	//开始上传
    	if ($info) {
    	//上传成功
    	//写入附件数据库信息
    		$first=array_shift($info);
    		$file=$first['savename'];
    		$_SESSION['avatar']=$file;
    		$this->ajaxReturn(array("file"=>$file),"上传成功！",1,"AJAX_UPLOAD");
    	} else {
    		//上传失败，返回错误
    		$this->ajaxReturn(array(),$upload->getError(),0,"AJAX_UPLOAD");
    	}
    }
    
    function avatar_update(){
    	if(!empty($_SESSION['avatar'])){
    		$targ_w = $_POST['w'];
    		$targ_h = $_POST['h'];
    		$jpeg_quality = 90;
    		
    		$avatar_dir=C("UPLOADPATH")."avatar/";
    		$avatar=$_SESSION['avatar'];
    		
    		$src = $avatar_dir.$avatar;
    		
    		$imginfo=getimagesize($src);
    		
    		$ext=array("2"=>".jpg","3"=>".png");
    		
    		if(empty($imginfo)){
    			$this->error("图像非法！");
    		}
    		
    		if(! array_key_exists($imginfo[2], $ext)){
    			$this->error("文件类型不支持！");
    		}
    		
    		$createmethods=array("2"=>"imagecreatefromjpeg","3"=>"imagecreatefrompng");
    		
    		$createmethod=$createmethods[$imginfo[2]];
    		
    		$img_r = $createmethod($src);
    		
    		imagesavealpha($img_r, true);
    		$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
    		
    		$color=imagecolorallocate($dst_r,255,255,255);
    		imagecolortransparent($dst_r,$color);
    		imagefill($dst_r,0,0,$color);
    		
    		imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
    		$targ_w,$targ_h,$targ_w,$targ_h);
    		
    		$result=imagepng($dst_r,$src,0);
    		if($result){
    			$userid=sp_get_current_userid();
    			$result=$this->users_model->where(array("id"=>$userid))->save(array("avatar"=>$avatar));
    			$_SESSION['user']['avatar']=$avatar;
    			if($result){
    				$this->success("头像更新成功！");
    			}else{
    				$this->error("头像更新失败！");
    			}
    		}else{
    			$this->success("头像文件保存失败！");
    		}
    		
    	}
    }
    
    
}
    
