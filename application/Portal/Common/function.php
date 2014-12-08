<?php

/**
 * @处理标签函数
 * @以字符串方式传入,通过sp_param_lable函数解析为以下变量
 * ids:调用指定id的一个或多个数据,如 1,2,3
 * cid:数据所在分类,可调出一个或多个分类数据,如 1,2,3 默认值为全部,在当前分类为:'.$cid.'
 * field:调用post指定字段,如(id,post_title...) 默认全部
 * limit:数据条数,默认值为10,可以指定从第几条开始,如3,8(表示共调用8条,从第3条开始)
 * order:推荐方式(post_date) (desc/asc/rand())
 */
function sp_sql_posts($tag,$where=array()){
	if(!is_array($where)){
		$where=array();
	}
	
	$tag=sp_param_lable($tag);
	$field = !empty($tag['field']) ? $tag['field'] : '*';
	$limit = !empty($tag['limit']) ? $tag['limit'] : '';
	$order = !empty($tag['order']) ? $tag['order'] : 'post_date';


	//根据参数生成查询条件
	$where['status'] = array('eq',1);
	$where['post_status'] = array('eq',1);

	if (isset($tag['cid'])) {
		$where['term_id'] = array('in',$tag['cid']);
	}
	
	if (isset($tag['ids'])) {
		$where['object_id'] = array('in',$tag['ids']);
	}
	


	$join = "".C('DB_PREFIX').'posts as b on a.object_id =b.id';
	$join2= "".C('DB_PREFIX').'users as c on b.post_author = c.id';
	$rs= M("TermRelationships");

	$posts=$rs->alias("a")->join($join)->join($join2)->field($field)->where($where)->order($order)->limit($limit)->select();
	return $posts;
}

/**
 * 功能：根据分类文章分类ID 获取该分类下所有文章（包含子分类中文章），调用方式同sp_sql_posts
 * create by labulaka 2014-11-09 14:30:49
 * param int $tid 文章分类ID.
 * param string $tag 以字符串方式传入,例："order:post_date desc,listorder desc;"
 * 		field:调用post指定字段,如(id,post_title...) 默认全部
 * 		limit:数据条数,默认值为10,可以指定从第几条开始,如3,8(表示共调用8条,从第3条开始)
 * 		order:推荐方式(post_date) (desc/asc/rand())
 * param int $pagesize 分页数字.
 * param string $pagetpl 以字符串方式传入,例："{first}{prev}{liststart}{list}{listend}{next}{last}"
 */

function sp_sql_posts_bycatid($cid,$tag,$where=array()){
	$cid=intval($cid);
	$catIDS=array();
	$terms=M("Terms")->field("term_id")->where("status=1 and ( term_id=$cid OR path like '%-$cid-%' )")->order('term_id asc')->select();

	foreach($terms as $item){
		$catIDS[]=$item['term_id'];
	}
	if(!empty($catIDS)){
		$catIDS=implode(",", $catIDS);
		$catIDS="cid:$catIDS;";
	}else{
		$catIDS="";
	}
	$content= sp_sql_posts($catIDS.$tag,$where);
	return $content;

}

/**
 * @ 处理标签函数
 * @ $tag以字符串方式传入,通过sp_param_lable函数解析为以下变量。例："cid:1,2;order:post_date desc,listorder desc;"
 * ids:调用指定id的一个或多个数据,如 1,2,3
 * cid:数据所在分类,可调出一个或多个分类数据,如 1,2,3 默认值为全部,在当前分类为:'.$cid.'
 * field:调用post指定字段,如(id,post_title...) 默认全部
 * limit:数据条数,默认值为10,可以指定从第几条开始,如3,8(表示共调用8条,从第3条开始)
 * order:推荐方式(post_date) (desc/asc/rand())
 */

function sp_sql_posts_paged($tag,$pagesize=20,$pagetpl='{first}{prev}{liststart}{list}{listend}{next}{last}'){
	$where=array();
	$tag=sp_param_lable($tag);
	$field = !empty($tag['field']) ? $tag['field'] : '*';
	$limit = !empty($tag['limit']) ? $tag['limit'] : '';
	$order = !empty($tag['order']) ? $tag['order'] : 'post_date';


	//根据参数生成查询条件
	$where['status'] = array('eq',1);
	$where['post_status'] = array('eq',1);

	if (isset($tag['cid'])) {
		$where['term_id'] = array('in',$tag['cid']);
	}

	if (isset($tag['ids'])) {
		$where['object_id'] = array('in',$tag['ids']);
	}

	$join = "".C('DB_PREFIX').'posts as b on a.object_id =b.id';
	$join2= "".C('DB_PREFIX').'users as c on b.post_author = c.id';
	$rs= M("TermRelationships");
	$totalsize=$rs->alias("a")->join($join)->join($join2)->field($field)->where($where)->count();
	
	import('Page');
	if ($pagesize == 0) {
		$pagesize = 20;
	}
	$PageParam = C("VAR_PAGE");
	$page = new \Page($totalsize,$pagesize);
	$page->setLinkWraper("li");
	$page->__set("PageParam", $PageParam);
	$page->SetPager('default', $pagetpl, array("listlong" => "6", "first" => "首页", "last" => "尾页", "prev" => "上一页", "next" => "下一页", "list" => "*", "disabledclass" => ""));
	$posts=$rs->alias("a")->join($join)->join($join2)->field($field)->where($where)->order($order)->limit($page->firstRow . ',' . $page->listRows)->select();

	$content['posts']=$posts;
	$content['page']=$page->show('default');
	return $content;
}

/**
 * 功能：根据关键字 搜索文章（包含子分类中文章），已经分页，调用方式同sp_sql_posts_paged
 * create by WelkinVan 2014-12-04
 * param string $keyword 关键字.
 * param string $tag 以字符串方式传入,例："order:post_date desc,listorder desc;"
 * 		field:调用post指定字段,如(id,post_title...) 默认全部
 * 		limit:数据条数,默认值为10,可以指定从第几条开始,如3,8(表示共调用8条,从第3条开始)
 * 		order:推荐方式(post_date) (desc/asc/rand())
 * param int $pagesize 分页数字.
 * param string $pagetpl 以字符串方式传入,例："{first}{prev}{liststart}{list}{listend}{next}{last}"
 */
function sp_sql_posts_paged_bykeyword($keyword,$tag,$pagesize=20,$pagetpl='{first}{prev}{liststart}{list}{listend}{next}{last}'){
	$where=array();
	$tag=sp_param_lable($tag);
	$field = !empty($tag['field']) ? $tag['field'] : '*';
	$limit = !empty($tag['limit']) ? $tag['limit'] : '';
	$order = !empty($tag['order']) ? $tag['order'] : 'post_date';


	//根据参数生成查询条件
	$where['status'] = array('eq',1);
	$where['post_status'] = array('eq',1);
	$where['post_title'] = array('like','%' . $keyword . '%');
	
	if (isset($tag['cid'])) {
		$where['term_id'] = array('in',$tag['cid']);
	}

	if (isset($tag['ids'])) {
		$where['object_id'] = array('in',$tag['ids']);
	}

	$join = "".C('DB_PREFIX').'posts as b on a.object_id =b.id';
	$join2= "".C('DB_PREFIX').'users as c on b.post_author = c.id';
	$rs= M("TermRelationships");
	$totalsize=$rs->alias("a")->join($join)->join($join2)->field($field)->where($where)->count();
	import('Page');
	if ($pagesize == 0) {
		$pagesize = 20;
	}
	$PageParam = C("VAR_PAGE");
	$page = new Page($totalsize,$pagesize);
	$page->setLinkWraper("li");
	$page->__set("PageParam", $PageParam);
	$page->SetPager('default', $pagetpl, array("listlong" => "6", "first" => "首页", "last" => "尾页", "prev" => "上一页", "next" => "下一页", "list" => "*", "disabledclass" => ""));
	$posts=$rs->alias("a")->join($join)->join($join2)->field($field)->where($where)->order($order)->limit($page->firstRow . ',' . $page->listRows)->select();
	$content['count']=$totalsize;
	$content['posts']=$posts;
	$content['page']=$page->show('default');
	return $content;
}

/**
 * 功能：根据分类文章分类ID 获取该分类下所有文章（包含子分类中文章），已经分页，调用方式同sp_sql_posts_paged
 * create by labulaka 2014-11-09 14:30:49
 * param int $tid 文章分类ID.
 * param string $tag 以字符串方式传入,例："order:post_date desc,listorder desc;"
 * 		field:调用post指定字段,如(id,post_title...) 默认全部
 * 		limit:数据条数,默认值为10,可以指定从第几条开始,如3,8(表示共调用8条,从第3条开始)
 * 		order:推荐方式(post_date) (desc/asc/rand())
 * param int $pagesize 分页数字.
 * param string $pagetpl 以字符串方式传入,例："{first}{prev}{liststart}{list}{listend}{next}{last}"
 */

function sp_sql_posts_paged_bycatid($cid,$tag,$pagesize=20,$pagetpl='{first}{prev}{liststart}{list}{listend}{next}{last}'){
	$cid=intval($cid);
	$catIDS=array();
	$terms=M("Terms")->field("term_id")->where("status=1 and ( term_id=$cid OR path like '%-$cid-%' )")->order('term_id asc')->select();
	
	foreach($terms as $item){
		$catIDS[]=$item['term_id'];
	}
	if(!empty($catIDS)){
		$catIDS=implode(",", $catIDS);
		$catIDS="cid:$catIDS;";
	}else{
		$catIDS="";
	}
	$content= sp_sql_posts_paged($catIDS.$tag,$pagesize,$pagetpl);
	return $content;

}


/**
 * @param int $tid 分类表下的tid.
 * @param string $tag 
 * @处理标签函数
 * @以字符串方式传入,通过sp_param_lable函数解析为以下变量
 * field:调用post指定字段,如(id,post_title...) 默认全部
 * 如：field:post_title;
 * @return array 返回指定id所有页面
 */
function sp_sql_post($tid,$tag){
	$where=array();
	$tag=sp_param_lable($tag);
	$field = !empty($tag['field']) ? $tag['field'] : '*';


	//根据参数生成查询条件
	$where['status'] = array('eq',1);
	$where['tid'] = array('eq',$tid);


	$join = "".C('DB_PREFIX').'posts as b on a.object_id =b.id';
	$join2= "".C('DB_PREFIX').'users as c on b.post_author = c.id';
	$rs= M("TermRelationships");

	$posts=$rs->alias("a")->join($join)->join($join2)->field($field)->where($where)->find();
	return $posts;
}

/**
 * @处理标签函数
 * @以字符串方式传入,通过sp_param_lable函数解析为以下变量
 * 返回符合条件的所有页面
 * ids:调用指定id的一个或多个数据,如 1,2,3
 * field:调用post指定字段,如(id,post_title...) 默认全部
 * limit:数据条数,默认值为10,可以指定从第几条开始,如3,8(表示共调用8条,从第3条开始)
 * order:推荐使用方式(post_date) (desc/asc/rand())
 * 使用：ids:1,2;field:post_date,post_content;limit:10;order:post_date DESC,id;
 */
function sp_sql_pages($tag){
	$where=array();
	$tag=sp_param_lable($tag);
	$field = !empty($tag['field']) ? $tag['field'] : '*';
	$limit = !empty($tag['limit']) ? $tag['limit'] : '';
	$order = !empty($tag['order']) ? $tag['order'] : 'post_date';


	//根据参数生成查询条件
	$where['post_status'] = array('eq',1);
	$where['post_type'] = array('eq',2);

	$rs= M("Posts");

	$posts=$rs->field($field)->where($where)->order($order)->limit($limit)->select();
	return $posts;
}

/**
 * @处理标签函数
 * @以字符串方式传入,通过sp_param_lable函数解析为以下变量
 * 返回指定id=$id的页面
 */
function sp_sql_page($id){
	$where=array();
	$where['id'] = array('eq',$id);

	$rs= M("Posts");
	$post=$rs->where($where)->find();
	return $post;
}


/**
 * 返回指定分类
 */
function sp_get_term($term_id){
	
	$terms=F('all_terms');
	if(empty($terms)){
		$term_obj= M("Terms");
		$terms=$term_obj->where("status=1")->select();
		$mterms=array();
		
		foreach ($terms as $t){
			$tid=$t['term_id'];
			$mterms["t$tid"]=$t;
		}
		
		F('all_terms',$mterms);
		return $mterms["t$term_id"];
	}else{
		return $terms["t$term_id"];
	}
}
/**
 * 返回指定分类下的子分类
 */
function sp_get_child_terms($term_id){

	$term_id=intval($term_id);
	$term_obj = M("Terms");
	$terms=$term_obj->where("status=1 and parent=$term_id")->order("listorder asc")->select();
	
	return $terms;
}
/**
 * 9
 * @处理标签函数
 * @以字符串方式传入,通过sp_param_lable函数解析为以下变量
 * 返回符合条件的所有分类
 * ids:调用指定id的一个或多个数据,如 1,2,3
 * field:调用post指定字段,如(id,post_title...) 默认全部
 * limit:数据条数,默认值为10,可以指定从第几条开始,如3,8(表示共调用8条,从第3条开始)
 * order:path (desc/asc/rand())
 * 使用：ids:1,2;field:post_date,post_content;limit:10;order:post_date DESC,id;
 */
function sp_get_terms($tag){
	
	$where=array();
	$tag=sp_param_lable($tag);
	$field = !empty($tag['field']) ? $tag['field'] : '*';
	$limit = !empty($tag['limit']) ? $tag['limit'] : '';
	$order = !empty($tag['order']) ? $tag['order'] : 'term_id';
	
	//根据参数生成查询条件
	$where['status'] = array('eq',1);
	
	if (isset($tag['ids'])) {
		$where['term_id'] = array('in',$tag['ids']);
	}
	
	$term_obj= M("Terms");
	$terms=$term_obj->field($field)->where($where)->order($order)->limit($limit)->select();
	return $terms;
}

function sp_admin_get_tpl_file_list(){
	$template_path=C("SP_TMPL_PATH").C("SP_DEFAULT_THEME")."/Portal/";
	$files=scandir($template_path);
	$tpl_files=array();
	foreach ($files as $f){
		if($f!="." || $f!=".."){
			if(is_file($template_path.$f)){
				$suffix=C("TMPL_TEMPLATE_SUFFIX");
				$result=preg_match("/$suffix$/", $f);
				if($result){
					$tpl=str_replace($suffix, "", $f);
					$tpl_files[$tpl]=$tpl;
				}
			}
		}
	}
	return $tpl_files;
}