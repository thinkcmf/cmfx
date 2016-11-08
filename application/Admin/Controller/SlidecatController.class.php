<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;

class SlidecatController extends AdminbaseController{
	
	protected $slidecat_model;
	
	public function _initialize() {
		parent::_initialize();
		$this->slidecat_model = D("Common/SlideCat");
	}
	
	// 幻灯片分类列表
	public function index(){
		$cats=$this->slidecat_model->where("cat_status!=0")->select();
		$this->assign("slidecats",$cats);
		$this->display();
	}
	
	// 幻灯片分类添加
    public function add() {
        $this->display();
    }
	
    // 幻灯片分类添加提交
    public function add_post() {
    	if (IS_POST) {
    		if ($this->slidecat_model->create()!==false) {
    			if ($this->slidecat_model->add()!==false) {
    				$this->success("添加成功！", U("slidecat/index"));
    			} else {
    				$this->error("添加失败！");
    			}
    		} else {
    			$this->error($this->slidecat_model->getError());
    		}
    	}
    }
    
    // 幻灯片分类编辑
	public function edit(){
		$id= I("get.id",0,'intval');
		$slidecat=$this->slidecat_model->where(array('cid'=>$id))->find();
		$this->assign($slidecat);
		$this->display();
	}
	
	// 幻灯片分类编辑提交
	public function edit_post(){
		if (IS_POST) {
			if ($this->slidecat_model->create()!==false) {
				if ($this->slidecat_model->save()!==false) {
					$this->success("保存成功！", U("slidecat/index"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->slidecat_model->getError());
			}
		}
	}
	
	// 幻灯片分类删除
	public function delete(){

		$id = I("get.id",0,'intval');
		if ($this->slidecat_model->delete($id)!==false) {
			$slide_obj=M("Slide");
			$slide_obj->where(array('slide_cid'=>$id))->save(array("slide_cid"=>0));
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
		
	}
	
	
}