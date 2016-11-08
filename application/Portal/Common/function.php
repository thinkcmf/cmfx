<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Tuolaji <479923197@qq.com>
// +----------------------------------------------------------------------
use Portal\Service\ApiService;
/**
 * 查询文章列表,不做分页
 * 注:此方法查询时关联三个表term_relationships,posts,users;在指定查询字段(field),排序(order),指定查询条件(where)最好指定一下表名
 * @param string $tag <pre>查询标签,以字符串方式传入
 * 例："cid:1,2;field:posts.post_title,posts.post_content;limit:0,8;order:post_date desc,listorder desc;where:id>0;"
 * ids:文章id,可以指定一个或多个文章id,以英文逗号分隔,如1或1,2,3
 * cid:文章所在分类,可指定一个或多个分类id,以英文逗号分隔,如1或1,2,3 默认值为全部
 * field:调用指定的字段
 *   如只调用posts表里的id和post_title字段可以是field:posts.id,posts.post_title; 默认全部,
 *   此方法查询时关联三个表term_relationships,posts,users;
 *   所以最好指定一下表名,以防字段冲突
 * limit:数据条数,默认值为10,可以指定从第几条开始,如3,8(表示共调用8条,从第3条开始)
 * order:排序方式,如按posts表里的post_date字段倒序排列：posts.post_date desc
 * where:查询条件,字符串形式,和sql语句一样,请在事先做好安全过滤,最好使用第二个参数$where的数组形式进行过滤,此方法查询时关联多个表,所以最好指定一下表名,以防字段冲突</pre>
 * @param array $where 查询条件(只支持数组),格式和thinkphp的where方法一样,此方法查询时关联多个表,所以最好指定一下表名,以防字段冲突;
 * 
 */
function sp_sql_posts($tag,$where=array()){
    return ApiService::postsNotPaged($tag,$where);
}

/**
 * 功能:查询文章列表,支持分页;<br>
 * 注:此方法查询时关联三个表term_relationships,posts,users;在指定查询字段(field),排序(order),指定查询条件(where)最好指定一下表名
 * @param string $tag <pre>查询标签,以字符串方式传入
 * 例："cid:1,2;field:posts.post_title,posts.post_content;limit:0,8;order:post_date desc,listorder desc;where:id>0;"
 * ids:文章id,可以指定一个或多个文章id,以英文逗号分隔,如1或1,2,3
 * cid:文章所在分类,可指定一个或多个分类id,以英文逗号分隔,如1或1,2,3 默认值为全部
 * field:调用指定的字段
 *   如只调用posts表里的id和post_title字段可以是field:posts.id,posts.post_title; 默认全部,
 *   此方法查询时关联三个表term_relationships,posts,users;
 *   所以最好指定一下表名,以防字段冲突
 * limit:数据条数,默认值为10,可以指定从第几条开始,如3,8(表示共调用8条,从第3条开始)
 * order:排序方式,如按posts表里的post_date字段倒序排列：posts.post_date desc
 * where:查询条件,字符串形式,和sql语句一样,请在事先做好安全过滤,最好使用第二个参数$where的数组形式进行过滤,此方法查询时关联多个表,所以最好指定一下表名,以防字段冲突</pre>
 * @param array $where 查询条件(只支持数组),格式和thinkphp的where方法一样,此方法查询时关联多个表,所以最好指定一下表名,以防字段冲突;
 * @param int $pagesize 每页条数,为0,false表示不分页
 * @param string $pagetpl 以字符串方式传入,例："{first}{prev}{liststart}{list}{listend}{next}{last}"
 * @return array 包括分页的文章列表<pre>
 * 格式:
 * array(
 *     "posts"=>array(),//文章列表,array
 * 	   "page"=>""//生成的分页html,不分页则没有此项
 *     "count"=>100 //符合条件的文章总数,不分页则没有此项
 * )</pre>
 */
function sp_posts($tag,$where=array(),$pagesize=0,$pagetpl=''){
    return ApiService::posts($tag,$where,$pagesize,$pagetpl);
}

/**
 * 功能：根据分类文章分类ID 获取该分类下所有文章(包含子分类中文章)
 * 注:此方法查询时关联三个表term_relationships,posts,users;在指定查询字段(field),排序(order),指定查询条件(where)最好指定一下表名
 * @author labulaka 2014-11-09 14:30:49
 * @param int $term_id 文章分类ID.
 * @param string $tag <pre>查询标签,以字符串方式传入
 * 例："cid:1,2;field:posts.post_title,posts.post_content;limit:0,8;order:post_date desc,listorder desc;where:id>0;"
 * ids:文章id,可以指定一个或多个文章id,以英文逗号分隔,如1或1,2,3
 * cid:文章所在分类,可指定一个或多个分类id,以英文逗号分隔,如1或1,2,3 默认值为全部
 * field:调用指定的字段
 *   如只调用posts表里的id和post_title字段可以是field:posts.id,posts.post_title; 默认全部,
 *   此方法查询时关联三个表term_relationships,posts,users;
 *   所以最好指定一下表名,以防字段冲突
 * limit:数据条数,默认值为10,可以指定从第几条开始,如3,8(表示共调用8条,从第3条开始)
 * order:排序方式,如按posts表里的post_date字段倒序排列：posts.post_date desc
 * where:查询条件,字符串形式,和sql语句一样,请在事先做好安全过滤,最好使用第二个参数$where的数组形式进行过滤,此方法查询时关联多个表,所以最好指定一下表名,以防字段冲突</pre>
 * @param array $where 查询条件(只支持数组),格式和thinkphp的where方法一样,此方法查询时关联多个表,所以最好指定一下表名,以防字段冲突;    
 */
function sp_sql_posts_bycatid($term_id,$tag,$where=array()){
	return ApiService::postsByTermId($term_id,$tag,$where);
}

/**
 * 文章分页查询方法
 * @param string $tag  查询标签，以字符串方式传入,例："cid:1,2;field:post_title,post_content;limit:0,8;order:post_date desc,listorder desc;where:id>0;"<br>
 * 	ids:调用指定id的一个或多个数据,如 1,2,3<br>
 * 	cid:数据所在分类,可调出一个或多个分类数据,如 1,2,3 默认值为全部,在当前分类为:'.$cid.'<br>
 * 	field:调用post指定字段,如(id,post_title...) 默认全部<br>
 * 	limit:数据条数,默认值为10,可以指定从第几条开始,如3,8(表示共调用8条,从第3条开始)<br>
 * 	order:排序方式，如：post_date desc<br>
 *	where:查询条件，字符串形式，和sql语句一样
 * @param int $pagesize 每页条数.
 * @param string $pagetpl 以字符串方式传入,例："{first}{prev}{liststart}{list}{listend}{next}{last}"
 * @return array 带分页数据的文章列表
 */
function sp_sql_posts_paged($tag,$pagesize=20,$pagetpl=''){
    return ApiService::postsPaged($tag,$pagesize,$pagetpl);
}

/**
 * 功能：根据关键字 搜索文章（包含子分类中文章）,已经分页,调用方式同sp_sql_posts_paged<br>
 * 注:此方法查询时关联三个表term_relationships,posts,users;在指定查询字段(field),排序(order),指定查询条件(where)最好指定一下表名
 * @author WelkinVan 2014-12-04
 * @param string $keyword 关键字.
 
 * @param string $tag <pre>查询标签,以字符串方式传入
 * 例："cid:1,2;field:posts.post_title,posts.post_content;limit:0,8;order:post_date desc,listorder desc;where:id>0;"
 * ids:文章id,可以指定一个或多个文章id,以英文逗号分隔,如1或1,2,3
 * cid:文章所在分类,可指定一个或多个分类id,以英文逗号分隔,如1或1,2,3 默认值为全部
 * field:调用指定的字段
 *   如只调用posts表里的id和post_title字段可以是field:posts.id,posts.post_title; 默认全部,
 *   此方法查询时关联三个表term_relationships,posts,users;
 *   所以最好指定一下表名,以防字段冲突
 * limit:数据条数,默认值为10,可以指定从第几条开始,如3,8(表示共调用8条,从第3条开始)
 * order:排序方式,如按posts表里的post_date字段倒序排列：posts.post_date desc
 * where:查询条件,字符串形式,和sql语句一样,请在事先做好安全过滤,最好使用第二个参数$where的数组形式进行过滤,此方法查询时关联多个表,所以最好指定一下表名,以防字段冲突</pre>
 * @param array $where 查询条件(只支持数组),格式和thinkphp的where方法一样,此方法查询时关联多个表,所以最好指定一下表名,以防字段冲突;
 * @param int $pagesize 每页条数.
 * @param string $pagetpl 以字符串方式传入,例："{first}{prev}{liststart}{list}{listend}{next}{last}"
 */
function sp_sql_posts_paged_bykeyword($keyword,$tag,$pagesize=20,$pagetpl='{first}{prev}{liststart}{list}{listend}{next}{last}'){
    return ApiService::postsPagedByKeyword($keyword,$tag,$pagesize,$pagetpl);
}

/**
 * 根据分类文章分类ID 获取该分类下所有文章（包含子分类中文章）,已经分页
 * 注:此方法查询时关联三个表term_relationships,posts,users;在指定查询字段(field),排序(order),指定查询条件(where)最好指定一下表名
 * @author labulaka 2014-11-09 14:30:49
 * @param int $cid 文章分类ID.
 * @param string $tag <pre>查询标签,以字符串方式传入
 * 例："cid:1,2;field:posts.post_title,posts.post_content;limit:0,8;order:post_date desc,listorder desc;where:id>0;"
 * ids:文章id,可以指定一个或多个文章id,以英文逗号分隔,如1或1,2,3
 * cid:文章所在分类,可指定一个或多个分类id,以英文逗号分隔,如1或1,2,3 默认值为全部
 * field:调用指定的字段
 *   如只调用posts表里的id和post_title字段可以是field:posts.id,posts.post_title; 默认全部,
 *   此方法查询时关联三个表term_relationships,posts,users;
 *   所以最好指定一下表名,以防字段冲突
 * limit:数据条数,默认值为10,可以指定从第几条开始,如3,8(表示共调用8条,从第3条开始)
 * order:排序方式,如按posts表里的post_date字段倒序排列：posts.post_date desc
 * where:查询条件,字符串形式,和sql语句一样,请在事先做好安全过滤,最好使用第二个参数$where的数组形式进行过滤,此方法查询时关联多个表,所以最好指定一下表名,以防字段冲突</pre>
 * @param int $pagesize 每页条数.
 * @param string $pagetpl 以字符串方式传入,例："{first}{prev}{liststart}{list}{listend}{next}{last}"
 */
function sp_sql_posts_paged_bycatid($term_id,$tag,$pagesize=20,$pagetpl=''){
	return ApiService::postsPagedByTermId($term_id,$tag,$pagesize,$pagetpl);
}

/**
 * 获取指定id的文章
 * @param int $post_id posts表下的id.
 * @param string $tag 查询标签，以字符串方式传入,例："field:post_title,post_content;"<br>
 *	field:调用post指定字段,如(id,post_title...) 默认全部<br>
 * @param array $where 查询条件(只支持数组),格式和thinkphp的where方法一样；
 * @return array 返回指定id的文章
 */
function sp_sql_post($post_id,$tag,$where=array()){
	return ApiService::post($post_id,$tag);
}

/**
 * 获取指定条件的页面列表
 * @param string $tag 查询标签，以字符串方式传入,例："ids:1,2;field:post_title,post_content;limit:0,8;order:post_date desc,listorder desc;where:id>0;"<br>
 * 	ids:调用指定id的一个或多个数据,如 1,2,3<br>
 * 	field:调用post指定字段,如(id,post_title...) 默认全部<br>
 * 	limit:数据条数,默认值为10,可以指定从第几条开始,如0,8(表示共调用8条,从第1条开始)<br>
 * 	order:排序方式，如：post_date desc<br>
 *	where:查询条件，字符串形式，和sql语句一样
 * @param array $where 查询条件(只支持数组),格式和thinkphp的where方法一样；
 * @return array 返回符合条件的所有页面
 */
function sp_sql_pages($tag,$where=array()){
    return ApiService::pages($tag,$where);
}

/**
 * 获取指定id的页面
 * @param int $id 页面的id
 * @return array 返回符合条件的页面
 */
function sp_sql_page($id){
	return ApiService::page($id);
}


/**
 * 返回指定分类
 * @param int $term_id 分类id
 * @return array 返回符合条件的分类
 */
function sp_get_term($term_id){
    return ApiService::term($term_id);
}

/**
 * 返回指定分类下的子分类
 * @param int $term_id 分类id
 * @return array 返回指定分类下的子分类
 */
function sp_get_child_terms($term_id){
	return ApiService::child_terms($term_id);
}

/**
 * 返回指定分类下的所有子分类
 * @param int $term_id 分类id
 * @return array 返回指定分类下的所有子分类
 */
function sp_get_all_child_terms($term_id){
    return ApiService::all_child_terms($term_id);
}

/**
 * 返回符合条件的所有分类
 * @param string $tag 查询标签，以字符串方式传入,例："ids:1,2;field:term_id,name,description,seo_title;limit:0,8;order:path asc,listorder desc;where:term_id>0;"<br>
 * 	ids:调用指定id的一个或多个数据,如 1,2,3
 * 	field:调用terms表里的指定字段,如(term_id,name...) 默认全部，用*代表全部
 * 	limit:数据条数,默认值为10,可以指定从第几条开始,如3,8(表示共调用8条,从第3条开始)
 * 	order:排序方式，如：path desc,listorder asc<br>
 * 	where:查询条件，字符串形式，和sql语句一样
 * @param array $where 查询条件(只支持数组),格式和thinkphp的where方法一样；
 * @return array 返回符合条件的所有分类
 * 
 */
function sp_get_terms($tag,$where=array()){
	return ApiService::terms($tag,$where);
}

/**
 * 获取Portal应用当前模板下的模板列表
 * @return array
 */
function sp_admin_get_tpl_file_list(){
	$template_path=C("SP_TMPL_PATH").C("SP_DEFAULT_THEME")."/Portal/";
	$files=sp_scan_dir($template_path."*");
	$tpl_files=array();
	foreach ($files as $f){
		if($f!="." || $f!=".."){
			if(is_file($template_path.$f)){
				$suffix=C("TMPL_TEMPLATE_SUFFIX");
				$result=preg_match("/$suffix$/", $f);
				if($result){
					$tpl=str_replace($suffix, "", $f);
					$tpl_files[$tpl]=$tpl;
				}else if(preg_match("/\.php$/", $f)){
				    $tpl=str_replace($suffix, "", $f);
				    $tpl_files[$tpl]=$tpl;
				}
			}
		}
	}
	return $tpl_files;
}

/**
 *  获取面包屑数据
 * @param int $term_id 当前文章所在分类,或者当前分类的id
 * @param boolean $with_current 是否获取当前分类
 * @return array 面包屑数据
 */
function sp_get_breadcrumb($term_id){
    return ApiService::breadcrumb($term_id);
}