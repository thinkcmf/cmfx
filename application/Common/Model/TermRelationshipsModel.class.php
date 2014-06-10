<?php
namespace Common\Model;
use Common\Model\CommonModel;
class TermRelationshipsModel extends CommonModel {
	
	function addRelationship($object_id, $term_id) {
		$has = $this->where ( "object_id='$object_id' and term_id='$term_id'" )->count ();
		if ($has) {
			return false;
		} else {
			$data ['object_id'] = $object_id;
			$data ['term_id'] = $term_id;
			return $this->add ( $data );
		}
	}
	
	function delObject($obj_id) {
	}
	
	function deleteTermByTermID($term_id) {
		$prefix = C ( 'DB_PREFIX' );
		$sql = "UPDATE _prefix_term_relationships  LEFT JOIN _prefix_posts   
		ON _prefix_term_relationships.object_id = _prefix_posts.ID
		SET _prefix_term_relationships.astatus=0, _prefix_posts.post_status='delete'
		where _prefix_term_relationships.term_id=$term_id";
		$sql = str_replace ( "_prefix_", C ( 'DB_PREFIX' ), $sql );
		return $this->execute ( $sql );
	}
	
	function deletePost($term_id, $post_id) {
		$prefix = C ( 'DB_PREFIX' );
		$sql = "UPDATE _prefix_term_relationships  LEFT JOIN _prefix_posts   
		ON _prefix_term_relationships.object_id = _prefix_posts.ID
		SET _prefix_term_relationships.status=0, _prefix_posts.post_status='delete'
		where _prefix_term_relationships.term_id=$term_id and _prefix_term_relationships.object_id in ($post_id)";
		$sql = str_replace ( "_prefix_", C ( 'DB_PREFIX' ), $sql );
		return $this->execute ( $sql );
	}
	
	/**
	 *
	 * @param $offset int       	
	 * @param $term_id int       	
	 * @param $pageNum int       	
	 * @param $status int       	
	 */
	function getPostByTermid($offset, $term_id = null, $pageNum = 10, $status = 1) {
		$sql='SELECT 
		_prefix_term_relationships.term_id,
		_prefix_posts.ID as id,
		_prefix_posts.post_author as author,
		_prefix_posts.post_date as date,
		_prefix_posts.post_content_filtered as describ,
		_prefix_posts.post_content as content,
		_prefix_posts.post_title as title,
		_prefix_posts.post_excerpt as excerpt, 
		_prefix_posts.post_status as status,
		_prefix_posts.comment_status,
		_prefix_posts.post_password as password, 
		_prefix_posts.post_name as name, 
		_prefix_posts.post_modified as modified, 
		_prefix_posts.post_type as type
		FROM _prefix_term_relationships
		LEFT JOIN _prefix_posts ON _prefix_term_relationships.object_id = _prefix_posts.ID';
		
		if ($term_id!=null) {
			$where ="  where _prefix_term_relationships.term_id=$term_id and _prefix_term_relationships.status=$status";
			if ($status == null) {
				$where = " where _prefix_term_relationships.term_id=$term_id";
			}
		}else{
			$where ="  where _prefix_term_relationships.status=$status";
			if ($status == null) {
				$where = " ";
			}
		}
		if ($status == null && $term_id!=null) {
			$where = " where _prefix_term_relationships.term_id=$term_id";
		}
		$limit=" limit $offset,$pageNum";
		$sql =$sql.$where.$limit;
		
		$sql = str_replace ( "_prefix_", C ( 'DB_PREFIX' ), $sql );
		return $this->query($sql);
	}
	
	function getTermidByObject($objectId){
		return $this->where("object_id=".intval($objectId))->getField('term_id');
	}
	
	function getCountByTermid($term_id = null, $status = 1) {
		$where = "";
		if ($term_id!=null) {
			$where = "term_id=$term_id and status=$status";
			if ($status == null) {
				$where = "term_id=$term_id";
			}
		}else {
			$where = "status=$status";
			if ($status == null) {
				$where = "";
			}
		}
		
		return $this->where ( $where )->count ();
	}
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}

}