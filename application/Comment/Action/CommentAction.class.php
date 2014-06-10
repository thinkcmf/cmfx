<?php
namespace Comment\Action;
use Common\Action\AppframeAction;
class CommentAction extends AppframeAction{
	
	protected $comments_model;
	
	function _initialize() {
		parent::_initialize();
		$this->comments_model=D("Comments");
	}
	
	
	function index(){
		
	}
	
	
	
	function post(){
		if($_SESSION['_verify_']['verify']!=I("post.verify")){
			$this->error("验证码错误！");
		}
		
		if (IS_POST){
			$_POST['post_table']=sp_authcode($_POST['post_table']);
			if ($this->comments_model->create()){
				
				if(isset($_SESSION["MEMBER_type"]) && $_SESSION["MEMBER_type"]=='local'){//用户已登陆,且是本站会员
					$uid=$_SESSION["MEMBER_id"];
					$_POST['uid']=$uid;
					$members_model=M('Members');
					$member=$members_model->field("user_login_name,user_email")->where("ID=$uid")->find();
					$username=$member['user_login_name'];
					$email=$member['user_email'];
					$_POST['full_name']=$username;
					$_POST['email']=$email;
				}
				
				$result=$this->comments_model->add();
				if ($result!==false){
					$this->success("评论成功！");
				} else {
					$this->error("评论失败！");
				}
			} else {
				$this->error($this->comments_model->getError());
			}
		}
		
	}
}