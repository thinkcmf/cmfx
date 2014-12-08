<?php
namespace Comment\Controller;
use Common\Controller\AdminbaseController;
class CommentadminController extends AdminbaseController{
	
	protected $comments_model;
	
	function _initialize(){
		parent::_initialize();
		$this->comments_model=D("Common/Comments");
	}

	function index($table=""){
		$where=array();
		if(!empty($table)){
			$where['post_table']=$table;
		}
		
		$post_id=I("get.post_id");
		if(!empty($post_id)){
			$where['post_id']=$post_id;
		}
		$count=$this->comments_model->where($where)->count();
		$page = $this->page($count, 20);
		$comments=$this->comments_model
		->where($where)
		->limit($page->firstRow . ',' . $page->listRows)
		->order("createtime DESC")
		->select();
		$this->assign("comments",$comments);
		$this->assign("Page", $page->show('Admin'));
		$this->display(":index");
	}
	
	function delete(){
		if(isset($_GET['id'])){
			$id = intval(I("get.id"));
			if ($this->comments_model->where("id=$id")->delete()!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
		if(isset($_POST['ids'])){
			$ids=join(",",$_POST['ids']);
			if ($this->comments_model->where("id in ($ids)")->delete()!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
	}
	
	function check(){
		if(isset($_POST['ids']) && $_GET["check"]){
			$data["status"]=1;
				
			$ids=join(",",$_POST['ids']);
			
			if ( $this->comments_model->where("id in ($ids)")->save($data)!==false) {
				$this->success("审核成功！");
			} else {
				$this->error("审核失败！");
			}
		}
		if(isset($_POST['ids']) && $_GET["uncheck"]){
				
			$data["status"]=0;
			$ids=join(",",$_POST['ids']);
			if ( $this->comments_model->where("id in ($ids)")->save($data)!==false) {
				$this->success("取消审核成功！");
			} else {
				$this->error("取消审核失败！");
			}
		}
	}
	
}