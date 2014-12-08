<?php
namespace Api\Controller;
use Common\Controller\HomeBaseController;
class LocationController extends HomeBaseController{


	function _initialize() {
	}
	
	function index(){
		
	}
	
	
	
	
	function city(){
		$location_city_model=M("LocationCity");
		$province_id=I("post.province_id",0,"intval");
		
		$citys=$location_city_model->where(array("status"=>1,"province_id"=>$province_id))->order("convert(city using gb2312) ASC")->select();
		exit(json_encode($citys));
	}
	
	
	function district(){
		$location_district_model=M("LocationDistrict");
		$city_id=I("post.city_id",0,"intval");	
		
		$districts=$location_district_model->where(array("status"=>1,"city_id"=>$city_id))->order("convert(district using gb2312) ASC")->select();
		exit(json_encode($districts));
	}
	
	
	
	
}