<?php
// +---------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +---------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +---------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +---------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +---------------------------------------------------------------------
namespace Common\Behavior;
use Think\Behavior;

// 初始化钩子信息
class UrldecodeGetBehavior extends Behavior {

    // 行为扩展的执行入口必须是run
    public function run(&$data){
        $_GET=array_map_recursive('urldecode',$_GET);
        $_REQUEST = array_merge($_POST,$_GET,$_COOKIE);
    }
}