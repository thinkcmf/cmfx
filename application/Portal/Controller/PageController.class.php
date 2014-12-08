<?php
namespace Portal\Controller;
use Common\Controller\HomeBaseController;
class PageController extends HomeBaseController{
	public function index() {
		$id=$_GET['id'];
		$content=sp_sql_page($id);
		$this->assign($content);
		$smeta=json_decode($content['smeta'],true);
		$tplname=isset($smeta['template'])?$smeta['template']:"";
		
		$tplname=sp_get_apphome_tpl($tplname, "page");
		
		$this->display(":$tplname");
	}
	
	public function nav_index(){
		$navcatname="页面";
		$datas=sp_sql_pages("field:id,post_title;");
		$navrule=array(
				"action"=>"Page/index",
				"param"=>array(
						"id"=>"id"
				),
				"label"=>"post_title");
		exit( sp_get_nav4admin($navcatname,$datas,$navrule) );
	}
}