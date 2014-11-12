<?php
namespace Comment\Action;
use Common\Action\MemberbaseAction;
class CommentAction extends MemberbaseAction{
	
	protected $comments_model;
	
	function _initialize() {
		parent::_initialize();
		$this->comments_model=D("Comments");
	}
	
	function index(){
		$uid=sp_get_current_userid();
		$where=array("uid"=>$uid);
		
		$count=$this->comments_model->where($where)->count();
		
		$page=$this->page($count,20);
		$page->setLinkWraper("li");
		
		$comments=$this->comments_model->where($where)
		->order("createtime desc")
		->limit($page->firstRow . ',' . $page->listRows)
		->select();
		
		$this->assign("pager",$page->show("default"));
		$this->assign("comments",$comments);
		$this->display(":index");
	}
	
	function post(){
		/* if($_SESSION['_verify_']['verify']!=I("post.verify")){
			$this->error("验证码错误！");
		} */
		
		if (IS_POST){
			
			$post_table=sp_authcode($_POST['post_table']);
			
			$_POST['post_table']=$post_table;
			
			$url=parse_url(urldecode($_POST['url']));
			$query=empty($url['query'])?"":"?{$url['query']}";
			$url="{$url['scheme']}://{$url['host']}{$url['path']}$query";

			$_POST['url']=sp_get_relative_url($url);
			
			if(isset($_SESSION["user"])){//用户已登陆,且是本站会员
				$uid=$_SESSION["user"]['id'];
				$_POST['uid']=$uid;
				$users_model=M('Users');
				$user=$users_model->field("user_login,user_email,user_nicename")->where("id=$uid")->find();
				$username=$user['user_login'];
				$user_nicename=$user['user_nicename'];
				$email=$user['user_email'];
				$_POST['full_name']=empty($user_nicename)?$username:$user_nicename;
				$_POST['email']=$email;
			}
			
			if(C("COMMENT_NEED_CHECK")){
				$_POST['status']=0;//评论审核功能开启
			}else{
				$_POST['status']=1;
			}
			
			if ($this->comments_model->create()){
				$this->check_last_action(60);
				$result=$this->comments_model->add();
				if ($result!==false){
					
					//评论计数
					$post_table=ucwords(str_replace("_", " ", $post_table));
					$post_table=str_replace(" ","",$post_table);
					$post_table_model=M($post_table);
					$pk=$post_table_model->getPk();
					$post_table_model->where(array($pk=>intval($_POST['post_id'])))->save(array("comment_count"=>array("exp","comment_count+1")));
					
					$this->ajaxReturn(array("id"=>$result),"评论成功！",1);
				} else {
					$this->error("评论失败！");
				}
			} else {
				$this->error($this->comments_model->getError());
			}
		}
		
	}
}