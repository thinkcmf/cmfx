<?php
namespace Qiushi\Model;
use Common\Model\CommonModel;
class QiushiModel extends CommonModel
{
	
	protected $_validate = array(
			
	);
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
		
	}
	
}

?>