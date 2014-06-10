<?php
/**
 * 参    数：
 * 作    者：lht
 * 功    能：OAth2.0协议下第三方登录数据报表
 * 修改日期：2013-12-13
 */
namespace Api\Action;
use Common\Action\AdminbaseAction;
class OauthadminAction extends AdminbaseAction {
	
	//用户列表
	function index(){
		$outhmember_model=M('OauthMember');
		$count=$outhmember_model->where("status=1")->count();
		$page = $this->page($count, 20);
		$lists = $outhmember_model
		->where("status=1")
		->limit($page->firstRow . ',' . $page->listRows)
		->select();
		$this->assign("page", $page->show('Admin'));
		$this->assign('lists', $lists);
		$this->display();
	}
	
	//删除用户
	function delete(){
		$id=intval($_GET['id']);
		if(empty($id)){
			$this->error('非法数据！');
		}
		$rst = M("OauthMember")->where("status=1 and ID=$id")->delete();
		if ($rst!==false) {
			$this->success("删除成功！", U("oauthadmin/index"));
		} else {
			$this->error('删除失败！');
		}
	}
	
	//设置
	function setting(){
		$this->display();
	}
	
	//设置
	function setting_post(){
		if($_POST){
			$host=$_SERVER["HTTP_HOST"];
			$protocol=is_ssl()?"https://":"http://";
			$qq_key=$_POST['qq_key'];
			$qq_sec=$_POST['qq_sec'];
			$sina_key=$_POST['sina_key'];
			$sina_sec=$_POST['sina_sec'];
			
			$call_back = $protocol.$host.__ROOT__.'/index.php?g=api&m=oauth&a=callback&type=';
			$data = array(
					'THINK_SDK_QQ' => array(
							'APP_KEY'    => $qq_key,
							'APP_SECRET' => $qq_sec,
							'CALLBACK'   => $call_back . 'qq',
					),
					'THINK_SDK_SINA' => array(
							'APP_KEY'    => $sina_key,
							'APP_SECRET' => $sina_sec,
							'CALLBACK'   => $call_back . 'sina',
					),
			);
			
			$result=sp_set_dynamic_config($data);
			
			if($result){
				$this->success("更新成功！");
			}else{
				$this->error("更新失败！");
			}
		}
	}
}