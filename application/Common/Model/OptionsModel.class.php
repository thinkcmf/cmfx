<?php
namespace Common\Model;
use Common\Model\CommonModel;
class OptionsModel extends CommonModel{
	static $website='website';
	
	function getFormatedOptions(){
		$options_data=$this->field('option_name,option_value')->where("autoload='yes' AND blog_id=0")->select();
		$formated_options=array();
		foreach ($options_data as $values){
			$formated_options[$values['option_name']]=$values['option_value'];
		}
		return $formated_options;
	}
	
	/**
	 * 添加 信息 如果存在 者更新
	 * @param unknown_type $blog_id 
	 * @param unknown_type $name  信息标题
	 * @param unknown_type $value  内容
	 * 
	 * 成功返回影响记录数   失败返回false
	 */
	public function addOption($blog_id, $name, $value)
	{
		$date['blog_id'] = $blog_id;
		$date['option_name'] = $name;
		$tmp = stripslashes($value);
		$date['option_value'] = htmlspecialchars($tmp);
		
		$bool = $this->where('option_name="'. $name .'"')->find();
		 if($bool == NULL)
		{
			$result = $this->add($date);
			return $result;
		}
		else if($bool == FALSE)
		{
			return "未知错误";
		}
		else
		{
			$result = $this->updateOption($bool['option_id'], $blog_id, $name, $value);
			return $result;
		} 	
		return NULL;	
	} 
	
	/**
	 *  更新option 信息
	 * @param unknown_type $option_id  
	 * @param unknown_type $blog_id 
	 * @param unknown_type $name  信息标题
	 * @param unknown_type $value   内容
	 * @param unknown_type $auto  是否自动加载
	 */
	
	private function updateOption($option_id, $blog_id, $name, $value)
	{
		$date['blog_id'] = $blog_id;
		$date['option_name'] = $name;
		$tmp = stripslashes($value);
		$date['option_value'] = htmlspecialchars($tmp);
		$date['autoload'] = 'yes';
		
		$result = $this->where('option_id='.$option_id)->save($date);
		return $result;
	}
	
	public function getOption($option_name)
	{
		$result = $this->where('option_name="'.$option_name.'"')->getField('option_value');
		return $result;
	}
	
	
	
	/**
	 * 修改是否自动加载
	 * @param unknown_type $option_id  数据id
	 * @param unknown_type $value  yes or  no
	 * 返回结果 成功返回影响记录数  否则返回false
	 */
	public function changAuto($option_id, $value)
	{
		$result = $this->where('option_id='.$option_id)->setField('autoload',$value);
		return $result;
		
	}
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}
	
	
}
?>