<?php
namespace Qiushi\Controller;
use Common\Controller\HomeBaseController; 
/**
 * 首页
 */
class IndexController extends HomeBaseController {
	
    //首页
	public function index() {
		$qiushi_cat_model=M("QiushiCat");
		
		$join = C('DB_PREFIX').'qiushi as b on a.id =b.cid';
		$join2 = C('DB_PREFIX').'users as c on c.id =b.uid';
		
		$where=array("b.status"=>1,"a.status"=>1);
		
		$catid=I("get.cat",0,"intval");
		
		if(!empty($catid)){
			$where['a.id']=$catid;
		}
		
		$order=array("b.istop"=>"desc");
		
		$sort=I("get.sort",0,"intval");
		
		if(empty($sort)){
			$order['b.createtime']="desc";
		}else{
			$sort==1?$order['b.last_comment']="desc":"";//按最新回复
			if($sort==2){//精华
				$where['b.star']=array("gt",0);
				$order['b.createtime']="desc";
			}
		}
		
		$totalsize=$qiushi_cat_model->alias("a")->join($join)->where($where)->count();
		
		import('Page');
		$PageParam = C("VAR_PAGE");
		$page = new \Page($totalsize,15);
		$page->setLinkWraper("li");
		$page->__set("PageParam", $PageParam);
		$pagetpl='{first}{prev}{liststart}{list}{listend}{next}{last}';
		$page->SetPager('default', $pagetpl, array("listlong" => "6", "first" => "首页", "last" => "尾页", "prev" => "上一页", "next" => "下一页", "list" => "*", "disabledclass" => ""));
		
		
		$qiushis=$qiushi_cat_model->field("a.cat_name,b.*,c.user_login,c.user_nicename")->alias("a")->join($join)->join($join2)
		->where($where)
		->order($order)
		->limit($page->firstRow . ',' . $page->listRows)
		->select();
		
		$this->assign("pager",$page->show('default'));
		
		$qiushi_cats=$qiushi_cat_model->where(array("status"=>1))->order("listorder asc")->select();
		
		$this->assign("qiushi_cats",$qiushi_cats);
		
		$catid=I("get.cat",0,"intval");
		if(!empty($catid)){
			$qiushi_cat=$qiushi_cat_model->where(array("id"=>$catid))->find();
			$this->assign("qiushi_cat",$qiushi_cat);
		}
		
		
		$this->assign("qiushis",$qiushis);
		
		$this->display(":index");
    }   

}

