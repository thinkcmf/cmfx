<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace plugins\Sharebaidu;//Demo插件英文名，改成你的插件英文就行了
use Common\Lib\Plugin;

/**
 * Demo
 */
class SharebaiduPlugin extends Plugin{//Demo插件英文名，改成你的插件英文就行了

        public $info = array(
            'name'=>'Sharebaidu',//Demo插件英文名，改成你的插件英文就行了
            'title'=>'百度分享',
            'description'=>'百度分享',
            'status'=>1,
            'author'=>'M',
            'version'=>'1.0'
        );

        public function install(){//安装方法必须实现
            return true;//安装成功返回true，失败false
        }

        public function uninstall(){//卸载方法必须实现
            return true;//卸载成功返回true，失败false
        }
        
        //实现的sharebaidu钩子方法

        public function footer_end($param){
            $type =  $param['position'] ? $param['position']:right;
            $this->assign("position",$type);    	
        	$this->display('widget');
        }

    }