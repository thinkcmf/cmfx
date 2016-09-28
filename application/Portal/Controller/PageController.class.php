<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace Portal\Controller;

use Common\Controller\HomebaseController;

class PageController extends HomebaseController{
    
	public function index() {
		$id=I('get.id',0,'intval');
		$content=sp_sql_page($id);
		
		if(empty($content)){
		    header('HTTP/1.1 404 Not Found');
		    header('Status:404 Not Found');
		    if(sp_template_file_exists(MODULE_NAME."/404")){
		        $this->display(":404");
		    }
		    return ;
		}
		
		$this->assign($content);
		$smeta=json_decode($content['smeta'],true);
		$tplname=empty($smeta['template'])?"":$smeta['template'];
		
		$tplname=sp_get_apphome_tpl($tplname, "page");
		
		$this->display(":$tplname");
	}
	
	public function nav_index(){
		$navcatname="页面";
		
		$where=array();
		$where['post_status'] = array('eq',1);
		$where['post_type'] = array('eq',2);
		
		$posts_model= M("Posts");
		
		$datas=$posts_model->where($where)->select();
		$navrule=array(
		        'id'=>'id',
				"action"=>"Portal/Page/index",
				"param"=>array(
						"id"=>"id"
				),
				"label"=>"post_title",
		        'parentid'=>0
		);
		return sp_get_nav4admin($navcatname,$datas,$navrule);
	}
}