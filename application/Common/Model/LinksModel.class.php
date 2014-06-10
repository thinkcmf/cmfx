<?php
namespace Common\Model;
use Common\Model\CommonModel;
class LinksModel extends CommonModel
{
	
	//自动验证
	protected $_validate = array(
			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
			array('link_name', 'require', '链接名称不能为空！', 1, 'regex', 3),
			array('link_url', 'require', '链接地址不能为空！', 1, 'regex', 3),
	);
	
	/**
	 *  添加连接
	 * @param unknown_type $url
	 * @param unknown_type $name
	 * @param unknown_type $image
	 * @param unknown_type $target
	 * @param unknown_type $desctiption
	 * @param unknown_type $visible
	 * @return Ambigous <mixed, boolean, unknown, string>
	 */
	function addLink($url, $name, $image, $target, $desctiption, $visible='Y')
	{
		
		$date['link_url'] = $url;
		$date['link_name'] = $name;
		$date['link_image'] = $image;
		$date['link_target'] = $target;
		$date['link_description'] = $desctiption;
		$date['link_visible'] = $visible;
		
	    $result =  $this->add($date);
		return   $result;
	}
	
	
	function updateLinkByArray($array){
		
	}
	
	/**
	 *  更新连接
	 * @param unknown_type $link_id
	 * @param unknown_type $url
	 * @param unknown_type $name
	 * @param unknown_type $image
	 * @param unknown_type $target
	 * @param unknown_type $desctiption
	 * @param unknown_type $visible
	 */
	
	public function updateLink($link_id, $url, $name, $image, $target, $desctiption, $visible='Y')
	{
		$date['link_url'] = $url;
		$date['link_name'] = $name;
		$date['link_image'] = $image;
		$date['link_target'] = $target;
		$date['link_description'] = $desctiption;
		$date['link_visible'] = $visible;
		
		$result = $this->where('link_id='.$link_id)->save($date);
		
		return $result;
	}
	
	/*
	 * 分页获取连接
	 */
	
	public function getLinksByPage($offset, $pageNum)
	{
		$result = $this->where("link_visible = 'Y'")->order('link_id desc')
		->limit($offset.','.$pageNum)->select();
		return $result;
	}
	
	//获得连接数
	public function getLinkCount()
	{
		$result = $this->where("link_visible = 'Y'")->count();
		return $result;
	}
	
	
	//根据id删除连接
	public function deletLink($id)
	{
		return $this->where('link_id='.$id)->setField('link_visible','N');
	}
	
	//获得一条连接
	public function getLinkById($id)
	{
		$result = $this->where('link_id='.$id)->select();
		return $result[0];
	}
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}
	
}




?>