<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace plugins\Comment;//Comment插件英文名，改成你的插件英文就行了
use Common\Lib\Plugin;

/**
 * Comment
 */
class CommentPlugin extends Plugin{//Comment插件英文名，改成你的插件英文就行了

        public $info = array(
            'name'=>'Comment',//Comment插件英文名，改成你的插件英文就行了
            'title'=>'系统评论插件',
            'description'=>'系统评论插件',
            'status'=>1,
            'author'=>'ThinkCMF',
            'version'=>'1.0'
        );
        
        public $has_admin=0;//插件是否有后台管理界面

        public function install(){//安装方法必须实现
            return true;//安装成功返回true，失败false
        }

        public function uninstall(){//卸载方法必须实现
            return true;//卸载成功返回true，失败false
        }
        
        //实现的comment钩子方法
        public function comment($param){
            echo Comments($param['post_table'],$param['post_id'],array('post_title'=>$param['post_title']));
        }

    }