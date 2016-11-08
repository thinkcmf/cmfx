<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Tuolaji <479923197@qq.com>
// +----------------------------------------------------------------------
namespace Portal\Controller;

use Common\Controller\AdminbaseController;

class AdminPageController extends AdminbaseController {
    
	protected $posts_model;
	
	function _initialize() {
		parent::_initialize();
		$this->posts_model =D("Common/Posts");
	}
	
	// 后台页面管理列表
	public function index(){
	    $this->_lists(array("post_status"=>array('neq',3)));
	    $this->display();
	}
	
	// 后台页面回收站
	public function recyclebin(){
	    $this->_lists(array('post_status'=>array('eq',3)));
	    $this->display();
	}
	
	/**
	 * 页面列表处理方法,根据不同条件显示不同的列表
	 * @param array $where 查询条件
	 */
	private function _lists($where=array()){
	
	    $where['post_type']=array('eq',2);
	
	    $start_time=I('request.start_time');
	    if(!empty($start_time)){
	        $where['post_date']=array(
	            array('EGT',$start_time)
	        );
	    }
	
	    $end_time=I('request.end_time');
	    if(!empty($end_time)){
	        if(empty($where['post_date'])){
	            $where['post_date']=array();
	        }
	        array_push($where['post_date'], array('ELT',$end_time));
	    }
	
	    $keyword=I('request.keyword');
	    if(!empty($keyword)){
	        $where['post_title']=array('like',"%$keyword%");
	    }
	    	
	    $count=$this->posts_model
	    ->alias("a")
	    ->where($where)
	    ->count();
	
	    $page = $this->page($count, 20);
	    
	    $posts=$this->posts_model
	    ->alias("a")
	    ->field('a.*,c.user_login,c.user_nicename')
	    ->join("__USERS__ c ON a.post_author = c.id")
	    ->where($where)
	    ->limit($page->firstRow , $page->listRows)
	    ->order("a.post_date DESC")
	    ->select();
	    
	    $this->assign("page", $page->show('Admin'));
	    $this->assign("formget",array_merge($_GET,$_POST));
	    $this->assign("posts",$posts);
	}
	
	// 页面添加
	public function add(){
         $this->display();
	}
	
	// 页面添加提交
	public function add_post(){
		if (IS_POST) {
			$_POST['smeta']['thumb'] = sp_asset_relative_url($_POST['smeta']['thumb']);
			$_POST['post']['post_date']=date("Y-m-d H:i:s",time());
			$_POST['post']['post_author']=sp_get_current_admin_id();
			$page=I("post.post");
			$page['smeta']=json_encode($_POST['smeta']);
			$page['post_content']=htmlspecialchars_decode($page['post_content']);
			$result=$this->posts_model->add($page);
			if ($result) {
				$this->success("添加成功！");
			} else {
				$this->error("添加失败！");
			}
		}
	}
	
	// 页面编辑
	public function edit(){
		$terms_obj = M("Terms");
		$term_id = I("get.term",0,'intval'); 
		$id= I("get.id",0,'intval');
		$term=$terms_obj->where(array('term_id'=>$term_id))->find();
		$post=$this->posts_model->where(array('id'=>$id))->find();
		
		$this->assign("post",$post);
		$this->assign("smeta",json_decode($post['smeta'],true));
		$this->assign("term",$term);
		$this->display();
	}
	
	// 页面编辑提交
	public function edit_post(){
		$terms_obj = D("Portal/Terms");
	
		if (IS_POST) {
			$_POST['smeta']['thumb'] = sp_asset_relative_url($_POST['smeta']['thumb']);
			
			unset($_POST['post']['post_author']);
			$page=I("post.post");
			$page['smeta']=json_encode($_POST['smeta']);
			$page['post_content']=htmlspecialchars_decode($page['post_content']);
			$result=$this->posts_model->save($page);
			if ($result !== false) {
				$this->success("保存成功！");
			} else {
				$this->error("保存失败！");
			}
		}
	}
	
	// 删除页面
	public function delete(){
		if(isset($_POST['ids'])){
			$ids = array_map("intval", $_POST['ids']);
			$data=array("post_status"=>3);
			if ($this->posts_model->where(array("id"=>array("in"=>$ids)))->save($data)) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}else{
			if(isset($_GET['id'])){
				$id = I("get.id",0,'intval');
				$data=array("id"=>$id,"post_status"=>3);
				if ($this->posts_model->save($data)) {
					$this->success("删除成功！");
				} else {
					$this->error("删除失败！");
				}
			}
		}
	}
	
	// 还原页面
	public function restore(){
		if(isset($_GET['id'])){
			$id = I("get.id",0,'intval');
			$data=array("id"=>$id,"post_status"=>"1");
			if ($this->posts_model->save($data)) {
				$this->success("还原成功！");
			} else {
				$this->error("还原失败！");
			}
		}
	}
	
	// 清除已删除的页面
	public function clean(){
		if(isset($_POST['ids'])){
			$ids = array_map("intval", $_POST['ids']);
			if ($this->posts_model->where(array("id"=>array("in"=>$ids)))->delete()!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
		
		if(isset($_GET['id'])){
			$id = I("get.id",0,'intval');
			if ($this->posts_model->delete($id)!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
	}
	
	
	
}