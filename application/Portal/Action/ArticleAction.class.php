<?php

/**
 * 文章内页
 */
namespace Portal\Action;
use Common\Action\HomeBaseAction;
class ArticleAction extends HomeBaseAction {
    //文章内页
    public function index() {
    	$article_id=intval($_GET['id']);
    	$article=sp_sql_post($article_id,'');
    	$termid=$article['term_id'];
    	$term_obj= D("Terms");
    	$term=$term_obj->where("term_id='$termid'")->find();
    	$smeta=json_decode($article[smeta],true);
    	$content_data=sp_content_page($article['post_content']);
    	$article['post_content']=$content_data['content'];
    	$this->assign("page",$content_data['page']);
    	$this->assign($article);
    	$this->assign("smeta",$smeta);
    	$this->assign("term",$term);
    	$this->assign("article_id",$article_id);
    	
    	$tplname=$term["one_tpl"];
    	$tplname=sp_get_apphome_tpl($tplname, "article");
    	$this->display(":$tplname");
    }   
}
?>
