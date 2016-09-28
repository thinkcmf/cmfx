<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
namespace Portal\Controller;

use Common\Controller\HomebaseController;

class ArticleController extends HomebaseController {
    //文章内页
    public function index() {
    	$article_id=I('get.id',0,'intval');
    	$term_id=I('get.cid',0,'intval');
    	
    	$posts_model=M("Posts");
    	
    	$article=$posts_model
    	->alias("a")
    	->field('a.*,c.user_login,c.user_nicename,b.term_id')
    	->join("__TERM_RELATIONSHIPS__ b ON a.id = b.object_id")
		->join("__USERS__ c ON a.post_author = c.id")
		->where(array('a.id'=>$article_id,'b.term_id'=>$term_id))
		->find();
    	
    	if(empty($article)){
    	    header('HTTP/1.1 404 Not Found');
    	    header('Status:404 Not Found');
    	    if(sp_template_file_exists(MODULE_NAME."/404")){
    	        $this->display(":404");
    	    }
    	    
    	    return;
    	}
    	
    	$terms_model= M("Terms");
    	$term=$terms_model->where(array('term_id'=>$term_id))->find();
    	
    	$posts_model->save(array("id"=>$article_id,"post_hits"=>array("exp","post_hits+1")));
    	
    	$article_date=$article['post_date'];
    	
    	$join = '__POSTS__ as b on a.object_id =b.id';
    	$join2= '__USERS__ as c on b.post_author = c.id';
    	
    	$term_relationships_model= M("TermRelationships");
    	
    	$next=$term_relationships_model
    	->alias("a")
    	->join($join)->join($join2)
    	->where(array("post_date"=>array("egt",$article_date), "object_id"=>array('neq',$article_id), "a.status"=>1,'a.term_id'=>$term_id,'post_status'=>1))
    	->order("post_date asc")
    	->find();
    	
    	$prev=$term_relationships_model
    	->alias("a")
    	->join($join)->join($join2)
    	->where(array("post_date"=>array("elt",$article_date), "object_id"=>array('neq',$article_id), "a.status"=>1,'a.term_id'=>$term_id,'post_status'=>1))
    	->order("post_date desc")
    	->find();
    	
    	$this->assign("next",$next);
    	$this->assign("prev",$prev);
    	
    	$smeta=json_decode($article['smeta'],true);
    	$content_data=sp_content_page($article['post_content']);
    	$article['post_content']=$content_data['content'];
    	
    	$this->assign("page",$content_data['page']);
    	$this->assign($article);
    	$this->assign("smeta",$smeta);
    	$this->assign("term",$term);
    	$this->assign("article_id",$article_id);
    	
    	$tplname=$term["one_tpl"];
    	$tplname=empty($smeta['template'])?$tplname:$smeta['template'];
    	$tplname=sp_get_apphome_tpl($tplname, "article");
    	
    	$this->display(":$tplname");
    }
    
    public function do_like(){
    	$this->check_login();
    	
    	$id=intval($_GET['id']);//posts表中id
    	
    	$posts_model=M("Posts");
    	
    	$can_like=sp_check_user_action("posts$id",1);
    	
    	if($can_like){
    		$posts_model->save(array("id"=>$id,"post_like"=>array("exp","post_like+1")));
    		$this->success("赞好啦！");
    	}else{
    		$this->error("您已赞过啦！");
    	}
    }
    
    public function add(){
        $this->check_login();
        $this->_getTermTree();
        $this->display();
    }
    
    /**
     * 提交话题
     */
    public function add_post(){
        if(IS_POST){
            $this->check_login();

            if(empty($_POST['term'])){
                $this->error("请至少选择一个分类！");
            }
            $posts_model=M('Posts');
            $term_relationships_model=M('TermRelationships');
            
            $_POST['smeta']['thumb'] = sp_asset_relative_url($_POST['smeta']['thumb']);
            
            $_POST['post']['post_date']=date("Y-m-d H:i:s",time());
            $_POST['post']['post_modified']=date("Y-m-d H:i:s",time());
            $_POST['post']['post_author']=sp_get_current_userid();
            $article=I("post.post");
            $article['smeta']=json_encode($_POST['smeta']);
            $article['post_content']=safe_html(htmlspecialchars_decode($article['post_content']));
            if ($posts_model->field('post_date,post_author,post_content,post_title,post_modified,smeta')->create($article)!==false) {
                $result=$posts_model->add();
                if ($result) {
                    $result=$term_relationships_model->add(array("term_id"=>intval($_POST['term']),"object_id"=>$result));
                    if($result){
                        $this->success("文章添加成功！");
                    }else{
                        $posts_model->delete($result);
                        $this->error("文章添加失败！");
                    }
                
                } else {
                    $this->error("文章添加失败！");
                }
            }else{
                $this->error($posts_model->getError());
            }
            
            
        }
    
    }
    
    public function edit(){
        $this->check_login();
        $id=I("get.id",0,'intval');
        $terms_model=M('Terms');
        $posts_model=M('Posts');
        
        $term_relationship = M('TermRelationships')->where(array("object_id"=>$id,"status"=>1))->getField("term_id",true);
        $this->_getTermTree();
        $post=$posts_model->where(array('id'=>$id,'post_author'=>sp_get_current_userid()))->find();
        if(!empty($post)){
            $this->assign("post",$post);
            $this->assign("smeta",json_decode($post['smeta'],true));
            $this->display();
        }else{
            $this->error('您编辑的文章不存在！');
        }
        
    }
    
    
    public function edit_post(){
        if(IS_POST){
            $this->check_login();

            $posts_model=M('Posts');
            $term_relationships_model=M('TermRelationships');
            
            $_POST['smeta']['thumb'] = sp_asset_relative_url($_POST['smeta']['thumb']);
            $_POST['post']['post_modified']=date("Y-m-d H:i:s",time());
            $article=I("post.post");
            $article['smeta']=json_encode($_POST['smeta']);
            $article['post_content']=safe_html(htmlspecialchars_decode($article['post_content']));
            if ($posts_model->field('id,post_author,post_content,post_title,post_modified,smeta')->create($article)!==false) {
                $result=$posts_model->where(array('id'=>$article['id'],'post_author'=>sp_get_current_userid()))->save($article);
                if ($result!==false) {
                    $this->success("文章编辑成功！");
                } else {
                    $this->error("文章编辑失败！");
                }
            }else{
                $this->error($posts_model->getError());
            }
            
        }
    }
    
    private function _getTermTree($term=array()){
        $result =M('Terms')->order(array("listorder"=>"asc"))->select();
    
        $tree = new \Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        foreach ($result as $r) {
            $r['str_manage'] = '<a href="' . U("AdminTerm/add", array("parent" => $r['term_id'])) . '">添加子类</a> | <a href="' . U("AdminTerm/edit", array("id" => $r['term_id'])) . '">修改</a> | <a class="js-ajax-delete" href="' . U("AdminTerm/delete", array("id" => $r['term_id'])) . '">删除</a> ';
            $r['visit'] = "<a href='#'>访问</a>";
            $r['taxonomys'] = $this->taxonomys[$r['taxonomy']];
            $r['id']=$r['term_id'];
            $r['parentid']=$r['parent'];
            $r['selected']=in_array($r['term_id'], $term)?"selected":"";
            $r['checked'] =in_array($r['term_id'], $term)?"checked":"";
            $array[] = $r;
        }
    
        $tree->init($array);
        $str="<option value='\$id' \$selected>\$spacer\$name</option>";
        $terms = $tree->get_tree(0, $str);
        $this->assign('terms', $terms);
    }
}
