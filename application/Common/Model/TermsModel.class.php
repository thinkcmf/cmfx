<?php
namespace Common\Model;
use Common\Model\CommonModel;
class TermsModel extends CommonModel {
	
	/*
	 * term_id category name description pid path status
	 */
	
	//自动验证
	protected $_validate = array(
			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
			array('name', 'require', '分类名称不能为空！', 1, 'regex', 3),
	);
	
	/**
	 * 添加分类
	 *
	 * @param $name string       	
	 * @param $parent int       	
	 * @param $category string       	
	 * @param $description string       	
	 */
	public function addTerm($name, $pid = 0, $category = 'category', $description = '') {
		$tmp = $this->where ( "name='$name' and pid='$pid'" )->find ();
		if ($tmp == NULL) {
			$date ['name'] = $name;
			$date ['pid'] = $pid;
			$date ['category'] = $category;
			$date ['description'] = $description;
			$result = $this->add ( $date );
			if ($result) {
				$id = $this->where ( "name='$name' and pid=$pid" )->find ();
				if ($pid != 0) {
					$parent = $this->where ( "term_id='$pid'" )->find ();
					$path = $parent ['path'] . '-' . $id ['pid'];
				} else {
					$path = '0';
				}
				
				$result = $this->updateTerm ( $id ['term_id'], $name, $path, $pid, $category, $description );
			}
			return $result;
		} else {
			$result = $this->updateTerm ( $tmp ['term_id'], $name, '', $pid, $category, $description );
			return $result;
		
		}
	}
	
	/**
	 * 更新分类
	 *
	 * @param $id int       	
	 * @param $name string       	
	 * @param $parent int       	
	 * @param $category string       	
	 * @param $description string       	
	 */
	public function updateTerm($id, $name, $path, $pid = 0, $category = 'category', $description = '') {
		$date ['name'] = $name;
		
		if ($path != '') {
			$date ['path'] = $path;
		}
		if ($pid != 0) {
			$date ['pid'] = $pid;
		}
		if ($category != "category") {
			$date ['category'] = $category;
		}
		if ($description != '') {
			$date ['description'] = $description;
		}
		$result = $this->where ( 'term_id=' . $id )->save ( $date );
		return $result;
	}
	
	/**
	 * 使用数据组格式更新分类
	 * 
	 * @param $id int       	
	 * @param $term_data_array array
	 *       	 数组格式
	 *       	 array(
	 *       	 'category'=>'',
	 *       	 'name'=>'',
	 *       	 'description'=>'',
	 *       	 'pid'=>0,
	 *       	 'path'=>''
	 *       	 'status'=>0);
	 */
	public function updateTermArray($id, $term_data_array) {
		return $this->where ( "term_id=$id" )->save ( $term_data_array );
	}
	/**
	 * 获得所有分类
	 *
	 * @param $category string
	 *       	 array(
	 *       	 'id'=>0,
	 *       	 'cat'=>'category',
	 *       	 'name'=>'this is a cat',
	 *       	 'pid'=>0,
	 *       	 'path'=>
	 *       	 );
	 */
	public function getTerm($category = null, $pid = null, $status = 1) {
		$wheres ['category'] = $category;
		$wheres ['pid'] = $pid;
		$wheres ['status'] = $status;
		$i = 0;
		$length = count ( $wheres );
		$where = '';
		foreach ( $wheres as $key => $value ) {
			$i ++;
			if (isset($value)) {
				if ($length == $i) {
					$where = $where . "$key='$value'";
				} else {
					$where = $where . "$key='$value' and ";
				}
			}
		}
		$data = $this->field ( "term_id as id,category as cat,name,pid,path,concat(path,'-',term_id) as bpath,description as des" )->where ( $where )->order ( "bpath asc" )->select ();
		foreach ( $data as $key => $val ) {
			$data [$key] ['count'] = substr_count ( $val ['path'], '-' );
		}
		return $data;
	
	}
	
	/**
	 *
	 * @param $id int       	
	 */
	public function getTermByID($id) {
		return $this->where ( "term_id=$id" )->find ();
	}
	
	public function updateStatus($term_id, $status) {
		$prefix = C ( 'DB_PREFIX' );
		$sql = "UPDATE _prefix_terms  
		LEFT JOIN _prefix_term_relationships USING(term_id)
		LEFT JOIN _prefix_posts ON _prefix_term_relationships.object_id = _prefix_posts.ID
		SET  _prefix_terms.status=0,_prefix_term_relationships.status=0, _prefix_posts.post_status='delete'
		where _prefix_terms.term_id=$term_id";
		$sql = str_replace ( "_prefix_", C ( 'DB_PREFIX' ), $sql );
		return $this->execute ( $sql );
	}
	
	function getPostsByCategory($category,$offset,$pageNum,$status=1){
		$prefix = C ( 'DB_PREFIX' );
		$sql ="select t.term_id,
		p.ID as id,
		p.post_author as author,
		p.post_date as date,
		p.post_content_filtered as describ,
		p.post_content as content,
		p.post_title as title,
		p.post_excerpt as excerpt, 
		p.post_status as status,
		p.comment_status,
		p.post_password as password, 
		p.post_name as name, 
		p.post_modified as modified, 
		p.post_type as type from _prefix_terms t right join _prefix_term_relationships tr  using(term_id) left join _prefix_posts p on tr.object_id =p.ID  
		WHERE t.category='$category' and tr.status=$status limit $offset,$pageNum ";
		$sql = str_replace ( "_prefix_", $prefix, $sql );
		return $this->query( $sql );
	}
	
	function getPostsCountByCategory($category,$status=1){
		return $this->join(C( 'DB_PREFIX' )."term_relationships using(term_id)")->where( C ( 'DB_PREFIX' )."term_relationships.status=$status and category='$category'  ")->count();
	}
	
	
	protected function _after_insert($data,$options){
		parent::_after_insert($data,$options);
		$term_id=$data['term_id'];
		$parent_id=$data['parent'];
		if($parent_id==0){
			$d['path']="0-$term_id";
		}else{
			$parent=$this->where("term_id=$parent_id")->find();
			$d['path']=$parent['path'].'-'.$term_id;
		}
		$this->where("term_id=$term_id")->save($d);
	}
	
	
	protected function _after_update($data,$options){
		parent::_after_update($data,$options);
		$term_id=$data['term_id'];
		$parent_id=$data['parent'];
		if($parent_id==0){
			$d['path']="0-$term_id";
		}else{
			$parent=$this->where("term_id=$parent_id")->find();
			$d['path']=$parent['path'].'-'.$term_id;
		}
		$this->where("term_id=$term_id")->save($d);
	}
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}
	

}