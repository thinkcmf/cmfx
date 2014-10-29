<?php
namespace Admin\Action;
use Common\Action\AdminbaseAction;
class SlideAction extends AdminbaseAction{
	
	protected $slide_obj;
	protected $slidecat_obj;
	
	function _initialize() {
		parent::_initialize();
		$this->slide_obj = D("Slide");
		$this->slidecat_obj = D("SlideCat");
		
	}
	
	function index(){
		$cates=array(
				array("cid"=>"0","cat_name"=>"默认分类"),
		);
		$categorys=$this->slidecat_obj->field("cid,cat_name")->where("cat_status!=0")->select();
		$categorys=array_merge($cates,$categorys);
		$this->assign("categorys",$categorys);
		$where="slide_status!=0";
		$cid=0;
		if(isset($_POST['cid']) && $_POST['cid']!=""){
			$cid=$_POST['cid'];
			$where="slide_status!=0 and slide_cid=$cid";
		}
		$this->assign("slide_cid",$cid);
		$slides=$this->slide_obj->where($where)->order("listorder ASC")->select();
		$this->assign('slides',$slides);
		$this->display();
	}
	
	function add(){
		$categorys=$this->slidecat_obj->field("cid,cat_name")->where("cat_status!=0")->select();
		$this->assign("categorys",$categorys);
		$this->display();
	}
	
	
	function add_post(){
		if(IS_POST){
			if ($this->slide_obj->create()) {
				$_POST['slide_pic']=sp_asset_relative_url($_POST['slide_pic']);
				if ($this->slide_obj->add($_POST)!==false) {
					$this->success("添加成功！", U("slide/index"));
				} else {
					$this->error("添加失败！");
				}
			} else {
				$this->error($this->slide_obj->getError());
			}
		}
	}
	function edit(){
		$categorys=$this->slidecat_obj->field("cid,cat_name")->where("cat_status!=0")->select();
		$id= intval(I("get.id"));
		$slide=$this->slide_obj->where("slide_id=$id")->find();
		$this->assign($slide);
		$this->assign("categorys",$categorys);
		$this->display();
	}
	
	function edit_post(){
		if(IS_POST){
			if ($this->slide_obj->create()) {
				$_POST['slide_pic']=sp_asset_relative_url($_POST['slide_pic']);
				if ($this->slide_obj->save($_POST)!==false) {
					$this->success("保存成功！", U("slide/index"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->slide_obj->getError());
			}
				
		}
	}
	
	
	function delete(){
		if(isset($_POST['ids'])){
			$ids = implode(",", $_POST['ids']);
			$data['slide_status']=0;
			if ($this->slide_obj->where("slide_id in ($ids)")->delete()!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}else{
			$id = intval(I("get.id"));
			if ($this->slide_obj->delete($id)!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
		
	}
	
	//排序
	public function listorders() {
		$status = parent::_listorders($this->slide_obj);
		if ($status) {
			$this->success("排序更新成功！");
		} else {
			$this->error("排序更新失败！");
		}
	}
	
}