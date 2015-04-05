<?php
namespace Qiushi\Controller;
use Common\Controller\HomeBaseController;
/**
 * 文章列表
*/
class ListController extends HomeBaseController {

	//文章内页
	public function index() {
    	$this->display(":list");
	}
	
	
}
