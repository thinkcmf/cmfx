<?php
/**
* 根据id获取糗事
* @param int $id
* @return array
**/
function sp_sql_qiushi($id){
    //TODO 返回糗事详细信息，包括发表者信息
    
	return array();
}

/**
 * 获取糗事列表
 * @param string $tag
 * @param array $where
 * @return array
 */
function sp_sql_qiushis($tag,$where=array()){
	
	//TODO 返回糗事列表，包括发表者信息
	
	$where = array();
	//根据参数生成查询条件
	$where['id'] = !empty($ids)?$ids:'';
	$where['uid'] = !empty($uid)?$uid:'';
	
	$qiushi_result = M("Qiushi")->where(" id in (".$where['id'].") and uid =".$where['uid'])->select();
	return 	$qiushi_result;
}


function sp_sql_qiushis_paged($tag="",$pagesize=20,$pagetpl='{first}{prev}{liststart}{list}{listend}{next}{last}'){
	
	$tag=sp_param_lable($tag);
	$field = !empty($tag['field']) ? $tag['field'] : 'b.*';
	$limit = !empty($tag['limit']) ? $tag['limit'] : '';
	$order = !empty($tag['order']) ? $tag['order'] : 'createtime desc';
	
	$field="a.cat_name,c.user_login,c.user_nicename,".$field;
	
	$qiushi_cat_model=M("QiushiCat");
	
	$join = C('DB_PREFIX').'qiushi as b on a.id =b.cid';
	$join2 = C('DB_PREFIX').'users as c on c.id =b.uid';
	
	$where=array("b.status"=>1,"a.status"=>1);
	
	if (isset($tag['cid'])) {
		$where['cid'] = array('in',$tag['cid']);
	}
	
	$totalsize=$qiushi_cat_model->alias("a")->join($join)->where($where)->count();
	
	import('Page');
	$PageParam = C("VAR_PAGE");
	$page = new \Page($totalsize,$pagesize);
	$page->setLinkWraper("li");
	$page->__set("PageParam", $PageParam);
	$pagetpl='{first}{prev}{liststart}{list}{listend}{next}{last}';
	$page->SetPager('default', $pagetpl, array("listlong" => "6", "first" => "首页", "last" => "尾页", "prev" => "上一页", "next" => "下一页", "list" => "*", "disabledclass" => ""));
	
	
	$qiushis=$qiushi_cat_model->field($field)->alias("a")->join($join)->join($join2)
	->where($where)
	->order($order)
	->limit($page->firstRow . ',' . $page->listRows)
	->select();
	
	$return['count']=$totalsize;
	$return['items']=$qiushis;
	$return['page']=$page->show('default');
	
	return $return;
}




