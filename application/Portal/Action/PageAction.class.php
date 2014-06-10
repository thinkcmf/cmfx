<?php
namespace Portal\Action;
use Common\Action\HomeBaseAction;
class PageAction extends HomeBaseAction{
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
		$datas=sp_sql_pages("field:ID,post_title;");
		$navrule=array(
				"action"=>"Page/index",
				"param"=>array(
						"id"=>"ID"
				),
				"label"=>"post_title");
		exit( sp_get_nav4admin($navcatname,$datas,$navrule) );
	}
}