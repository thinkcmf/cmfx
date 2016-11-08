<?php
namespace Common\Model;

use Common\Model\CommonModel;

class RouteModel extends CommonModel{
    
	//自动验证
	protected $_validate = array(
	   //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
	   array('full_url', 'require', '原始网址不能为空！', 1, 'regex', 3),
	   array('url', 'require', '显示网址不能为空！', 1, 'regex', 3),
	);
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}
}