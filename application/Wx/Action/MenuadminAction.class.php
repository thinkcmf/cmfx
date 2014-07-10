<?php
namespace Wx\Action;
use Common\Action\AdminbaseAction;
class MenuadminAction extends AdminbaseAction {
	
	protected $Menu;
	
	function _initialize() {
		parent::_initialize();
		$this->Menu = D("WxMenu");
	}
	
	/**
     *  显示微信菜单
     */
    public function index() {
        $result = $this->Menu->where('status=1')->order(array("listorder" => "ASC"))->select();
        import("Tree");
        $tree = new \Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        foreach ($result as $r) {
            $r['str_manage'] = '<a href="' . U("Menuadmin/edit", array("id" => $r['menu_id'])) . '">修改</a> | <a class="J_ajax_del" href="' . U("Menuadmin/delete", array("id" => $r['menu_id'])) . '">删除</a> ';
            $r['id'] = $r['menu_id'];
            $array[] = $r;
        }
        
        $tree->init($array);
        $str = "<tr>
					<td><input name='listorders[\$menu_id]' type='text' size='3' value='\$listorder' class='input input-order'></td>
					<td>\$menu_id</td>
					<td >\$spacer\$menu_name</td>
				    <td>\$menu_type</td>
				    <td>\$view_url</td>
				    <td>\$event_key</td>
					<td>\$str_manage</td>
				</tr>"; 
        $categorys = $tree->get_tree(0, $str);
        
        $this->assign("categorys", $categorys);
        $this->display();
    }
    
    //生成微信菜单
    function createmenu(){
    	$accessToken = self::_getAccessToken();
    	
    	$postUrl = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$accessToken;
    	//菜单格式
    	import("Curl");
    	$curl = new \Curl();
    	
    	$rst = $curl->execute('post', $postUrl, self::_getMenuFormat());
    	$rst_arr = json_decode($rst, true);
    	if($rst_arr['errcode']==0){
    		$this->success('菜单更新成功！重新关注该公众号查看效果。');
    	}else{
    		include_once C('WX_PATH').'api/api.returnCode.php';
    		$this->error($errorCode[$rst_arr['errcode']]);
    	}
    }
    
    //格式化菜单
    private function _getMenuFormat(){
    	$menuTab = $this->Menu;
    	$result = $menuTab->where('status=1 and parentid=0')->order(array("listorder" => "ASC"))->select();
    	$data['button'] = array();
    	foreach($result as $k=>$v){
    		$f = array();
    		$children = $menuTab->where('parentid='.$v['menu_id'])->order(array("listorder" => "ASC"))->select();
    		if(!empty($children)){
    			$childArr = array();
    			foreach($children as $ck=>$cv){
    				$x = array(
    						'type' => $cv['menu_type'],
    						'name' => $cv['menu_name'],
    				);
    				$cv['menu_type']=='click' ? $x['key']=$cv['event_key'] : $x['url']=$cv['view_url'];
    				array_push($childArr, $x);
    			}
    			$f = array(
    					'name' => $v['menu_name'],
    					'sub_button' => $childArr,
    			);
    		}else{
    			$f = array(
    					'type' => $v['menu_type'],
    					'name' => $v['menu_name'],
    			);
    			$v['menu_type']=='click' ? $f['key']=$v['event_key'] : $f['url']=$v['view_url'];
    		}
    		array_push($data['button'], $f);
    	}
    	//正则替换\uxx成为中文
    	$dataStr = preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))", json_encode($data));
    	return $dataStr;
    }
    
    //获取access_token
    function _getAccessToken(){
	    //实例化数据库类并连接数据库
	    $wxConfig = M('wxConfig');
	    $accessToken = $wxConfig->where("_key='WX_ACCESS_TOKEN'")->getField('_value');
	    $access = $accessToken ? json_decode($access, true) : array();
	    
	    if($access['expires_in'] < time()){ //已经过期
	    	$appid = $wxConfig->where("_key='WX_APPID'")->getField('_value');
	    	$appsecret = $wxConfig->where("_key='WX_APPSECRET'")->getField('_value');
	    	import("Curl");
	    	$curl = new \Curl();
	    	$access = $curl->execute('get', 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret);
	    	$access_arr = json_decode($access, true);
	    	$access_arr['expires_in'] += time();
	    	$data = array(
	    			'_key' => 'WX_ACCESS_TOKEN',
	    			'_value' => json_encode($access_arr),
	    	);
	    	//保存
	    	if(empty($accessToken)){ //需要创建
	    		$wxConfig->add($data);
	    	}else{ //需要更新
	    		$wxConfig->where("_key='WX_ACCESS_TOKEN'")->save($data);
	    	}
	    	return $access_arr['access_token'];
	    }else{
	    	return $access['access_token'];
	    }
    }
    
    //添加菜单
    function add(){
    	$this->assign('navs', $this->_select());
    	$this->display();
    }
    
    function add_post(){
    		$data['parentid'] = intval($_POST['parentid']);
    		$data['menu_name'] = $_POST['name'];
    		$data['menu_type'] = $_POST['type'];
    		$data['view_url'] = $_POST['url'] ? $_POST['url'] : '';
    		$data['event_key'] = $_POST['_key'] ? $_POST['_key'] : '';
    		$data['status'] = 1;
    		if(strlen($data['menu_name'])<1){
    			$this->error('显示名称必填！');
    		}
    		if ($this->Menu->add($data)) {
    			$this->success("添加成功！", U("Menuadmin/index"));
    		} else {
    			$this->error("添加失败！");
    		}
    }
    
    /**
     * 排序
     */
    public function listorders() {
    	$status = parent::_listorders($this->Menu);
    	if ($status) {
    		$this->success("排序更新成功！");
    	} else {
    		$this->error("排序更新失败！");
    	}
    }
    
    /**
     *  删除
     */
    public function delete() {
    	$id = intval(I("get.id"));
    	$count = $this->Menu->where(array("parentid" => $id))->count();
    	if ($count > 0) {
    		$this->error("该菜单下还有子菜单，无法删除！");
    	}
    	if ($this->Menu->delete($id)!==false) {
    		$this->success("删除菜单成功！");
    	} else {
    		$this->error("删除失败！");
    	}
    }
    
    /**
     *  编辑
     */
    public function edit() {
    		$id = intval($_GET["id"]);
    		$result = $this->Menu->where("menu_id=$id")->find();
    		$this->assign("rst", $result);
    		$menu_list = $this->Menu->where('status=1 and parentid=0')->select();
    		$this->assign("menu_list", $menu_list);
    		$this->assign('navs', $this->_select());
    		$this->display();
    }
    
    function edit_post(){
    	$data['parentid'] = intval($_POST['parentid']);
    	$data['menu_name'] = $_POST['name'];
    	$data['menu_type'] = $_POST['type'];
    	$data['view_url'] = $_POST['url'] ? $_POST['url'] : '';
    	$data['event_key'] = $_POST['_key'] ? $_POST['_key'] : '';
    	
    	if ($this->Menu->where('menu_id='.$_POST['menu_id'])->save($data)) {
    		$this->success("保存成功！", U("Menuadmin/index"));
    	} else {
    		$this->error("保存失败！");
    	}
    }
    
    //选择菜单
    private function _select(){
    	$apps = scandir(SPAPP);
    	$host = (is_ssl() ? 'https' : 'http')."://".$_SERVER['HTTP_HOST'];
    	$navs = array();
    	foreach ($apps as $a){
    		if(is_dir(SPAPP.$a)){
    			if(!(strpos($a, ".") === 0)){
    				$navfile = SPAPP.$a."/nav.php";
    				$app=$a;
    				if(file_exists($navfile)){
    					$navgeturls=include $navfile;
    					
    					foreach ($navgeturls as $url){
    						//echo U("$app/$url");
    						$nav = file_get_contents($host.U("$app/$url"));
    						$nav = json_decode($nav,true);
    						$navs[] = $nav;
    					}
    				}
    					
    			}
    		}
    	}
    	return $navs;
    }
}