<?php

/**
 * 会员注册登录
 */
namespace Member\Action;
use Common\Action\AdminbaseAction;
class IndexadminAction extends AdminbaseAction {
    function index(){
    	$count=M("Members")->where("user_status=1")->count();
    	$page = $this->page($count, 20);
    	$lists = M("Members")
    	->where("user_status=1")
    	->limit($page->firstRow . ',' . $page->listRows)
    	->select();
    	$this->assign('lists', $lists);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display(":index");
    }
    
    function delete(){
    	$id=intval($_GET['id']);
    	if ($id) {
    		$rst = M("Members")->where("user_status=1 and ID=$id")->setField('user_status','0');
    		if ($rst) {
    			$this->success("保存成功！", U("indexadmin/index"));
    		} else {
    			$this->error('会员删除失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    	
    }
}
?>
