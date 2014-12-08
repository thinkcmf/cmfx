<?php

/**
 * 文章内页
 */
namespace Qiushi\Action;
use Common\Action\HomeBaseAction;
class ArticleAction extends HomeBaseAction {
    //文章内页
    public function index() {
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
				M("Qiushi")->save(array("id"=>$id,"like"=>array("exp","like+1")));
				$this->success("您赞了一个！");
			}else{
				M("Qiushi")->save(array("id"=>$id,"unlike"=>array("exp","unlike+1")));
				$this->success("您拍了一板砖");
			}
    		
		}else{
			$this->success("您已经点过了");
		}
	}
    
	/**
	 * 发表糗事
	 */
	public function add_post(){
		if(IS_POST){
			//TODO 用户提交title(可选),content(必须)
		}
		
	}
}
?>
