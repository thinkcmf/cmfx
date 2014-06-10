<?php
namespace Common\Model;
use Common\Model\CommonModel;
class UsersModel extends CommonModel
{
	
	protected $_validate = array(
			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
			array('user_login', 'require', '用户名称不能为空！', 1, 'regex', CommonModel:: MODEL_INSERT  ),
			array('user_pass', 'require', '密码不能为空！', 1, 'regex', CommonModel:: MODEL_INSERT ),
			array('user_login', 'require', '用户名称不能为空！', 0, 'regex', CommonModel:: MODEL_UPDATE  ),
			array('user_pass', 'require', '密码不能为空！', 0, 'regex', CommonModel:: MODEL_UPDATE  ),
			array('user_login','','用户名已经存在！',0,'unique',CommonModel:: MODEL_BOTH ), // 验证user_login字段是否唯一
			array('user_email','','邮箱帐号已经存在！',0,'unique',CommonModel:: MODEL_BOTH ), // 验证user_email字段是否唯一
			array('user_email','email','邮箱格式不正确！',0,'',CommonModel:: MODEL_BOTH ), // 验证user_email字段格式是否正确
	);
	
	
	
	/**
	 * 添加用户
	 * @param string $user_login 登入名
	 * @param string $user_pass  密码
	 * @param string $user_nicename  昵称   默认为字符“null”
	 * @param string $user_email  邮箱    默认为"null"
	 * @return int   sqlIndex   返回添加成功后的索引 否则返回false
	 */
	
	public function addUser($user_login, $user_pass, $user_nicename="null", $user_email="null", $user_statue=2)
	{
		$date['user_login'] = $user_login;
		$date['user_pass'] = sp_password($user_pass);
		$date['user_nicename'] = $user_nicename;
		$date['user_email'] = $user_email;
		$date['user_status']=$user_statue;
		$date['user_registered'] = $this->mGetDate();
		$result = $this->add($date);
		return $result;
	}
	
	/**
	 * 通过id获取用户 
	 * 返回用户数据数组
	 * @param int $userId  用户id
	 */
	
	public function getUserByID($userId)
	{
		
		$user = $this->where("ID = ".$userId)->find();
		return $user;
	}
	
	function  getUserAndItsMetaByID($user_id){
		$user = $this->where("ID = ".$user_id)->find();
		$usermeta_obj=new UsermetaModel();
		$usermetas=$usermeta_obj->getUserMetas($user_id);
		return array_merge($user,$usermetas);
	}
	
	/**
	 * 通过名字获取用户
	 */
	public function getUserByName($name)
	{
		$user = $this->where("user_login = '$name'")->find();
		return $user;
	}
	
	
	/**
	 * 更新用户信息
	 * @param int $ID  用户id
	 * @param string $user_login 用户名
	 * @param string $user_pass  密码
	 * @param unknown_type $user_nicename  
	 * @param unknown_type $user_email
	 */
	public function updateUser($ID, $user_login, $user_pass, $user_nicename, $user_email, $statu=2)
	{
		$date['user_login'] = $user_login;
		$date['user_pass'] = $user_pass;
		$date['user_nicename'] = $user_nicename;
		$date['user_email'] = $user_email;
		$date['user_status'] = $statu;
		$result = $this->where("ID=".$ID)->save($date);
		return $result;
	}
	
	public function updateUserMail($ID, $user_email)
	{
		$result = $this->where("ID=".$ID)->setField("user_email", $user_email);
		return $result;
	}
	
	
	/**
	 * 分页获得所有用户
	 */
	public function getUsersByPage($offset, $pageNum)
	{
		
		$result = $this->order('ID desc')
		->limit($offset.','.$pageNum)->select();
		return $result;
		
	}
	
	/**
	 * 获得所有用户
	 */
	public function getCount()
	{
		return $this->count();
	}
	
	
	/**
	 * 管理员使用方法，管理用户权限
	 * @param unknown_type $userID
	 * @param unknown_type $statue
	 */
	
	public function changUserStatue($userID, $statue)
	{
		$result = $this->where('ID='.$userID)->setField("user_status", $statue);
		return $result;
		
	}
	
	
	
	
	//用于获取时间，格式为2012-02-03 12:12:12,注意,方法不能为private
	function mGetDate() {
		return date('Y-m-d H:i:s');
	}
	
	function deleteUsers($ids){
		$result = $this->where("ID in ($ids)")->delete();
		return $result;
	}
	
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
		
		if(!empty($data['user_pass']) && strlen($data['user_pass'])<25){
			$data['user_pass']=sp_password($data['user_pass']);
		}
	}
	
}

?>