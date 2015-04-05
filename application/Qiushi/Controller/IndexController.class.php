<?php
namespace Qiushi\Controller;
use Common\Controller\HomeBaseController; 
/**
 * 首页
 */
class IndexController extends HomeBaseController {
	
    //首页
	public function index() {
    	$this->display(":index");
    }   

}

