<?php
namespace Portal\Model;

use Common\Model\CommonModel;

class PostsModel extends CommonModel {
    
    //自动验证
    protected $_validate = array(
        //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
        array('post_title', 'require', '标题不能为空！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('birthday', 'require', '生日不能为空！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );
    
	protected $_auto = array (
		array ('post_date', 'mGetDate', self::MODEL_INSERT, 'callback' ),
		array ('post_modified', 'mGetDate',self::MODEL_BOTH, 'callback' ) 
	);
	
	// 获取当前时间
	public function mGetDate() {
		return date ( 'Y-m-d H:i:s' );
	}
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}
}