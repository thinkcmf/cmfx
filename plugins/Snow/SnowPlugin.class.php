<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace plugins\Snow;
use Common\Lib\Plugin;

/**
 * Snow
 */
class SnowPlugin extends Plugin{

        public $info = array(
            'name'=>'Snow',
            'title'=>'圣诞雪花',
            'description'=>'圣诞雪花特效',
            'status'=>1,
            'author'=>'ThinkCMF',
            'version'=>'1.0'
        );

        public function install(){//安装方法必须实现
            return true;//安装成功返回true，失败false
        }

        public function uninstall(){//卸载方法必须实现
            return true;//卸载成功返回true，失败false
        }
        
        //实现的footer钩子方法
        public function footer_end($param){
        	$config=$this->getConfig();
        	$this->assign($config);
        	$this->display('widget');
        }

    }