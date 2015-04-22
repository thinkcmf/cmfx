<?php
namespace Qiushi\Controller;
use Common\Controller\MemberbaseController;
/**
 * 首页
 */
class UserController extends MemberbaseController {
	
    //首页
	public function index() {
		
		$qiushi_cat_model=M("QiushiCat");
		
		$join = C('DB_PREFIX').'qiushi as b on a.id =b.cid';
		$join2 = C('DB_PREFIX').'users as c on c.id =b.uid';
		
		$where=array("b.uid"=>get_current_userid());
		
		$totalsize=$qiushi_cat_model->alias("a")->join($join)->where($where)->count();
		
		import('Page');
		$PageParam = C("VAR_PAGE");
		$page = new \Page($totalsize,10);
		$page->setLinkWraper("li");
		$page->__set("PageParam", $PageParam);
		$pagetpl='{first}{prev}{liststart}{list}{listend}{next}{last}';
		$page->SetPager('default', $pagetpl, array("listlong" => "6", "first" => "首页", "last" => "尾页", "prev" => "上一页", "next" => "下一页", "list" => "*", "disabledclass" => ""));
		
		$qiushi_cats=$qiushi_cat_model->where(array("status"=>1))->select();
		$this->assign("qiushi_cats",$qiushi_cats);
		
		$join = C('DB_PREFIX').'qiushi as b on a.id =b.cid';
		$join2 = C('DB_PREFIX').'users as c on c.id =b.uid';
		$qiushis=$qiushi_cat_model->field("a.cat_name,b.*,c.user_login,c.user_nicename")->alias("a")->join($join)->join($join2)
		->where($where)
		->order("b.createtime desc")
		->limit($page->firstRow . ',' . $page->listRows)
		->select();
		
		$this->assign("pager",$page->show('default'));
		$this->assign("qiushis",$qiushis);
		
    	$this->display();
    }   

}

