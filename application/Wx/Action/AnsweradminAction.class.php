<?php

/**
 * 自动回复设置
 */
namespace Wx\Action;
use Common\Action\AdminbaseAction;
class AnsweradminAction extends AdminbaseAction {
    function index(){
    	$this->display();
    }
    
    //设置默认回复和欢迎词
    function index_post(){
    	wx_val($_POST);
    	$this->success('更新成功!');
    }
    
    function fixed(){
    	$rst = M('WxAnswer')->select();
		$this->assign('result', $rst);
		$this->display();
    }
    
    //固定回答设置
    function fixed_post(){
    	$k=trim($_POST['k']); $v=trim($_POST['v']); $id=intval($_POST['id']);
    	$ans = M('WxAnswer');
    	//设置或修改
    	if(!empty($k) && !empty($v)){
    		if($ans->where("_key='{$k}' and _value='{$v}'")->count()>0){
    			$this->ajaxReturn('1','您没有做任何更改！',1);
    		}else if($ans->where("_key='{$k}'")->count()>0){
    			$ans->where("_key='{$k}'")->setField('_value',$v);
    			$this->ajaxReturn('1','修改成功！',1);
    		}else{
    			$data=array(
    					'_key' => $k,
    					'_value' => $v,
    			);
    			$ans->add($data);
    			$this->ajaxReturn('1','成功添加项！',1);
    		}
    		//删除项
    	}else if(!empty($id)){
    		$id_arr = split(',',$id);
    		foreach($id_arr as $k=>$v){
    			$ans->where("id=".$v)->delete();
    		}
    		$this->ajaxReturn('1','删除成功！',1);
    	}
    }	

	//机器人问题集
	function robot(){
		$rst = M('WxAnswerRobot')->select();
		$this->assign('result', $rst);
		$this->display();
	}
	//机器人数据
	function robot_post(){
		$k=trim($_POST['question']); $v=trim($_POST['answer']); $id=intval($_POST['id']);
		$ans = M('WxAnswerRobot');
		//设置或修改
		if(!empty($k) && !empty($v)){
			$data=array(
					'question' => $k,
					'answer' => $v,
					'key1' 	=> $_POST['key1'],
					'key2' 	=> $_POST['key2'],
					'key3' 	=> $_POST['key3'],
			);
			if($ans->where("question='{$k}'")->count()>0){
				$ans->where("question='{$k}'")->save($data);
				$this->ajaxReturn('1','修改成功！',1);
			}else{
				$ans->add($data);
				$this->ajaxReturn('1','成功添加项！',1);
			}
			//删除项
		}else if(!empty($id)){
			$ans->where("id=".$id)->delete();
			$this->ajaxReturn('1','删除成功！',1);
		}
	}
	
	//分词并返回数组
	function split(){
		$str = $_POST['words'];
		include_once C('WX_PATH').'handle/modules/splitwords/index.php';
		$result='';
		if(!empty($str)){
			$arr = splitWords($str);
			foreach($arr as $k=>$v){
				$result .= '<span onclick="fill_keys(this)">'.$v.'</span>';
			}
			$this->ajaxReturn('1', $result,1);
		}else{
			$this->ajaxReturn('0', '',0);
		}
	}
}
?>
