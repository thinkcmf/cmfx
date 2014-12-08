<?php
/**
 * Navcat(菜单分类管理)
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class NavcatController extends AdminbaseController {
	
	protected $navcat;
	
	function _initialize() {
		parent::_initialize();
		$this->navcat =D("Common/NavCat");
	}
	
	
	/**
	 *  显示
	 */
	public function index() {
		$cats=$this->navcat->select();
		$this->assign("navcats",$cats);
		$this->display();
	}
	
	/**
	 *  添加
	 */
	public function add() {
		$this->display();
	}
	
	/**
	 *  添加
	 */
	public function add_post() {
		if (IS_POST) {
			if(empty($_POST['active'])){
				$_POST['active']=0;
			}else{
				$this->navcat->where("active=1")->save(array("active"=>0));
			}
			if ($this->navcat->create()) {
				if ($this->navcat->add($_POST)) {
					$this->success("添加成功！", U("navcat/index"));
				} else {
					$this->error("添加失败！");
				}
			} else {
				$this->error($this->navcat->getError());
			}
		}
	}
	
	/**
	 * 编辑
	 */
	function edit(){
		$id= intval(I("get.id"));
		$navcat=$this->navcat->where("navcid=$id")->find();
		$this->assign($navcat);
		$this->display();
	}
	
	/**
	 * 编辑
	 */
	function edit_post(){
		if (IS_POST) {
			if(empty($_POST['active'])){
				$_POST['active']=0;
			}else{
				$this->navcat->where("active=1")->save(array("active"=>0));
			}
			if ($this->navcat->create()) {
				if ($this->navcat->save($_POST) !== false) {
					$this->success("保存成功！", U("navcat/index"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->navcat->getError());
			}
		}
	}
	
	
	function delete(){
		$id = intval(I("get.id"));
		if ($this->navcat->where("navcid=$id")->delete()!==false) {
			$nav_obj=D("Common/Nav");
			$nav_obj->where("cid=$id")->delete();
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}
	
	
}