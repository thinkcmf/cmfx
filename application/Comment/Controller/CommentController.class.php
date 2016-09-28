<?php
namespace Comment\Controller;

use Common\Controller\MemberbaseController;

class CommentController extends MemberbaseController{
	
	protected $comments_model;
	
	public function _initialize() {
		parent::_initialize();
		$this->comments_model=D("Common/Comments");
	}
	
	public function index(){
		$uid=sp_get_current_userid();
		$where=array("uid"=>$uid);
		
		$count=$this->comments_model->where($where)->count();
		
		$page=$this->page($count,20);
		
		$comments=$this->comments_model->where($where)
		->order("createtime desc")
		->limit($page->firstRow . ',' . $page->listRows)
		->select();
		
		$this->assign("page",$page->show("default"));
		$this->assign("comments",$comments);
		$this->display(":index");
	}
	
	public function post(){
		if (IS_POST){
			
			$post_table=sp_authcode(I('post.post_table'));
			
			$_POST['post_table']=$post_table;
			
			$url=parse_url(urldecode($_POST['url']));
			$query=empty($url['query'])?"":"?{$url['query']}";
			$url="{$url['scheme']}://{$url['host']}{$url['path']}$query";

			$_POST['url']=sp_get_relative_url($url);
			
			$session_user=session('user');
			if(!empty($session_user)){//用户已登陆,且是本站会员
				$uid=session('user.id');
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
			$data=$this->comments_model->create();
			if ($data!==false){
				$this->check_last_action(intval(C("COMMENT_TIME_INTERVAL")));
				$result=$this->comments_model->add();
				if ($result!==false){
				    hook("after_comment",array_merge($data,$_POST));
					//评论计数
					$post_table=ucwords(str_replace("_", " ", $post_table));
					$post_table=str_replace(" ","",$post_table);
					$post_table_model=M($post_table);
					$pk=$post_table_model->getPk();
					
					$post_table_model->create(array("comment_count"=>array("exp","comment_count+1")));
					$post_table_model->where(array($pk=>intval($_POST['post_id'])))->save();
					
					$post_table_model->create(array("last_comment"=>time()));
					$post_table_model->where(array($pk=>intval($_POST['post_id'])))->save();
					
					$this->ajaxReturn(sp_ajax_return(array("id"=>$result),"评论成功！",1));
				} else {
					$this->error("评论失败！");
				}
			} else {
				$this->error($this->comments_model->getError());
			}
		}
		
	}
}