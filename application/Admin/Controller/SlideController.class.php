<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class SlideController extends AdminbaseController{
	
	protected $slide_model;
	protected $slidecat_model;
	
	public function _initialize() {
		parent::_initialize();
		$this->slide_model = D("Common/Slide");
		$this->slidecat_model = D("Common/SlideCat");
	}
	
	// 幻灯片列表
	public function index(){
		$cates=array(
			array("cid"=>"0","cat_name"=>"默认分类"),
		);
		$categorys=$this->slidecat_model->field("cid,cat_name")->where("cat_status!=0")->select();
		if($categorys){
			$categorys=array_merge($cates,$categorys);
		}else{
			$categorys=$cates;
		}
		$this->assign("categorys",$categorys);
		$where=array();
		$cid = I('post.cid',0,'intval');
		if(!empty($cid)){
			$where=array('slide_cid'=>$cid);
		}
		$this->assign("slide_cid",$cid);
		$slides=$this->slide_model->where($where)->order("listorder ASC")->select();
		$this->assign('slides',$slides);
		$this->display();
	}
	
	// 幻灯片添加
	public function add(){
		$categorys=$this->slidecat_model->field("cid,cat_name")->where("cat_status!=0")->select();
		$this->assign("categorys",$categorys);
		$this->display();
	}
	
	// 幻灯片添加提交
	public function add_post(){
		if(IS_POST){
			if ($this->slide_model->create()!==false) {
				if ($this->slide_model->add()!==false) {
					$this->success("添加成功！", U("slide/index"));
				} else {
					$this->error("添加失败！");
				}
			} else {
				$this->error($this->slide_model->getError());
			}
		}
	}
	
	// 幻灯片编辑
	public function edit(){
		$categorys=$this->slidecat_model->field("cid,cat_name")->where("cat_status!=0")->select();
		$id = I("get.id",0,'intval');
		$slide=$this->slide_model->where(array('slide_id'=>$id))->find();
		$this->assign($slide);
		$this->assign("categorys",$categorys);
		$this->display();
	}
	
	// 幻灯片编辑提交
	public function edit_post(){
		if(IS_POST){
			if ($this->slide_model->create()!==false) {
				if ($this->slide_model->save()!==false) {
					$this->success("保存成功！", U("slide/index"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->slide_model->getError());
			}
				
		}
	}
	
	// 幻灯片删除
	public function delete(){
		if(isset($_POST['ids'])){
			$ids = implode(",", $_POST['ids']);
			$data['slide_status']=0;
			if ($this->slide_model->where("slide_id in ($ids)")->delete()!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}else{
			$id = I("get.id",0,'intval');
			if ($this->slide_model->delete($id)!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
		
	}
	
	// 幻灯片显示/隐藏
	public function toggle(){
		if(isset($_POST['ids']) && $_GET["display"]){
			$ids = I('post.ids/a');
			if ($this->slide_model->where(array('slide_id'=>array('in',$ids)))->save(array('slide_status'=>1))!==false) {
				$this->success("显示成功！");
			} else {
				$this->error("显示失败！");
			}
		}
		if(isset($_POST['ids']) && $_GET["hide"]){
			$ids = I('post.ids/a');
			if ($this->slide_model->where(array('slide_id'=>array('in',$ids)))->save(array('slide_status'=>0))!==false) {
				$this->success("隐藏成功！");
			} else {
				$this->error("隐藏失败！");
			}
		}
	}
	
	// 幻灯片隐藏
	public function ban(){
    	$id = I('get.id',0,'intval');
    	if ($id) {
    		$rst = $this->slide_model->where(array('slide_id'=>$id))->save(array('slide_status'=>0));
    		if ($rst) {
    			$this->success("幻灯片隐藏成功！");
    		} else {
    			$this->error('幻灯片隐藏失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
    
    // 幻灯片启用
    public function cancelban(){
    	$id = I('get.id',0,'intval');
    	if ($id) {
    		$result = $this->slide_model->where(array('slide_id'=>$id))->save(array('slide_status'=>1));
    		if ($result) {
    			$this->success("幻灯片启用成功！");
    		} else {
    			$this->error('幻灯片启用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
    
	// 幻灯片排序
	public function listorders() {
		$status = parent::_listorders($this->slide_model);
		if ($status) {
			$this->success("排序更新成功！");
		} else {
			$this->error("排序更新失败！");
		}
	}
	
}