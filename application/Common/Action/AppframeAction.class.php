<?php
namespace Common\Action;
use Think\Action;
use Think\Controller;
/**
 * Appframe项目公共Action
 */
class AppframeAction extends Action {


    function _initialize() {
        //消除所有的magic_quotes_gpc转义
        //Input::noGPC();
        //跳转时间
        $this->assign("waitSecond", 3);
        //$this->assign("__token__", $this->getToken());
       	$time=time();
        $this->assign("js_debug",APP_DEBUG?"?v=$time":"");
        if(APP_DEBUG){
        	//sp_clear_cache();
        }
    }

    //获取表单令牌
    protected function getToken() {
        $tokenName = C('TOKEN_NAME');
        // 标识当前页面唯一性
        $tokenKey = md5($_SERVER['REQUEST_URI']);
        $tokenAray = session($tokenName);
        //获取令牌
        $tokenValue = $tokenAray[$tokenKey];
        return $tokenKey . '_' . $tokenValue;
    }

    /**
     * Ajax方式返回数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type AJAX返回数据格式
     * @return void
     */
    protected function ajaxReturn($data, $type = '') {
        if (func_num_args() > 2) {// 兼容3.0之前用法
            $args = func_get_args();
            array_shift($args);
            $info = array();
            $info['data'] = $data;
            $info['info'] = array_shift($args);
            $info['status'] = array_shift($args);
            $data = $info;
            $type = $args ? array_shift($args) : '';
        }
        //返回格式
        $return = array(
            //跳转地址
            "referer" => $data['url'] ? $data['url'] : "",
            //提示类型，success fail
            "state" => $data['status'] ? "success" : "fail",
            //提示内容
            "info" => $data['info'],
            "status" => $data['status'],
            //数据
            "data" => $data['data'],
        );
        if (empty($type))
            $type = C('DEFAULT_AJAX_RETURN');
        switch (strtoupper($type)) {
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($return));
            case 'XML' :
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit(xml_encode($return));
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $handler = isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
                exit($handler . '(' . json_encode($return) . ');');
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($return);
            case 'AJAX_UPLOAD':
            	// 返回JSON数据格式到客户端 包含状态信息
            	header('Content-Type:text/html; charset=utf-8');
            	exit(json_encode($return));
            default :
                // 用于扩展其他返回格式数据
                tag('ajax_return', $return);
        }
    }



    
    //分页
    protected function page($Total_Size = 1, $Page_Size = 0, $Current_Page = 1, $listRows = 6, $PageParam = '', $PageLink = '', $Static = FALSE) {
    	import('Page');
    	if ($Page_Size == 0) {
    		$Page_Size = C("PAGE_LISTROWS");
    	}
    	if (empty($PageParam)) {
    		$PageParam = C("VAR_PAGE");
    	}
    	$Page = new \Page($Total_Size, $Page_Size, $Current_Page, $listRows, $PageParam, $PageLink, $Static);
    	$Page->SetPager('default', '{first}{prev}&nbsp;{liststart}{list}{listend}&nbsp;{next}{last}', array("listlong" => "6", "first" => "首页", "last" => "尾页", "prev" => "上一页", "next" => "下一页", "list" => "*", "disabledclass" => ""));
    	return $Page;
    }


    /**
     * 验证码验证
     * @param type $verify 验证码
     * @param type $type 验证码类型
     * @return boolean
     */
    static public function verify($verify, $type = "verify") {
        $verifyArr = session("_verify_");
        if (!is_array($verifyArr)) {
            $verifyArr = array();
        }
        if ($verifyArr[$type] == strtolower($verify)) {
            unset($verifyArr[$type]);
            if (!$verifyArr) {
                $verifyArr = array();
            }
            session('_verify_', $verifyArr);
            return true;
        } else {
            return false;
        }
    }

    //空操作
    public function _empty() {
        $this->error('该页面不存在！');
    }
    
    /**
     * 检查操作频率
     * @param int $duration 距离最后一次操作的时长
     */
    protected function check_last_action($duration){
    	
    	$action=MODULE_NAME."-".CONTROLLER_NAME."-".ACTION_NAME;
    	$time=time();
    	
    	if(!empty($_SESSION['last_action']['action']) && $action==$_SESSION['last_action']['action']){
    		$mduration=$time-$_SESSION['last_action']['time'];
    		if($duration>$mduration){
    			$this->error("您的操作太过频繁，请稍后再试~~~");
    		}else{
    			$_SESSION['last_action']['time']=$time;
    		}
    	}else{
    		$_SESSION['last_action']['action']=$action;
    		$_SESSION['last_action']['time']=$time;
    	}
    }

}

?>
