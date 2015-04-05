<?php
namespace Common\Model;
use Common\Model\CommonModel;
class UsermetaModel extends CommonModel
{
	/**
	 * 添加用户附加信息, 如果有了则更新
	 * @param unknown_type $user_id
	 * @param unknown_type $key
	 * @param unknown_type $value
	 */
	public function addMeta($user_id, $key, $value)
	{
		$date['user_id'] = $user_id;
		$date['meta_key'] = $key;
		$date['meta_value'] = $value;
		$bool =  $this->where('user_id='.$user_id.' AND meta_key="'.$key.'"')->find();
		if($bool == NULL){
			$result = $this->add($date);
		}else{
			$result = $this->where('user_id='.$user_id.' AND meta_key="'.$key.'"')->setField("meta_value", $value);
		}
		return $result;
	}
	
	
	/**
	 * 获得用户附加信息
	 * @param unknown_type $user_id
	 * @param unknown_type $key
	 */
	public function getMeta($user_id, $key){
		$result = $this->where('user_id='.$user_id.' AND meta_key="'.$key.'"')->getField('meta_value');
		return $result;
	}
	
	//添加登入时间
	public function addTime($userId, $time)
	{
		$result = $this->where('user_id='.$userId.' AND meta_key="current_time"')->getField('meta_value');
		if($result == NULL){
			$this->addMeta($userId, "current_time", $time);
			$this->addMeta($userId, "last_login", $time);
			return;
		}
		$this->where('user_id='.$userId.' AND meta_key="last_login"')->setField('meta_value', $result);
		$this->where('user_id='.$userId.' AND meta_key="current_time"')->setField('meta_value', $time);
	}
	
	public function addIP($userId, $ip){
		$result = $this->where('user_id='.$userId.' AND meta_key="current_ip"')->getField('meta_value');
		if($result == NULL){
			$this->addMeta($userId, "current_ip", $ip);
			$this->addMeta($userId, "last_ip", $ip);
			return;
		}
		$this->where('user_id='.$userId.' AND meta_key="last_ip"')->setField('meta_value', $result);
		$this->where('user_id='.$userId.' AND meta_key="current_ip"')->setField('meta_value', $ip);
	}
	
	function getUserMetas($user_id){
		$result=$this->field('meta_key,meta_value')->where("user_id=$user_id")->select();
		$usermetas=array();
		foreach ($result as $val){
			$usermetas[$val['meta_key']]=$val['meta_value'];
		}
		return $usermetas;
	}
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}
	
}
