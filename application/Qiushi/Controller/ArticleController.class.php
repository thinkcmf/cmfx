<?php

/**
 * 文章内页
 */
namespace Qiushi\Controller;
use Common\Controller\HomeBaseController;
class ArticleController extends HomeBaseController {
	
	protected $qiushi_model;
	function _initialize(){
		parent::_initialize();
		$this->qiushi_model=D("Qiushi");
	}
	
    //糗事内页
    public function index() {
    	$id=I("get.id");
    	
    	$qiushi_cat_model=M("QiushiCat");
    	
    	$join = C('DB_PREFIX').'qiushi as b on a.id =b.cid';
    	$join2 = C('DB_PREFIX').'users as c on c.id =b.uid';
    	$qiushi=$qiushi_cat_model->field("a.cat_name,b.*,c.user_login,c.user_nicename")->alias("a")->join($join)->join($join2)
    	->order("b.createtime desc")
    	->where(array("b.id"=>$id))
    	->find();
    	
    	$data['hits'] = array('exp','hits+1');
    	$this->qiushi_model->where(array("id"=>$id))->save($data);
    	
    	$createtime=$qiushi['createtime'];
    	
    	$next=$this->qiushi_model->where(array("createtime"=>array("egt",$createtime), "id"=>array('neq',$id), "status"=>1))->order("createtime asc")->find();
    	$prev=$this->qiushi_model->where(array("createtime"=>array("elt",$createtime), "id"=>array('neq',$id), "status"=>1))->order("createtime desc")->find();
    	
    	$this->assign("next",$next);
    	$this->assign("prev",$prev);
    	$this->assign($qiushi);
    	$this->display(":article");
    }
	
	//设置赞和拍 1赞 -1拍
	public function set_like(){
		$this->check_login();
		
		$id=intval($_GET['id']);
		$type=intval($_GET['like_type']);
		if($type!=1) $type=-1;
		
		$can_set_like=sp_check_user_action("qiushi$id",1);
		if($can_set_like){
			if($type==1){
				M("Qiushi")->save(array("id"=>$id,"likes"=>array("exp","likes+1")));
				$this->success("您赞了一个！");
			}else{
				M("Qiushi")->save(array("id"=>$id,"unlikes"=>array("exp","unlikes+1")));
				$this->success("您拍了一板砖！");
			}
    		
		}else{
			$this->error("您已经点过了！");
		}
	}
	
	/**
	 * 发表糗事
	 */
	public function add(){
		$this->check_login();
		$qiushi_cat_model=M("QiushiCat");
		$qiushi_cats=$qiushi_cat_model->where(array("status"=>1))->select();
		$this->assign("qiushi_cats",$qiushi_cats);
	
		$this->display(":add");
	}
    
	/**
	 * 发表糗事
	 */
	public function add_post(){
		if(IS_POST){
		$this->check_login();
			$_POST=I("post.");
			$_POST['content']=h(htmlspecialchars_decode($_POST['content']));
			$_POST['uid']=get_current_userid();
			if ($this->qiushi_model->field("cid,uid,title,content")->create($_POST)){
				if ($this->qiushi_model->add()!==false) {
					$this->success("糗事发表成功！", U("qiushi/index/index"));
				} else {
					$this->error("糗事发表失败！");
				}
			} else {
				$this->error($this->qiushi_model->getError());
			}
		}
		
	}
	
	/**
	 * 编辑糗事
	 */
	public function edit(){
		$this->check_login();
		$id=I("get.id");
		$qiushi_cat_model=M("QiushiCat");
		$qiushi_cats=$qiushi_cat_model->where(array("status"=>1))->select();
		$this->assign("qiushi_cats",$qiushi_cats);
		$qiushi=$this->qiushi_model->where(array("id"=>$id))->find();
		$this->assign($qiushi);
	
		$this->display(":edit");
	}
	
	/**
	 * 编辑糗事
	 */
	public function edit_post(){
		if(IS_POST){
			$this->check_login();
			$uid=get_current_userid();
			$_POST=I("post.");
			$find_qiushi=$this->qiushi_model->where(array("id"=>intval($_POST['id']),"uid"=>$uid))->find();
				
			if(empty($find_qiushi)){
				$this->error("糗事不存在！");
			}
				
			$_POST['content']=h(htmlspecialchars_decode($_POST['content']));
			if ($this->qiushi_model->field("id,cid,title,content")->create($_POST)){
				if ($this->qiushi_model->save()!==false) {
					$this->success("糗事发表成功！", U("qiushi/index/index"));
				} else {
					$this->error("糗事发表失败！");
				}
			} else {
				$this->error($this->qiushi_model->getError());
			}
		}
	
	}
}
