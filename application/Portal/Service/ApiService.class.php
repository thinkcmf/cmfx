<?php
namespace Portal\Service;

class ApiService {
    
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
    public static function posts($tag,$where=array(),$pagesize=0,$pagetpl=''){
    	$where=is_array($where)?$where:array();
    	$tag=sp_param_lable($tag);
    	
    	$field = !empty($tag['field']) ? $tag['field'] : '*';
    	$limit = !empty($tag['limit']) ? $tag['limit'] : '0,10';
    	$order = !empty($tag['order']) ? $tag['order'] : 'post_date DESC';
    
    	//根据参数生成查询条件
    	$where['term_relationships.status'] = array('eq',1);
    	$where['posts.post_status'] = array('eq',1);
    
    	if (isset($tag['cid'])) {
    	    $tag['cid']=explode(',', $tag['cid']);
    	    $tag['cid']=array_map('intval', $tag['cid']);
    		$where['term_relationships.term_id'] = array('in',$tag['cid']);
    	}
    
    	if (isset($tag['ids'])) {
    	    $tag['ids']=explode(',', $tag['ids']);
    	    $tag['ids']=array_map('intval', $tag['ids']);
    		$where['term_relationships.object_id'] = array('in',$tag['ids']);
    	}
    	
    	if (isset($tag['where'])) {
    		$where['_string'] = $tag['where'];
    	}
    
    	$join = '__POSTS__ as posts on term_relationships.object_id = posts.id';
    	$join2= '__USERS__ as users on posts.post_author = users.id';
    	
    	$term_relationships_model= M("TermRelationships");
    	$content=array();
    
    	if (empty($pagesize)) {
    	    $posts=$term_relationships_model
    	    ->alias("term_relationships")
    	    ->join($join)
    	    ->join($join2)
    	    ->field($field)
    	    ->where($where)
    	    ->order($order)
    	    ->limit($limit)
    	    ->select();
    	}else{
    	    $pagetpl = empty($pagetpl) ? '{first}{prev}{liststart}{list}{listend}{next}{last}' : $pagetpl;
    	    $totalsize=$term_relationships_model
    	    ->alias("term_relationships")
    	    ->join($join)
    	    ->join($join2)
    	    ->field($field)
    	    ->where($where)
    	    ->count();
    	    
    	    $pagesize = intval($pagesize);
    	    $page_param = C("VAR_PAGE");
    	    $page = new \Page($totalsize,$pagesize);
    	    $page->setLinkWraper("li");
    	    $page->__set("PageParam", $page_param);
    	    $pagesetting=array("listlong" => "9", "first" => "首页", "last" => "尾页", "prev" => "上一页", "next" => "下一页", "list" => "*", "disabledclass" => "");
    	    $page->SetPager('default', $pagetpl,$pagesetting);
    	    
    	    $posts=$term_relationships_model
    	    ->alias("term_relationships")
    	    ->join($join)
    	    ->join($join2)
    	    ->field($field)
    	    ->where($where)
    	    ->order($order)
    	    ->limit($page->firstRow, $page->listRows)
    	    ->select();
    	    
    	    $content['page']=$page->show('default');
    	    $content['count']=$totalsize;
    	}
    	
    	$content['posts']=$posts;
    	
    	return $content;
    }
    
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
    public static function postsNotPaged($tag,$where=array()){
        $content=self::posts($tag,$where);
        return $content['posts'];
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
    public static function postsByTermId($term_id,$tag,$where=array()){
        $term_id=intval($term_id);
        
        if(!is_array($where)){
            $where=array();
        }
        
        $term_ids=array();
        
        $term_ids=M("Terms")->where("status=1 and ( term_id=$term_id OR path like '%-$term_id-%' )")->order('term_id asc')->getField('term_id',true);
        
        if(!empty($term_ids)){
            $where['term_relationships.term_id']=array('in',$term_ids);
        }
        
        $content=self::posts($tag,$where);
        
        return $content['posts'];
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
    public static function postsPaged($tag,$pagesize=20,$pagetpl=''){
        return self::posts($tag,array(),$pagesize,$pagetpl);
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
    public static function postsPagedByTermId($term_id,$tag,$pagesize=20,$pagetpl=''){
        $term_id=intval($term_id);
        $term_ids=array();
        $where=array();
        $term_ids=M("Terms")->field("term_id")->where("status=1 and ( term_id=$term_id OR path like '%-$term_id-%' )")->order('term_id asc')->getField('term_id',true);
        
        if(!empty($term_ids)){
            $where['term_relationships.term_id']=array('in',$term_ids);
        }
        
        $content=self::posts($tag,$where,$pagesize,$pagetpl);
        
        return $content;
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
    public static function postsPagedByKeyword($keyword,$tag,$pagesize=20,$pagetpl=''){
        $where=array();
        $where['posts.post_title'] = array('like',"%$keyword%");
        
        $content=self::posts($tag,$where,$pagesize,$pagetpl);
        
        return $content;
    }
    
    /**
     * 获取指定id的文章
     * @param int $post_id posts表下的id.
     * @param string $tag 查询标签,以字符串方式传入,例："field:post_title,post_content;"<br>
     *	field:调用post指定字段,如(id,post_title...) 默认全部<br>
     * @return array 返回指定id的文章
     */
    public static function post($post_id,$tag){
        $where=array();
        
        $tag=sp_param_lable($tag);
        $field = !empty($tag['field']) ? $tag['field'] : '*';
        
        $where['post_status'] = array('eq',1);
        $where['id'] = array('eq',$post_id);
        
        $post=M('Posts')->field($field)->where($where)->find();
        
        return $post;
    }
    
    /**
     * 获取指定条件的页面列表
     * @param string $tag 查询标签,以字符串方式传入,例："ids:1,2;field:post_title,post_content;limit:0,8;order:post_date desc,listorder desc;where:id>0;"<br>
     * 	ids:调用指定id的一个或多个数据,如 1,2,3<br>
     * 	field:调用post指定字段,如(id,post_title...) 默认全部<br>
     * 	limit:数据条数,默认值为10,可以指定从第几条开始,如0,8(表示共调用8条,从第1条开始)<br>
     * 	order:排序方式,如：post_date desc<br>
     *	where:查询条件,字符串形式,和sql语句一样
     * @param array $where 查询条件(只支持数组),格式和thinkphp的where方法一样；
     * @return array 返回符合条件的所有页面
     */
    public static function pages($tag,$where=array()){
        if(!is_array($where)){
            $where=array();
        }
        $tag=sp_param_lable($tag);
        $field = !empty($tag['field']) ? $tag['field'] : '*';
        $limit = !empty($tag['limit']) ? $tag['limit'] : '0,10';
        $order = !empty($tag['order']) ? $tag['order'] : 'post_date DESC';
        
        //根据参数生成查询条件
        $where['post_status'] = array('eq',1);
        $where['post_type'] = array('eq',2);
        
        if (isset($tag['ids'])) {
            $tag['ids']=explode(',', $tag['ids']);
            $tag['ids']=array_map('intval', $tag['ids']);
            $where['id'] = array('in',$tag['ids']);
        }
        
        if (isset($tag['where'])) {
            $where['_string'] = $tag['where'];
        }
        
        $posts_model= M("Posts");
        
        $pages=$posts_model->field($field)->where($where)->order($order)->limit($limit)->select();
        
        return $pages;
    }
    
    /**
     * 获取指定id的页面
     * @param int $id 页面的id
     * @return array 返回符合条件的页面
     */
    public static function page($id){
        $where=array();
        $where['id'] = array('eq',$id);
        $where['post_type'] = array('eq',2);
        
        $posts_model = M("Posts");
        $post = $posts_model->where($where)->find();
        return $post;
    }
    
    /**
     * 返回指定分类
     * @param int $term_id 分类id
     * @return array 返回符合条件的分类
     */
    public static function term($term_id){
    	$terms=F('all_terms');
    	if(empty($terms)){
    		$terms_model= M("Terms");
    		$terms=$terms_model->where("status=1")->select();
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
     * @param int $term_id 分类id
     * @return array 返回指定分类下的子分类
     */
    public static function child_terms($term_id){
        $term_id=intval($term_id);
        $terms_model = M("Terms");
        $terms=$terms_model->where("status=1 and parent=$term_id")->order("listorder asc")->select();
    
        return $terms;
    }
    
    /**
     * 返回指定分类下的所有子分类
     * @param int $term_id 分类id
     * @return array 返回指定分类下的所有子分类
     */
    public static function all_child_terms($term_id){
        $term_id=intval($term_id);
        $terms_model = M("Terms");
    
        $terms=$terms_model->where("status=1 and path like '%-$term_id-%'")->order("listorder asc")->select();
    
        return $terms;
    }
    
    /**
     * 返回符合条件的所有分类
     * @param string $tag 查询标签,以字符串方式传入,例："ids:1,2;field:term_id,name,description,seo_title;limit:0,8;order:path asc,listorder desc;where:term_id>0;"<br>
     * 	ids:调用指定id的一个或多个数据,如 1,2,3
     * 	field:调用terms表里的指定字段,如(term_id,name...) 默认全部,用*代表全部
     * 	limit:数据条数,默认值为10,可以指定从第几条开始,如3,8(表示共调用8条,从第3条开始)
     * 	order:排序方式,如：path desc,listorder asc<br>
     * 	where:查询条件,字符串形式,和sql语句一样
     * @param array $where 查询条件(只支持数组),格式和thinkphp的where方法一样；
     * @return array 返回符合条件的所有分类
     *
     */
    public static function terms($tag,$where=array()){
        if(!is_array($where)){
            $where=array();
        }
        
        $tag=sp_param_lable($tag);
        $field = !empty($tag['field']) ? $tag['field'] : '*';
        $limit = !empty($tag['limit']) ? $tag['limit'] : '';
        $order = !empty($tag['order']) ? $tag['order'] : 'term_id';
    
        //根据参数生成查询条件
        $where['status'] = array('eq',1);
    
        if (isset($tag['ids'])) {
            $tag['ids']=explode(',', $tag['ids']);
            $tag['ids']=array_map('intval', $tag['ids']);
            $where['term_id'] = array('in',$tag['ids']);
        }
    
        if (isset($tag['where'])) {
            $where['_string'] = $tag['where'];
        }
    
        $terms_model= M("Terms");
        $terms=$terms_model->field($field)->where($where)->order($order)->limit($limit)->select();
        return $terms;
    }
    
    /**
     *  获取面包屑数据
     * @param int $term_id 当前文章所在分类,或者当前分类的id
     * @return array 面包屑数据
     */
    public static function breadcrumb($term_id){
        $terms_model= M("Terms");
        $data=array();
        $path=$terms_model->where(array('term_id'=>$term_id))->getField('path');
        if(!empty($path)){
            $parents=explode('-', $path);
            array_pop($parents);
            if(!empty($parents)){
                $data=$terms_model->where(array('term_id'=>array('in',$parents)))->order('path ASC')->select();
            }
        }
        
        return $data;
    }
}