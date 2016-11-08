<?php
/**
 * Menu(菜单管理)
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class MenuController extends AdminbaseController {

    protected $menu_model;
    protected $auth_rule_model;

    public function _initialize() {
        parent::_initialize();
        $this->menu_model = D("Common/Menu");
        $this->auth_rule_model = D("Common/AuthRule");
    }

    // 后台菜单列表
    public function index() {
    	session('admin_menu_index','Menu/index');
        $result = $this->menu_model->order(array("listorder" => "ASC"))->select();
        $tree = new \Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        
        $newmenus=array();
        foreach ($result as $m){
        	$newmenus[$m['id']]=$m;
        	 
        }
        foreach ($result as $n=> $r) {
        	
        	//$result[$n]['level'] = $this->_get_level($r['id'], $newmenus);
        	$result[$n]['parentid_node'] = ($r['parentid']) ? ' class="child-of-node-' . $r['parentid'] . '"' : '';
        	
        	$result[$n]['style'] = empty($r['parentid']) ? '' : 'display:none;';
        	
            $result[$n]['str_manage'] = '<a href="' . U("Menu/add", array("parentid" => $r['id'], "menuid" => I("get.menuid"))) . '">'.L('ADD_SUB_MENU').'</a> | <a href="' . U("Menu/edit", array("id" => $r['id'], "menuid" => I("get.menuid"))) . '">'.L('EDIT').'</a> | <a class="js-ajax-delete" href="' . U("Menu/delete", array("id" => $r['id'], "menuid" => I("get.menuid")) ). '">'.L('DELETE').'</a> ';
            $result[$n]['status'] = $r['status'] ? L('DISPLAY') : L('HIDDEN');
            if(APP_DEBUG){
            	$result[$n]['app']=$r['app']."/".$r['model']."/".$r['action'];
            }
        }

        $tree->init($result);
        $str = "<tr id='node-\$id' \$parentid_node style='\$style'>
					<td style='padding-left:20px;'><input name='listorders[\$id]' type='text' size='3' value='\$listorder' class='input input-order'></td>
					<td>\$id</td>
        			<td>\$app</td>
					<td>\$spacer\$name</td>
				    <td>\$status</td>
					<td>\$str_manage</td>
				</tr>";
        $categorys = $tree->get_tree(0, $str);
        $this->assign("categorys", $categorys);
        $this->display();
    }
    
    /**
     * 获取菜单深度
     * @param $id
     * @param $array
     * @param $i
     */
    protected function _get_level($id, $array = array(), $i = 0) {
    
    	if ($array[$id]['parentid']==0 || empty($array[$array[$id]['parentid']]) || $array[$id]['parentid']==$id){
    		return  $i;
    	}else{
    		$i++;
    		return $this->_get_level($array[$id]['parentid'],$array,$i);
    	}
    
    }
    
    // 后台所有菜单列表
    public function lists(){
    	session('admin_menu_index','Menu/lists');
    	$result = $this->menu_model->order(array("app" => "ASC","model" => "ASC","action" => "ASC"))->select();
    	$this->assign("menus",$result);
    	$this->display();
    }

    // 后台菜单添加
    public function add() {
    	$tree = new \Tree();
    	$parentid = I("get.parentid",0,'intval');
    	$result = $this->menu_model->order(array("listorder" => "ASC"))->select();
    	foreach ($result as $r) {
    		$r['selected'] = $r['id'] == $parentid ? 'selected' : '';
    		$array[] = $r;
    	}
    	$str = "<option value='\$id' \$selected>\$spacer \$name</option>";
    	$tree->init($array);
    	$select_categorys = $tree->get_tree(0, $str);
    	$this->assign("select_categorys", $select_categorys);
    	$this->display();
    }
    
    // 后台菜单添加提交
    public function add_post() {
    	if (IS_POST) {
    		if ($this->menu_model->create()!==false) {
    			if ($this->menu_model->add()!==false) {
    				$app=I("post.app");
    				$model=I("post.model");
    				$action=I("post.action");
    				$name=strtolower("$app/$model/$action");
    				$menu_name=I("post.name");
    				$mwhere=array("name"=>$name);
    				
    				$find_rule_count=$this->auth_rule_model->where($mwhere)->count();
    				if(empty($find_rule_count)){
    					$this->auth_rule_model->add(array("name"=>$name,"module"=>$app,"type"=>"admin_url","title"=>$menu_name));//type 1-admin rule;2-user rule
    				}
    				$session_admin_menu_index=session('admin_menu_index');
    				$to=empty($session_admin_menu_index)?"Menu/index":$session_admin_menu_index;
    				$this->_export_app_menu_default_lang($app);
    				$this->success("添加成功！", U($to));
    			} else {
    				$this->error("添加失败！");
    			}
    		} else {
    			$this->error($this->menu_model->getError());
    		}
    	}
    }

    // 后台菜单删除
    public function delete() {
        $id = I("get.id",0,'intval');
        $count = $this->menu_model->where(array("parentid" => $id))->count();
        if ($count > 0) {
            $this->error("该菜单下还有子菜单，无法删除！");
        }
        if ($this->menu_model->delete($id)!==false) {
            $this->success("删除菜单成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    // 后台菜单编辑
    public function edit() {
        $tree = new \Tree();
        $id = I("get.id",0,'intval');
        $rs = $this->menu_model->where(array("id" => $id))->find();
        $result = $this->menu_model->order(array("listorder" => "ASC"))->select();
        foreach ($result as $r) {
        	$r['selected'] = $r['id'] == $rs['parentid'] ? 'selected' : '';
        	$array[] = $r;
        }
        $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
        $tree->init($array);
        $select_categorys = $tree->get_tree(0, $str);
        $this->assign("data", $rs);
        $this->assign("select_categorys", $select_categorys);
        $this->display();
    }
    
    // 后台菜单编辑提交
    public function edit_post() {
    	if (IS_POST) {
    	    $id = I('post.id',0,'intval');
    	    $old_menu=$this->menu_model->where(array('id'=>$id))->find();
    		if ($this->menu_model->create()!==false) {
    			if ($this->menu_model->save() !== false) {
    				$app=I("post.app");
    				$model=I("post.model");
    				$action=I("post.action");
    				$name=strtolower("$app/$model/$action");
    				$menu_name=I("post.name");
    				$mwhere=array("name"=>$name);
    				
    				$find_rule_count=$this->auth_rule_model->where($mwhere)->count();
    				if(empty($find_rule_count)){
    				    $old_app=$old_menu['app'];
    				    $old_model=$old_menu['model'];
    				    $old_action=$old_menu['action'];
    				    $old_name=strtolower("$old_app/$old_model/$old_action");
    				    $find_old_rule_id=$this->auth_rule_model->where(array("name"=>$old_name))->getField('id');
    				    if(empty($find_old_rule_id)){
    				        $this->auth_rule_model->add(array("name"=>$name,"module"=>$app,"type"=>"admin_url","title"=>$menu_name));//type 1-admin rule;2-user rule
    				    }else{
    				        $this->auth_rule_model->where(array('id'=>$find_old_rule_id))->save(array("name"=>$name,"module"=>$app,"type"=>"admin_url","title"=>$menu_name));//type 1-admin rule;2-user rule
    				    }
    				}else{
    					$this->auth_rule_model->where($mwhere)->save(array("name"=>$name,"module"=>$app,"type"=>"admin_url","title"=>$menu_name));//type 1-admin rule;2-user rule
    				}
    				$this->_export_app_menu_default_lang($app);
    				$this->success("更新成功！");
    			} else {
    				$this->error("更新失败！");
    			}
    		} else {
    			$this->error($this->menu_model->getError());
    		}
    	}
    }

    // 后台菜单排序
    public function listorders() {
        $status = parent::_listorders($this->menu_model);
        if ($status) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序更新失败！");
        }
    }
    
    // 后台菜单备份
    public function backup_menu(){
    	$menus=$this->menu_model->get_menu_tree(0);
    	
    	$menus_str= var_export($menus,true);
    	$menus_str=preg_replace("/\s+\d+\s=>\s(\n|\r)/", "\n", $menus_str);

    	foreach ($menus as $m){
    		$app=$m['app'];
    		$menudir=SPAPP.$app."/Menu";
    		if(!file_exists($menudir)){
    			mkdir($menudir);
    		}
    		$model=strtolower($m['model']);
    		
    		$menus_str= var_export($m,true);
    		$menus_str=preg_replace("/\s+\d+\s=>\s(\n|\r)/", "\n", $menus_str);
    		
    		file_put_contents($menudir."/admin_$model.php", "<?php\nreturn $menus_str;");
    		
    	}
    	$this->success('菜单备份成功！');
    }
    
    /**
     *  导出后台菜单语言包
     * @param string $app
     */
    private function _export_app_menu_default_lang($app){
        $menus = $this->menu_model->where(array("app"=>$app))->order(array("listorder"=>"ASC","app" => "ASC","model" => "ASC","action" => "ASC"))->select();
        $lang_dir=C('DEFAULT_LANG');
        $admin_menu_lang_file_default=SITE_PATH."data/lang/$app/Lang/".$lang_dir."/admin_menu.php";
        	
        if(!empty($admin_menu_lang_file_default) && !file_exists_case(dirname($admin_menu_lang_file_default))){
            mkdir(dirname($admin_menu_lang_file_default),0777,true);
        }
        
        $lang=array();
        
        foreach ($menus as $menu){
            $lang_key=strtoupper($menu['app'].'_'.$menu['model'].'_'.$menu['action']);
            $lang[$lang_key]=$menu['name'];
        }
        
        $lang_str= var_export($lang,true);
        $lang_str=preg_replace("/\s+\d+\s=>\s(\n|\r)/", "\n", $lang_str);
        
        if(!empty($admin_menu_lang_file_default)){
            file_put_contents($admin_menu_lang_file_default, "<?php\nreturn $lang_str;");
        }
    }
    
    // 导出后台菜单语言
    public function export_menu_lang(){
    	$apps=sp_scan_dir(SPAPP."*",GLOB_ONLYDIR);
    	$default_lang=C('DEFAULT_LANG');
    	foreach ($apps as $app){
    		if(is_dir(SPAPP.$app)){
    			$lang_dirs=sp_scan_dir(SPAPP."$app/Lang/*",GLOB_ONLYDIR);
    			
    			if(empty($lang_dirs)) continue;
    			
    			$menus = $this->menu_model->where(array("app"=>$app))->order(array("listorder"=>"ASC","app" => "ASC","model" => "ASC","action" => "ASC"))->select();
    			
    			foreach ($lang_dirs as $lang_dir){
    			    $admin_menu_lang_file=SPAPP.$app."/Lang/".$lang_dir."/admin_menu.php";
    			    $admin_menu_lang_file_default='';
    			    if($default_lang==$lang_dir){
    			        $admin_menu_lang_file_default=SITE_PATH."data/lang/$app/Lang/".$lang_dir."/admin_menu.php";
    			    }
    			    
    			    if(!file_exists_case(dirname($admin_menu_lang_file))){
    			        mkdir(dirname($admin_menu_lang_file),0777,true);
    			    }
    			    
    			    if(!empty($admin_menu_lang_file_default) && !file_exists_case(dirname($admin_menu_lang_file_default))){
    			        mkdir(dirname($admin_menu_lang_file_default),0777,true);
    			    }
    				
    				$lang=array();
    				
    				if($lang_dir!=$default_lang && is_file($admin_menu_lang_file)){
    				     $lang=include $admin_menu_lang_file;
    				}
    				
    				foreach ($menus as $menu){
    					$lang_key=strtoupper($menu['app'].'_'.$menu['model'].'_'.$menu['action']);
    					
    					if($lang_dir==$default_lang){
    						$lang[$lang_key]=$menu['name'];
    					}else if(!isset($lang[$lang_key])){
    					    $lang[$lang_key]=$menu['name'];
    					}
    				}
    				
    				$lang_str= var_export($lang,true);
    				$lang_str=preg_replace("/\s+\d+\s=>\s(\n|\r)/", "\n", $lang_str);
    		      
    				file_put_contents($admin_menu_lang_file, "<?php\nreturn $lang_str;");
    				if(!empty($admin_menu_lang_file_default)){
    				    file_put_contents($admin_menu_lang_file_default, "<?php\nreturn $lang_str;");
    				}
    			}
    			
    		}
    		
    	}
    	$this->success('生成菜单语言包已经完成！');
    }
    
    /* public function dev_import_menu(){
    	$menus=F("Menu");
    	if(!empty($menus)){
    		$table_menu=C('DB_PREFIX')."menu";
    		$this->menu_model->execute("TRUNCATE TABLE $table_menu;");
    		 
    		foreach($menus as $menu){
    			$this->menu_model->add($menu);
    		}
    	}
    	
    	$this->display();
    } */
    
    // 导入后台菜单
    private function _import_menu($menus,$parentid=0,&$error_menus=array()){
    	foreach ($menus as $menu){
    	
    		$app=$menu['app'];
    		$model=$menu['model'];
    		$action=$menu['action'];
    			
    		$where['app']=$app;
    		$where['model']=$model;
    		$where['action']=$action;
    		$children=isset($menu['children'])?$menu['children']:false;
    		unset($menu['children']);
    		$find_menu=$this->menu_model->where($where)->find();
    		if($find_menu){
    			$newmenu=array_merge($find_menu,$menu);
    			$result=$this->menu_model->save($newmenu);
    			if($result===false){
    				$error_menus[]="$app/$model/$action";
    				$parentid2=false;
    			}else{
    				$parentid2=$find_menu['id'];
    			}
    		}else{
    			$menu['parentid']=$parentid;
    			$result=$this->menu_model->add($menu);
    			if($result===false){
    				$error_menus[]="$app/$model/$action";
    				$parentid2=false;
    			}else{
    				$parentid2=$result;
    			}
    		}
    		
    		$name=strtolower("$app/$model/$action");
    		$mwhere=array("name"=>$name);
    		
    		$find_rule=$this->auth_rule_model->where($mwhere)->find();
    		if(!$find_rule){
    			$this->auth_rule_model->add(array("name"=>$name,"module"=>$app,"type"=>"admin_url","title"=>$menu['name']));//type 1-admin rule;2-user rule
    		}else{
    			$this->auth_rule_model->where($mwhere)->save(array("module"=>$app,"type"=>"admin_url","title"=>$menu['name']));//type 1-admin rule;2-user rule
    		}
    		
    		if($children && $parentid!==false){
    			$this->_import_menu($children,$parentid2,$error_menus);
    		}
    	}
    	
    }
    
    // 还原菜单
    public function restore_menu(){
    	
    	$apps=sp_scan_dir(SPAPP."*",GLOB_ONLYDIR);
    	$error_menus=array();
    	foreach ($apps as $app){
    		if(is_dir(SPAPP.$app)){
    			$menudir=SPAPP.$app."/Menu";
    			$menu_files=sp_scan_dir($menudir."/admin_*.php",null);
    			if(count($menu_files)){
    				foreach ($menu_files as $mf){
    					//是php文件
    					$mf_path=$menudir."/$mf";
    					if(file_exists($mf_path)){
    						$menudatas=include   $mf_path;
    						if(is_array($menudatas) && !empty($menudatas)){
    							$this->_import_menu(array($menudatas),0,$error_menus);
    						}
    					}
    						
    						
    				}
    			}
    			 
    		}
    	}
    	if (empty($error_menus)){
    	    $this->success('菜单恢复成功！');
    	}else{
    	    $this->error('菜单恢复失败：'.implode(',', $error_menus));
    	}
    }
    
    private function _import_submenu($submenus,$parentid){
    	foreach($submenus as $sm){
    		$data=$sm;
    		$data['parentid']=$parentid;
    		unset($data['items']);
    		$id=$this->menu_model->add($data);
    		if(!empty($sm['items'])){
    				$this->_import_submenu($sm['items'],$id);
    		}else{
    			return;
    		}
    	}
    }
    
    private function _generate_submenu(&$rootmenu,$m){
    	$parentid=$m['id'];
    	$rm=$this->menu_model->menu($parentid);
    	unset($rootmenu['id']);
    	unset($rootmenu['parentid']);
    	if(count($rm)){
    		
    		$count=count($rm);
    		for($i=0;$i<$count;$i++){
    			$this->_generate_submenu($rm[$i],$rm[$i]);
    			
    		}
    		$rootmenu['items']=$rm;
    		
    	}else{
    		return ;
    	}
    	
    }
    
    // 导入新菜单
    public function getactions(){
    	$apps_r=array("Comment");
    	$groups=C("MODULE_ALLOW_LIST");
    	$group_count=count($groups);
    	$newmenus=array();
    	$g=I("get.app");
    	if(empty($g)){
    		$g=$groups[0];
    	}
    	
    	if(in_array($g, $groups)){
    		if(is_dir(SPAPP.$g)){
    			if(!(strpos($g, ".") === 0)){
    				$actiondir=SPAPP.$g."/Controller";
    				$actions=sp_scan_dir($actiondir."/*");
    				if(count($actions)){
    					foreach ($actions as $mf){
    						if(!(strpos($mf, ".") === 0)){
    							if($g=="Admin"){
    								$m=str_replace("Controller.class.php", "",$mf);
    								$noneed_models=array("Public","Index","Main");
    								if(in_array($m, $noneed_models)){
    									continue;
    								}
    							}else{
    								if(strpos($mf,"adminController.class.php") || strpos($mf,"Admin")===0){
    									$m=str_replace("Controller.class.php", "",$mf);
    								}else{
    									continue;
    								}
    							}
    							$class=A($g."/".$m);
    							$adminbaseaction=new \Common\Controller\AdminbaseController();
    							$base_methods=get_class_methods($adminbaseaction);
    							$methods=get_class_methods($class);
    							$methods=array_diff($methods, $base_methods);
    							
    							foreach ($methods as $a){
    								if(!(strpos($a, "_") === 0) && !(strpos($a, "spmy_") === 0)){
    									$where['app']=$g;
    									$where['model']=$m;
    									$where['action']=$a;
    									$count=$this->menu_model->where($where)->count();
    									if(!$count){
    										$data['parentid']=0;
    										$data['app']=$g;
    										$data['model']=$m;
    										$data['action']=$a;
    										$data['type']="1";
    										$data['status']="0";
    										$data['name']="未知";
    										$data['listorder']="0";
    										$result=$this->menu_model->add($data);
    										if($result!==false){
    											$newmenus[]=   $g."/".$m."/".$a."";
    										}
    									}
    									
    									$name=strtolower("$g/$m/$a");
    									$mwhere=array("name"=>$name);
    									
    									$find_rule=$this->auth_rule_model->where($mwhere)->find();
    									if(!$find_rule){
    										$this->auth_rule_model->add(array("name"=>$name,"module"=>$g,"type"=>"admin_url","title"=>""));//type 1-admin rule;2-user rule
    									}
    								}
    							}
    						}
    						 
    		
    					}
    				}
    			}
    		}
    		
    		$index=array_search($g, $groups);
    		$nextindex=$index+1;
    		$nextindex=$nextindex>=$group_count?0:$nextindex;
    		if($nextindex){
    			$this->assign("nextapp",$groups[$nextindex]);
    		}
    		$this->assign("app",$g);
    	}
    	 
    	$this->assign("newmenus",$newmenus);
    	$this->display("getactions");
    	
    }

}
