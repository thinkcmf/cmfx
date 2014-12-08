<?php
namespace Qiushi\Action;
use Common\Action\HomeBaseAction;
/**
 * 文章列表
*/
class ListAction extends HomeBaseAction {

	//文章内页
	public function index() {
    	$this->display(":list");
	}
	
	
}
?>
