<?php
namespace Api\Controller;
use Common\Controller\AdminbaseController;
class LocationadminController extends AdminbaseController {


	function _initialize() {
	}
	
	function province(){
		$location_province_model=M("LocationProvince");
		$provinces=$location_province_model->select();
		$this->assign("provinces",$provinces);
		$this->display();
	}
	
	function province_show(){
		$location_province_model=M("LocationProvince");
		if(isset($_POST['ids']) && $_GET["show"]){
			$data["status"]=1;
	
			$ids=join(",",$_POST['ids']);
				
			if ( $location_province_model->where("province_id in ($ids)")->save($data)!==false) {
				$this->success("操作成功！");
			} else {
				$this->error("操作失败！");
			}
		}
		if(isset($_POST['ids']) && $_GET["unshow"]){
	
			$data["status"]=0;
			$ids=join(",",$_POST['ids']);
			if ( $location_province_model->where("province_id in ($ids)")->save($data)!==false) {
				$this->success("操作成功！");
			} else {
				$this->error("操作失败！");
			}
		}
	}
	
	
	function city(){
		$location_province_model=M("LocationProvince");
		
		$citys=$location_province_model->alias("a")->join(C ( 'DB_PREFIX' )."location_city b ON a.province_id = b.province_id")->select();
		$this->assign("citys",$citys);
		$this->display();
	}
	
	function city_show(){
		$location_city_model=M("LocationCity");
		if(isset($_POST['ids']) && $_GET["show"]){
			$data["status"]=1;
	
			$ids=join(",",$_POST['ids']);
	
			if ( $location_city_model->where("city_id in ($ids)")->save($data)!==false) {
				$this->success("操作成功！");
			} else {
				$this->error("操作失败！");
			}
		}
		if(isset($_POST['ids']) && $_GET["unshow"]){
	
			$data["status"]=0;
			$ids=join(",",$_POST['ids']);
			if ( $location_city_model->where("city_id in ($ids)")->save($data)!==false) {
				$this->success("操作成功！");
			} else {
				$this->error("操作失败！");
			}
		}
	}
	
	function district(){
		$location_province_model=M("LocationProvince");
		$districts=$location_province_model->alias("a")
		->join(C ( 'DB_PREFIX' )."location_city b ON a.province_id = b.province_id")
		->join(C ( 'DB_PREFIX' )."location_district c ON b.city_id = c.city_id")
		->select();
		$this->assign("districts",$districts);
		$this->display();
	}
	
	function district_show(){
		$location_district_model=M("LocationDistrict");
		if(isset($_POST['ids']) && $_GET["show"]){
			$data["status"]=1;
	
			$ids=join(",",$_POST['ids']);
	
			if ( $location_district_model->where("district_id in ($ids)")->save($data)!==false) {
				$this->success("操作成功！");
			} else {
				$this->error("操作失败！");
			}
		}
		if(isset($_POST['ids']) && $_GET["unshow"]){
	
			$data["status"]=0;
			$ids=join(",",$_POST['ids']);
			if ( $location_district_model->where("district_id in ($ids)")->save($data)!==false) {
				$this->success("操作成功！");
			} else {
				$this->error("操作失败！");
			}
		}
	}
	
	
	
}