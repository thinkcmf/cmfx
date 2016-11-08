<?php
namespace Install\Controller;
use Think\Controller;
use Think\Db;
/**
 * 首页
 */
class IndexController extends Controller {
	
    function _initialize(){
        if(file_exists_case("./data/install.lock")){
            redirect(__ROOT__."/");
        }
    }
    //首页
	public function index() {
    	$this->display(":index");
    }
    
    public function step2(){
        if(file_exists_case('data/conf/config.php')){
            @unlink('data/conf/config.php');
        }
        $data=array();
        $data['phpversion'] = @ phpversion();
        $data['os']=PHP_OS;
        $tmp = function_exists('gd_info') ? gd_info() : array();
        $server = $_SERVER["SERVER_SOFTWARE"];
        $host = (empty($_SERVER["SERVER_ADDR"]) ? $_SERVER["SERVER_HOST"] : $_SERVER["SERVER_ADDR"]);
        $name = $_SERVER["SERVER_NAME"];
        $max_execution_time = ini_get('max_execution_time');
        $allow_reference = (ini_get('allow_call_time_pass_reference') ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
        $allow_url_fopen = (ini_get('allow_url_fopen') ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
        $safe_mode = (ini_get('safe_mode') ? '<font color=red>[×]On</font>' : '<font color=green>[√]Off</font>');
        
        $err = 0;
        if (empty($tmp['GD Version'])) {
            $gd = '<font color=red>[×]Off</font>';
            $err++;
        } else {
            $gd = '<font color=green>[√]On</font> ' . $tmp['GD Version'];
        }
        
        if (class_exists('pdo')) {
            $data['pdo'] = '<i class="fa fa-check correct"></i> 已开启';
        } else {
            $data['pdo'] = '<i class="fa fa-remove error"></i> 未开启';
            $err++;
        }
        
        if (extension_loaded('pdo_mysql')) {
            $data['pdo_mysql'] = '<i class="fa fa-check correct"></i> 已开启';
        } else {
            $data['pdo_mysql'] = '<i class="fa fa-remove error"></i> 未开启';
            $err++;
        }
        
        if (extension_loaded('curl')) {
            $data['curl'] = '<i class="fa fa-check correct"></i> 已开启';
        } else {
            $data['curl'] = '<i class="fa fa-remove error"></i> 未开启';
            $err++;
        }
        
        if (extension_loaded('gd')) {
            $data['gd'] = '<i class="fa fa-check correct"></i> 已开启';
        } else {
            $data['gd'] = '<i class="fa fa-remove error"></i> 未开启';
            if (function_exists('imagettftext')) {
                $data['gd'].='<br><i class="fa fa-remove error"></i> FreeType Support未开启';
            }
            $err++;
        }
        
        if (extension_loaded('mbstring')) {
            $data['mbstring'] = '<i class="fa fa-check correct"></i> 已开启';
        } else {
            $data['mbstring'] = '<i class="fa fa-remove error"></i> 未开启';
            if (function_exists('imagettftext')) {
                $data['mbstring'].='<br><i class="fa fa-remove error"></i> FreeType Support未开启';
            }
            $err++;
        }
        
        if (ini_get('file_uploads')) {
            $data['upload_size'] = '<i class="fa fa-check correct"></i> ' . ini_get('upload_max_filesize');
        } else {
            $data['upload_size'] = '<i class="fa fa-remove error"></i> 禁止上传';
        }
        
        if (function_exists('session_start')) {
            $data['session'] = '<i class="fa fa-check correct"></i> 支持';
        } else {
            $data['session'] = '<i class="fa fa-remove error"></i> 不支持';
            $err++;
        }
        
        $folders = array(
            'data',
            'data/conf',
            'data/runtime',
            'data/runtime/Cache',
            'data/runtime/Data',
            'data/runtime/Logs',
            'data/runtime/Temp',
            'data/upload',
        );
        $new_folders=array();
        foreach($folders as $dir){
            $Testdir = "./".$dir;
            sp_dir_create($Testdir);
            if(sp_testwrite($Testdir)){
                $new_folders[$dir]['w']=true;
            }else{
                $new_folders[$dir]['w']=false;
                $err++;
            }
            if(is_readable($Testdir)){
                $new_folders[$dir]['r']=true;
            }else{
                $new_folders[$dir]['r']=false;
                $err++;
            }
        }
        $data['folders']=$new_folders;
        
        $this->assign($data);
    	$this->display(":step2");
    }
    
    public function step3(){
    	$this->display(":step3");
    }
    
    public function step4(){
        if(IS_POST){
            //创建数据库
            $dbconfig['DB_TYPE']="mysql";
            $dbconfig['DB_HOST']=I('post.dbhost');
            $dbconfig['DB_USER']=I('post.dbuser');
            $dbconfig['DB_PWD']=I('post.dbpw');
            $dbconfig['DB_PORT']=I('post.dbport');
            $db  = Db::getInstance($dbconfig);
            $dbname=strtolower(I('post.dbname'));
            $sql = "CREATE DATABASE IF NOT EXISTS `{$dbname}` DEFAULT CHARACTER SET utf8";
            $db->execute($sql) || $this->error($db->getError());
            
            $this->display(":step4");
            
            //创建数据表
            $dbconfig['DB_NAME']=$dbname;
            $dbconfig['DB_PREFIX']=trim(I('post.dbprefix'));
            $db  = Db::getInstance($dbconfig);
            
            $table_prefix=I("post.dbprefix");
            sp_execute_sql($db, "thinkcmf.sql", $table_prefix);

            //更新配置信息
            sp_update_site_configs($db, $table_prefix);
            
            $authcode=sp_random_string(18);
            //创建管理员
            sp_create_admin_account($db, $table_prefix,$authcode);
            
            //生成网站配置文件
            sp_create_config($dbconfig, $authcode);
            session("_install_step",4);
            sleep(1);
            $this->redirect("step5");
        }else{
            exit;
        }
    }
    
    public function step5(){
        if(session("_install_step")==4){
            @touch('./data/install.lock');
            $this->display(":step5");
        }else{
            $this->error("非法安装！");
        }
            
    	
    }
    
    public function testdbpwd(){
        if(IS_POST){
            $dbconfig=I("POST.");
            $dbconfig['DB_TYPE']="mysql";
            $db  = Db::getInstance($dbconfig);
            try{
               $db->query("show databases;");
            }catch (\Exception $e){
                die("");
            }
            exit("1");
        }else{
            exit("need post!");
        }
        
    }

}

