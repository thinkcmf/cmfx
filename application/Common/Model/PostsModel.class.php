<?php
namespace Common\Model;
use Common\Model\CommonModel;
class PostsModel extends CommonModel {
	/*
	 * 表结构
	 * ID:post的自增ID
	 * post_author:用户的id
	 * post_date:发布时间
	 * post_content
	 * post_title
	 * post_excerpt:发表内容的摘录
	 * post_status:发表的状态,可以有多个值,分别为publish->发布,delete->删除,...
	 * comment_status:
	 * post_password
	 * post_name
	 * post_modified:更新时间
	 * post_content_filtered
	 * post_parent:为父级的post_id,就是这个表里的ID,一般用于表示某个发表的自动保存，和相关媒体而设置
	 * post_type:可以为多个值,image->表示某个post的附件图片;audio->表示某个post的附件音频;video->表示某个post的附件视频;...
	 */
	//post_type,post_status注意变量定义格式;
	/** @var string product*/
	public static $post_type_product='product';
	
	protected $_auto = array (
		array ('post_date', 'mGetDate', 1, 'callback' ), 	// 增加的时候调用回调函数
		//array ('post_modified', 'mGetDate', 2, 'callback' ) 
	);
	// 获取当前时间
	function mGetDate() {
		return date ( 'Y-m-d H:i:s' );
	}
	
	/**
	 * 
	 * @param array $arr
	 * array(
	 * 'uid'=>'',
	 * 'classify'=>'',
	 * 'title'=>'',
	 * 'content'=>'',
	 * 'content_type'=>''
	 * )
	 */
	function addPost($arr) {
		$date['post_author'] = $arr['uid'];	//发布着id
		$date['post_parent']=$arr['classify'] or $arr['parent']; //产品分类id
		$date['post_title'] = $arr['title'];	//产品名称
		//$date['post_excerpt']=$arr['discount'];	//折扣
		//$tmp = stripslashes($arr['content']);
		$date['post_content']=htmlspecialchars($arr['content']);	//内容为图片完整路径
		//$date['post_content_filtered'] =htmlspecialchars($tmp);	//产品描述
		$date['post_status']='publish';	//产品状态为发布（可正常浏览）
		$date['post_type'] = $arr['content_type'];	//类型为产品
		$date['post_date'] = $this->mGetDate ();
		$date['post_modified'] = $this->mGetDate ();
		$result = $this->add($date);
		return $result;
	}
	
	
	/**
	 * 更新发表的post
	 * @param int $post_id
	 * @param array $post_datas_array
	 * array(<br>
	 * 'uid'=>0, <br>
	 * 'parent'=>100,<br>
	 * 'title'=>'this is title',<br>
	 * 'content'=>'this is content',<br>
	 * 'status'=>'publish',<br>
	 * 'content_type'=>'post',<br>
	 * )
	 */
	function updatePost($post_id,$post_datas_array){
		if(empty($post_id))return;
		
		empty($post_datas_array['uid'])?"":$data['post_author'] = $post_datas_array['uid'];	//发布着id
		empty($post_datas_array['parent'])?"":$data['post_parent']=$post_datas_array['parent']; //产品分类id
		empty($post_datas_array['title'])?"":$data['post_title'] = $post_datas_array['title'];	//产品名称
		empty($post_datas_array['content'])?"":$data['post_content']=htmlspecialchars($post_datas_array['content']);	//内容为图片完整路径
		empty($post_datas_array['status'])?"":$data['post_status']=$post_datas_array['status'];	//产品状态为发布（可正常浏览）
		empty($post_datas_array['content_type'])?"":$data['post_type'] = $post_datas_array['content_type'];	//类型为产品
		$data['post_modified'] = $this->mGetDate ();
		$where['ID']=$post_id;
		$this->where($where)->save($data);
	}
	
	
	/*
	 * 添加文章登
	 */
	
	function addInfomation($post_author, $post_content, $post_title, $post_type, $post_parent = 0,$post_excerpt="") {
		$date ['post_author'] = $post_author;
		$tmp = stripslashes($post_content);
		$date ['post_content'] = htmlspecialchars($tmp);
		$date ['post_title'] = $post_title;
		$date ['post_parent'] = $post_parent;
		$date ['post_type'] = $post_type;
		$date ['post_excerpt'] = $post_excerpt;
		$date ['post_date'] = $this->mGetDate ();
		$date ['post_modified'] = $this->mGetDate ();
		$result = $this->add ( $date );
		return $result;
	}
	
	function updateInfomation($id,$post_content, $post_title,$post_type='post') {
		$tmp = stripslashes($post_content);
		$date ['post_content'] = $tmp/* htmlspecialchars($tmp) */;
		if($post_title!=""){
			$date ['post_title'] = $post_title;
		}
		$date ['post_date'] = $this->mGetDate ();
		$date ['post_modified'] = $this->mGetDate ();
		$result = $this->where("ID=$id")->save($date);
		print_r($result);
		return $result;
	}
	
	
	
	private $mPostmetaModel;
	
	/**
	 * 根据发布类型得到发布的所有信息
	 *
	 * @param $postType array
	 *       	 发布类型
	 *       	 $count int
	 *       	 $whichPage int
	 *       	 $pageNum int
	 *       	
	 * @return array 数组中存放的也数组 每个数组的结构<br>
	 *         {<br>
	 *         ["id"] => string(1) "3"<br>
	 *         ["author"] => string(1) "1"<br>
	 *         ["date"] => string(19) "2012-06-03 02:16:18"<br>
	 *         ["describ"]=>string() "这里是宝贝描述<hr>"
	 *         ["content"] => string(0) ""<br>
	 *         ["title"] => string(12) "title"<br>
	 *         ["excerpt"] => string(0) ""<br>
	 *         ["status"] => string(10) "auto-draft"<br>
	 *         ["comment_status"] => string(4) "open"<br>
	 *         ["password"] => string(0) ""<br>
	 *         ["name"] => string(0) ""<br>
	 *         ["modified"] => string(19) "2012-06-03 02:16:18"<br>
	 *         ["type"] => string(4) "post"<br>
	 *         }<br>
	 */
	function getPostsByPostTypeOnePage($postTypes, $offset, $pageNum=20, $post_parents,  $post_status='publish') {
		$post_type_strs="";
		if(count($postTypes)>0){
			foreach ($postTypes as $value){
				$post_type_strs=$post_type_strs."post_type='$value' or ";
			}
			$post_type_strs=substr($post_type_strs, 0,strlen($post_type_strs)-4);
			$post_type_strs="and ($post_type_strs)";
		}
		
		
		$parent_str="";
		if(count($post_parents)>0){
			foreach ($post_parents as $value){
				$parent_str=$parent_str."post_parent='$value' or ";
			}
			$parent_str=substr($parent_str, 0,strlen($parent_str)-4);
			$parent_str="and ($parent_str)";
		}
		
		return $datas = $this->field ( 'ID as id,post_author as author,post_date as date,post_content_filtered as describ,
		post_content as content,post_title as title,post_excerpt as excerpt, post_status as status,
		comment_status,post_password as password, post_name as name, post_modified as modified, post_type as type' )
		->where ( " post_status='$post_status' $post_type_strs  $parent_str"  )
		->order('ID desc')
		->limit($offset.','.$pageNum)
		->select();
	}
	
	/**
	 * 根据发布类型得到发布的所有信息
	 *
	 * @param $postType string
	 *       	 发布类型
	 *       	 $count int
	 *       	 $whichPage int
	 *       	 $pageNum int
	 *
	 * @return array 数组中存放的也数组 每个数组的结构<br>
	 *         {<br>
	 *         ["id"] => string(1) "3"<br>
	 *         ["author"] => string(1) "1"<br>
	 *         ["date"] => string(19) "2012-06-03 02:16:18"<br>
	 *         ["describ"]=>string() "这里是宝贝描述<hr>"
	 *         ["content"] => string(0) ""<br>
	 *         ["title"] => string(12) "title"<br>
	 *         ["excerpt"] => string(0) ""<br>
	 *         ["status"] => string(10) "auto-draft"<br>
	 *         ["comment_status"] => string(4) "open"<br>
	 *         ["password"] => string(0) ""<br>
	 *         ["name"] => string(0) ""<br>
	 *         ["modified"] => string(19) "2012-06-03 02:16:18"<br>
	 *         ["type"] => string(4) "post"<br>
	 *         }<br>
	 */
	function getPostsByPostType($postType,  $post_parent = 0,  $post_status='publish') {
		return $datas = $this->field ( 'ID as id,post_author as author,post_date as date,post_content_filtered as describ,
				post_content as content,post_title as title,post_excerpt as excerpt, post_status as status,
				comment_status,post_password as password, post_name as name, post_modified as modified, post_type as type' )
				->where ( "post_type='$postType' and post_status='$post_status' and post_parent='$post_parent' "  )
				->order('ID asc')
				->select();
	}
	
	/**
	 * 分页得到发表在所有信息，包括基本内容还有meta里内容
	 * $postType string 发布类型
	 * $count int
	 * $whichPage int
	 * $pageNum int
	 *
	 * @return array 数组中存放的也数组 每个数组的结构<br>
	 *         {<br>
	 *         ["id"] => string(1) "3"<br>
	 *         ["author"] => string(1) "1"<br>
	 *         ["date"] => string(19) "2012-06-03 02:16:18"<br>
	 *         ["content"] => string(0) ""<br>
	 *         ["title"] => string(12) "title"<br>
	 *         ["excerpt"] => string(0) ""<br>
	 *         ["status"] => string(10) "auto-draft"<br>
	 *         ["comment_status"] => string(4) "open"<br>
	 *         ["password"] => string(0) ""<br>
	 *         ["name"] => string(0) ""<br>
	 *         ["modified"] => string(19) "2012-06-03 02:16:18"<br>
	 *         ["type"] => string(4) "post"<br>
	 *         ["meta_key的值1"]=>"meta_value的值1"<br>
	 *         ["meta_key的值2"]=>"meta_value的值2"<br>
	 *         。。。
	 *         ["meta_key的值n"]=>"meta_value的值n"<br>
	 *         }<br>
	 */
	function getPostsAndItsPostMetas($postType, $offset, $pageNum=6, $post_parent = 1) {
		
		$postmeta_obj = new PostmetaModel ();
		$posts = $this->getPostsByPostType ( $postType, $offset, $pageNum, $post_parent);
		foreach ( $posts as $key => $value ) {
			$postmeta = $postmeta_obj->getMetasByPostID ( $value ['id'] );
			if(empty($postmeta)){
				$posts [$key] = array_merge ( $posts [$key]);
			}else{
				$posts [$key] = array_merge ( $posts [$key],$postmeta);
			}
			
		}
		return $posts;
	
	}
	
	function getCount($postType, $post_parent = 1, $post_status='publish',$orderby='ID',$asc='asc'){
		if($postType=='all'&&$post_status!='all'){
			return $this->where ( "post_status='$post_status'" )->order("'$orderby' '$asc'")->count();
		}
		if($postType!='all'&&$post_status=='all'){
			return $this->where ( "post_type='$postType' and post_parent= '$post_parent'" )->order("'$orderby' '$asc'")->count();
		}
		return $this->where ( "post_type='$postType' and post_status='$post_status' and post_parent= '$post_parent'" )->order("'$orderby' '$asc'")->count();
	}
	/**
	 * 得到某个发表信息，不包括它的metas
	 * $post_id int 发布id
	 *
	 * @return array 数组的结构<br>
	 *         {<br>
	 *         ["id"] => string(1) "3"<br>
	 *         ["author"] => string(1) "1"<br>
	 *         ["date"] => string(19) "2012-06-03 02:16:18"<br>
	 *         ["content"] => string(0) ""<br>
	 *         ["title"] => string(12) "title"<br>
	 *         ["excerpt"] => string(0) ""<br>
	 *         ["status"] => string(10) "auto-draft"<br>
	 *         ["comment_status"] => string(4) "open"<br>
	 *         ["password"] => string(0) ""<br>
	 *         ["name"] => string(0) ""<br>
	 *         ["modified"] => string(19) "2012-06-03 02:16:18"<br>
	 *         ["type"] => string(4) "post"<br>
	 *         }<br>
	 */
	function getPostByID($post_id) {
		return $datas = $this->field ( 'ID as id,post_author as author,post_date as date,
				post_content as content,post_title as title,post_excerpt as excerpt, post_status as status,
				comment_status,post_password as password, post_name as name, post_modified as modified, post_type as type' )
		->where ( "ID='$post_id'" )->find();
	}
	
	function getPostByIDs($post_ids) {
	
		
		if(is_array($post_ids)){
			$posts=array();
			foreach ($post_ids as $id){
				array_push($posts, $this->getPostByID($id));
			}
			return $posts;
		}
		return false;
	}
	
	function getPostsAndItsMetasByIDs($post_ids){
		if(is_array($post_ids)){
			$posts=array();
			foreach ($post_ids as $id){
				array_push($posts, $this->getPostAndItsMetasByID($id));
			}
			return $posts;
		}
		return false;
	}
	/**
	 * 得到某个发表信息，包括它的metas
	 * $post_id int 发布id
	 *
	 * @return array 数组的结构<br>
	 *         {<br>
	 *         ["id"] => string(1) "3"<br>
	 *         ["author"] => string(1) "1"<br>
	 *         ["date"] => string(19) "2012-06-03 02:16:18"<br>
	 *         ["content"] => string(0) ""<br>
	 *         ["title"] => string(12) "title"<br>
	 *         ["excerpt"] => string(0) ""<br>
	 *         ["status"] => string(10) "auto-draft"<br>
	 *         ["comment_status"] => string(4) "open"<br>
	 *         ["password"] => string(0) ""<br>
	 *         ["name"] => string(0) ""<br>
	 *         ["modified"] => string(19) "2012-06-03 02:16:18"<br>
	 *         ["type"] => string(4) "post"<br>
	 *         ["meta_key的值1"]=>"meta_value的值1"<br>
	 *         ["meta_key的值2"]=>"meta_value的值2"<br>
	 *         。。。
	 *         ["meta_key的值n"]=>"meta_value的值n"<br>
	 *         }<br>
	 */
	function  getPostAndItsMetasByID($post_id){
		$post_data=$this->getPostByID($post_id);
		if(empty($this->mPostmetaModel)){
			$this->mPostmetaModel = new PostmetaModel ();
		}
		
		$post_meta_data=$this->mPostmetaModel->getMetasByPostID($post_id);
		return array_merge($post_data,$post_meta_data);
	}
	
	/**
	 * 更新某个发表的发表状态
	 * @param int $post_id
	 * @param string $status
	 */
	function updatePostStatus($post_id, $status){
		return $this->where("ID=$post_id")->setField("post_status", $status);
	}
	
	
	
	public function updateexcerpt($id, $excerpt)
	{
	   $result = $this->where('ID = '.$id)->setField('post_excerpt', $excerpt);
	   return $result;
	}
	
	
	public function getPostNoHtml($postType, $offset, $pageNum=6, $post_parent = 1,  $post_status='publish'){
		$arr=$this->getPostsByPostType($postType, $offset, $pageNum, $post_parent,$post_status);
		for($i=0;$i<count($arr);$i++){
			array_push($arr[$i],$offset+$i+1); //这是第几条数据
			$arr[$i]['describ'] = htmlspecialchars_decode($arr[$i]['describ']);
		}
		return $arr;
	}
	
	
	//产品分页
	public function getPosts($postType, $offset, $pageNum=6, $post_parent = 0)
	{
		$arr=$this->field ( 'ID as id,post_author as author,post_date as date,post_content_filtered as describ,
				post_content as content,post_title as title,post_excerpt as excerpt, post_status as status,
				comment_status,post_password as password, post_name as name, post_modified as modified, post_type as type' )
				->where ( "post_type='$postType' and post_status != 'delete' and post_parent='$post_parent' "  )
				->order('ID asc')
				->limit($offset.','.$pageNum)
				->select();
		for($i=0;$i<count($arr);$i++){
			array_push($arr[$i],$offset+$i+1); //这是第几条数据
			$arr[$i]['describ'] = htmlspecialchars_decode($arr[$i]['describ']);
		}
		return $arr;
	}
	
	//获得产品数量
	/**
	 * 
	 * @param array $postTypes
	 * @param int $post_parent
	 * @param strign $orderby
	 * @param string $asc
	 */
	public function getPostCount($postTypes ,$post_parent =0 ,$orderby='ID',$asc='asc')
	{
		$post_type_strs="";
		if(count($postTypes)){
			foreach ($postTypes as $value){
				$post_type_strs=$post_type_strs."post_type='$value' or ";
			}
			$post_type_strs=substr($post_type_strs, 0,strlen($post_type_strs)-4);
			$post_type_strs="and ($post_type_strs)";
		}
		
		return $this->where ( "post_status != 'delete' and post_parent= '$post_parent' $post_type_strs" )->order("'$orderby' '$asc'")->count();
	}
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}
}