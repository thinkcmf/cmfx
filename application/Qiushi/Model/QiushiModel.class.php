<?php
namespace Qiushi\Model;
use Common\Model\CommonModel;
class QiushiModel extends CommonModel
{
	
	protected $_validate = array(
			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
			//array('title', 'require', '标题不能为空！', 1, 'regex', 3),
			array('cid', 'checkCid', '分类不存在！', 1, 'callback', 1),
			array('content', 'require', '内容不能为空！', 1, 'regex', 3),
			
			
	);
	
	protected $_auto = array (          
			 array('createtime','time',1,'function'), // 对createtime字段在新增的时候写入当前时间戳     
	);
	
	public function checkCid($cid){
		$find_cat=M("QiushiCat")->where(array("id"=>$cid,"status"=>1))->find();
		if($find_cat){
			return true;
		}else{
			return false;
		}
	}
	
	
}

